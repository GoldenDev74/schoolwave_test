<?php

namespace App\DataTables;

use App\Models\Enseignant;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class EnseignantDataTable extends DataTable
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
            ->editColumn('date_naissance', function ($request) {
                return $request->date_naissance ? $request->date_naissance->format('d-m-Y') : '';
            })
            ->editColumn('date_engagement', function ($request) {
                return $request->date_engagement ? $request->date_engagement->format('d-m-Y') : '';
            })
            ->editColumn('date_diplome', function ($request) {
                return $request->date_diplome ? $request->date_diplome->format('d-m-Y') : '';
            })
            ->editColumn('diplome', function ($row) {
                return $row->diplomes ? $row->diplomes->libelle : '';
            })
            ->editColumn('filiere', function ($row) {
                return $row->filieres ? $row->filieres->libelle : '';
            })
            ->editColumn('sexe', function ($row) {
                return $row->sexes ? $row->sexes->libelle : '';
            })
            ->editColumn('type_cours', function ($row) {
                return $row->typeCours ? $row->typeCours->libelle : '';
            })
            ->addColumn('action', 'enseignants.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Enseignant $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Enseignant $model)
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
                    'url' => url('vendor/datatables/French.json')
                ],
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
            'nom_prenom',
            'date_naissance',
            'date_engagement',
            'date_diplome',
            'diplome',
            'filiere',
            'sexe',
            'type_cours'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'enseignants_datatable_' . time();
    }

}