<?php

namespace App\Http\Controllers;

use App\DataTables\EnseignantControleDataTable;
use App\Http\Requests\CreateControleRequest;
use App\Http\Requests\UpdateControleRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ControleRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;
use Illuminate\Http\Request;
use App\Models\Enseignant;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\TypeCours;
use App\Models\Horaire;
use App\Models\Effectif;
use App\Models\Controle;
use App\Models\AffectationMatiere;
use Illuminate\Support\Facades\Log;
use App\Models\Parents;
use Illuminate\Support\Facades\DB;

use Flash;

class EnseignantControleController extends AppBaseController
{
    /** @var ControleRepository $controleRepository */
    private $controleRepository;

    public function __construct(ControleRepository $controleRepo)
    {
        $this->controleRepository = $controleRepo;
    }

    /**
     * Affichage de l'historique des contrôles de présence.
     */
    public function index(EnseignantControleDataTable $dataTable)
    {
        // Récupérer l’utilisateur connecté
        $userId = auth()->user()->id;

        // Vérifier dans la table user_profil que l'utilisateur est un enseignant
        $userProfil = DB::table('user_profil')->where('user', $userId)->first();
        if (!$userProfil || !$userProfil->personnel) {
            abort(403, 'Accès interdit. Vous n\'êtes pas autorisé à accéder à cette vue.');
        }

        // Récupérer l'id de l'enseignant et l'objet enseignant
        $teacherId = $userProfil->personnel;
        $enseignant = Enseignant::find($teacherId);
        if (!$enseignant) {
            abort(403, 'Accès interdit. Enseignant non trouvé.');
        }

        // Récupérer toutes les affectations liées à cet enseignant
        $affectations = AffectationMatiere::where('enseignant', $teacherId)->get();

        // Extraire les IDs de classes uniques sur lesquelles il intervient
        $classesIds = $affectations->pluck('classe')->unique();

        // Récupérer les libellés des classes concernées
        $classes = Classe::whereIn('id', $classesIds)->pluck('libelle', 'id');

        // On passe à la vue l’objet enseignant et la liste des classes
        return $dataTable->render('enseignantcontroles.index', compact('enseignant', 'classes'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau contrôle.
     */
    public function create()
    {
        $enseignants = Enseignant::pluck('nom_prenom', 'id'); // Liste des enseignants
        $classes = Classe::pluck('libelle', 'id'); // Liste des classes
        return view('enseignantcontroles.create')->with(compact('enseignants', 'classes'));
    }

    /**
     * Enregistre un nouveau contrôle en base.
     */
    public function store(Request $request)
    {
        $request->validate([
            'affectation_matiere' => 'required|exists:affectation_matiere,id',
            'eleves'              => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Récupération de l'affectation et définition du contexte
            $affectation = AffectationMatiere::findOrFail($request->affectation_matiere);
            $classeId    = $affectation->classe;
            $dateControle = now()->toDateString();

            // Vérifier s'il existe déjà un contrôle pour cette affectation à cette date
            $existingControle = Controle::where('affectation_cours', $affectation->id)
                ->where('date_controle', $dateControle)
                ->first();

            // Récupérer tous les identifiants des élèves de la classe
            $allEleves = Effectif::where('classe', $classeId)
                ->pluck('eleve')
                ->toArray();

            if ($existingControle) {
                // Mettre à jour la présence pour les élèves cochés (présents)
                foreach ($request->eleves as $eleveId) {
                    $effectif = Effectif::where('classe', $classeId)
                        ->where('eleve', $eleveId)
                        ->first();

                    if ($effectif) {
                        Controle::updateOrCreate(
                            [
                                'effectif'         => $effectif->id,
                                'affectation_cours' => $affectation->id,
                                'date_controle'    => $dateControle,
                            ],
                            ['present' => true]
                        );
                    }
                }

                // Marquer les élèves non cochés comme absents
                $absentEleves = array_diff($allEleves, $request->eleves);
                foreach ($absentEleves as $eleveId) {
                    $effectif = Effectif::where('classe', $classeId)
                        ->where('eleve', $eleveId)
                        ->first();

                    if ($effectif) {
                        Controle::updateOrCreate(
                            [
                                'effectif'         => $effectif->id,
                                'affectation_cours' => $affectation->id,
                                'date_controle'    => $dateControle,
                            ],
                            ['present' => false]
                        );
                    }
                }
            } else {
                // Création des enregistrements pour les élèves présents
                foreach ($request->eleves as $eleveId) {
                    $effectif = Effectif::where('classe', $classeId)
                        ->where('eleve', $eleveId)
                        ->first();

                    if ($effectif) {
                        Controle::create([
                            'effectif'         => $effectif->id,
                            'affectation_cours' => $affectation->id,
                            'date_controle'    => $dateControle,
                            'present'          => true,
                        ]);
                    }
                }

                // Création des enregistrements pour les élèves absents
                $absentEleves = array_diff($allEleves, $request->eleves);
                foreach ($absentEleves as $eleveId) {
                    $effectif = Effectif::where('classe', $classeId)
                        ->where('eleve', $eleveId)
                        ->first();

                    if ($effectif) {
                        Controle::create([
                            'effectif'         => $effectif->id,
                            'affectation_cours' => $affectation->id,
                            'date_controle'    => $dateControle,
                            'present'          => false,
                        ]);
                    }
                }
            }

            // Envoi des emails aux parents pour les absences
            if (!empty($absentEleves)) {
                foreach ($absentEleves as $eleveId) {
                    $eleve = Eleve::find($eleveId);
                    if (!$eleve) {
                        Log::warning("Élève non trouvé pour id : {$eleveId}");
                        continue;
                    }

                    $parent = Parents::find($eleve->parent);
                    if (!$parent || empty($parent->email)) {
                        Log::warning("Parent non trouvé ou email manquant pour l'élève id : {$eleveId}");
                        continue;
                    }

                    $classe     = Classe::find($affectation->classe);
                    $matiere    = Matiere::find($affectation->matiere);
                    $horaire    = Horaire::find($affectation->horaire);
                    $typeCours  = TypeCours::find($affectation->type_cours);
                    $enseignant = Enseignant::find($affectation->enseignant);

                    $formattedDate = \Carbon\Carbon::parse($dateControle)->format('d/m/Y');

                    $mailData = [
                        'nom'     => auth()->user()->name,
                        'subject' => "Absence de {$eleve->nom_prenom} le {$formattedDate}",
                        'email'   => $parent->email,
                        'message' => "
        <div style='font-size:16px; line-height:1.6; font-family: Arial, sans-serif;'>
            <p>Bonjour,</p>
            <p>
                Votre enfant <strong>{$eleve->nom_prenom}</strong> a été marqué(e) comme <strong>ABSENT(e)</strong> lors du contrôle de présence du <strong>{$formattedDate}</strong>.
            </p>
            <p><strong>Détails du cours :</strong></p>
            <ul style='margin: 0 0 20px 20px;'>
                <li><strong>Classe :</strong> " . ($classe ? $classe->libelle : 'Non défini') . "</li>
                <li><strong>Matière :</strong> " . ($matiere ? $matiere->libelle : 'Non défini') . "</li>
                <li><strong>Horaire :</strong> " . ($horaire ? $horaire->libelle : 'Non défini') . "</li>
                <li><strong>Type de cours :</strong> " . ($typeCours ? $typeCours->libelle : 'Non défini') . "</li>
                <li><strong>Enseignant :</strong> " . ($enseignant ? $enseignant->nom_prenom : 'Non défini') . "</li>
            </ul>
            <p>
                Pour toute information complémentaire, veuillez contacter l'administration.
            </p>
            <p>Cordialement,<br>
            " . auth()->user()->name . "</p>
        </div>
    "
                    ];

                    Log::info("Envoi d'email pour l'absence de {$eleve->nom_prenom} à l'email {$parent->email}", $mailData);

                    Mail::to($parent->email)->send(new Contact($mailData));
                }
            } else {
                Log::info("Aucun élève absent détecté pour le contrôle du {$dateControle}.");
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur dans ControleController@store : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Affiche les détails d’un contrôle.
     */
    public function details(Request $request)
    {
        $request->validate([
            'affectation_id' => 'required|integer',
            'date_controle'  => 'required|date'
        ]);

        $affectationId = (int)$request->get('affectation_id');
        $dateControle  = $request->get('date_controle');

        $affectation = AffectationMatiere::findOrFail($affectationId);

        $effectifs = \App\Models\Effectif::where('classe', $affectation->classe)
            ->with('eleve')
            ->get();

        $controles = Controle::where('affectation_cours', $affectationId)
            ->where('date_controle', $dateControle)
            ->get()
            ->keyBy('effectif');

        $presents = [];
        $absents  = [];

        foreach ($effectifs as $effectif) {
            $status = $controles[$effectif->id]->present ?? null;
            $eleve = [
                'id'         => $effectif->eleve->id,
                'nom_prenom' => $effectif->eleve->nom_prenom
            ];

            if ($status === true) {
                $presents[] = $eleve;
            } else {
                $absents[] = $eleve;
            }
        }

        return response()->json([
            'presents' => $presents,
            'absents'  => $absents
        ]);
    }

    // Les autres méthodes (show, edit, update, destroy, getEnseignantsByClasse, getElevesByClasse) restent inchangées...
}
