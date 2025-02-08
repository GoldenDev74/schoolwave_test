<?php

namespace App\DataTables;

use App\Models\Parents;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class ParentDataTable extends DataTable
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
                return $request->date_naissance->format('d-m-Y');
            })
            ->editColumn('lien_eleve', function ($row) {
                return $row->lienEleves['libelle'];
            })
            ->editColumn('pays_residence', function ($row) {
                return $row->paysResidences['libelle'];
            })
            ->editColumn('nationalite', function ($row) {
                return $row->nationalites['libelle'];
            })
        ->addColumn('action', 'parents.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Parents $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Parents $model)
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
            'lieu_naissance',
            'nationalite',
            'adresse',
            'ville',
            'pays_residence',
            'telephone',
            'email',
            'lien_eleve'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'parents_datatable_' . time();
    }
}
