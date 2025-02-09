<?php

namespace App\Http\Controllers;

use App\DataTables\SuiviCoursParentsDataTable;
use App\Http\Requests\CreateSuiviCoursRequest;
use App\Http\Requests\UpdateSuiviCoursRequest;
use App\Models\SuiviCours;
use App\Models\Parents;
use App\Models\Eleve;
use App\Models\Effectif;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Response;

class SuiviCoursParentsController extends AppBaseController
{
    public function index(Request $request)
    {
        // Vérifier si l'utilisateur a le profil parent
        $user = Auth::user();
        $userProfil = DB::table('user_profil')
            ->join('profil', 'profil.id', '=', 'user_profil.profil')
            ->where('user_profil.user', $user->id)
            ->where('profil.libelle', 'Parent')
            ->first();

        if (!$userProfil) {
            if ($request->ajax()) {
                return response()->json(['data' => []]);
            }
            Flash::error('Vous n\'avez pas accès à cette page.');
            return view('suiviCoursParents.index')->with('hasAccess', false);
        }

        // Récupérer le parent correspondant à l'utilisateur connecté
        $parent = DB::table('parent')
            ->where('id', $userProfil->parent)
            ->first();

        if (!$parent) {
            if ($request->ajax()) {
                return response()->json(['data' => []]);
            }
            Flash::error('Aucun parent trouvé pour votre compte.');
            return view('suiviCoursParents.index')->with('hasAccess', false);
        }
        
        // Récupérer tous les enfants liés à ce parent
        $enfants = DB::table('eleve')
            ->where('parent', $parent->id)
            ->get();

        if ($enfants->isEmpty()) {
            if ($request->ajax()) {
                return response()->json(['data' => []]);
            }
            Flash::error('Aucun enfant n\'est associé à votre compte.');
            return view('suiviCoursParents.index')->with('hasAccess', false);
        }

        // Pour les requêtes AJAX (DataTables)
        if ($request->ajax()) {
            // Si aucun élève ou aucune matière n'est sélectionnée, retourner un tableau vide
            if (!$request->has('eleve') || $request->eleve == '' || 
                !$request->has('matiere') || $request->matiere == '') {
                return response()->json(['data' => []]);
            }

            $query = DB::table('suivi_cours as sc')
                ->join('affectation_matiere as am', 'am.id', '=', 'sc.affection_matiere')
                ->join('classe as c', 'c.id', '=', 'am.classe')
                ->join('matiere as m', 'm.id', '=', 'am.matiere')
                ->join('annee_scolaire as a', 'a.id', '=', 'am.annee_scolaire')
                ->join('enseignant as e', 'e.id', '=', 'am.enseignant')
                ->select([
                    'sc.date',
                    'c.libelle as classe',
                    'm.libelle as matiere',
                    'sc.titre',
                    'sc.resume',
                    'sc.observation',
                    'am.type_cours',
                    'e.nom_prenom as enseignant'
                ])
                ->where('a.en_cours', true);

            // Récupérer l'effectif de l'élève
            $effectif = DB::table('effectif')
                ->where('eleve', $request->eleve)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('annee_scolaire')
                        ->whereRaw('annee_scolaire.id = effectif.annee_scolaire')
                        ->where('en_cours', true);
                })
                ->first();

            if ($effectif) {
                $query->where('am.classe', $effectif->classe);

                // Filtrer par matière si spécifiée
                if ($request->has('matiere') && $request->matiere != '') {
                    $query->where('am.matiere', $request->matiere);
                }

                return datatables()->of($query)->toJson();
            }

            return response()->json(['data' => []]);
        }

        // Pour l'affichage initial de la page
        return view('suiviCoursParents.index')
            ->with('hasAccess', true)
            ->with('enfants', $enfants->pluck('nom_prenom', 'id'));
    }

    public function show($id)
    {
        $suiviCours = SuiviCours::find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi de cours non trouvé');
            return redirect(route('suiviCoursParents.index'));
        }

        return view('suivi_cours_parents.show')->with('suiviCours', $suiviCours);
    }

    // Méthode pour charger les matières d'un élève via AJAX
    public function getMatieres(Request $request)
    {
        $eleveId = $request->input('eleve');
        
        if (!$eleveId) {
            return response()->json([]);
        }

        // Récupérer la classe de l'élève pour l'année en cours
        $effectif = DB::table('effectif')
            ->where('eleve', $eleveId)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('annee_scolaire')
                    ->whereRaw('annee_scolaire.id = effectif.annee_scolaire')
                    ->where('en_cours', true);
            })
            ->first();

        if (!$effectif) {
            return response()->json([]);
        }

        // Récupérer les matières de la classe
        $matieres = DB::table('affectation_matiere as am')
            ->join('matiere as m', 'm.id', '=', 'am.matiere')
            ->join('annee_scolaire as a', 'a.id', '=', 'am.annee_scolaire')
            ->where('am.classe', $effectif->classe)
            ->where('a.en_cours', true)
            ->select('m.id', 'm.libelle')
            ->distinct()
            ->orderBy('m.libelle')
            ->get();

        return response()->json($matieres);
    }
}
