<?php

namespace App\Http\Controllers;

use App\DataTables\DiplomeDataTable;
use App\Http\Requests\CreateDiplomeRequest;
use App\Http\Requests\UpdateDiplomeRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DiplomeRepository;
use Illuminate\Http\Request;
use Flash;

class DiplomeController extends AppBaseController
{
    /** @var DiplomeRepository $diplomeRepository*/
    private $diplomeRepository;

    public function __construct(DiplomeRepository $diplomeRepo)
    {
        $this->diplomeRepository = $diplomeRepo;
    }

    /**
     * Display a listing of the Diplome.
     */
    public function index(DiplomeDataTable $diplomeDataTable)
    {
    return $diplomeDataTable->render('diplomes.index');
    }


    /**
     * Show the form for creating a new Diplome.
     */
    public function create()
    {
        return view('diplomes.create');
    }

    /**
     * Store a newly created Diplome in storage.
     */
    public function store(CreateDiplomeRequest $request)
    {
        $input = $request->all();

        $diplome = $this->diplomeRepository->create($input);

        Flash::success('Enregistrement effectué avec succès!');

        return redirect(route('diplomes.index'));
    }

    /**
     * Display the specified Diplome.
     */
    public function show($id)
    {
        $diplome = $this->diplomeRepository->find($id);

        if (empty($diplome)) {
            Flash::error('Diplome non trouvé');

            return redirect(route('diplomes.index'));
        }

        return view('diplomes.show')->with('diplome', $diplome);
    }

    /**
     * Show the form for editing the specified Diplome.
     */
    public function edit($id)
    {
        $diplome = $this->diplomeRepository->find($id);

        if (empty($diplome)) {
            Flash::error('Diplome non trouvé');

            return redirect(route('diplomes.index'));
        }

        return view('diplomes.edit')->with('diplome', $diplome);
    }

    /**
     * Update the specified Diplome in storage.
     */
    public function update($id, UpdateDiplomeRequest $request)
    {
        $diplome = $this->diplomeRepository->find($id);

        if (empty($diplome)) {
            Flash::error('Diplome non trouvé');

            return redirect(route('diplomes.index'));
        }

        $diplome = $this->diplomeRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('diplomes.index'));
    }

    /**
     * Remove the specified Diplome from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $diplome = $this->diplomeRepository->find($id);

        if (empty($diplome)) {
            Flash::error('Diplome non trouvé');

            return redirect(route('diplomes.index'));
        }

        $this->diplomeRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!.');

        return redirect(route('diplomes.index'));
    }
}
