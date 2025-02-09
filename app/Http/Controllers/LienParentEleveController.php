<?php

namespace App\Http\Controllers;

use App\DataTables\LienParentEleveDataTable;
use App\Http\Requests\CreateLienParentEleveRequest;
use App\Http\Requests\UpdateLienParentEleveRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LienParentEleveRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class LienParentEleveController extends AppBaseController
{
    /** @var LienParentEleveRepository $lienParentEleveRepository*/
    private $lienParentEleveRepository;

    public function __construct(LienParentEleveRepository $lienParentEleveRepo)
    {
        $this->lienParentEleveRepository = $lienParentEleveRepo;
    }

    /**
     * Display a listing of the LienParentEleve.
     */
    public function index(LienParentEleveDataTable $lienParentEleveDataTable)
    {
    return $lienParentEleveDataTable->render('lien_parent_eleves.index');
    }


    /**
     * Show the form for creating a new LienParentEleve.
     */
    public function create()
    {
        return view('lien_parent_eleves.create');
    }

    /**
     * Store a newly created LienParentEleve in storage.
     */
    public function store(CreateLienParentEleveRequest $request)
    {
        $input = $request->all();

        $lienParentEleve = $this->lienParentEleveRepository->create($input);

        Flash::success('Enregistrement créé avec succès.');

        return redirect(route('lienParentEleves.index'));
    }

    /**
     * Display the specified LienParentEleve.
     */
    public function show($id)
    {
        $lienParentEleve = $this->lienParentEleveRepository->find($id);

        if (empty($lienParentEleve)) {
            Flash::error('Lien Parent Eleve not found');

            return redirect(route('lienParentEleves.index'));
        }

        return view('lien_parent_eleves.show')->with('lienParentEleve', $lienParentEleve);
    }

    /**
     * Show the form for editing the specified LienParentEleve.
     */
    public function edit($id)
    {
        $lienParentEleve = $this->lienParentEleveRepository->find($id);

        if (empty($lienParentEleve)) {
            Flash::error('Lien Parent Eleve not found');

            return redirect(route('lienParentEleves.index'));
        }

        return view('lien_parent_eleves.edit')->with('lienParentEleve', $lienParentEleve);
    }

    /**
     * Update the specified LienParentEleve in storage.
     */
    public function update($id, UpdateLienParentEleveRequest $request)
    {
        $lienParentEleve = $this->lienParentEleveRepository->find($id);

        if (empty($lienParentEleve)) {
            Flash::error('Lien Parent Eleve not found');

            return redirect(route('lienParentEleves.index'));
        }

        $lienParentEleve = $this->lienParentEleveRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès.');

        return redirect(route('lienParentEleves.index'));
    }

    /**
     * Remove the specified LienParentEleve from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $lienParentEleve = $this->lienParentEleveRepository->find($id);

        if (empty($lienParentEleve)) {
            Flash::error('Lien Parent Eleve not found');

            return redirect(route('lienParentEleves.index'));
        }

        $this->lienParentEleveRepository->delete($id);

        Flash::success('Enregistrement supprimé avec succès.');

        return redirect(route('lienParentEleves.index'));
    }
}
