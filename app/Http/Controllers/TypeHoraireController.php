<?php

namespace App\Http\Controllers;

use App\DataTables\TypeHoraireDataTable;
use App\Http\Requests\CreateTypeHoraireRequest;
use App\Http\Requests\UpdateTypeHoraireRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TypeHoraireRepository;
use Illuminate\Http\Request;
use Flash;

class TypeHoraireController extends AppBaseController
{
    /** @var TypeHoraireRepository $typeHoraireRepository*/
    private $typeHoraireRepository;

    public function __construct(TypeHoraireRepository $typeHoraireRepo)
    {
        $this->typeHoraireRepository = $typeHoraireRepo;
    }

    /**
     * Display a listing of the TypeHoraire.
     */
    public function index(TypeHoraireDataTable $typeHoraireDataTable)
    {
    return $typeHoraireDataTable->render('type_horaires.index');
    }


    /**
     * Show the form for creating a new TypeHoraire.
     */
    public function create()
    {
        return view('type_horaires.create');
    }

    /**
     * Store a newly created TypeHoraire in storage.
     */
    public function store(CreateTypeHoraireRequest $request)
    {
        $input = $request->all();

        $typeHoraire = $this->typeHoraireRepository->create($input);

        Flash::success('Type Horaire enrégitré avec succès.');

        return redirect(route('typeHoraires.index'));
    }

    /**
     * Display the specified TypeHoraire.
     */
    public function show($id)
    {
        $typeHoraire = $this->typeHoraireRepository->find($id);

        if (empty($typeHoraire)) {
            Flash::error('Type Horaire non trouvé');

            return redirect(route('typeHoraires.index'));
        }

        return view('type_horaires.show')->with('typeHoraire', $typeHoraire);
    }

    /**
     * Show the form for editing the specified TypeHoraire.
     */
    public function edit($id)
    {
        $typeHoraire = $this->typeHoraireRepository->find($id);

        if (empty($typeHoraire)) {
            Flash::error('Type Horaire non trouvé');

            return redirect(route('typeHoraires.index'));
        }

        return view('type_horaires.edit')->with('typeHoraire', $typeHoraire);
    }

    /**
     * Update the specified TypeHoraire in storage.
     */
    public function update($id, UpdateTypeHoraireRequest $request)
    {
        $typeHoraire = $this->typeHoraireRepository->find($id);

        if (empty($typeHoraire)) {
            Flash::error('Type Horaire non trouvé');

            return redirect(route('typeHoraires.index'));
        }

        $typeHoraire = $this->typeHoraireRepository->update($request->all(), $id);

        Flash::success('Type Horaire mise à jour avec succès.');

        return redirect(route('typeHoraires.index'));
    }

    /**
     * Remove the specified TypeHoraire from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $typeHoraire = $this->typeHoraireRepository->find($id);

        if (empty($typeHoraire)) {
            Flash::error('Type Horaire non trouvé');

            return redirect(route('typeHoraires.index'));
        }

        $this->typeHoraireRepository->delete($id);

        Flash::success('Type Horaire supprimé avec succès.');

        return redirect(route('typeHoraires.index'));
    }
}
