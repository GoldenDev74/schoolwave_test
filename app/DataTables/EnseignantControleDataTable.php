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

class EnseignantControleDataTable extends DataTable
{
    /**
     * Construction de la DataTable.
     *
     * @param mixed $query Résultats de la méthode query()
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->addColumn('enseignant', function ($controle) {
                $affectation = AffectationMatiere::find($controle->affectation_cours);
                return $affectation ? Enseignant::find($affectation->enseignant)->nom_prenom ?? 'Non défini' : 'Non défini';
            })
            ->addColumn('affectation', function ($controle) {
                $affectation = AffectationMatiere::find($controle->affectation_cours);
                if (!$affectation) {
                    return 'Non défini';
                }
                $classe   = Classe::find($affectation->classe)->libelle ?? 'Non défini';
                $matiere  = Matiere::find($affectation->matiere)->libelle ?? 'Non défini';
                $horaire  = Horaire::find($affectation->horaire)->libelle ?? 'Non défini';
                $typeCours = TypeCours::find($affectation->type_cours)->libelle ?? 'Non défini';

                return "$classe, $matiere ($horaire, $typeCours)";
            })
            ->addColumn('effectif', function ($controle) {
                $affectation = AffectationMatiere::find($controle->affectation_cours);
                return $affectation ? Effectif::where('classe', $affectation->classe)->count() : 0;
            })
            ->addColumn('presents', function ($controle) {
                return $controle->presents_count;
            })
            ->editColumn('date_controle', function ($controle) {
                return Carbon::parse($controle->date_controle)->format('d-m-Y');
            })
            ->addColumn('action', function ($controle) {
                return view('controles.datatables_actions', compact('controle'));
            })
            ->rawColumns(['action']);
    }

    /**
     * Construction de la requête pour le DataTable.
     *
     * @param \App\Models\Controle $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        Log::info("Filtres reçus :", [
            'enseignant' => $this->request->get('enseignant'),
            'classe'     => $this->request->get('classe'),
            'date_start' => $this->request->get('date_start'),
            'date_end'   => $this->request->get('date_end')
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

        // Récupération des filtres (le filtre enseignant est pré-rempli dans la vue)
        $enseignant = $this->request->get('enseignant');
        $classe     = $this->request->get('classe');
        $dateStart  = $this->request->get('date_start');
        $dateEnd    = $this->request->get('date_end');

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

        if ($dateStart && $dateEnd) {
            try {
                $start = Carbon::parse($dateStart)->startOfDay();
                $end   = Carbon::parse($dateEnd)->endOfDay();
                $query->whereBetween('date_controle', [$start, $end]);
            } catch (\Exception $e) {
                Log::error("Erreur de parsing des dates : $dateStart - $dateEnd");
            }
        }

        return $query;
    }

    /**
     * Configuration de l'HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('controles-table')
            ->columns($this->getColumns())
            ->ajax([
                'url'  => route('controles.index'),
                'data' => 'function(d) {
                    d.enseignant = $("#filter-enseignant").val();
                    d.classe     = $("#filter-classe").val();
                    d.date_start = $("#filter-date-start").val();
                    d.date_end   = $("#filter-date-end").val();
                }'
            ])
            ->dom("<'row'<'col-sm-12 col-md-6'B>>" .
                  "<'row'<'col-sm-12'tr>>" .
                  "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
            ->orderBy(4, 'desc')
            ->parameters([
                'language'  => ['url' => url('vendor/datatables/French.json')],
                'stateSave' => true,
                'serverSide'=> true,
                'processing'=> true,
            ]);
    }

    /**
     * Définition des colonnes.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'enseignant'    => ['title' => 'Enseignant'],
            'affectation'   => ['title' => 'Affectation'],
            'effectif'      => ['title' => 'Effectif'],
            'presents'      => ['title' => 'Présents'],
            'date_controle' => ['title' => 'Date'],
        ];
    }

    /**
     * Nom du fichier exporté.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'controles_' . date('YmdHis');
    }
}
