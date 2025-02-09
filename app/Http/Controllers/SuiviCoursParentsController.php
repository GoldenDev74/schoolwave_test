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
use Flash;
use Response;
use Auth;
use DB;

class SuiviCoursParentsController extends AppBaseController
{
    public function index(SuiviCoursParentsDataTable $dataTable)
    {
        // Vérifier si l'utilisateur a le profil parent
        $user = Auth::user();
        $userProfil = DB::table('user_profil')
            ->join('profil', 'profil.id', '=', 'user_profil.profil')
            ->where('user_profil.user', $user->id)
            ->where('profil.libelle', 'Parent')
            ->first();

        if (!$userProfil) {
            Flash::error('Vous n\'avez pas accès à cette page.');
            return view('suiviCoursParents.index')->with('hasAccess', false);
        }

        // Récupérer le parent correspondant à l'utilisateur connecté
        $parent = DB::table('parent')
            ->where('id', $userProfil->parent)
            ->first();

        if (!$parent) {
            Flash::error('Aucun parent trouvé pour votre compte.');
            return view('suiviCoursParents.index')->with('hasAccess', false);
        }
        
        // Récupérer tous les enfants liés à ce parent
        $enfants = DB::table('eleve')
            ->where('parent', $parent->id)
            ->get();

        if ($enfants->isEmpty()) {
            Flash::error('Aucun enfant n\'est associé à votre compte.');
            return view('suiviCoursParents.index')->with('hasAccess', false);
        }

        // Récupérer les effectifs des enfants
        $effectifs = DB::table('effectif')
            ->join('annee_scolaire', 'annee_scolaire.id', '=', 'effectif.annee_scolaire')
            ->join('classe', 'classe.id', '=', 'effectif.classe')
            ->whereIn('effectif.eleve', $enfants->pluck('id'))
            ->where('annee_scolaire.en_cours', true)
            ->get();

        // Récupérer les suivis de cours pour les classes des enfants
        $suivis = DB::table('suivi_cours as sc')
            ->join('affectation_matiere as am', 'am.id', '=', 'sc.affection_matiere')
            ->join('classe as c', 'c.id', '=', 'am.classe')
            ->join('matiere as m', 'm.id', '=', 'am.matiere')
            ->whereIn('am.classe', $effectifs->pluck('classe'))
            ->select(
                'sc.*',
                'm.libelle as matiere',
                'c.libelle as classe',
                'am.type_cours',
                'am.horaire'
            )
            ->get();

        // Préparer la liste des enfants pour le filtre
        $enfantsList = $enfants->mapWithKeys(function($enfant) {
            return [$enfant->id => $enfant->nom_prenom];
        });

        return $dataTable->render('suiviCoursParents.index', [
            'hasAccess' => true,
            'enfants' => $enfantsList,
            'matieres' => DB::table('matiere')->pluck('libelle', 'id')
        ]);
    }

    public function show($id)
    {
        $suiviCours = SuiviCours::find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi Cours not found');
            return redirect(route('suiviCoursParents.index'));
        }

        return view('suivi_cours_parents.show')->with('suiviCours', $suiviCours);
    }

    // Méthode pour charger les matières d'un élève via AJAX
    public function getMatieres(Request $request)
    {
        $eleveId = $request->get('eleve_id');
        
        // Récupérer l'effectif (classe) actuel de l'élève
        $effectif = Effectif::where('eleve', $eleveId)
            ->whereHas('anneeScolaires', function($q) {
                $q->where('en_cours', true);
            })
            ->first();

        if (!$effectif) {
            return response()->json([]);
        }

        // Récupérer les matières de la classe
        $matieres = Matiere::whereHas('affectationMatieres', function($query) use ($effectif) {
            $query->where('classe', $effectif->classe);
        })->pluck('libelle', 'id');

        return response()->json($matieres);
    }
}