<?php

namespace App\Http\Controllers;

use App\DataTables\SuiviCoursElevesDataTable;
use App\Models\SuiviCours;
use App\Models\User;
use App\Models\UserProfil;
use App\Models\Effectif;
use App\Models\AffectationMatiere;
use App\Models\Eleve;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuiviCoursElevesController extends AppBaseController
{
    public function index(SuiviCoursElevesDataTable $dataTable)
    {
        // Vérifier si l'utilisateur a le profil élève
        $user = Auth::user();
        $userProfil = DB::table('user_profil')
            ->join('profil', 'profil.id', '=', 'user_profil.profil')
            ->where('user_profil.user', $user->id)
            ->where('profil.libelle', 'Eleve')
            ->first();

        if (!$userProfil) {
            Flash::error('Vous n\'avez pas accès à cette page.');
            return view('suiviCoursEleves.index')->with('hasAccess', false);
        }

        // Si on arrive ici, c'est que l'utilisateur est un élève
        // Récupérer l'élève correspondant à l'utilisateur connecté
        $eleve = DB::table('eleve')
            ->where('id', $userProfil->eleve)
            ->first();

        if (!$eleve) {
            Flash::error('Aucun élève trouvé pour votre compte.');
            return view('suiviCoursEleves.index')->with('hasAccess', false);
        }
        
        // Récupérer l'effectif (classe) actuel de l'élève
        $effectif = DB::table('effectif')
            ->join('annee_scolaire', 'annee_scolaire.id', '=', 'effectif.annee_scolaire')
            ->where('effectif.eleve', $eleve->id)
            ->where('annee_scolaire.en_cours', true)
            ->first();

        if (!$effectif) {
            Flash::error('Vous n\'êtes pas inscrit dans une classe pour l\'année en cours.');
            return view('suiviCoursEleves.index')->with('hasAccess', false);
        }

        // Récupérer les suivis de cours pour la classe de l'élève
        $suivis = DB::table('suivi_cours as sc')
            ->join('affectation_matiere as am', 'am.id', '=', 'sc.affection_matiere')
            ->join('classe as c', 'c.id', '=', 'am.classe')
            ->join('matiere as m', 'm.id', '=', 'am.matiere')
            ->where('am.classe', $effectif->classe)
            ->select(
                'sc.*',
                'm.libelle as matiere',
                'c.libelle as classe',
                'am.type_cours',
                'am.horaire'
            )
            ->get();

        return view('suiviCoursEleves.index')
            ->with('hasAccess', true)
            ->with('suivis', $suivis)
            ->with('matieres', DB::table('matiere')->pluck('libelle', 'id'));
    }

    public function show($id)
    {
        $suiviCours = SuiviCours::find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi de cours non trouvé');
            return redirect(route('suiviCoursEleves.index'));
        }

        return view('suivi_cours_eleves.show', compact('suiviCours'));
    }
}