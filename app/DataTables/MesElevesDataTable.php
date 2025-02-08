<?php

namespace App\DataTables;

use App\Models\Eleve;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class MesElevesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

     public $idParent;
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
        ->editColumn('date_naissance', function ($request) {
            return $request->date_naissance ? $request->date_naissance->format('d-m-Y') : '';
        })
        ->editColumn('nationalite', function ($row) {
            return $row->nationalites->libelle;
        })
        ->editColumn('pays_residence', function ($row) {
            return $row->paysResidence->libelle;
        })
        ->editColumn('parent', function ($row) {
            return $row->parents->nom_prenom;
        })
        ->editColumn('sexe', function ($row) {
            return $row->sexes->libelle;
        })
        ->addColumn('action', 'meseleves.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Eleve $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Eleve $model)
    {
       
        $query=$model->where('parent',$this->idParent);
        return $query->newQuery()
            ->with(['nationalites', 'paysResidence', 'parents', 'sexes']);
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
            'nom_prenom' => ['title' => 'Nom & Prénom'],
            'date_naissance' => ['title' => 'Date Naissance'],
            'lieu_naissance' => ['title' => 'Lieu Naissance'],
            'nationalite' => ['title' => 'Nationalité'],
            'pays_residence' => ['title' => 'Pays Résidence'],
            'telephone' => ['title' => 'Téléphone'],
            'email' => ['title' => 'Email'],
            'sexe' => ['title' => 'Sexe'],
            'parent' => ['title' => 'Parent']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'eleves_datatable_' . time();
    }
}
