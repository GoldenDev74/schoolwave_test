<?php
namespace App\Http\Controllers;

use App\Models\AffectationMatiere;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Horaire;
use App\Models\JourSemaine;
use App\Models\Effectif;
use App\Models\Controle;
use App\Models\Matiere;
use App\Models\ModeAffectation;
use App\Models\TypeCours;
use App\Models\TypeExamen; // Ajoutez cette ligne pour importer le modèle TypeExamen
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MesAffectationMatiereController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Récupération de l'utilisateur connecté
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
            }

            // Vérification du profil enseignant
            $enseignant = $this->getEnseignantConnecte($user->id);
            if (!$enseignant) {
                return back()->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page. Seuls les enseignants peuvent y accéder.');
            }

            // Récupération des données
            $data = $this->prepareDonneesEmploiDuTemps($enseignant);

            if (isset($data['error'])) {
                return back()->with('error', $data['error']);
            }

            // Récupération des types d'examens
            $typeExamens = TypeExamen::pluck('libelle', 'id');

            return view('mes_affectation_matiere.index', array_merge($data, compact('typeExamens')));

        } catch (\Exception $e) {
            Log::error("Erreur MesAffectationMatiereController@index : " . $e->getMessage());
            return redirect()->route('home')->with('error', 'Erreur technique');
        }
    }

    private function getEnseignantConnecte($userId)
    {
        $userProfil = DB::table('user_profil')
            ->where('user', $userId)
            ->first();

        if (!$userProfil || !$userProfil->personnel) {
            return null;
        }

        return DB::table('enseignant')
            ->where('id', $userProfil->personnel)
            ->where('enseignant', true)
            ->first();
    }

    private function prepareDonneesEmploiDuTemps($enseignant)
    {
        // Récupération de l'année scolaire active
        $annee = AnneeScolaire::where('en_cours', true)->first();

        if (!$annee) {
            return ['error' => 'Aucune année scolaire n\'est actuellement active. Veuillez contacter l\'administration.'];
        }

        // Récupération des affectations de l'enseignant
        $affectations = AffectationMatiere::select([
            'affectation_matiere.id', // Ajout explicite de l'ID
            'affectation_matiere.horaire',
            'affectation_matiere.jour',
            'affectation_matiere.debut',
            'affectation_matiere.fin',
            'c.id as classe_id', // Ajout de l'ID de classe
            'c.libelle as classe_libelle',
            's.libelle as salle_libelle',
            'm.id as matiere_id', // Ajout de l'ID matière
            'm.libelle as matiere_libelle'
        ])
        ->join('classe as c', 'affectation_matiere.classe', '=', 'c.id')
        ->join('matiere as m', 'affectation_matiere.matiere', '=', 'm.id')
        ->leftJoin('salles as s', 'c.salle', '=', 's.id')
        ->where('affectation_matiere.enseignant', $enseignant->id)
        ->where('affectation_matiere.annee_scolaire', $annee->id)
        ->where('affectation_matiere.annulation', false) // Ajout de la condition pour annulation
        ->orderBy('affectation_matiere.jour')
        ->orderBy('affectation_matiere.horaire')
        ->get()
        ->map(function($affectation) {
            return (object)[
                'id' => $affectation->id,
                'horaire' => $affectation->horaire,
                'jour' => $affectation->jour,
                'debut' => $affectation->debut,
                'fin' => $affectation->fin,
                'matiere' => (object)[
                    'id' => $affectation->matiere_id, // Ajout de l'ID
                    'libelle' => $affectation->matiere_libelle
                ],
                'classe' => (object)[
                    'id' => $affectation->classe_id, // Ajout de l'ID
                    'libelle' => $affectation->classe_libelle,
                    'salle' => $affectation->salle_libelle
                ]
            ];
        });
        

        // Récupération des horaires filtrés par type de cours
        $horaires = Horaire::where('type_cours', $enseignant->type_cours)
            ->orderBy('id')
            ->get(['id', 'libelle']);

        // Récupération des jours
        $jours = JourSemaine::orderBy('id')->get(['id', 'libelle']);

        return [
            'affectations' => $affectations,
            'jours' => $jours,
            'horaires' => $horaires,
            'error' => null,
            'enseignant' => $enseignant
        ];
    }

    public function emploiDuTemps(Request $request)
    {
        try {
            $enseignant = $this->getEnseignantConnecte(Auth::id());

            if (!$enseignant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé. Seuls les enseignants peuvent accéder à cette fonctionnalité.'
                ], 403);
            }

            $data = $this->prepareDonneesEmploiDuTemps($enseignant);

            if (isset($data['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $data['error']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Emploi du temps récupéré avec succès',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur emploiDuTemps : " . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }

    }

    public function getElevesByClasse($classeId)
    {
        try {
            $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();

            if (!$anneeScolaire) {
                return response()->json(['error' => 'Aucune année scolaire en cours'], 404);
            }

            // Correction ici : 'classe_id' -> 'classe'
            $eleves = Effectif::where('classe', $classeId)
                ->where('annee_scolaire', $anneeScolaire->id)
                ->join('eleve', 'effectif.eleve', '=', 'eleve.id')
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
}
