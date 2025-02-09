<?php

namespace App\Http\Controllers;

use App\DataTables\LiensDataTable;
use App\Http\Requests\CreateLiensRequest;
use App\Http\Requests\UpdateLiensRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LiensRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class LiensController extends AppBaseController
{
    /** @var LiensRepository $liensRepository*/
    private $liensRepository;

    public function __construct(LiensRepository $liensRepo)
    {
        $this->liensRepository = $liensRepo;
    }

    /**
     * Display a listing of the Liens.
     */
    public function index(LiensDataTable $liensDataTable)
    {
    return $liensDataTable->render('liens.index');
    }


    /**
     * Show the form for creating a new Liens.
     */
    public function create()
    {
        return view('liens.create');
    }

    /**
     * Store a newly created Liens in storage.
     */
    public function store(CreateLiensRequest $request)
    {
        $input = $request->all();

        $liens = $this->liensRepository->create($input);

        Flash::success('Enregistrement éffectué avec succès.');

        return redirect(route('liens.index'));
    }

    /**
     * Display the specified Liens.
     */
    public function show($id)
    {
        $liens = $this->liensRepository->find($id);

        if (empty($liens)) {
            Flash::error('Liens not found');

            return redirect(route('liens.index'));
        }

        return view('liens.show')->with('liens', $liens);
    }

    /**
     * Show the form for editing the specified Liens.
     */
    public function edit($id)
    {
        $liens = $this->liensRepository->find($id);

        if (empty($liens)) {
            Flash::error('Liens not found');

            return redirect(route('liens.index'));
        }

        return view('liens.edit')->with('liens', $liens);
    }

    /**
     * Update the specified Liens in storage.
     */
    public function update($id, UpdateLiensRequest $request)
    {
        $liens = $this->liensRepository->find($id);

        if (empty($liens)) {
            Flash::error('Liens not found');

            return redirect(route('liens.index'));
        }

        $liens = $this->liensRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('liens.index'));
    }

    /**
     * Remove the specified Liens from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $liens = $this->liensRepository->find($id);

        if (empty($liens)) {
            Flash::error('Liens not found');

            return redirect(route('liens.index'));
        }

        $this->liensRepository->delete($id);

        Flash::success('Suppression éffectué avec succès.');

        return redirect(route('liens.index'));
    }
}
