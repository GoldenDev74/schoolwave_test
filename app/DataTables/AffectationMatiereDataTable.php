<?php

namespace App\DataTables;

use App\Models\AffectationMatiere;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class AffectationMatiereDataTable extends DataTable
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
        ->editColumn('classe', function ($row) {
            return $row->classes['libelle'];
        })
        ->editColumn('annee_scolaire', function ($row) {
            return $row->anneeScolaires['libelle'];
        })
        ->editColumn('matiere', function ($row) {
            return $row->matieres['libelle'];
        })
        ->editColumn('enseignant', function ($row) {
            return $row->enseignants['nom_prenom'];
        })
        ->editColumn('horaire', function ($row) {
            return $row->horaires['libelle'];
        })
        ->editColumn('type_cours', function ($row) {
            return $row->typeCourss['libelle'];
        })
        ->editColumn('jour', function ($row) {
            return $row->jours['libelle'];
        })
        ->addColumn('action', 'affectation_matieres.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AffectationMatiere $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AffectationMatiere $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'language' => [
                    'url' => url('vendor/datatables/French.json')],
                'buttons'   => [
                    // Enable Buttons as per your need
//                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
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
            'classe',
            'annee_scolaire',
            'matiere',
            'enseignant',
            'horaire',
            'type_cours',
            'jour',
            'mode_affection',
            'debut',
            'fin'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'affectation_matieres_datatable_' . time();
    }
}
