<?php

namespace App\Http\Controllers;

use App\DataTables\TypepersonnelDataTable;
use App\Http\Requests\CreateTypepersonnelRequest;
use App\Http\Requests\UpdateTypepersonnelRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TypepersonnelRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TypepersonnelController extends AppBaseController
{
    /** @var TypepersonnelRepository $typepersonnelRepository*/
    private $typepersonnelRepository;

    public function __construct(TypepersonnelRepository $typepersonnelRepo)
    {
        $this->typepersonnelRepository = $typepersonnelRepo;
    }

    /**
     * Display a listing of the Typepersonnel.
     */
    public function index(TypepersonnelDataTable $typepersonnelDataTable)
    {
    return $typepersonnelDataTable->render('typepersonnels.index');
    }


    /**
     * Show the form for creating a new Typepersonnel.
     */
    public function create()
    {
        return view('typepersonnels.create');
    }

    /**
     * Store a newly created Typepersonnel in storage.
     */
    public function store(CreateTypepersonnelRequest $request)
    {
        $input = $request->all();

        $typepersonnel = $this->typepersonnelRepository->create($input);

        Flash::success('Typepersonnel saved successfully.');

        return redirect(route('typepersonnels.index'));
    }

    /**
     * Display the specified Typepersonnel.
     */
    public function show($id)
    {
        $typepersonnel = $this->typepersonnelRepository->find($id);

        if (empty($typepersonnel)) {
            Flash::error('Typepersonnel not found');

            return redirect(route('typepersonnels.index'));
        }

        return view('typepersonnels.show')->with('typepersonnel', $typepersonnel);
    }

    /**
     * Show the form for editing the specified Typepersonnel.
     */
    public function edit($id)
    {
        $typepersonnel = $this->typepersonnelRepository->find($id);

        if (empty($typepersonnel)) {
            Flash::error('Typepersonnel not found');

            return redirect(route('typepersonnels.index'));
        }

        return view('typepersonnels.edit')->with('typepersonnel', $typepersonnel);
    }

    /**
     * Update the specified Typepersonnel in storage.
     */
    public function update($id, UpdateTypepersonnelRequest $request)
    {
        $typepersonnel = $this->typepersonnelRepository->find($id);

        if (empty($typepersonnel)) {
            Flash::error('Typepersonnel not found');

            return redirect(route('typepersonnels.index'));
        }

        $typepersonnel = $this->typepersonnelRepository->update($request->all(), $id);

        Flash::success('Typepersonnel updated successfully.');

        return redirect(route('typepersonnels.index'));
    }

    /**
     * Remove the specified Typepersonnel from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $typepersonnel = $this->typepersonnelRepository->find($id);

        if (empty($typepersonnel)) {
            Flash::error('Typepersonnel not found');

            return redirect(route('typepersonnels.index'));
        }

        $this->typepersonnelRepository->delete($id);

        Flash::success('Typepersonnel deleted successfully.');

        return redirect(route('typepersonnels.index'));
    }
}
