<?php

namespace App\Http\Controllers;

use App\DataTables\FilereDataTable;
use App\Http\Requests\CreateFilereRequest;
use App\Http\Requests\UpdateFilereRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\FilereRepository;
use Illuminate\Http\Request;
use Flash;

class FilereController extends AppBaseController
{
    /** @var FilereRepository $filereRepository*/
    private $filereRepository;

    public function __construct(FilereRepository $filereRepo)
    {
        $this->filereRepository = $filereRepo;
    }

    /**
     * Display a listing of the Filere.
     */
    public function index(FilereDataTable $filereDataTable)
    {
    return $filereDataTable->render('fileres.index');
    }


    /**
     * Show the form for creating a new Filere.
     */
    public function create()
    {
        return view('fileres.create');
    }

    /**
     * Store a newly created Filere in storage.
     */
    public function store(CreateFilereRequest $request)
    {
        $input = $request->all();

        $filere = $this->filereRepository->create($input);

        Flash::success('Enregistrement effectué avec succès!');

        return redirect(route('fileres.index'));
    }

    /**
     * Display the specified Filere.
     */
    public function show($id)
    {
        $filere = $this->filereRepository->find($id);

        if (empty($filere)) {
            Flash::error('Filere not found');

            return redirect(route('fileres.index'));
        }

        return view('fileres.show')->with('filere', $filere);
    }

    /**
     * Show the form for editing the specified Filere.
     */
    public function edit($id)
    {
        $filere = $this->filereRepository->find($id);

        if (empty($filere)) {
            Flash::error('Filere not found');

            return redirect(route('fileres.index'));
        }

        return view('fileres.edit')->with('filere', $filere);
    }

    /**
     * Update the specified Filere in storage.
     */
    public function update($id, UpdateFilereRequest $request)
    {
        $filere = $this->filereRepository->find($id);

        if (empty($filere)) {
            Flash::error('Filere not found');

            return redirect(route('fileres.index'));
        }

        $filere = $this->filereRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('fileres.index'));
    }

    /**
     * Remove the specified Filere from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $filere = $this->filereRepository->find($id);

        if (empty($filere)) {
            Flash::error('Filere not found');

            return redirect(route('fileres.index'));
        }

        $this->filereRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('fileres.index'));
    }
}
