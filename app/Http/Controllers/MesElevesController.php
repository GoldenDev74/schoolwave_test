<?php

namespace App\Http\Controllers;

use App\DataTables\MesElevesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\Eleve;
use App\Models\AffectationMatiere;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Horaire;
use App\Models\JourSemaine;
use App\Models\Effectif;
use Illuminate\Http\Request;
use App\Models\Pays;
use App\Models\Sexe;
use App\Models\Examen;
use Flash;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class MesElevesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the Eleve.
     */
    public function index(MesElevesDataTable $dataTable)
    {
        try {
            Log::info('Début de la méthode index de MesElevesController');
            
            // 1. Récupérer le parent connecté
            $parent = Parents::where('email', Auth::user()->email)->first();
            
            if (!$parent) {
                Flash::error('Votre compte n\'est pas associé à un parent.');
                return redirect(route('login'));
            }

            // 2. Récupérer l'année scolaire active
            $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();
            
            if (!$anneeScolaire) {
                Flash::error('Aucune année scolaire active n\'est définie.');
                return redirect()->back();
            }

            // 3. Récupérer les enfants du parent
            $eleves = Eleve::with([
                'effectifs' => function($query) use ($anneeScolaire) {
                    $query->where('annee_scolaire', $anneeScolaire->id);
                },
                'effectifs.classes'
            ])
            ->where('parent', $parent->id)
            ->get();

            // 4. Récupérer les jours de la semaine
            $jours = JourSemaine::orderBy('id')->get();

            // 5. Pour chaque enfant, récupérer son emploi du temps
            foreach ($eleves as $eleve) {
                foreach ($eleve->effectifs as $effectif) {
                    if ($effectif->classes) {
                        // Récupérer la classe avec son type de cours
                        $classe = Classe::find($effectif->classe);

                        if ($classe) {
                            // Récupérer les horaires pour ce type de cours
                            $horaires = Horaire::where('type_cours', $classe->type_cours)
                                ->orderBy('debut')
                                ->get();

                            // Récupérer les affectations de cette classe
                            $affectations = AffectationMatiere::where([
                                    'classe' => $effectif->classe,
                                    'annee_scolaire' => $anneeScolaire->id,
                                    'type_cours' => $classe->type_cours
                                ])
                                ->with(['matiere', 'enseignant'])
                                ->get();

                            // Organiser les affectations par horaire et jour
                            $emploiDuTemps = [];
                            foreach ($horaires as $horaire) {
                                foreach ($jours as $jour) {
                                    $cours = $affectations->first(function($affectation) use ($horaire, $jour) {
                                        return $affectation->horaire == $horaire->id && $affectation->jour_semaine == $jour->id;                                    });
                                    
                                    if ($cours) {
                                        $emploiDuTemps[$horaire->id][$jour->id] = $cours;
                                    }
                                }
                            }

                            $effectif->emploiDuTemps = $emploiDuTemps;
                            $effectif->horaires = $horaires;
                        }
                    }
                }
            }

            Log::info('Données chargées avec succès', [
                'eleves' => $eleves->count(),
                'jours' => $jours->count()
            ]);

            // Dans la méthode index()
            $eleves->each(function ($eleve) {
                $eleve->notes = Examen::select('examen.*', 'type_examen.libelle as type_examen_libelle', 'matiere.libelle as matiere_libelle')
                    ->join('affectation_matiere', 'examen.affectation', '=', 'affectation_matiere.id')
                    ->join('matiere', 'affectation_matiere.matiere', '=', 'matiere.id')
                    ->leftJoin('type_examen', 'examen.type_examen', '=', 'type_examen.id')
                    ->where('examen.eleve', $eleve->id)
                    ->get()
                    ->map(function ($note) {
                        return [
                            'matiere' => $note->matiere_libelle ?? 'Non spécifié',
                            'type_examen' => $note->type_examen_libelle ?? 'Non spécifié',
                            'note' => $note->note,
                            'created_at' => $note->created_at->format('d/m/Y') ?? 'Non spécifié',
                        ];
                    });
            });
            
            return $dataTable->render('meseleves.index', compact('eleves', 'jours'));

        } catch (\Exception $e) {
            Log::error('Erreur dans MesElevesController@index: ' . $e->getMessage());
            // Flash::error('Une erreur est survenue lors du chargement des données.');
            return redirect()->back();
        }
    }

    public function getClasseAffectations($eleveId)
{
    try {
        // Récupérer l'année scolaire active
        $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();
        if (!$anneeScolaire) {
            Log::error('Aucune année scolaire active trouvée pour l\'élève ID: ' . $eleveId);
            return response()->json(['error' => 'Aucune année scolaire active'], 404);
        }

        // Récupérer l'effectif de l'élève
        $effectif = Effectif::where('eleve', $eleveId)->where('annee_scolaire', $anneeScolaire->id)->first();
        if (!$effectif) {
            Log::error('Aucun effectif trouvé pour l\'élève ID: ' . $eleveId);
            return response()->json(['error' => 'Aucun effectif trouvé pour cet élève'], 404);
        }

        // Récupérer la classe de l'élève
        $classe = Classe::find($effectif->classe);
        if (!$classe) {
            Log::error('Aucune classe trouvée pour l\'élève ID: ' . $eleveId);
            return response()->json(['error' => 'Aucune classe trouvée pour cet élève'], 404);
        }

        // Récupérer les affectations de la classe
        $affectations = AffectationMatiere::where('classe', $classe->id)
            ->where('annee_scolaire', $anneeScolaire->id)
            ->with(['matiere', 'enseignant', 'horaire', 'jour'])
            ->get();

        // Récupérer les jours de la semaine
        $jours = JourSemaine::orderBy('id')->get();

        // Récupérer les horaires spécifiques au type de cours de la classe
        $horaires = Horaire::where('type_cours', $classe->type_cours)->orderBy('debut')->get();

        return response()->json([
            'success' => true,
            'classe' => $classe,
            'affectations' => $affectations,
            'jours' => $jours,
            'horaires' => $horaires
        ]);

    } catch (\Exception $e) {
        Log::error('Erreur dans MesElevesController@getClasseAffectations: ' . $e->getMessage());
        return response()->json(['error' => 'Erreur serveur'], 500);
    }
}

    public function getNotes($eleveId){
        try {
            $notes = Examen::select('examen.*', 'type_examen.libelle as type_examen_libelle', 'matiere.libelle as matiere_libelle')
                ->join('affectation_matiere', 'examen.affectation', '=', 'affectation_matiere.id')
                ->join('matiere', 'affectation_matiere.matiere', '=', 'matiere.id')
                ->leftJoin('type_examen', 'examen.type_examen', '=', 'type_examen.id')
                ->where('examen.eleve', $eleveId)
                ->get()
                ->map(function ($note) {
                    return [
                        'matiere' => $note->matiere_libelle ?? 'Non spécifié',
                        'type_examen' => $note->type_examen_libelle ?? 'Non spécifié',
                        'note' => $note->note,
                        'created_at' => $note->created_at->format('d/m/Y'),
                    ];
                });

            return view('meseleves.partials.notes', compact('notes'));
        } catch (\Exception $e) {
            Log::error('Erreur dans getNotes: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue lors du chargement des notes.'], 500);
        }
    }

    public function getEmploiTemps($eleveId){
        try {
            // Récupérer l'année scolaire active
            $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();
            if (!$anneeScolaire) {
                Log::error('Aucune année scolaire active trouvée pour l\'élève ID: ' . $eleveId);
                return response()->json(['error' => 'Aucune année scolaire active'], 404);
            }

            // Récupérer l'effectif de l'élève
            $effectif = Effectif::where('eleve', $eleveId)
                ->where('annee_scolaire', $anneeScolaire->id)
                ->first();
            if (!$effectif) {
                Log::error('Aucun effectif trouvé pour l\'élève ID: ' . $eleveId);
                return response()->json(['error' => 'Aucun effectif trouvé pour cet élève'], 404);
            }

            // Récupérer la classe de l'élève
            $classe = Classe::find($effectif->classe);
            if (!$classe) {
                Log::error('Aucune classe trouvée pour l\'élève ID: ' . $eleveId);
                return response()->json(['error' => 'Aucune classe trouvée pour cet élève'], 404);
            }

            // Récupérer les jours de la semaine
            $jours = JourSemaine::orderBy('id')->get();

            // Récupérer les horaires pour ce type de cours
            $horaires = Horaire::where('type_cours', $classe->type_cours)
                ->orderBy('debut')
                ->get();

            // Récupérer les affectations de cette classe
            $affectations = AffectationMatiere::where([
                    'classe' => $effectif->classe,
                    'annee_scolaire' => $anneeScolaire->id,
                    'type_cours' => $classe->type_cours
                ])
                ->with(['matiere', 'enseignant'])
                ->get();

            // Organiser les affectations par horaire et jour
            $emploiDuTemps = [];
            foreach ($horaires as $horaire) {
                foreach ($jours as $jour) {
                    $cours = $affectations->first(function($affectation) use ($horaire, $jour) {
                        return $affectation->horaire == $horaire->id && $affectation->jour_semaine == $jour->id;
                    });
                    
                    if ($cours) {
                        $emploiDuTemps[$horaire->id][$jour->id] = [
                            'matiere' => $cours->matiere->libelle ?? 'Non spécifié',
                            'enseignant' => $cours->enseignant->nom_complet ?? 'Non spécifié',
                            'horaire_debut' => $horaire->debut,
                            'horaire_fin' => $horaire->fin
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'emploiDuTemps' => $emploiDuTemps,
                'jours' => $jours->map(function($jour) {
                    return [
                        'id' => $jour->id,
                        'libelle' => $jour->libelle
                    ];
                }),
                'horaires' => $horaires->map(function($horaire) {
                    return [
                        'id' => $horaire->id,
                        'debut' => $horaire->debut,
                        'fin' => $horaire->fin
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans MesElevesController@getEmploiTemps: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Show the form for creating a new Enfant.
     */
    public function create()
    {
        $pays = Pays::pluck('libelle', 'id')->prepend('Sélectionner un pays', '');
        $nationalites = Pays::pluck('libelle', 'id')->prepend('Sélectionner une nationalité', '');
        $sexes = Sexe::pluck('libelle', 'id')->prepend('Sélectionner un sexe', '');
        return view('meseleves.create')
            ->with('pays', $pays)
            ->with('nationalites', $nationalites)
            ->with('sexes', $sexes);
    }

    /**
     * Store a newly created Eleve in storage.
     */
    public function store(Request $request){
        try {
            $parent = Parents::where('email', Auth::user()->email)->first();
            
            if (!$parent) {
                Flash::error('Votre compte n\'est pas associé à un parent.');
                return redirect(route('meseleves.index'));
            }

            $input = $request->all();
            $input['parent'] = $parent->id;

            Eleve::create($input);

            Flash::success('Enfant ajouté avec succès.');
            return redirect(route('meseleves.index'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout de l\'enfant: ' . $e->getMessage());
            Flash::error('Une erreur est survenue lors de l\'ajout.');
            return redirect()->back();
        }
    }

    /**
     * Display the specified Eleve.
     */
    public function show($id)
    {
        $eleve = Eleve::find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('meseleves.index'));
        }

        return view('eleves.show')->with('eleve', $eleve);
    }

    /**
     * Show the form for editing the specified Eleve.
     */
    public function edit($id)
    {
        $eleve = Eleve::find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }
        $parents = Parents::pluck('nom_prenom', 'id')->prepend('Sélectionner un parent', '');
        $pays = Pays::pluck('libelle', 'id')->prepend('Sélectionner un pays', '');
        $nationalites = Pays::pluck('libelle', 'id')->prepend('Sélectionner une nationalité', '');
        $sexes = Sexe::pluck('libelle', 'id')->prepend('Sélectionner un sexe', '');
        return view('eleves.edit')
            ->with('eleve', $eleve)
            ->with('parents', $parents)
            ->with('pays', $pays)
            ->with('nationalites', $nationalites)
            ->with('sexes', $sexes);
    }

    /**
     * Update the specified Eleve in storage.
     */
    public function update(Request $request, $id)
    {
        $eleve = Eleve::find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('meseleves.index'));
        }

        $eleve->update($request->all());

        Flash::success('Enfant mise à jour avec succès.');

        return redirect(route('meseleves.index'));
    }

    /**
     * Remove the specified Eleve from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $eleve = Eleve::find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('meseleves.index'));
        }

        $eleve->delete();

        Flash::success('Eleve supprimé avec succès.');

        return redirect(route('meseleves.index'));
    }

    
}
