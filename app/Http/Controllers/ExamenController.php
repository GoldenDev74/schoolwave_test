<?php

namespace App\Http\Controllers;

use App\DataTables\ExamenDataTable;
use App\Http\Requests\CreateExamenRequest;
use App\Http\Requests\UpdateExamenRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ExamenRepository;
// Ajoutez ces lignes en haut du fichier ExamenController.php
use App\Models\AffectationMatiere;
use App\Models\Effectif;
use App\Models\Examen;
use Illuminate\Http\Request;
use Flash;
use App\Models\TypeExamen;
use Illuminate\Support\Facades\Log;

class ExamenController extends AppBaseController
{
    /** @var ExamenRepository $examenRepository*/
    private $examenRepository;

    public function __construct(ExamenRepository $examenRepo)
    {
        $this->examenRepository = $examenRepo;
    }

    /**
     * Display a listing of the Examen.
     */
    public function index(ExamenDataTable $examenDataTable)
    {
    return $examenDataTable->render('examens.index');
    }


    /**
     * Show the form for creating a new Examen.
     */
    public function create()
    {
        $typeExamens = TypeExamen::pluck('libelle', 'id'); // Liste des types d'examens
        
        return view('examens.create')->with(compact('typeExamens', 'classes'));
    }
    

    /**
     * Store a newly created Examen in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'libelle' => 'required|string|max:100',
        'type_examen' => 'required|exists:type_examen,id',
        'affectation_matiere' => 'required|exists:affectation_matiere,id',
        'notes' => 'required|array'
    ]);

    try {
        // Log de débogage pour les données reçues
        Log::info('Données reçues pour l\'enregistrement des notes:', [
            'affectation' => $request->affectation_matiere,
            'notes' => $request->notes
        ]);

        foreach ($request->notes as $eleveId => $note) {
            // Conversion en integer pour sécurité
            $eleveId = (int)$eleveId;
            
            // Log détaillé pour chaque élève
            Log::debug("Tentative d'enregistrement pour élève ID: $eleveId", [
                'note' => $note,
                'eleve_id_type' => gettype($eleveId)
            ]);

            Examen::create([
                'libelle' => $request->libelle,
                'note' => $note,
                'eleve' => $eleveId, // Utilisation directe de l'ID élève
                'type_examen' => $request->type_examen,
                'affectation' => $request->affectation_matiere,
            ]);
        }

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        Log::error('Erreur ExamenController: '.$e->getMessage()."\n".$e->getTraceAsString());
        return response()->json(['error' => 'Erreur serveur'], 500);
    }
}

    /**
     * Display the specified Examen.
     */
    public function show($id)
    {
        $examen = $this->examenRepository->find($id);

        if (empty($examen)) {
            Flash::error('Examen non trouvé');

            return redirect(route('examens.index'));
        }

        return view('examens.show')->with('examen', $examen);
    }

    /**
     * Show the form for editing the specified Examen.
     */
    public function edit($id)
    {
        $examen = $this->examenRepository->find($id);

        if (empty($examen)) {
            Flash::error('Examen non trouvé');

            return redirect(route('examens.index'));
        }

        return view('examens.edit')->with('examen', $examen);
    }

    /**
     * Update the specified Examen in storage.
     */
    public function update($id, UpdateExamenRequest $request)
    {
        $examen = $this->examenRepository->find($id);

        if (empty($examen)) {
            Flash::error('Examen non trouvé');

            return redirect(route('examens.index'));
        }

        $examen = $this->examenRepository->update($request->all(), $id);

        Flash::success('Examen mise à jour avec succès.');

        return redirect(route('examens.index'));
    }

    /**
     * Remove the specified Examen from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $examen = $this->examenRepository->find($id);

        if (empty($examen)) {
            Flash::error('Examen non trouvé');

            return redirect(route('examens.index'));
        }

        $this->examenRepository->delete($id);

        Flash::success('Examen supprimé avec succès.');

        return redirect(route('examens.index'));
    }
}
