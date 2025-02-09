<?php

namespace App\Http\Controllers;

use App\DataTables\JourSemaineDataTable;
use App\Http\Requests\CreateJourSemaineRequest;
use App\Http\Requests\UpdateJourSemaineRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\JourSemaineRepository;
use Illuminate\Http\Request;
use App\Models\JourSemaine;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Response;

class JourSemaineController extends AppBaseController
{
    /** @var JourSemaineRepository $jourSemaineRepository*/
    private $jourSemaineRepository;

    public function __construct(JourSemaineRepository $jourSemaineRepo)
    {
        $this->jourSemaineRepository = $jourSemaineRepo;
    }

    /**
     * Display a listing of the JourSemaine.
     */
    public function index(JourSemaineDataTable $jourSemaineDataTable)
    {
    return $jourSemaineDataTable->render('jour_semaines.index');
    }


    /**
     * Show the form for creating a new JourSemaine.
     */
    public function create()
    {
        $jours = JourSemaine::pluck('libelle', 'id');
        return view('jour_semaines.create')->with('jours', $jours);
    }

    /**
     * Store a newly created JourSemaine in storage.
     */
    public function store(CreateJourSemaineRequest $request)
    {
        $input = $request->all();

        $jourSemaine = $this->jourSemaineRepository->create($input);

        Flash::success('Enregistrement effectué avec succès');

        return redirect(route('jourSemaines.index'));
    }

    /**
     * Display the specified JourSemaine.
     */
    public function show($id)
    {
        $jourSemaine = $this->jourSemaineRepository->find($id);

        if (empty($jourSemaine)) {
            Flash::error('Jour Semaine non trouvé');

            return redirect(route('jourSemaines.index'));
        }

        return view('jour_semaines.show')->with('jourSemaine', $jourSemaine);
    }

    /**
     * Show the form for editing the specified JourSemaine.
     */
    public function edit($id)
    {
        $jourSemaine = $this->jourSemaineRepository->find($id);

        if (empty($jourSemaine)) {
            Flash::error('Jour Semaine non trouvé');

            return redirect(route('jourSemaines.index'));
        }
        $jours = JourSemaine::pluck('libelle', 'id');

        return view('jour_semaines.edit')->with('jourSemaine', $jourSemaine)->with('jours', $jours);
    }

    /**
     * Update the specified JourSemaine in storage.
     */
    public function update($id, UpdateJourSemaineRequest $request)
    {
        $jourSemaine = $this->jourSemaineRepository->find($id);

        if (empty($jourSemaine)) {
            Flash::error('Jour Semaine non trouvé');

            return redirect(route('jourSemaines.index'));
        }

        $jourSemaine = $this->jourSemaineRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('jourSemaines.index'));
    }

    /**
     * Remove the specified JourSemaine from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $jourSemaine = $this->jourSemaineRepository->find($id);

        if (empty($jourSemaine)) {
            Flash::error('Jour Semaine non trouvé');

            return redirect(route('jourSemaines.index'));
        }

        $this->jourSemaineRepository->delete($id);

        Flash::success('Suppression effectuée avec succès');

        return redirect(route('jourSemaines.index'));
    }
}
