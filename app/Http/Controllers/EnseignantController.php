<?php

namespace App\Http\Controllers;

use App\DataTables\EnseignantDataTable;
use App\Http\Requests\CreateEnseignantRequest;
use App\Http\Requests\UpdateEnseignantRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EnseignantRepository;
use Illuminate\Http\Request;
use Flash;
use App\Models\Sexe;
use App\Models\Diplome;
use App\Models\Filere;
use App\Models\TypeCours;

class EnseignantController extends AppBaseController
{
    /** @var EnseignantRepository $enseignantRepository*/
    private $enseignantRepository;

    public function __construct(EnseignantRepository $enseignantRepo)
    {
        $this->enseignantRepository = $enseignantRepo;
    }

    /**
     * Display a listing of the Enseignant.
     */
    public function index(EnseignantDataTable $enseignantDataTable)
    {
        return $enseignantDataTable->render('enseignants.index');
    }


    /**
     * Show the form for creating a new Enseignant.
     */
    public function create()
    {
        $typeCours = TypeCours::pluck('libelle', 'id');
        $sexes = Sexe::pluck('libelle', 'id');
        $diplomes = Diplome::pluck('libelle', 'id');
        $filieres = Filere::pluck('libelle', 'id');
        return view('enseignants.create')->with('sexes', $sexes)->with('diplomes', $diplomes)->with('filieres', $filieres)->with('typeCours', $typeCours);
    }

    /**
     * Store a newly created Enseignant in storage.
     */
    public function store(CreateEnseignantRequest $request)
    {
        $input = $request->all();

        $enseignant = $this->enseignantRepository->create($input);

        Flash::success('Enseignant enregistré avec succès.');

        return redirect(route('enseignants.index'));
    }

    /**
     * Display the specified Enseignant.
     */
    public function show($id)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }

        return view('enseignants.show')->with('enseignant', $enseignant);
    }

    /**
     * Show the form for editing the specified Enseignant.
     */
    public function edit($id)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }
        $typeCours = TypeCours::pluck('libelle', 'id');
        $sexes = Sexe::pluck('libelle', 'id');
        $diplomes = Diplome::pluck('libelle', 'id');
        $filieres = Filere::pluck('libelle', 'id');
        return view('enseignants.edit')->with('enseignant', $enseignant)->with('sexes', $sexes)->with('diplomes', $diplomes)->with('filieres', $filieres)->with('typeCours', $typeCours);
    }

    /**
     * Update the specified Enseignant in storage.
     */
    public function update($id, UpdateEnseignantRequest $request)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }

        $enseignant = $this->enseignantRepository->update($request->all(), $id);

        Flash::success('Enseignant mise à jour avec succès.');

        return redirect(route('enseignants.index'));
    }

    /**
     * Remove the specified Enseignant from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }

        $this->enseignantRepository->delete($id);

        Flash::success('Enseignant Supprimé avec succès.');

        return redirect(route('enseignants.index'));
    }
}
