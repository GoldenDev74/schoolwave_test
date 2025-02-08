<?php

namespace App\DataTables;

use App\Models\SuiviCours;
use App\Models\Effectif;
use App\Models\Enseignant;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class SuiviCoursParentsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->addColumn('enseignant', function($suiviCours) {
                $enseignantId = $suiviCours->affectationMatiere->enseignant ?? null;
                if ($enseignantId) {
                    $enseignant = Enseignant::find($enseignantId);
                    return $enseignant ? $enseignant->nom_prenom : 'Non défini';
                }
                return 'Non défini';
            })
            ->editColumn('date', function($suiviCours) {
                return $suiviCours->date ? $suiviCours->date->format('d-m-Y') : '';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SuiviCours $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Par défaut, retourner une requête vide
        $query = SuiviCours::whereRaw('1 = 0');

        // Ne procéder que si un élève ET une matière sont sélectionnés
        if ($this->request()->has('eleve') && $this->request()->has('matiere')) {
            $eleveId = $this->request()->get('eleve');
            $matiereId = $this->request()->get('matiere');

            if ($eleveId && $matiereId) {  // Vérifier que les valeurs ne sont pas vides
                $effectif = Effectif::where('eleve', $eleveId)
                    ->whereHas('anneeScolaires', function($q) {
                        $q->where('en_cours', true);
                    })
                    ->first();

                if ($effectif) {
                    $query = SuiviCours::with('affectationMatiere')
                        ->whereHas('affectationMatiere', function($q) use ($effectif, $matiereId) {
                            $q->where('classe', $effectif->classe)
                              ->where('matiere', $matiereId);
                        });
                }
            }
        }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('dataTableBuilder')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => false,
                'order'     => [[0, 'desc']],
                'language' => [
                    'url' => url('vendor/datatables/French.json')
                ],
                'buttons' => []
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'date' => ['name' => 'date', 'data' => 'date', 'title' => 'Date'],
            'enseignant' => ['name' => 'enseignant', 'data' => 'enseignant', 'title' => 'Enseignant'],
            'titre' => ['name' => 'titre', 'data' => 'titre', 'title' => 'Titre'],
            'resume' => ['name' => 'resume', 'data' => 'resume', 'title' => 'Résumé'],
            'observation' => ['name' => 'observation', 'data' => 'observation', 'title' => 'Observation']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'suivi_cours_parents_datatable_' . time();
    }
}
