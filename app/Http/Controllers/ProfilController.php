<?php

namespace App\Http\Controllers;

use App\DataTables\ProfilDataTable;
use App\Http\Requests\CreateProfilRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ProfilRepository;
use Illuminate\Http\Request;
use Flash;

class ProfilController extends AppBaseController
{
    /** @var ProfilRepository $profilRepository*/
    private $profilRepository;

    public function __construct(ProfilRepository $profilRepo)
    {
        $this->profilRepository = $profilRepo;
    }

    /**
     * Display a listing of the Profil.
     */
    public function index(ProfilDataTable $profilDataTable)
    {
    return $profilDataTable->render('profils.index');
    }


    /**
     * Show the form for creating a new Profil.
     */
    public function create()
    {
        return view('profils.create');
    }

    /**
     * Store a newly created Profil in storage.
     */
    public function store(CreateProfilRequest $request)
    {
        $input = $request->all();

        $profil = $this->profilRepository->create($input);

        Flash::success('Profil saved successfully.');

        return redirect(route('profils.index'));
    }

    /**
     * Display the specified Profil.
     */
    public function show($id)
    {
        $profil = $this->profilRepository->find($id);

        if (empty($profil)) {
            Flash::error('Profil not found');

            return redirect(route('profils.index'));
        }

        return view('profils.show')->with('profil', $profil);
    }

    /**
     * Show the form for editing the specified Profil.
     */
    public function edit($id)
    {
        $profil = $this->profilRepository->find($id);

        if (empty($profil)) {
            Flash::error('Profil not found');

            return redirect(route('profils.index'));
        }

        return view('profils.edit')->with('profil', $profil);
    }

    /**
     * Update the specified Profil in storage.
     */
    public function update($id, UpdateProfilRequest $request)
    {
        $profil = $this->profilRepository->find($id);

        if (empty($profil)) {
            Flash::error('Profil not found');

            return redirect(route('profils.index'));
        }

        $profil = $this->profilRepository->update($request->all(), $id);

        Flash::success('Profil updated successfully.');

        return redirect(route('profils.index'));
    }

    /**
     * Remove the specified Profil from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $profil = $this->profilRepository->find($id);

        if (empty($profil)) {
            Flash::error('Profil not found');

            return redirect(route('profils.index'));
        }

        $this->profilRepository->delete($id);

        Flash::success('Profil deleted successfully.');

        return redirect(route('profils.index'));
    }
}
