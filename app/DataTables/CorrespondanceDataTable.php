<?php

namespace App\DataTables;

use App\Models\Correspondance;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Support\Facades\DB;

class CorrespondanceDataTable extends DataTable
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
            ->editColumn('expediteur', function ($row) {
                return $row->expediteurUser ? $row->expediteurUser->name : 'N/A';
            })
            ->editColumn('cible', function ($row) {
                return $row->cibleProfil ? $row->cibleProfil->libelle : 'N/A';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i');
            })
            ->addColumn('action', 'correspondances.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Correspondance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Correspondance $model)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur est un enseignant en consultant la table user_profil
        $userProfil = DB::table('user_profil')->where('user', $user->id)->first();
        
        // Si l'utilisateur possède un personnel associé, on considère qu'il est enseignant
        if ($userProfil && isset($userProfil->personnel)) {
            $query = $model->newQuery()
                ->where('expediteur', $user->id)
                ->with(['expediteurUser', 'cibleProfil']);
        } else {
            $query = $model->newQuery()
                ->with(['expediteurUser', 'cibleProfil']);
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
            ->columns($this->getColumns())
            ->ajax(route('correspondances.index'))
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
            'objet',
            'destinataire',
            'message',
            [
                'data' => 'expediteur',
                'title' => 'Expéditeur'
            ],
            [
                'data' => 'cible',
                'title' => 'Profil Cible'
            ],
            [
                'data' => 'created_at',
                'title' => 'Date Envoi'
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
        return 'correspondances_datatable_' . time();
    }
}
