<?php

namespace App\Http\Controllers;

use App\DataTables\MatiereDataTable;
use App\Http\Requests\CreateMatiereRequest;
use App\Http\Requests\UpdateMatiereRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MatiereRepository;
use Illuminate\Http\Request;
use App\Models\CategorieMatiere;
use Laracasts\Flash\Flash;

class MatiereController extends AppBaseController
{
    /** @var MatiereRepository $matiereRepository*/
    private $matiereRepository;

    public function __construct(MatiereRepository $matiereRepo)
    {
        $this->matiereRepository = $matiereRepo;
    }

    /**
     * Display a listing of the Matiere.
     */
    public function index(MatiereDataTable $matiereDataTable)
    {
    return $matiereDataTable->render('matieres.index');
    }


    /**
     * Show the form for creating a new Matiere.
     */
    public function create()
    {
    
        $categorie_matiere = CategorieMatiere::pluck('libelle','id');
        return view('matieres.create')->with('categorie_matiere', $categorie_matiere);

    }

    /**
     * Store a newly created Matiere in storage.
     */
    public function store(CreateMatiereRequest $request)
    {
        $input = $request->all();

        $matiere = $this->matiereRepository->create($input);

        Flash::success('Enregistrement effectué avec succès!');

        return redirect(route('matieres.index'));
    }

    /**
     * Display the specified Matiere.
     */
    public function show($id)
    {
        $matiere = $this->matiereRepository->find($id);

        if (empty($matiere)) {
            Flash::error('Matiere not found');

            return redirect(route('matieres.index'));
        }

        return view('matieres.show')->with('matiere', $matiere);
    }

    /**
     * Show the form for editing the specified Matiere.
     */
    public function edit($id)
    {
        $matiere = $this->matiereRepository->find($id);

        if (empty($matiere)) {
            Flash::error('Matiere not found');

            return redirect(route('matieres.index'));
        }

        return view('matieres.edit')->with('matiere', $matiere);
    }

    /**
     * Update the specified Matiere in storage.
     */
    public function update($id, UpdateMatiereRequest $request)
    {
        $matiere = $this->matiereRepository->find($id);

        if (empty($matiere)) {
            Flash::error('Matiere not found');

            return redirect(route('matieres.index'));
        }

        $matiere = $this->matiereRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('matieres.index'));
    }

    /**
     * Remove the specified Matiere from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $matiere = $this->matiereRepository->find($id);

        if (empty($matiere)) {
            Flash::error('Matiere not found');

            return redirect(route('matieres.index'));
        }

        $this->matiereRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('matieres.index'));
    }
}
