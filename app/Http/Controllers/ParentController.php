<?php

namespace App\Http\Controllers;

use App\DataTables\ParentDataTable;
use App\Http\Requests\CreateParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ParentRepository;
use Illuminate\Http\Request;
use Flash;
use App\Models\LienParentEleve;
use App\Models\Pays;

class ParentController extends AppBaseController
{
    /** @var ParentRepository $parentRepository*/
    private $parentRepository;

    public function __construct(ParentRepository $parentRepo)
    {
        $this->parentRepository = $parentRepo;
    }

    /**
     * Display a listing of the Parent.
     */
    public function index(ParentDataTable $parentDataTable)
    {
    return $parentDataTable->render('parents.index');
    }


    /**
     * Show the form for creating a new Parent.
     */
    public function create()
    {
        $lienEleves = LienParentEleve::pluck('libelle', 'id');
        $pays_residence = Pays::pluck('libelle', 'id');
        $nationalite = Pays::pluck('libelle', 'id');
        return view('parents.create')->with('lienEleves', $lienEleves)->with('pays_residence', $pays_residence)->with('nationalite', $nationalite);
    }

    /**
     * Store a newly created Parent in storage.
     */
    public function store(CreateParentRequest $request)
    {
        $input = $request->all();

        $parent = $this->parentRepository->create($input);

        Flash::success('Enregistrement éffectué avec succès.');

        return redirect(route('parents.index'));
    }

    /**
     * Display the specified Parent.
     */
    public function show($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }

        return view('parents.show')->with('parent', $parent);
    }

    /**
     * Show the form for editing the specified Parent.
     */
    public function edit($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }
        
        $lienEleves = LienParentEleve::pluck('libelle', 'id');
        $pays_residence = Pays::pluck('libelle', 'id');
        $nationalite = Pays::pluck('libelle', 'id');
        return view('parents.edit')->with('parent', $parent)->with('lienEleves', $lienEleves)->with('pays_residence', $pays_residence)->with('nationalite', $nationalite);
    }

    /**
     * Update the specified Parent in storage.
     */
    public function update($id, UpdateParentRequest $request)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }

        $parent = $this->parentRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('parents.index'));
    }

    /**
     * Remove the specified Parent from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent non trouvé');

            return redirect(route('parents.index'));
        }

        $this->parentRepository->delete($id);

        Flash::success('Suppréssion éffectué avec succès.');

        return redirect(route('parents.index'));
    }
}