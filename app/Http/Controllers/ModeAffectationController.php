<?php

namespace App\Http\Controllers;

use App\DataTables\ModeAffectationDataTable;
use App\Http\Requests\CreateModeAffectationRequest;
use App\Http\Requests\UpdateModeAffectationRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ModeAffectationRepository;
use Illuminate\Http\Request;
use Flash;

class ModeAffectationController extends AppBaseController
{
    /** @var ModeAffectationRepository $modeAffectationRepository*/
    private $modeAffectationRepository;

    public function __construct(ModeAffectationRepository $modeAffectationRepo)
    {
        $this->modeAffectationRepository = $modeAffectationRepo;
    }

    /**
     * Display a listing of the ModeAffectation.
     */
    public function index(ModeAffectationDataTable $modeAffectationDataTable)
    {
    return $modeAffectationDataTable->render('mode_affectations.index');
    }


    /**
     * Show the form for creating a new ModeAffectation.
     */
    public function create()
    {
        return view('mode_affectations.create');
    }

    /**
     * Store a newly created ModeAffectation in storage.
     */
    public function store(CreateModeAffectationRequest $request)
    {
        $input = $request->all();

        $modeAffectation = $this->modeAffectationRepository->create($input);

        Flash::success('Enregistrement éffectué avec succès.');

        return redirect(route('modeAffectations.index'));
    }

    /**
     * Display the specified ModeAffectation.
     */
    public function show($id)
    {
        $modeAffectation = $this->modeAffectationRepository->find($id);

        if (empty($modeAffectation)) {
            Flash::error('Mode Affectation not found');

            return redirect(route('modeAffectations.index'));
        }

        return view('mode_affectations.show')->with('modeAffectation', $modeAffectation);
    }

    /**
     * Show the form for editing the specified ModeAffectation.
     */
    public function edit($id)
    {
        $modeAffectation = $this->modeAffectationRepository->find($id);

        if (empty($modeAffectation)) {
            Flash::error('Mode Affectation not found');

            return redirect(route('modeAffectations.index'));
        }

        return view('mode_affectations.edit')->with('modeAffectation', $modeAffectation);
    }

    /**
     * Update the specified ModeAffectation in storage.
     */
    public function update($id, UpdateModeAffectationRequest $request)
    {
        $modeAffectation = $this->modeAffectationRepository->find($id);

        if (empty($modeAffectation)) {
            Flash::error('Mode Affectation not found');

            return redirect(route('modeAffectations.index'));
        }

        $modeAffectation = $this->modeAffectationRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('modeAffectations.index'));
    }

    /**
     * Remove the specified ModeAffectation from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $modeAffectation = $this->modeAffectationRepository->find($id);

        if (empty($modeAffectation)) {
            Flash::error('Mode Affectation not found');

            return redirect(route('modeAffectations.index'));
        }

        $this->modeAffectationRepository->delete($id);

        Flash::success('Suppression éffectué avec succès.');

        return redirect(route('modeAffectations.index'));
    }
}
