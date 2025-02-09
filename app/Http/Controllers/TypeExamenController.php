<?php

namespace App\Http\Controllers;

use App\DataTables\TypeExamenDataTable;
use App\Http\Requests\CreateTypeExamenRequest;
use App\Http\Requests\UpdateTypeExamenRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TypeExamenRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TypeExamenController extends AppBaseController
{
    /** @var TypeExamenRepository $typeExamenRepository*/
    private $typeExamenRepository;

    public function __construct(TypeExamenRepository $typeExamenRepo)
    {
        $this->typeExamenRepository = $typeExamenRepo;
    }

    /**
     * Display a listing of the TypeExamen.
     */
    public function index(TypeExamenDataTable $typeExamenDataTable)
    {
    return $typeExamenDataTable->render('type_examens.index');
    }


    /**
     * Show the form for creating a new TypeExamen.
     */
    public function create()
    {
        return view('type_examens.create');
    }

    /**
     * Store a newly created TypeExamen in storage.
     */
    public function store(CreateTypeExamenRequest $request)
    {
        $input = $request->all();

        $typeExamen = $this->typeExamenRepository->create($input);

        Flash::success('Enregistrement effectué avec succès!');

        return redirect(route('typeExamens.index'));
    }

    /**
     * Display the specified TypeExamen.
     */
    public function show($id)
    {
        $typeExamen = $this->typeExamenRepository->find($id);

        if (empty($typeExamen)) {
            Flash::error('Type Examen non trouvé');

            return redirect(route('typeExamens.index'));
        }

        return view('type_examens.show')->with('typeExamen', $typeExamen);
    }

    /**
     * Show the form for editing the specified TypeExamen.
     */
    public function edit($id)
    {
        $typeExamen = $this->typeExamenRepository->find($id);

        if (empty($typeExamen)) {
            Flash::error('Type Examen non trouvé');

            return redirect(route('typeExamens.index'));
        }

        return view('type_examens.edit')->with('typeExamen', $typeExamen);
    }

    /**
     * Update the specified TypeExamen in storage.
     */
    public function update($id, UpdateTypeExamenRequest $request)
    {
        $typeExamen = $this->typeExamenRepository->find($id);

        if (empty($typeExamen)) {
            Flash::error('Type Examen non trouvé');

            return redirect(route('typeExamens.index'));
        }

        $typeExamen = $this->typeExamenRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('typeExamens.index'));
    }

    /**
     * Remove the specified TypeExamen from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $typeExamen = $this->typeExamenRepository->find($id);

        if (empty($typeExamen)) {
            Flash::error('Type Examen non trouvé');

            return redirect(route('typeExamens.index'));
        }

        $this->typeExamenRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('typeExamens.index'));
    }
}
