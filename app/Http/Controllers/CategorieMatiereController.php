<?php

namespace App\Http\Controllers;

use App\DataTables\CategorieMatiereDataTable;
use App\Http\Requests\CreateCategorieMatiereRequest;
use App\Http\Requests\UpdateCategorieMatiereRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CategorieMatiereRepository;
use Illuminate\Http\Request;
use Flash;

class CategorieMatiereController extends AppBaseController
{
    /** @var CategorieMatiereRepository $categorieMatiereRepository*/
    private $categorieMatiereRepository;

    public function __construct(CategorieMatiereRepository $categorieMatiereRepo)
    {
        $this->categorieMatiereRepository = $categorieMatiereRepo;
    }

    /**
     * Display a listing of the CategorieMatiere.
     */
    public function index(CategorieMatiereDataTable $categorieMatiereDataTable)
    {
    return $categorieMatiereDataTable->render('categorie_matieres.index');
    }


    /**
     * Show the form for creating a new CategorieMatiere.
     */
    public function create()
    {
        return view('categorie_matieres.create');
    }

    /**
     * Store a newly created CategorieMatiere in storage.
     */
    public function store(CreateCategorieMatiereRequest $request)
    {
        $input = $request->all();

        $categorieMatiere = $this->categorieMatiereRepository->create($input);

        Flash::success('Enregistrement éffectué avec succès.');

        return redirect(route('categorieMatieres.index'));
    }

    /**
     * Display the specified CategorieMatiere.
     */
    public function show($id)
    {
        $categorieMatiere = $this->categorieMatiereRepository->find($id);

        if (empty($categorieMatiere)) {
            Flash::error('Categorie Matiere not found');

            return redirect(route('categorieMatieres.index'));
        }

        return view('categorie_matieres.show')->with('categorieMatiere', $categorieMatiere);
    }

    /**
     * Show the form for editing the specified CategorieMatiere.
     */
    public function edit($id)
    {
        $categorieMatiere = $this->categorieMatiereRepository->find($id);

        if (empty($categorieMatiere)) {
            Flash::error('Categorie Matiere not found');

            return redirect(route('categorieMatieres.index'));
        }

        return view('categorie_matieres.edit')->with('categorieMatiere', $categorieMatiere);
    }

    /**
     * Update the specified CategorieMatiere in storage.
     */
    public function update($id, UpdateCategorieMatiereRequest $request)
    {
        $categorieMatiere = $this->categorieMatiereRepository->find($id);

        if (empty($categorieMatiere)) {
            Flash::error('Categorie Matiere not found');

            return redirect(route('categorieMatieres.index'));
        }

        $categorieMatiere = $this->categorieMatiereRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('categorieMatieres.index'));
    }

    /**
     * Remove the specified CategorieMatiere from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $categorieMatiere = $this->categorieMatiereRepository->find($id);

        if (empty($categorieMatiere)) {
            Flash::error('Categorie Matiere not found');

            return redirect(route('categorieMatieres.index'));
        }

        $this->categorieMatiereRepository->delete($id);

        Flash::success('Suppression éffectué avec succès.');

        return redirect(route('categorieMatieres.index'));
    }
}
