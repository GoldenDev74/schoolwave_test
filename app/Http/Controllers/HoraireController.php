<?php

namespace App\Http\Controllers;

use App\DataTables\HoraireDataTable;
use App\Http\Requests\CreateHoraireRequest;
use App\Http\Requests\UpdateHoraireRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\HoraireRepository;
use Illuminate\Http\Request;
use App\Models\TypeCours;
use Laracasts\Flash\Flash;

class HoraireController extends AppBaseController
{
    /** @var HoraireRepository $horaireRepository*/
    private $horaireRepository;

    public function __construct(HoraireRepository $horaireRepo)
    {
        $this->horaireRepository = $horaireRepo;
    }

    /**
     * Display a listing of the Horaire.
     */
    public function index(HoraireDataTable $horaireDataTable)
    {
    return $horaireDataTable->render('horaires.index');
    }


    /**
     * Show the form for creating a new Horaire.
     */
    public function create()
    {
        $typeCours = TypeCours::pluck('libelle', 'id');
        return view('horaires.create')->with('typeCours', $typeCours);
    }

    /**
     * Store a newly created Horaire in storage.
     */
    public function store(CreateHoraireRequest $request)
    {
        $input = $request->all();

        $horaire = $this->horaireRepository->create($input);

        Flash::success('Enregistrement effectué avec succès!');

        return redirect(route('horaires.index'));
    }

    /**
     * Display the specified Horaire.
     */
    public function show($id)
    {
        $horaire = $this->horaireRepository->find($id);

        if (empty($horaire)) {
            Flash::error('Horaire non trouvé');

            return redirect(route('horaires.index'));
        }

        return view('horaires.show')->with('horaire', $horaire);
    }

    /**
     * Show the form for editing the specified Horaire.
     */
    public function edit($id)
    {
        $horaire = $this->horaireRepository->find($id);

        if (empty($horaire)) {
            Flash::error('Horaire non trouvé');

            return redirect(route('horaires.index'));
        }
        $typeCours = TypeCours::pluck('libelle', 'id');

        return view('horaires.edit')->with('horaire', $horaire)->with('typeCours', $typeCours);
    }

    /**
     * Update the specified Horaire in storage.
     */
    public function update($id, UpdateHoraireRequest $request)
    {
        $horaire = $this->horaireRepository->find($id);

        if (empty($horaire)) {
            Flash::error('Horaire non trouvé');

            return redirect(route('horaires.index'));
        }

        $horaire = $this->horaireRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('horaires.index'));
    }

    /**
     * Remove the specified Horaire from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $horaire = $this->horaireRepository->find($id);

        if (empty($horaire)) {
            Flash::error('Horaire non trouvé');

            return redirect(route('horaires.index'));
        }

        $this->horaireRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('horaires.index'));
    }
}
