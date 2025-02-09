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
        
        ->editColumn('date', function($suiviCours) {
            return $suiviCours->date ? $suiviCours->date->format('d-m-Y') : '';
        })
        ->addColumn('action', 'suivi_cours.datatables_actions');
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

        // Ne procéder que si un élève est sélectionné
        if ($this->request()->has('eleve') && $this->request()->get('eleve') != '') {
            $eleveId = $this->request()->get('eleve');
            
            // Récupérer la classe de l'élève pour l'année en cours
            $effectif = Effectif::where('eleve', $eleveId)
                ->whereHas('anneeScolaire', function($q) {
                    $q->where('en_cours', true);
                })
                ->first();

            if ($effectif) {
                $query = SuiviCours::with(['affectationMatiere' => function($q) {
                    $q->with(['enseignant', 'matiere', 'classe']);
                }]);

                $query->whereHas('affectationMatiere', function($q) use ($effectif) {
                    $q->where('classe', $effectif->classe);
                });

                // Si une matière est sélectionnée, filtrer par matière
                if ($this->request()->has('matiere') && $this->request()->get('matiere') != '') {
                    $matiereId = $this->request()->get('matiere');
                    $query->whereHas('affectationMatiere', function($q) use ($matiereId) {
                        $q->where('matiere', $matiereId);
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
            ->minifiedAjax('', '
            {
                data: function(d) {
                    d.eleve = $("#enfant-filter").val();
                    d.matiere = $("#matiere-filter").val();
                }
            }
        ')
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'pageLength' => 10,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Tout']],
                'language' => [
                    'url' => url('vendor/datatables/French.json')
                ],
                'buttons' => [
                    'pageLength',
                    'colvis'
                ],
                'orderCellsTop' => true,
                'fixedHeader' => true
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
            'date' => [
                'name' => 'date',
                'data' => 'date',
                'title' => 'Date',
                'orderable' => true,
                'searchable' => true
            ],
            'enseignant' => [
                'name' => 'enseignant',
                'data' => 'enseignant',
                'title' => 'Enseignant',
                'orderable' => true,
                'searchable' => true
            ],
            'titre' => [
                'name' => 'titre',
                'data' => 'titre',
                'title' => 'Titre',
                'orderable' => true,
                'searchable' => true
            ],
            'resume' => [
                'name' => 'resume',
                'data' => 'resume',
                'title' => 'Résumé',
                'orderable' => true,
                'searchable' => true
            ],
            'observation' => [
                'name' => 'observation',
                'data' => 'observation',
                'title' => 'Observation',
                'orderable' => true,
                'searchable' => true
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'suivi_cours_datatable_' . time();
    }
}
