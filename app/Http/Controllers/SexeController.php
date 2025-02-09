<?php

namespace App\Http\Controllers;

use App\DataTables\SexeDataTable;
use App\Http\Requests\CreateSexeRequest;
use App\Http\Requests\UpdateSexeRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SexeRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class SexeController extends AppBaseController
{
    /** @var SexeRepository $sexeRepository*/
    private $sexeRepository;

    public function __construct(SexeRepository $sexeRepo)
    {
        $this->sexeRepository = $sexeRepo;
    }

    /**
     * Display a listing of the Sexe.
     */
    public function index(SexeDataTable $sexeDataTable)
    {
    return $sexeDataTable->render('sexes.index');
    }


    /**
     * Show the form for creating a new Sexe.
     */
    public function create()
    {
        return view('sexes.create');
    }

    /**
     * Store a newly created Sexe in storage.
     */
    public function store(CreateSexeRequest $request)
    {
        $input = $request->all();

        $sexe = $this->sexeRepository->create($input);

        Flash::success('Enregistrement éffectué avec succès.');

        return redirect(route('sexes.index'));
    }

    /**
     * Display the specified Sexe.
     */
    public function show($id)
    {
        $sexe = $this->sexeRepository->find($id);

        if (empty($sexe)) {
            Flash::error('Sexe not found');

            return redirect(route('sexes.index'));
        }

        return view('sexes.show')->with('sexe', $sexe);
    }

    /**
     * Show the form for editing the specified Sexe.
     */
    public function edit($id)
    {
        $sexe = $this->sexeRepository->find($id);

        if (empty($sexe)) {
            Flash::error('Sexe not found');

            return redirect(route('sexes.index'));
        }

        return view('sexes.edit')->with('sexe', $sexe);
    }

    /**
     * Update the specified Sexe in storage.
     */
    public function update($id, UpdateSexeRequest $request)
    {
        $sexe = $this->sexeRepository->find($id);

        if (empty($sexe)) {
            Flash::error('Sexe not found');

            return redirect(route('sexes.index'));
        }

        $sexe = $this->sexeRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('sexes.index'));
    }

    /**
     * Remove the specified Sexe from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $sexe = $this->sexeRepository->find($id);

        if (empty($sexe)) {
            Flash::error('Sexe not found');

            return redirect(route('sexes.index'));
        }

        $this->sexeRepository->delete($id);

        Flash::success('Suppression éffectuée avec succès.');

        return redirect(route('sexes.index'));
    }
}
