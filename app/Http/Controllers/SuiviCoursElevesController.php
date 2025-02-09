<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuiviCoursElevesController extends Controller
{
    public function index(Request $request)
    {
        // Vérifier si l'utilisateur a le profil élève
        $user = Auth::user();
        $userProfil = DB::table('user_profil')
            ->join('profil', 'profil.id', '=', 'user_profil.profil')
            ->where('user_profil.user', $user->id)
            ->where('profil.libelle', 'Eleve')
            ->first();

        if (!$userProfil) {
            if ($request->ajax()) {
                return response()->json(['data' => []]);
            }
            return view('suiviCoursEleves.index')->with('hasAccess', false);
        }

        // Pour les requêtes AJAX (DataTables)
        if ($request->ajax()) {
            // Si aucune matière n'est sélectionnée, retourner un tableau vide
            if (!$request->has('matiere') || empty($request->matiere)) {
                return response()->json(['data' => []]);
            }

            // Récupérer la classe de l'élève pour l'année en cours
            $effectif = DB::table('effectif')
                ->where('eleve', $userProfil->eleve)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('annee_scolaire')
                        ->whereRaw('annee_scolaire.id = effectif.annee_scolaire')
                        ->where('en_cours', true);
                })
                ->first();

            if (!$effectif) {
                return response()->json(['data' => []]);
            }

            $query = DB::table('suivi_cours as sc')
                ->join('affectation_matiere as am', 'am.id', '=', 'sc.affection_matiere')
                ->join('annee_scolaire as a', 'a.id', '=', 'am.annee_scolaire')
                ->join('enseignant as e', 'e.id', '=', 'am.enseignant')
                ->where('am.classe', $effectif->classe)
                ->where('am.matiere', $request->matiere)
                ->where('a.en_cours', true)
                ->select([
                    'sc.date',
                    'sc.titre',
                    'sc.resume',
                    'sc.observation',
                    'am.type_cours',
                    'e.nom_prenom as enseignant'
                ]);

            return datatables()->of($query)->toJson();
        }

        // Pour l'affichage initial de la page
        return view('suiviCoursEleves.index')
            ->with('hasAccess', true);
    }

    public function getMatieres(Request $request)
    {
        // Vérifier si l'utilisateur a le profil élève
        $user = Auth::user();
        $userProfil = DB::table('user_profil')
            ->join('profil', 'profil.id', '=', 'user_profil.profil')
            ->where('user_profil.user', $user->id)
            ->where('profil.libelle', 'Eleve')
            ->first();

        if (!$userProfil) {
            return response()->json([]);
        }

        // Récupérer la classe de l'élève pour l'année en cours
        $effectif = DB::table('effectif')
            ->where('eleve', $userProfil->eleve)
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

        // Récupérer uniquement les matières affectées à la classe de l'élève
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