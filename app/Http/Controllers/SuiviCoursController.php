<?php

namespace App\Http\Controllers;

use App\DataTables\SuiviCoursDataTable;
use App\Http\Requests\CreateSuiviCoursRequest;
use App\Http\Requests\UpdateSuiviCoursRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SuiviCoursRepository;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Log;

class SuiviCoursController extends AppBaseController
{
    /** @var SuiviCoursRepository $suiviCoursRepository*/
    private $suiviCoursRepository;

    public function __construct(SuiviCoursRepository $suiviCoursRepo)
    {
        $this->suiviCoursRepository = $suiviCoursRepo;
    }

    /**
     * Display a listing of the SuiviCours.
     */
    public function index(SuiviCoursDataTable $suiviCoursDataTable)
    {
        return $suiviCoursDataTable->render('suivi_cours.index');
    }


    /**
     * Show the form for creating a new SuiviCours.
     */
    public function create()
    {
        return view('suivi_cours.create');
    }

    /**
     * Store a newly created SuiviCours in storage.
     */
    public function store(CreateSuiviCoursRequest $request)
    {
        // Récupérer toutes les données validées du formulaire
        $input = $request->all();

        try {
            // Si vous utilisez un repository pour gérer la création, par exemple :
            $suiviCours = $this->suiviCoursRepository->create($input);

            // Sinon, vous pouvez directement utiliser le modèle :
            // $suiviCours = SuiviCours::create($input);

            return response()->json([
                'success' => true,
                'message' => 'Suivi de cours enregistré avec succès.'
            ]);
        } catch (\Exception $e) {
            // On log l'erreur pour pouvoir la débugger plus tard
            Log::error('Erreur store SuiviCoursController: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erreur serveur'
            ], 500);
        }
    }



    /**
     * Display the specified SuiviCours.
     */
    public function show($id)
    {
        $suiviCours = $this->suiviCoursRepository->find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi Cours not found');

            return redirect(route('suiviCours.index'));
        }

        return view('suivi_cours.show')->with('suiviCours', $suiviCours);
    }

    /**
     * Show the form for editing the specified SuiviCours.
     */
    public function edit($id)
    {
        $suiviCours = $this->suiviCoursRepository->find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi Cours not found');

            return redirect(route('suiviCours.index'));
        }

        return view('suivi_cours.edit')->with('suiviCours', $suiviCours);
    }

    /**
     * Update the specified SuiviCours in storage.
     */
    public function update($id, UpdateSuiviCoursRequest $request)
    {
        $suiviCours = $this->suiviCoursRepository->find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi Cours not found');

            return redirect(route('suiviCours.index'));
        }

        $suiviCours = $this->suiviCoursRepository->update($request->all(), $id);

        Flash::success('Suivi Cours updated successfully.');

        return redirect(route('suiviCours.index'));
    }

    /**
     * Remove the specified SuiviCours from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $suiviCours = $this->suiviCoursRepository->find($id);

        if (empty($suiviCours)) {
            Flash::error('Suivi Cours not found');

            return redirect(route('suiviCours.index'));
        }

        $this->suiviCoursRepository->delete($id);

        Flash::success('Suivi Cours deleted successfully.');

        return redirect(route('suiviCours.index'));
    }
}
