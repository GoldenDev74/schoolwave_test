<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSuiviCoursRequest;
use App\Http\Requests\UpdateSuiviCoursRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\SuiviCours;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use App\Models\AffectationMatiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Response;
use App\DataTables\SuiviCoursDataTable;
use Illuminate\Support\Facades\Auth;

class SuiviCoursEnseignantController extends AppBaseController
{
    public function index(Request $request)
    {
        // Récupérer le nom de l'utilisateur connecté
        $userName = Auth::user()->name;
        
        // Chercher l'ID de l'enseignant correspondant au nom d'utilisateur
        $enseignant = DB::table('enseignant')
            ->where('nom_prenom', $userName)
            ->first();
            
        if (!$enseignant) {
            Flash::error('Aucun enseignant trouvé pour votre compte.');
            return redirect()->back();
        }

        // Récupérer les suivis de cours de l'enseignant
        $suiviCoursEnseignant = DB::table('suivi_cours as sc')
            ->join('affectation_matiere as am', 'am.id', '=', 'sc.affection_matiere')
            ->where('am.enseignant', $enseignant->id)
            ->select(
                'sc.*',
                'am.id as affectation_id'
            )
            ->orderBy('sc.date', 'desc')
            ->paginate(10);

        return view('suiviCoursEnseignant.index')
            ->with('suiviCoursEnseignant', $suiviCoursEnseignant);
    }

    public function create()
    {
        // Récupérer le nom de l'utilisateur connecté
        $userName = Auth::user()->name;
        
        // Chercher l'ID de l'enseignant correspondant au nom d'utilisateur
        $enseignant = DB::table('enseignant')
            ->where('nom_prenom', $userName)
            ->first();
            
        if (!$enseignant) {
            Flash::error('Aucun enseignant trouvé pour votre compte.');
            return redirect()->back();
        }

        // Récupérer les affectations matières de l'enseignant avec les détails
        $affectationMatieres = DB::table('affectation_matiere as am')
            ->join('classe as c', 'c.id', '=', 'am.classe')
            ->join('matiere as m', 'm.id', '=', 'am.matiere')
            ->where('am.enseignant', $enseignant->id)
            ->select(
                'am.id',
                'm.libelle as matiere_nom',
                'c.libelle as classe_nom',
                'am.horaire',
                'am.type_cours'
            )
            ->get()
            ->mapWithKeys(function($affectation) {
                $typeCoursLabel = $affectation->type_cours == 1 ? 'Cours' : 'TD';
                return [
                    $affectation->id => sprintf(
                        '%s - %s %s - %sH',
                        $affectation->matiere_nom,
                        $affectation->classe_nom,
                        $typeCoursLabel,
                        $affectation->horaire
                    )
                ];
            });

        if ($affectationMatieres->isEmpty()) {
            Flash::error('Aucune affectation de matière trouvée pour votre compte.');
            return redirect()->back();
        }

        return view('suiviCoursEnseignant.create', ['affectationMatieres' => $affectationMatieres]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->validate([
                'date' => 'required|date',
                'titre' => 'required|string|max:100',
                'resume' => 'required|string|max:100',
                'observation' => 'required|string|max:100',
                'affection_matiere' => 'required|exists:affectation_matiere,id'
            ]);

            // Créer le suivi de cours
            DB::table('suivi_cours')->insert([
                'date' => $input['date'],
                'titre' => $input['titre'],
                'resume' => $input['resume'],
                'observation' => $input['observation'],
                'affection_matiere' => $input['affection_matiere'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            Flash::success('Suivi de cours créé avec succès.');
            return redirect(route('suiviCoursEnseignant.index'));

        } catch (\Exception $e) {
            DB::rollback();
            Flash::error('Erreur lors de la création du suivi. Veuillez réessayer.');
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {
        // Récupérer le nom de l'utilisateur connecté
        $userName = Auth::user()->name;
        
        // Chercher l'ID de l'enseignant
        $enseignant = DB::table('enseignant')
            ->where('nom_prenom', $userName)
            ->first();
            
        if (!$enseignant) {
            Flash::error('Aucun enseignant trouvé pour votre compte.');
            return redirect()->back();
        }

        // Vérifier que le suivi appartient à l'enseignant
        $suiviCours = DB::table('suivi_cours as sc')
            ->join('affectation_matiere as am', 'am.id', '=', 'sc.affection_matiere')
            ->join('classe as c', 'c.id', '=', 'am.classe')
            ->join('matiere as m', 'm.id', '=', 'am.matiere')
            ->where('sc.id', $id)
            ->where('am.enseignant', $enseignant->id)
            ->select(
                'sc.*',
                'm.libelle as matiere_nom',
                'c.libelle as classe_nom',
                'am.horaire',
                'am.type_cours'
            )
            ->first();

        if (!$suiviCours) {
            Flash::error('Vous n\'êtes pas autorisé à voir ce suivi.');
            return redirect()->back();
        }

        // Formater l'affichage de l'affectation
        $typeCoursLabel = $suiviCours->type_cours == 1 ? 'Cours' : 'TD';
        $suiviCours->affectation_display = sprintf(
            '%s - %s %s - %sH',
            $suiviCours->matiere_nom,
            $suiviCours->classe_nom,
            $typeCoursLabel,
            $suiviCours->horaire
        );

        return view('suiviCoursEnseignant.show')->with('suiviCours', $suiviCours);
    }

    public function edit($id)
    {
        // Récupérer le nom de l'utilisateur connecté
        $userName = Auth::user()->name;
        
        // Chercher l'ID de l'enseignant
        $enseignant = DB::table('enseignant')
            ->where('nom_prenom', $userName)
            ->first();
            
        if (!$enseignant) {
            Flash::error('Aucun enseignant trouvé pour votre compte.');
            return redirect()->back();
        }

        // Récupérer le suivi à modifier
        $suiviCours = DB::table('suivi_cours')
            ->join('affectation_matiere as am', 'am.id', '=', 'affection_matiere')
            ->where('suivi_cours.id', $id)
            ->where('am.enseignant', $enseignant->id)
            ->select('suivi_cours.*')
            ->first();

        if (!$suiviCours) {
            Flash::error('Vous n\'êtes pas autorisé à modifier ce suivi.');
            return redirect()->back();
        }

        // Récupérer les affectations matières pour le select
        $affectationMatieres = DB::table('affectation_matiere as am')
            ->join('classe as c', 'c.id', '=', 'am.classe')
            ->join('matiere as m', 'm.id', '=', 'am.matiere')
            ->where('am.enseignant', $enseignant->id)
            ->select(
                'am.id',
                'm.libelle as matiere_nom',
                'c.libelle as classe_nom',
                'am.horaire',
                'am.type_cours'
            )
            ->get()
            ->mapWithKeys(function($affectation) {
                $typeCoursLabel = $affectation->type_cours == 1 ? 'Cours' : 'TD';
                return [
                    $affectation->id => sprintf(
                        '%s - %s %s - %sH',
                        $affectation->matiere_nom,
                        $affectation->classe_nom,
                        $typeCoursLabel,
                        $affectation->horaire
                    )
                ];
            });

        // Convertir l'objet en array pour le Form::model
        $suiviCours = (array) $suiviCours;

        return view('suiviCoursEnseignant.create')
            ->with('suiviCours', $suiviCours)
            ->with('affectationMatieres', $affectationMatieres)
            ->with('isEdit', true);
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Récupérer le nom de l'utilisateur connecté
            $userName = Auth::user()->name;
            
            // Chercher l'ID de l'enseignant
            $enseignant = DB::table('enseignant')
                ->where('nom_prenom', $userName)
                ->first();
                
            if (!$enseignant) {
                throw new \Exception('Aucun enseignant trouvé pour votre compte.');
            }

            // Vérifier que le suivi appartient à l'enseignant
            $suiviCours = DB::table('suivi_cours as sc')
                ->join('affectation_matiere as am', 'am.id', '=', 'sc.affection_matiere')
                ->where('sc.id', $id)
                ->where('am.enseignant', $enseignant->id)
                ->first();

            if (!$suiviCours) {
                throw new \Exception('Vous n\'êtes pas autorisé à modifier ce suivi.');
            }

            $input = $request->validate([
                'date' => 'required|date',
                'titre' => 'required|string|max:100',
                'resume' => 'required|string|max:100',
                'observation' => 'required|string|max:100',
                'affection_matiere' => 'required|exists:affectation_matiere,id'
            ]);

            // Vérifier que la nouvelle affectation appartient à l'enseignant
            $newAffectation = DB::table('affectation_matiere')
                ->where('id', $input['affection_matiere'])
                ->where('enseignant', $enseignant->id)
                ->first();

            if (!$newAffectation) {
                throw new \Exception('L\'affectation sélectionnée n\'est pas valide.');
            }

            // Mettre à jour le suivi
            DB::table('suivi_cours')
                ->where('id', $id)
                ->update([
                    'date' => $input['date'],
                    'titre' => $input['titre'],
                    'resume' => $input['resume'],
                    'observation' => $input['observation'],
                    'affection_matiere' => $input['affection_matiere'],
                    'updated_at' => now()
                ]);

            DB::commit();
            Flash::success('Suivi de cours mis à jour avec succès.');
            return redirect(route('suiviCoursEnseignant.index'));

        } catch (\Exception $e) {
            DB::rollback();
            Flash::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $suiviCours = SuiviCours::find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi Cours non trouvé');
            return redirect(route('suiviCoursEnseignant.index'));
        }

        // Vérifier que le suivi appartient à l'enseignant
        $affectationMatiere = DB::table('affectation_matiere')
            ->where('id', $suiviCours->affection_matiere)
            ->where('enseignant', Auth::id())
            ->first();

        if (!$affectationMatiere) {
            Flash::error('Vous n\'êtes pas autorisé à supprimer ce suivi');
            return redirect(route('suiviCoursEnseignant.index'));
        }

        $suiviCours->delete();

        Flash::success('Suivi Cours supprimé avec succès.');

        return redirect(route('suiviCoursEnseignant.index'));
    }
}
