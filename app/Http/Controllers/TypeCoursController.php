<?php

namespace App\Http\Controllers;

use App\DataTables\TypeCoursDataTable;
use App\Http\Requests\CreateTypeCoursRequest;
use App\Http\Requests\UpdateTypeCoursRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TypeCoursRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TypeCoursController extends AppBaseController
{
    /** @var TypeCoursRepository $typeCoursRepository*/
    private $typeCoursRepository;

    public function __construct(TypeCoursRepository $typeCoursRepo)
    {
        $this->typeCoursRepository = $typeCoursRepo;
    }

    /**
     * Display a listing of the TypeCours.
     */
    public function index(TypeCoursDataTable $typeCoursDataTable)
    {
    return $typeCoursDataTable->render('type_cours.index');
    }


    /**
     * Show the form for creating a new TypeCours.
     */
    public function create()
    {
        return view('type_cours.create');
    }

    /**
     * Store a newly created TypeCours in storage.
     */
    public function store(CreateTypeCoursRequest $request)
    {
        $input = $request->all();

        $typeCours = $this->typeCoursRepository->create($input);

        Flash::success('Enregistrement effectué avec succès!');

        return redirect(route('typeCours.index'));
    }

    /**
     * Display the specified TypeCours.
     */
    public function show($id)
    {
        $typeCours = $this->typeCoursRepository->find($id);

        if (empty($typeCours)) {
            Flash::error('Type Cours not found');

            return redirect(route('typeCours.index'));
        }

        return view('type_cours.show')->with('typeCours', $typeCours);
    }

    /**
     * Show the form for editing the specified TypeCours.
     */
    public function edit($id)
    {
        $typeCours = $this->typeCoursRepository->find($id);

        if (empty($typeCours)) {
            Flash::error('Type Cours not found');

            return redirect(route('typeCours.index'));
        }

        return view('type_cours.edit')->with('typeCours', $typeCours);
    }

    /**
     * Update the specified TypeCours in storage.
     */
    public function update($id, UpdateTypeCoursRequest $request)
    {
        $typeCours = $this->typeCoursRepository->find($id);

        if (empty($typeCours)) {
            Flash::error('Type Cours not found');

            return redirect(route('typeCours.index'));
        }

        $typeCours = $this->typeCoursRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('typeCours.index'));
    }

    /**
     * Remove the specified TypeCours from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $typeCours = $this->typeCoursRepository->find($id);

        if (empty($typeCours)) {
            Flash::error('Type Cours not found');

            return redirect(route('typeCours.index'));
        }

        $this->typeCoursRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('typeCours.index'));
    }
}
