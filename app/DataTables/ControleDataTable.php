<?php

namespace App\DataTables;

use App\Models\Controle;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use App\Models\Enseignant;
use App\Models\Classe;
use App\Models\Effectif;
use App\Models\Matiere;
use App\Models\TypeCours;
use App\Models\Horaire;
use App\Models\AffectationMatiere;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ControleDataTable extends DataTable
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
            // Ajouter une colonne formatée pour "enseignant"
            ->addColumn('enseignant', function ($controle) {
                $affectation = AffectationMatiere::find($controle->affectation_cours);
                return $affectation ? Enseignant::find($affectation->enseignant)->nom_prenom ?? 'Non défini' : 'Non défini';
            })
            // Ajouter une colonne formatée pour "affectation"
            ->addColumn('affectation', function ($controle) {
                $affectation = AffectationMatiere::find($controle->affectation_cours);
                if (!$affectation) {
                    return 'Non défini';
                }
                $classe = Classe::find($affectation->classe)->libelle ?? 'Non défini';
                $matiere = Matiere::find($affectation->matiere)->libelle ?? 'Non défini';
                $horaire = Horaire::find($affectation->horaire)->libelle ?? 'Non défini';
                $typeCours = TypeCours::find($affectation->type_cours)->libelle ?? 'Non défini';

                return "$classe, $matiere ($horaire, $typeCours)";
            })
            // Ajouter une colonne pour "effectif"
            ->addColumn('effectif', function ($controle) {
                $affectation = AffectationMatiere::find($controle->affectation_cours);
                return $affectation ? Effectif::where('classe', $affectation->classe)->count() : 0;
            })
            // Ajouter une colonne pour "presents"
            ->addColumn('presents', function ($controle) {
                return $controle->presents_count;
            })
            // Ajouter le formatage pour "date_controle"
            ->editColumn('date_controle', function ($controle) {
                // Convertir la date en utilisant Carbon
                return \Carbon\Carbon::parse($controle->date_controle)->format('d-m-Y');
            })
            // Ajouter une colonne pour "action"
            ->addColumn('action', function ($controle) {
                return view('controles.datatables_actions', compact('controle'));
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Controle $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        Log::info("Filtres reçus :", [
            'enseignant' => $this->request->get('enseignant'),
            'classe' => $this->request->get('classe'),
            'date_start' => $this->request->get('date_start'),
            'date_end' => $this->request->get('date_end')
        ]);

        
        $query = Controle::query()
            ->selectRaw('
                MIN(id) as id, 
                affectation_cours, 
                date_controle, 
                GROUP_CONCAT(id) as controle_ids, 
                COUNT(id) as presents_count
            ')
            ->where('present', true)
            ->groupBy('affectation_cours', 'date_controle');
    
        // Récupération des filtres avec get()
        $enseignant = $this->request->get('enseignant');
        $classe = $this->request->get('classe');
        $dateStart = $this->request->get('date_start');
        $dateEnd = $this->request->get('date_end');
    
        if ($enseignant) {
            $query->whereHas('affectationCourss', function($q) use ($enseignant) {
                $q->where('enseignant', $enseignant);
            });
        }
    
        if ($classe) {
            $query->whereHas('affectationCourss', function($q) use ($classe) {
                $q->where('classe', $classe);
            });
        }
    
        // Récupération des dates au format YYYY-MM-DD
    $dateStart = $this->request->get('date_start');
    $dateEnd = $this->request->get('date_end');

    if ($dateStart && $dateEnd) {
        // Validation des dates
        try {
            $start = Carbon::parse($dateStart)->startOfDay();
            $end = Carbon::parse($dateEnd)->endOfDay();
            
            $query->whereBetween('date_controle', [$start, $end]);
        } catch (\Exception $e) {
            Log::error("Erreur de parsing des dates : $dateStart - $dateEnd");
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
            ->setTableId('controles-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => route('controles.index'),
                'data' => 'function(d) {
                    d.enseignant = $("#filter-enseignant").val();
                    d.classe = $("#filter-classe").val();
                    d.date_start = $("#filter-date-start").val();
                    d.date_end = $("#filter-date-end").val();
                }'
            ])            
            ->dom("<'row'<'col-sm-12 col-md-6'B>>" .
                  "<'row'<'col-sm-12'tr>>" .
                  "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
            ->orderBy(4, 'desc')
            ->parameters([
                'language' => ['url' => url('vendor/datatables/French.json')],
                'stateSave' => true,
                'serverSide' => true,
                'processing' => true,
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
            'enseignant' => ['title' => 'Enseignant'],
            'affectation' => ['title' => 'Affectation'],
            'effectif' => ['title' => 'Effectif'],
            'presents' => ['title' => 'Présents'],
            'date_controle' => ['title' => 'Date'],
           
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'controles_' . date('YmdHis');
    }
}
