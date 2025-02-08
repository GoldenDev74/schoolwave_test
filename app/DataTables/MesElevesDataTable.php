<?php

namespace App\DataTables;

use App\Models\Eleve;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class MesElevesDataTable extends DataTable
{
    public function dataTable($query)
    {
        $idParent = auth()->user()->id;
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->editColumn('date_naissance', function ($eleve) {
                return $eleve->date_naissance 
                    ? $eleve->date_naissance->format('d/m/Y') 
                    : '';
            })
            ->editColumn('nationalite', function ($eleve) {
                return $eleve->nationalites ? $eleve->nationalites->libelle : '';
            })
            ->editColumn('pays_residence', function ($eleve) {
                return $eleve->paysResidence ? $eleve->paysResidence->libelle : '';
            })
            ->editColumn('sexe', function ($eleve) {
                return $eleve->sexes ? $eleve->sexes->libelle : '';
            })
            ->addColumn('action', function($eleve) {
                return view('meseleves.datatables_actions', [
                    'id' => $eleve->id,
                    'nom_prenom' => $eleve->nom_prenom
                ]);
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(Eleve $model)
    {
        $parent = \App\Models\Parents::where('email', auth()->user()->email)->first();
        $parentId = $parent ? $parent->id : 0;
        
        return $model->newQuery()
            ->with(['nationalites', 'paysResidence', 'sexes'])
            ->where('parent', $parentId);
    }

    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'asc']],
                'language'  => ['url' => url('vendor/datatables/French.json')],
                'buttons'   => []
            ]);
    }

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
        ];
    }

    protected function filename(): string
    {
        return 'eleves_datatable_' . date('YmdHis');
    }
}