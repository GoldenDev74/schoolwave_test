<?php

namespace App\Http\Controllers;

use App\DataTables\EleveDataTable;
use App\Http\Requests\CreateEleveRequest;
use App\Http\Requests\UpdateEleveRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EleveRepository;
use Illuminate\Http\Request;
use App\Models\Parents;
use App\Models\Pays;
use App\Models\Sexe;
use Flash;

class EleveController extends AppBaseController
{
    /** @var EleveRepository $eleveRepository*/
    private $eleveRepository;

    public function __construct(EleveRepository $eleveRepo)
    {
        $this->eleveRepository = $eleveRepo;
    }

    /**
     * Display a listing of the Eleve.
     */
    public function index(EleveDataTable $eleveDataTable)
    {
    return $eleveDataTable->render('eleves.index');
    }


    /**
     * Show the form for creating a new Eleve.
     */
    public function create()
    {
        $parents = Parents::pluck('nom_prenom', 'id')->prepend('Sélectionner un parent', '');
        $pays = Pays::pluck('libelle', 'id')->prepend('Sélectionner un pays', '');
        $nationalites = Pays::pluck('libelle', 'id')->prepend('Sélectionner une nationalité', '');
        $sexes = Sexe::pluck('libelle', 'id')->prepend('Sélectionner un sexe', '');
        return view('eleves.create')
            ->with('parents', $parents)
            ->with('pays', $pays)
            ->with('nationalites', $nationalites)
            ->with('sexes', $sexes);
    }

    /**
     * Store a newly created Eleve in storage.
     */
    public function store(CreateEleveRequest $request)
    {
        $input = $request->all();

        $eleve = $this->eleveRepository->create($input);

        Flash::success('Eleve crée avec succès.');

        return redirect(route('eleves.index'));
    }

    /**
     * Display the specified Eleve.
     */
    public function show($id)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }

        return view('eleves.show')->with('eleve', $eleve);
    }

    /**
     * Show the form for editing the specified Eleve.
     */
    public function edit($id)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }
        $parents = Parents::pluck('nom_prenom', 'id')->prepend('Sélectionner un parent', '');
        $pays = Pays::pluck('libelle', 'id')->prepend('Sélectionner un pays', '');
        $nationalites = Pays::pluck('libelle', 'id')->prepend('Sélectionner une nationalité', '');
        $sexes = Sexe::pluck('libelle', 'id')->prepend('Sélectionner un sexe', '');
        return view('eleves.edit')
            ->with('eleve', $eleve)
            ->with('parents', $parents)
            ->with('pays', $pays)
            ->with('nationalites', $nationalites)
            ->with('sexes', $sexes);
    }

    /**
     * Update the specified Eleve in storage.
     */
    public function update($id, UpdateEleveRequest $request)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }

        $eleve = $this->eleveRepository->update($request->all(), $id);

        Flash::success('Eleve mise à jour avec succès.');

        return redirect(route('eleves.index'));
    }

    /**
     * Remove the specified Eleve from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }

        $this->eleveRepository->delete($id);

        Flash::success('Eleve supprimé avec succès.');

        return redirect(route('eleves.index'));
    }

   
    

}
