<?php

namespace App\DataTables;

use App\Models\Eleve;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class EleveDataTable extends DataTable
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
                return $request->date_naissance ? date('d-m-Y', strtotime($request->date_naissance)) : '';
            })
            ->editColumn('nationalite', function ($row) {
                return $row->nationalites ? $row->nationalites->libelle : '';
            })
            ->editColumn('pays_residence', function ($row) {
                return $row->paysResidence ? $row->paysResidence->libelle : '';
            })
            ->editColumn('parent', function ($row) {
                return $row->parents ? $row->parents->nom_prenom : '';
            })
            ->editColumn('sexe', function ($row) {
                return $row->sexes ? $row->sexes->libelle : '';
            })
            ->addColumn('action', 'eleves.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Eleve $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Eleve $model)
    {
        return $model->newQuery()
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
                'buttons'   => []
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
        return 'eleves_' . date('YmdHis');
    }
}
