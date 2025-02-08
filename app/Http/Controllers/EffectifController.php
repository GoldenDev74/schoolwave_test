<?php

namespace App\Http\Controllers;

use App\DataTables\EffectifDataTable;
use App\Http\Requests\CreateEffectifRequest;
use App\Http\Requests\UpdateEffectifRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EffectifRepository;
use Illuminate\Http\Request;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use Flash;

class EffectifController extends AppBaseController
{
/** @var EffectifRepository $effectifRepository*/
    private $effectifRepository;
    public function __construct(EffectifRepository $effectifRepo)
    {
        $this->effectifRepository = $effectifRepo;
    }

/**
 * Display a listing of the Effectif.
 */
    public function index(Request $request)
    {
        // Récupérer l'année scolaire en cours
        $anneeEnCours = AnneeScolaire::where('en_cours', true)->first();

        if ($request->has('classe')) {
            $eleves = \App\Models\Eleve::with(['sexes', 'parents', 'effectifs'])
                ->whereHas('effectifs', function ($query) use ($request, $anneeEnCours) {
                    $query->where('classe', $request->classe)
                        ->where('annee_scolaire', $anneeEnCours->id);
                })->get();
        } else {
            $eleves = collect();
        }

        return view('effectifs.index', compact('eleves'));
    }


/**
 * Show the form for creating a new Effectif.
 */
    public function create()
    {
        $currentAnneeScolaire = AnneeScolaire::where('en_cours', true)->first();
        $anneeScolaires = AnneeScolaire::pluck('libelle', 'id');
        $classes = Classe::pluck('libelle', 'id');
        $eleves = Eleve::whereDoesntHave('effectifs', function ($query) use ($currentAnneeScolaire) {
            $query->where('annee_scolaire', $currentAnneeScolaire->id);
        })->pluck('nom_prenom', 'id');
        return view('effectifs.create')->with('annees_scolaires', $anneeScolaires)->with('classes', $classes)->with('eleves', $eleves)->with('current_annee_scolaire', $currentAnneeScolaire);
    }

/**
 * Store a newly created Effectif in storage.
 */
    public function store(CreateEffectifRequest $request)
    {
        $input = $request->all();

        // Vérifier si l'élève est déjà inscrit dans une classe pour cette année scolaire
        $effectifExistant = \App\Models\Effectif::where('eleve', $input['eleve'])
            ->where('annee_scolaire', $input['annee_scolaire'])
            ->first();

        if ($effectifExistant) {
            $classe = \App\Models\Classe::find($effectifExistant->classe);
            Flash::error("Cet élève est déjà inscrit dans la classe " . $classe->libelle . " pour cette année scolaire.");
            return redirect()->back()->withInput();
        }

        $effectif = $this->effectifRepository->create($input);
        $eleve = \App\Models\Eleve::find($input['eleve']);
        $classe = \App\Models\Classe::find($input['classe']);

        Flash::success("L'élève " . $eleve->nom_prenom . " a été ajouté à la classe " . $classe->libelle . " avec succès.");

        return redirect(route('effectifs.index'));
    }

/**
 * Display the specified Effectif.
 */
    public function show($id)
    {
        $effectif = \App\Models\Effectif::with(['eleves.nationalites', 'eleves.paysResidence', 'eleves.parents', 'eleves.sexes', 'classes', 'anneeScolaires'])
            ->find($id);

        if (empty($effectif)) {
            Flash::error('Effectif non trouvé');
            return redirect(route('effectifs.index'));
        }

        return view('effectifs.show', compact('effectif'));
    }

/**
 * Show the form for editing the specified Effectif.
 */
    public function edit($id)
    {
        $effectif = \App\Models\Effectif::with(['eleves.nationalites', 'eleves.paysResidence', 'eleves.parents', 'eleves.sexes', 'classes', 'anneeScolaires'])
        ->find($id);

        if (empty($effectif)) {
            Flash::error('Effectif non trouvé');
            return redirect(route('effectifs.index'));
        }
        $anneeScolaires = AnneeScolaire::pluck('libelle', 'id');
        $classes = Classe::pluck('libelle', 'id');
        $currentClass = $effectif->classe;

        $currentAnneeScolaire = AnneeScolaire::where('en_cours', true)->first();
        $eleves = Eleve::whereDoesntHave('effectifs', function ($query) use ($currentAnneeScolaire, $effectif) {
            $query->where('annee_scolaire', $currentAnneeScolaire->id)
                ->where('eleve', '!=', $effectif->eleve);
        })->orWhere('id', $effectif->eleve)->pluck('nom_prenom', 'id');

        return view('effectifs.edit')
        ->with('effectif', $effectif)
            ->with('annees_scolaires', $anneeScolaires)
            ->with('classes', $classes)
            ->with('eleves', $eleves)
            ->with('current_annee_scolaire', $currentAnneeScolaire);
    }


/**
 * Update the specified Effectif in storage.
 */
    public function update($id, UpdateEffectifRequest $request)
    {
        $effectif = $this->effectifRepository->find($id);

        if (empty($effectif)) {
            Flash::error('Effectif non trouvé');
            return redirect(route('effectifs.index'));
        }

        $input = $request->all();

        // Vérifier si l'élève est déjà inscrit dans une autre classe pour cette année scolaire
        $effectifExistant = \App\Models\Effectif::where('eleve', $input['eleve'])
            ->where('annee_scolaire', $input['annee_scolaire'])
            ->where('id', '!=', $id)  // Exclure l'effectif actuel
            ->first();

        if ($effectifExistant) {
            $classe = \App\Models\Classe::find($effectifExistant->classe);
            Flash::error("Cet élève est déjà inscrit dans la classe " . $classe->libelle . " pour cette année scolaire.");
            return redirect()->back()->withInput();
        }

        $effectif = $this->effectifRepository->update($input, $id);

        Flash::success('Effectif mis à jour avec succès.');

        return redirect(route('effectifs.index'));
    }

/**
 * Remove the specified Effectif from storage.
 *
 * @throws \Exception
 */
    public function destroy($id)
    {
        $effectif = $this->effectifRepository->find($id);

        if (empty($effectif)) {
            Flash::error('Effectif non trouvé');
            return redirect(route('effectifs.index'));
        }

        // Vérifier les dépendances dans la table `controle`
        $dependencies = \App\Models\Controle::where('effectif', $id)->exists();

        if ($dependencies) {
            Flash::error("Impossible de supprimer cet effectif car il est utilisé dans la vue 'controle'.");
            return redirect(route('effectifs.index'));
        }

        $classe = \App\Models\Classe::find($effectif->classe);
        $eleve = \App\Models\Eleve::find($effectif->eleve);

        $this->effectifRepository->delete($id);

        Flash::success("L'élève " . $eleve->nom_prenom . " a été retiré de la classe " . $classe->libelle);

        return redirect(route('effectifs.index') . '?classe=' . $classe->id);
    }



}
