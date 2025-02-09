<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\AffectationMatiere;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\AnneeScolaire;
use App\Models\TypeCours;
use App\Models\JourSemaine;
use App\Models\Horaire;
use App\Models\ModeAffectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class AffectationMatiereController
 * @package App\Http\Controllers
 */
class AffectationMatiereController extends AppBaseController
{
    public function index()
    {
        try {
            Log::info('Début de la méthode index');
    
            // Initialiser les variables avec des valeurs par défaut
            $classes = collect();
            $enseignants = collect();
            $matieres = collect();
            $typeCourss = collect();
            $jours = collect();
            $horaires = collect();
            $modesAffectation = collect();
            $affectations = [];
            $error = null;
    
            Log::info('Recherche de l\'année scolaire active');
            $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();
    
            if (!$anneeScolaire) {
                Log::warning('Aucune année scolaire active trouvée');
                $error = 'Aucune année scolaire active n\'est définie';
            } else {
                Log::info('Chargement des données de référence');
                try {
                    // Récupérer les données pour les selects
                    $classes = Classe::with('typeCourss')->get()->map(function($classe) {
                        return [
                            'id' => $classe->id,
                            'libelle' => $classe->libelle,
                            'type_cours' => $classe->typeCourss ? $classe->typeCourss->id : null
                        ];
                    });
                    Log::info('Classes chargées', ['count' => $classes->count()]);
    
                    $enseignants = Enseignant::with('typeCours')->where('enseignant', true)->get()->map(function($enseignant) {
                        return [
                            'id' => $enseignant->id,
                            'nom_prenom' => $enseignant->nom_prenom,
                            'type_cours' => $enseignant->typeCours ? $enseignant->typeCours->id : null,
                            'type_cours_libelle' => $enseignant->typeCours ? $enseignant->typeCours->libelle : null
                        ];
                    });
                    $matieres = Matiere::pluck('libelle', 'id');
                    Log::info('Matières chargées', ['count' => $matieres->count()]);
    
                    $typeCourss = TypeCours::all()->map(function($type) {
                        return [
                            'id' => $type->id,
                            'libelle' => $type->libelle
                        ];
                    });
                    Log::info('Types de cours chargés', ['count' => $typeCourss->count()]);
    
                    $jours = JourSemaine::orderBy('id')->get();
                    Log::info('Jours chargés', ['count' => $jours->count()]);
    
                    $horaires = Horaire::orderBy('id')->get();
                    Log::info('Horaires chargés', ['count' => $horaires->count()]);
    
                    $modesAffectation = ModeAffectation::all();
                    Log::info('Modes d\'affectation chargés', ['count' => $modesAffectation->count()]);
    
                    // Récupérer les affectations avec la condition annulation = false
                    $affectations = AffectationMatiere::where('annulation', false)
                        ->where('annee_scolaire', $anneeScolaire->id)
                        ->get()
                        ->map(function($affectation) {
                            return (object)[
                                'id' => $affectation->id,
                                'horaire' => $affectation->horaire,
                                'jour' => $affectation->jour,
                                'debut' => $affectation->debut,
                                'fin' => $affectation->fin,
                                'matiere' => (object)[
                                    'id' => $affectation->matiere_id,
                                    'libelle' => $affectation->matiere_libelle
                                ],
                                'classe' => (object)[
                                    'id' => $affectation->classe_id,
                                    'libelle' => $affectation->classe_libelle,
                                    'salle' => $affectation->salle_libelle
                                ]
                            ];
                        });
                    Log::info('Affectations chargées', ['count' => $affectations->count()]);
    
                } catch (\Exception $e) {
                    Log::error('Erreur lors du chargement des données de référence: ' . $e->getMessage());
                    throw $e;
                }
            }
    
            Log::info('Préparation des données pour la vue');
            $viewData = compact(
                'classes',
                'enseignants',
                'matieres',
                'typeCourss',
                'jours',
                'horaires',
                'modesAffectation',
                'affectations',
                'anneeScolaire',
                'error'
            );
            Log::info('Données préparées', ['keys' => array_keys($viewData)]);
    
            return view('affectation_matieres.index', $viewData);
    
        } catch (\Exception $e) {
            Log::error('Erreur dans AffectationMatiereController@index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
    
            // En cas d'erreur, retourner la vue avec des collections vides et un message d'erreur
            return view('affectation_matieres.index', [
                'classes' => collect(),
                'enseignants' => collect(),
                'matieres' => collect(),
                'typeCourss' => collect(),
                'jours' => collect(),
                'horaires' => collect(),
                'modesAffectation' => collect(),
                'affectations' => [],
                'anneeScolaire' => null,
                'error' => 'Une erreur est survenue lors du chargement de la page: ' . $e->getMessage()
            ]);
        }
    }
    
    public function store(Request $request)
    {
        try {
            // Validation des données avec messages d'erreur détaillés
            $validator = Validator::make($request->all(), [
                'jour' => 'required|exists:jour_semaine,id',
                'horaire' => 'required|exists:horaire,id',
                'classe' => 'required|exists:classe,id',
                'matiere' => 'required|exists:matiere,id',
                'enseignant' => 'required|exists:enseignant,id',
                'type_cours' => 'required|exists:type_cours,id',
                'mode_affection' => 'required|in:1,2',
                'debut' => 'required_if:mode_affection,2|date',
                'fin' => 'required_if:mode_affection,2|date|after_or_equal:debut',
            ], [
                'jour.required' => 'Le jour est obligatoire',
                'horaire.required' => 'L\'horaire est obligatoire',
                'classe.required' => 'La classe est obligatoire',
                'matiere.required' => 'La matière est obligatoire',
                'enseignant.required' => 'L\'enseignant est obligatoire',
                'type_cours.required' => 'Le type de cours est obligatoire',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'type' => 'validation',
                    'code' => 'VALIDATION_FAILED',
                    'message' => 'Veuillez vérifier tous les champs',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            // Récupération de l'année scolaire active
            $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();
            if (!$anneeScolaire) {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'NO_ACTIVE_YEAR',
                    'message' => 'Aucune année scolaire active n\'est définie'
                ], 422);
            }
    
            // Vérifier les doublons
            $existingAffectation = AffectationMatiere::where([
                'jour' => $request->jour,
                'horaire' => $request->horaire,
                'annee_scolaire' => $anneeScolaire->id,
                'annulation' => false
            ])
            ->where(function($query) use ($request) {
                $query->where('classe', $request->classe)
                      ->orWhere('enseignant', $request->enseignant);
            })
            ->first();
    
            if ($existingAffectation) {
                $conflit = '';
                if ($existingAffectation->classe == $request->classe) {
                    $conflit = 'Cette classe a déjà un cours à cet horaire';
                } else {
                    $conflit = 'Cet enseignant a déjà un cours à cet horaire';
                }
    
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'DUPLICATE_AFFECTATION',
                    'message' => $conflit
                ], 422);
            }
    
            // Vérifier si le type de cours de l'horaire choisi correspond au champ type_cours
            $horaire = Horaire::find($request->horaire);
            if ($horaire->type_cours != $request->type_cours) {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'TYPE_COURS_MISMATCH',
                    'message' => 'Le type de cours de l\'horaire choisi ne correspond pas au type de cours sélectionné'
                ], 422);
            }
    
            // Préparation des données
            $data = $request->all();
            $data['annee_scolaire'] = $anneeScolaire->id;
            $data['annulation'] = false;
    
            // Début de la transaction
            DB::beginTransaction();
            try {
                // Création de l'affectation
                $affectation = AffectationMatiere::create($data);
    
                // Commit de la transaction
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'type' => 'create',
                    'message' => 'Affectation créée avec succès',
                    'affectation' => $affectation
                ]);
    
            } catch (\Exception $createException) {
                // Rollback en cas d'erreur
                DB::rollBack();
    
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'CREATE_FAILED',
                    'message' => 'Impossible de créer l\'affectation : ' . $createException->getMessage()
                ], 500);
            }
    
        } catch (\Exception $globalException) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'UNEXPECTED_ERROR',
                'message' => 'Une erreur inattendue est survenue : ' . $globalException->getMessage()
            ], 500);
        }
    }
    public function emploiDuTemps(Request $request)
    {
        try {
            $type = $request->input('type');
            $id = $request->input('id');
    
            // Validation stricte des paramètres
            if (!$type || !$id) {
                Log::warning('Paramètres invalides', [
                    'type' => $type,
                    'id' => $id
                ]);
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'INVALID_PARAMETERS',
                    'message' => 'Type et ID sont requis'
                ], 400);
            }
    
            // Validation du type
            if (!in_array($type, ['classe', 'enseignant'])) {
                Log::warning('Type invalide', ['type' => $type]);
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'INVALID_TYPE',
                    'message' => 'Type invalide'
                ], 400);
            }
    
            // Récupération de l'entité
            $entity = null;
            if ($type === 'classe') {
                $entity = Classe::find($id);
            } elseif ($type === 'enseignant') {
                $entity = Enseignant::find($id);
            }
    
            // Vérification de l'existence de l'entité
            if (!$entity) {
                Log::warning('Entité non trouvée', [
                    'type' => $type,
                    'id' => $id
                ]);
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'ENTITY_NOT_FOUND',
                    'message' => ucfirst($type) . ' non trouvé(e)'
                ], 404);
            }
    
            // Récupération du type_cours
            $typeCours = null;
            if ($type === 'classe') {
                $typeCours = $entity->type_cours;
                Log::info('Type cours pour classe', ['type_cours' => $typeCours]);
            } elseif ($type === 'enseignant') {
                $typeCours = $entity->type_cours;
                Log::info('Type cours pour enseignant', [
                    'type_cours' => $typeCours,
                    'enseignant_id' => $entity->id,
                    'enseignant_nom' => $entity->nom_prenom
                ]);
            }
    
            // Vérification du type_cours
            if (!$typeCours) {
                Log::warning('Aucun type de cours trouvé', [
                    'type' => $type,
                    'id' => $id
                ]);
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'NO_TYPE_COURS',
                    'message' => 'Aucun type de cours défini pour cette entité'
                ], 400);
            }
    
            // Fetch horaires filtered by type_cours
            $horaires = Horaire::where('type_cours', $typeCours)->get();
            $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();
            Log::info('Horaires trouvés', ['count' => $horaires->count()]);
    
            // Fetch affectations with related data, filtered by type_cours
            $query = AffectationMatiere::with([
                'enseignant',
                'classe',
                'matiere',
                'typeCours',
                'anneeScolaire',
                'horaire',
                'jour',
                'modeAffectation'
            ])->where($type, $id)
              ->where('annee_scolaire', $anneeScolaire->id) // Ajout de la condition pour année scolaire active
              ->where('type_cours', $typeCours)
              ->where('annulation', false); // Ajout de la condition pour annulation
    
            $affectations = $query->get();
            Log::info('Affectations trouvées', ['count' => $affectations->count()]);
    
            return response()->json([
                'success' => true,
                'affectations' => $affectations,
                'horaires' => $horaires,
                'type_cours' => $typeCours
            ]);
    
        } catch (\Exception $e) {
            // Log détaillé de l'erreur
            Log::error('Erreur lors de la récupération de l\'emploi du temps', [
                'message' => $e->getMessage(),
                'type' => $type ?? 'Non défini',
                'id' => $id ?? 'Non défini',
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'FETCH_FAILED',
                'message' => 'Erreur lors de la récupération de l\'emploi du temps: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getClasseInfo($id)
    {
        try {
            $classe = Classe::with('typeCourss')->findOrFail($id);
            
            if (!$classe->typeCourss) {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'code' => 'NO_TYPE_COURS',
                    'message' => 'Aucun type de cours associé à cette classe'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'classe' => [
                    'id' => $classe->id,
                    'libelle' => $classe->libelle,
                    'type_cours' => $classe->typeCourss->id
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'CLASSE_NOT_FOUND',
                'message' => 'Classe non trouvée'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des informations de la classe:', [
                'classe_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'FETCH_FAILED',
                'message' => 'Erreur lors de la récupération des informations de la classe'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Commencer une transaction pour garantir que les changements sont atomiques
            DB::beginTransaction();
    
            // Désactiver temporairement les vérifications de clés étrangères (pour MySQL)
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
            $affectation = AffectationMatiere::findOrFail($id);
    
            // Supprimer l'affectation (tous les enregistrements liés dans d'autres tables seront "orphelins")
            $affectation->delete();
    
            // Réactiver les vérifications de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
            // Valider la transaction
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Affectation supprimée avec succès'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            \Log::error('Affectation non trouvée:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
    
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'NOT_FOUND',
                'message' => 'Affectation non trouvée'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la suppression de l\'affectation:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
    
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'DELETE_FAILED',
                'message' => 'Erreur lors de la suppression de l\'affectation'
            ], 500);
        }
    }

    public function annuler($id)
    {
        $affectation = AffectationMatiere::find($id); // Utilisez AffectationMatiere ici
        if ($affectation) {
            $affectation->annulation = true;
            $affectation->save();
            return response()->json(['success' => true, 'message' => 'Affectation annulée avec succès']);
        }
        return response()->json(['success' => false, 'message' => 'Affectation non trouvée'], 404);
    }
    
    
    /**
     * Récupère les détails d'une affectation pour édition
     *
     * @param int $id Identifiant de l'affectation
     * @return \Illuminate\Http\JsonResponse
     */

     public function getDetails($id)
     {
         $affectation = AffectationMatiere::with(['enseignant', 'classe', 'matiere', 'jour', 'horaire', 'typeCours'])
                                          ->findOrFail($id);
         return response()->json([
             'success' => true,
             'data' => $affectation
         ]);
     }

    public function edit($id)
    {
        try {
            Log::info('Requête de récupération des détails d\'affectation', [
                'affectation_id' => $id,
                'user_id' => auth()->id()
            ]);
    
            $affectation = AffectationMatiere::with([
                'jour',
                'horaire',
                'classe',
                'enseignant',
                'matiere',
                'typeCours'
            ])->where('id', $id)
              ->where('annulation', false) // Ajout de la condition pour annulation
              ->firstOrFail();
    
            return response()->json([
                'success' => true,
                'affectation' => [
                    'id' => $affectation->id,
                    'jour' => $affectation->jour_id,
                    'horaire' => $affectation->horaire_id,
                    'classe' => $affectation->classe_id,
                    'enseignant' => $affectation->enseignant_id,
                    'matiere' => $affectation->matiere_id,
                    'type_cours' => $affectation->type_cours_id,
                    'mode_affection' => $affectation->mode_affection_id,
                    'debut' => $affectation->debut,
                    'fin' => $affectation->fin
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des détails d\'affectation', [
                'error' => $e->getMessage(),
                'affectation_id' => $id
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Impossible de charger l\'affectation',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    
    public function update(Request $request, $id)
    {
        $affectation = AffectationMatiere::findOrFail($id);
    
        // Si la requête contient uniquement le champ annulation, on l'update seul.
        if ($request->only('annulation') && count($request->all()) === 2) { // le token CSRF est aussi présent
            $annulation = filter_var($request->annulation, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            $affectation->update(['annulation' => $annulation]);
            return response()->json(['success' => true]);
        }
    
        // Validation des données
        $validatedData = $request->validate([
            'jour'           => 'required|exists:jour_semaine,id',
            'horaire'        => 'required|exists:horaire,id',
            'classe'         => 'required|exists:classe,id',
            'matiere'        => 'required|exists:matiere,id',
            'enseignant'     => 'required|exists:enseignant,id',
            'type_cours'     => 'required|exists:type_cours,id',
            'mode_affection' => 'required|in:1,2',
            'debut' => 'nullable|required_if:mode_affection,2|date', // Ajout de 'nullable'
            'fin' => 'nullable|required_if:mode_affection,2|date|after_or_equal:debut', // Ajout de 'nullable'
        ]);
    
        // Récupération de l'année scolaire active
        $anneeScolaire = AnneeScolaire::where('en_cours', true)->first();
        if (!$anneeScolaire) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'NO_ACTIVE_YEAR',
                'message' => 'Aucune année scolaire active n\'est définie'
            ], 422);
        }
    
        // Vérifier les doublons
        $existingAffectation = AffectationMatiere::where([
            'jour' => $request->jour,
            'horaire' => $request->horaire,
            'annee_scolaire' => $anneeScolaire->id,
            'annulation' => false
        ])
        ->where(function($query) use ($request, $id) {
            $query->where('classe', $request->classe)
                  ->orWhere('enseignant', $request->enseignant);
        })
        ->where('id', '!=', $id) // Exclure l'affectation actuelle
        ->first();
    
        if ($existingAffectation) {
            $conflit = '';
            if ($existingAffectation->classe == $request->classe) {
                $conflit = 'Cette classe a déjà un cours à cet horaire';
            } else {
                $conflit = 'Cet enseignant a déjà un cours à cet horaire';
            }
    
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'DUPLICATE_AFFECTATION',
                'message' => $conflit
            ], 422);
        }
    
        // Vérifier si le type de cours de l'horaire choisi correspond au champ type_cours
        $horaire = Horaire::find($request->horaire);
        if ($horaire->type_cours != $request->type_cours) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'code' => 'TYPE_COURS_MISMATCH',
                'message' => 'Le type de cours de l\'horaire choisi ne correspond pas au type de cours sélectionné'
            ], 422);
        }
    
        // Mise à jour des données
        $validatedData['annee_scolaire'] = $anneeScolaire->id;
        $affectation->update($validatedData);
    
        return response()->json(['success' => true]);
    }
    
   

    /**
     * Met à jour une affectation existante
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id Identifiant de l'affectation
     * @return \Illuminate\Http\JsonResponse
     */
    
}