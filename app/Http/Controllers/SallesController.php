<?php

namespace App\Http\Controllers;

use App\DataTables\SallesDataTable;
use App\Http\Requests\CreateSallesRequest;
use App\Http\Requests\UpdateSallesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SallesRepository;
use Illuminate\Http\Request;
use Flash;

class SallesController extends AppBaseController
{
    /** @var SallesRepository $sallesRepository*/
    private $sallesRepository;

    public function __construct(SallesRepository $sallesRepo)
    {
        $this->sallesRepository = $sallesRepo;
    }

    /**
     * Display a listing of the Salles.
     */
    public function index(SallesDataTable $sallesDataTable)
    {
    return $sallesDataTable->render('salles.index');
    }


    /**
     * Show the form for creating a new Salles.
     */
    public function create()
    {
        return view('salles.create');
    }

    /**
     * Store a newly created Salles in storage.
     */
    public function store(CreateSallesRequest $request)
    {
        $input = $request->all();

        $salles = $this->sallesRepository->create($input);

        Flash::success('Enregistrement effectué avec succès!');

        return redirect(route('salles.index'));
    }

    /**
     * Display the specified Salles.
     */
    public function show($id)
    {
        $salles = $this->sallesRepository->find($id);

        if (empty($salles)) {
            Flash::error('Salle non trouvé ');

            return redirect(route('salles.index'));
        }

        return view('salles.show')->with('salles', $salles);
    }

    /**
     * Show the form for editing the specified Salles.
     */
    public function edit($id)
    {
        $salles = $this->sallesRepository->find($id);

        if (empty($salles)) {
            Flash::error('Salle non trouvé ');

            return redirect(route('salles.index'));
        }

        return view('salles.edit')->with('salles', $salles);
    }

    /**
     * Update the specified Salles in storage.
     */
    public function update($id, UpdateSallesRequest $request)
    {
        $salles = $this->sallesRepository->find($id);

        if (empty($salles)) {
            Flash::error('Salle non trouvé ');

            return redirect(route('salles.index'));
        }

        $salles = $this->sallesRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('salles.index'));
    }

    /**
     * Remove the specified Salles from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $salles = $this->sallesRepository->find($id);

        if (empty($salles)) {
            Flash::error('Salle non trouvé ');

            return redirect(route('salles.index'));
        }

        $this->sallesRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('salles.index'));
    }
}
