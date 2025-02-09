<?php

namespace App\Http\Controllers;

use App\DataTables\UserProfilDataTable;
use App\Http\Requests\CreateUserProfilRequest;
use App\Http\Requests\UpdateUserProfilRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserProfilRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class UserProfilController extends AppBaseController
{
    /** @var UserProfilRepository $userProfilRepository*/
    private $userProfilRepository;

    public function __construct(UserProfilRepository $userProfilRepo)
    {
        $this->userProfilRepository = $userProfilRepo;
    }

    /**
     * Display a listing of the UserProfil.
     */
    public function index(UserProfilDataTable $userProfilDataTable)
    {
    return $userProfilDataTable->render('user_profils.index');
    }


    /**
     * Show the form for creating a new UserProfil.
     */
    public function create()
    {
        return view('user_profils.create');
    }

    /**
     * Store a newly created UserProfil in storage.
     */
    public function store(CreateUserProfilRequest $request)
    {
        $input = $request->all();

        $userProfil = $this->userProfilRepository->create($input);

        Flash::success('User Profil saved successfully.');

        return redirect(route('userProfils.index'));
    }

    /**
     * Display the specified UserProfil.
     */
    public function show($id)
    {
        $userProfil = $this->userProfilRepository->find($id);

        if (empty($userProfil)) {
            Flash::error('User Profil not found');

            return redirect(route('userProfils.index'));
        }

        return view('user_profils.show')->with('userProfil', $userProfil);
    }

    /**
     * Show the form for editing the specified UserProfil.
     */
    public function edit($id)
    {
        $userProfil = $this->userProfilRepository->find($id);

        if (empty($userProfil)) {
            Flash::error('User Profil not found');

            return redirect(route('userProfils.index'));
        }

        return view('user_profils.edit')->with('userProfil', $userProfil);
    }

    /**
     * Update the specified UserProfil in storage.
     */
    public function update($id, UpdateUserProfilRequest $request)
    {
        $userProfil = $this->userProfilRepository->find($id);

        if (empty($userProfil)) {
            Flash::error('User Profil not found');

            return redirect(route('userProfils.index'));
        }

        $userProfil = $this->userProfilRepository->update($request->all(), $id);

        Flash::success('User Profil updated successfully.');

        return redirect(route('userProfils.index'));
    }

    /**
     * Remove the specified UserProfil from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userProfil = $this->userProfilRepository->find($id);

        if (empty($userProfil)) {
            Flash::error('User Profil not found');

            return redirect(route('userProfils.index'));
        }

        $this->userProfilRepository->delete($id);

        Flash::success('User Profil deleted successfully.');

        return redirect(route('userProfils.index'));
    }
}
