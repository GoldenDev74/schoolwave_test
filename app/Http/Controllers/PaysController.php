<?php

namespace App\Http\Controllers;

use App\DataTables\PaysDataTable;
use App\Http\Requests\CreatePaysRequest;
use App\Http\Requests\UpdatePaysRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PaysRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class PaysController extends AppBaseController
{
    /** @var PaysRepository $paysRepository*/
    private $paysRepository;

    public function __construct(PaysRepository $paysRepo)
    {
        $this->paysRepository = $paysRepo;
    }

    /**
     * Display a listing of the Pays.
     */
    public function index(PaysDataTable $paysDataTable)
    {
    return $paysDataTable->render('pays.index');
    }


    /**
     * Show the form for creating a new Pays.
     */
    public function create()
    {
        return view('pays.create');
    }

    /**
     * Store a newly created Pays in storage.
     */
    public function store(CreatePaysRequest $request)
    {
        $input = $request->all();

        $pays = $this->paysRepository->create($input);

        Flash::success('Pays crée avec succès.');

        return redirect(route('pays.index'));
    }

    /**
     * Display the specified Pays.
     */
    public function show($id)
    {
        $pays = $this->paysRepository->find($id);

        if (empty($pays)) {
            Flash::error('Pays non trouvé');

            return redirect(route('pays.index'));
        }

        return view('pays.show')->with('pays', $pays);
    }

    /**
     * Show the form for editing the specified Pays.
     */
    public function edit($id)
    {
        $pays = $this->paysRepository->find($id);

        if (empty($pays)) {
            Flash::error('Pays non trouvé');

            return redirect(route('pays.index'));
        }

        return view('pays.edit')->with('pays', $pays);
    }

    /**
     * Update the specified Pays in storage.
     */
    public function update($id, UpdatePaysRequest $request)
    {
        $pays = $this->paysRepository->find($id);

        if (empty($pays)) {
            Flash::error('Pays non trouvé');

            return redirect(route('pays.index'));
        }

        $pays = $this->paysRepository->update($request->all(), $id);

        Flash::success('Pays mis à jour avec succès.');

        return redirect(route('pays.index'));
    }

    /**
     * Remove the specified Pays from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $pays = $this->paysRepository->find($id);

        if (empty($pays)) {
            Flash::error('Pays non trouvé');

            return redirect(route('pays.index'));
        }

        $this->paysRepository->delete($id);

        Flash::success('Pays supprimé avec succès.');

        return redirect(route('pays.index'));
    }
}
