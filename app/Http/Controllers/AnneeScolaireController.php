<?php

namespace App\Http\Controllers;

use App\DataTables\AnneeScolaireDataTable;
use App\Http\Requests\CreateAnneeScolaireRequest;
use App\Http\Requests\UpdateAnneeScolaireRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AnneeScolaireRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class AnneeScolaireController extends AppBaseController
{
    /** @var AnneeScolaireRepository $anneeScolaireRepository*/
    private $anneeScolaireRepository;

    public function __construct(AnneeScolaireRepository $anneeScolaireRepo)
    {
        $this->anneeScolaireRepository = $anneeScolaireRepo;
    }

    /**
     * Display a listing of the AnneeScolaire.
     */
    public function index(AnneeScolaireDataTable $anneeScolaireDataTable)
    {
    return $anneeScolaireDataTable->render('annee_scolaires.index');
    }


    /**
     * Show the form for creating a new AnneeScolaire.
     */
    public function create()
    {
        return view('annee_scolaires.create');
    }

    /**
     * Store a newly created AnneeScolaire in storage.
     */
    public function store(CreateAnneeScolaireRequest $request)
    {
        
        $input = $request->all();

        $this->anneeScolaireRepository->updateAll(['en_cours' => false]);

        $anneeScolaire = $this->anneeScolaireRepository->create($input);

        Flash::success('Enregistrement éffectué avec succès.');

        return redirect(route('anneeScolaires.index'));
    }

    /**
     * Display the specified AnneeScolaire.
     */
    public function show($id)
    {
        $anneeScolaire = $this->anneeScolaireRepository->find($id);

        if (empty($anneeScolaire)) {
            Flash::error('Annee Scolaire not found');

            return redirect(route('anneeScolaires.index'));
        }

        return view('annee_scolaires.show')->with('anneeScolaire', $anneeScolaire);
    }

    /**
     * Show the form for editing the specified AnneeScolaire.
     */
    public function edit($id)
    {
        $anneeScolaire = $this->anneeScolaireRepository->find($id);

        if (empty($anneeScolaire)) {
            Flash::error('Annee Scolaire not found');

            return redirect(route('anneeScolaires.index'));
        }

        return view('annee_scolaires.edit')->with('anneeScolaire', $anneeScolaire);
    }

    /**
     * Update the specified AnneeScolaire in storage.
     */
    public function update($id, UpdateAnneeScolaireRequest $request)
    {
        $anneeScolaire = $this->anneeScolaireRepository->find($id);

        if (empty($anneeScolaire)) {
            Flash::error('Annee Scolaire not found');

            return redirect(route('anneeScolaires.index'));
        }

        $anneeScolaire = $this->anneeScolaireRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('anneeScolaires.index'));
    }

    /**
     * Remove the specified AnneeScolaire from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $anneeScolaire = $this->anneeScolaireRepository->find($id);

        if (empty($anneeScolaire)) {
            Flash::error('Annee Scolaire not found');

            return redirect(route('anneeScolaires.index'));
        }

        $this->anneeScolaireRepository->delete($id);

        Flash::success('Suppression éffectué avec succès.');

        return redirect(route('anneeScolaires.index'));
    }
}
