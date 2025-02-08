<?php

namespace App\Http\Controllers;

use App\DataTables\ControleDataTable;
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

class ControleController extends AppBaseController
{
    /** @var ControleRepository $controleRepository*/
    private $controleRepository;

    public function __construct(ControleRepository $controleRepo)
    {
        $this->controleRepository = $controleRepo;
    }

    /**
     * Display a listing of the Controle.
     */
    public function index(ControleDataTable $dataTable)
    {
        $enseignants = Enseignant::pluck('nom_prenom', 'id');
        $classes = Classe::pluck('libelle', 'id');

        return $dataTable->render('controles.index', compact('enseignants', 'classes'));
    }
    /**
     * Show the form for creating a new Controle.
     */
    public function create()
    {
        $enseignants = Enseignant::pluck('nom_prenom', 'id'); // Liste des enseignants
        $classes = Classe::pluck('libelle', 'id'); // Liste des classes
        return view('controles.create')->with(compact('enseignants', 'classes'));
    }


    /**
     * Store a newly created Controle in storage.
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

            /*
             * ============================================
             * Envoi des emails aux parents pour les absences
             * ============================================
             *
             * Pour chaque élève absent, nous récupérons son enregistrement dans la table "eleve",
             * puis nous récupérons le parent associé afin d'obtenir son email.
             * Ensuite, nous enrichissons le message avec :
             * - le nom complet de l'élève,
             * - les détails de l'affectation (classe, matière, horaire, type de cours, enseignant).
             */

            if (!empty($absentEleves)) {
                foreach ($absentEleves as $eleveId) {
                    // Récupérer l'élève
                    $eleve = Eleve::find($eleveId);
                    if (!$eleve) {
                        Log::warning("Élève non trouvé pour id : {$eleveId}");
                        continue;
                    }

                    // Récupérer le parent (modèle "Parents" utilisé pour éviter le conflit avec le mot réservé "Parent")
                    $parent = Parents::find($eleve->parent);
                    if (!$parent || empty($parent->email)) {
                        Log::warning("Parent non trouvé ou email manquant pour l'élève id : {$eleveId}");
                        continue;
                    }

                    // Récupérer les détails de l'affectation
                    $classe     = Classe::find($affectation->classe);
                    $matiere    = Matiere::find($affectation->matiere);
                    $horaire    = Horaire::find($affectation->horaire);
                    $typeCours  = TypeCours::find($affectation->type_cours);
                    $enseignant = Enseignant::find($affectation->enseignant);

                    // Composition du message
                    // Composition du message
                    // Conversion de la date au format jour/mois/année
                    $formattedDate = \Carbon\Carbon::parse($dateControle)->format('d/m/Y');

                    $mailData = [
                        'nom'     => auth()->user()->name, // Nom de l'utilisateur connecté
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

                    // Envoi de l'email personnalisé
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
     * Display the specified Controle.
     */
    public function show($id)
    {
        $controle = $this->controleRepository->find($id);

        if (empty($controle)) {
            Flash::error('Controle non trouvé');

            return redirect(route('controles.index'));
        }

        return view('controles.show')->with('controle', $controle);
    }

    /**
     * Show the form for editing the specified Controle.
     */
    public function edit($id)
    {
        $controle = $this->controleRepository->find($id);

        if (empty($controle)) {
            Flash::error('Controle non trouvé');

            return redirect(route('controles.index'));
        }
        $enseignants = Enseignant::pluck('nom_prenom', 'id'); // Liste des enseignants
        $classes = Classe::pluck('libelle', 'id'); // Liste des classes

        return view('controles.edit')->with('controle', $controle)->with(compact('enseignants', 'classes'));
    }

    /**
     * Update the specified Controle in storage.
     */
    public function update($id, UpdateControleRequest $request)
    {
        $controle = $this->controleRepository->find($id);

        if (empty($controle)) {
            Flash::error('Controle non trouvé');

            return redirect(route('controles.index'));
        }

        $controle = $this->controleRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('controles.index'));
    }

    /**
     * Remove the specified Controle from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $controle = $this->controleRepository->find($id);

        if (empty($controle)) {
            Flash::error('Controle non trouvé');

            return redirect(route('controles.index'));
        }

        $this->controleRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('controles.index'));
    }


    public function getEnseignantsByClasse($classeId)
    {
        try {
            // Récupérer les enseignants associés à la classe via une jointure avec la table affectation_matiere
            $enseignants = \App\Models\AffectationMatiere::join('enseignant', 'affectation_matiere.enseignant', '=', 'enseignant.id')
                ->where('affectation_matiere.classe', $classeId)
                ->select('enseignant.id', 'enseignant.nom_prenom')
                ->get();

            return response()->json($enseignants);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des enseignants: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    public function getElevesByClasse($classeId)
    {
        try {
            // Récupérer les élèves de la classe depuis la table effectif
            $eleves = \App\Models\Effectif::where('classe', $classeId)
                ->join('eleve', 'effectif.eleve', '=', 'eleve.id') // Jointure avec la table élève
                ->select('eleve.id', 'eleve.nom_prenom')
                ->get()
                ->map(function ($eleve) {
                    return [
                        'id' => $eleve->id,
                        'nom_prenom' => $eleve->nom_prenom,
                    ];
                });

            return response()->json($eleves);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des élèves: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }


    public function details(Request $request)
    {
        // Validation des paramètres
        $request->validate([
            'affectation_id' => 'required|integer',
            'date_controle' => 'required|date'
        ]);

        // Conversion explicite en integer
        $affectationId = (int)$request->get('affectation_id'); // ✅ Correction ici
        $dateControle = $request->get('date_controle');

        // Récupération avec vérification de type
        $affectation = AffectationMatiere::findOrFail($affectationId);

        // Récupérer tous les élèves de la classe
        $effectifs = Effectif::where('classe', $affectation->classe)
            ->with('eleve')
            ->get();

        // Récupérer les présences
        $controles = Controle::where('affectation_cours', $affectationId)
            ->where('date_controle', $dateControle)
            ->get()
            ->keyBy('effectif');

        // Séparer présents/absents
        $presents = [];
        $absents = [];

        foreach ($effectifs as $effectif) {
            $status = $controles[$effectif->id]->present ?? null;
            $eleve = [
                'id' => $effectif->eleve->id,
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
            'absents' => $absents
        ]);
    }
}
