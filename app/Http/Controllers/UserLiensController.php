<?php

namespace App\Http\Controllers;

use App\DataTables\UserLiensDataTable;
use App\Http\Requests\CreateUserLiensRequest;
use App\Http\Requests\UpdateUserLiensRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserLiensRepository;
use Illuminate\Http\Request;
use Flash;

class UserLiensController extends AppBaseController
{
    /** @var UserLiensRepository $userLiensRepository*/
    private $userLiensRepository;

    public function __construct(UserLiensRepository $userLiensRepo)
    {
        $this->userLiensRepository = $userLiensRepo;
    }

    /**
     * Display a listing of the UserLiens.
     */
    public function index(UserLiensDataTable $userLiensDataTable)
    {
    return $userLiensDataTable->render('user_liens.index');
    }


    /**
     * Show the form for creating a new UserLiens.
     */
    public function create()
    {
        return view('user_liens.create');
    }

    /**
     * Store a newly created UserLiens in storage.
     */
    public function store(CreateUserLiensRequest $request)
    {
        $input = $request->all();

        $userLiens = $this->userLiensRepository->create($input);

        Flash::success('Enregistrement éffectué avec succès.');

        return redirect(route('userLiens.index'));
    }

    /**
     * Display the specified UserLiens.
     */
    public function show($id)
    {
        $userLiens = $this->userLiensRepository->find($id);

        if (empty($userLiens)) {
            Flash::error('User Liens not found');

            return redirect(route('userLiens.index'));
        }

        return view('user_liens.show')->with('userLiens', $userLiens);
    }

    /**
     * Show the form for editing the specified UserLiens.
     */
    public function edit($id)
    {
        $userLiens = $this->userLiensRepository->find($id);

        if (empty($userLiens)) {
            Flash::error('User Liens not found');

            return redirect(route('userLiens.index'));
        }

        return view('user_liens.edit')->with('userLiens', $userLiens);
    }

    /**
     * Update the specified UserLiens in storage.
     */
    public function update($id, UpdateUserLiensRequest $request)
    {
        $userLiens = $this->userLiensRepository->find($id);

        if (empty($userLiens)) {
            Flash::error('User Liens not found');

            return redirect(route('userLiens.index'));
        }

        $userLiens = $this->userLiensRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('userLiens.index'));
    }

    /**
     * Remove the specified UserLiens from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userLiens = $this->userLiensRepository->find($id);

        if (empty($userLiens)) {
            Flash::error('User Liens not found');

            return redirect(route('userLiens.index'));
        }

        $this->userLiensRepository->delete($id);

        Flash::success('Suppression éffectué avec succès.');

        return redirect(route('userLiens.index'));
    }
}
