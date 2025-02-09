<?php

namespace App\DataTables;

use App\Models\SuiviCours;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use App\Models\Classe;
use App\Models\Effectif;
use App\Models\Matiere;
use App\Models\TypeCours;
use App\Models\Horaire;
use App\Models\AffectationMatiere;

class SuiviCoursDataTable extends DataTable
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
        ->addColumn('affectation', function ($suiviCours) {
            $affectation = AffectationMatiere::find($suiviCours->affection_matiere); // Correction: 'affection_matiere' au lieu de 'affectation_cours'
            if (!$affectation) {
                return 'Non défini';
            }
            $classe = Classe::find($affectation->classe)->libelle ?? 'Non défini';
            $matiere = Matiere::find($affectation->matiere)->libelle ?? 'Non défini';
            $horaire = Horaire::find($affectation->horaire)->libelle ?? 'Non défini';
            $typeCours = TypeCours::find($affectation->type_cours)->libelle ?? 'Non défini';
        
            return "$classe, $matiere ($horaire, $typeCours)";
        })
        
            ->editColumn('date', function ($request) {
                return $request->date ? date('d-m-Y', strtotime($request->date)) : '';
            })
            ->addColumn('action', 'suivi_cours.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SuiviCours $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SuiviCours $model)
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
                'language' => ['url' => url('vendor/datatables/French.json')],
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
            'date',
            'titre',
            'resume',
            'observation',
            //'affection_matiere',
            'affectation' => ['title' => 'Affectation'],
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
