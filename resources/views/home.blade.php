@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête Statistiques -->
    <div class="bg-white px-4">
        <h4 class="text-indigo-900 mb-4">Statistiques</h4>

        <div class="row g-4 mb-5">
        <!-- Carte Élèves -->
        <div class="col-md-3">
            <div class="card border-0" style="background-color: #FFF1F2;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3" style="background-color: #FECDD3;">
                            <i class="fas fa-users" style="color: #EC4899;"></i>
                        </div>
                        <div>
                            <div class="h3 mb-0">527</div>
                            <div class="text-muted small">Nbre d'élèves</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Enseignants -->
        <div class="col-md-3">
            <div class="card border-0" style="background-color: #FEFCE8;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3" style="background-color: #FEF08A;">
                            <i class="fas fa-graduation-cap" style="color: #EAB308;"></i>
                        </div>
                        <div>
                            <div class="h3 mb-0">23</div>
                            <div class="text-muted small">Nbre d'enseignants actifs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Taux de présence -->
        <div class="col-md-3">
            <div class="card border-0" style="background-color: #F0FDF4;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3" style="background-color: #BBF7D0;">
                            <i class="fas fa-percentage" style="color: #22C55E;"></i>
                        </div>
                        <div>
                            <div class="h3 mb-0">67%</div>
                            <div class="text-muted small">
                                Taux de présence
                                <!-- <div class="small" style="color: #22C55E;">+1.2% par rapport à hier</div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Paiements -->
        <div class="col-md-3">
            <div class="card border-0" style="background-color: #F3E8FF;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3" style="background-color: #E9D5FF;">
                            <i class="fas fa-wallet" style="color: #9333EA;"></i>
                        </div>
                        <div>
                            <div class="h3 mb-0">780 000</div>
                            <div class="text-muted small">Montant total des paiement</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    </div>

    <!-- Graphiques -->
    <div class="row g-4 mb-5">
        <!-- Revenue Total -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 ps-4">
                    <h5 class="card-title text-indigo-900">Revenue Total</h5>
                </div>
                <div class="card-body">
                    <div id="revenueChart"></div>
                </div>
            </div>
        </div>

        <!-- Inscriptions -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 ps-4">
                    <h5 class="card-title text-indigo-900">Inscriptions</h5>
                </div>
                <div class="card-body">
                    <div id="inscriptionsChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des paiements -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 ps-4">
            <h5 class="card-title text-indigo-900">Liste des paiements éffectués</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-muted">#</th>
                            <th class="text-muted">Nom de l'élève</th>
                            <th class="text-muted">Montant</th>
                            <th class="text-muted">Date du paiement</th>
                            <th class="text-muted">Méthode de paiement</th>
                            <th class="text-muted">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01</td>
                            <td>ABALO Jean</td>
                            <td>150.000 fcfa</td>
                            <td>19/10/2024</td>
                            <td>Carte bancaire</td>
                            <td><span class="badge rounded-pill text-primary border border-primary" style="background-color: #EEF2FF;">Confirmé</span></td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td>AKAPKO Cédric</td>
                            <td>135.000 fcfa</td>
                            <td>13/09/2024</td>
                            <td>Carte bancaire</td>
                            <td><span class="badge rounded-pill text-secondary border border-secondary" style="background-color: #F9FAFB;">En attente</span></td>
                        </tr>
                        <tr>
                            <td>03</td>
                            <td>AJAVON Willy</td>
                            <td>195.000 fcfa</td>
                            <td>10/09/2024</td>
                            <td>MTN Mobile Money</td>
                            <td><span class="badge rounded-pill text-primary border border-primary" style="background-color: #EEF2FF;">Confirmé</span></td>
                        </tr>
                        <tr>
                            <td>04</td>
                            <td>SEPKO Edouard</td>
                            <td>180.000 fcfa</td>
                            <td>10/09/2024</td>
                            <td>Flooz</td>
                            <td><span class="badge rounded-pill text-primary border border-primary" style="background-color: #EEF2FF;">Confirmé</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .gap-3 { gap: 1rem; }
    .card {
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .rounded-circle {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .text-indigo-900 {
        color: #312E81;
    }
    .table > thead > tr > th {
        font-weight: 500;
        border-bottom-width: 1px;
    }
    .badge {
        font-weight: 500;
        padding: 0.5em 1em;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du graphique Revenue Total
    const revenueOptions = {
        series: [{
            name: 'Entrées',
            data: [12, 15, 5, 15, 10, 15]
        }, {
            name: 'Sorties',
            data: [12, 11, 20, 6, 10, 12]
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        colors: ['#818CF8', '#F472B6'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '60%',
            }
        },
        dataLabels: {
            enabled: false
        },
        grid: {
            show: false
        },
        xaxis: {
            categories: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            show: true,
            labels: {
                formatter: function(val) {
                    return val + 'k';
                }
            }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center'
        }
    };

    // Configuration du graphique Inscriptions
    const inscriptionsOptions = {
        series: [{
            name: 'Anciens inscrits',
            data: [300, 280, 250, 260, 240, 280, 300, 320, 350, 300, 280]
        }, {
            name: 'Nouveaux inscrits',
            data: [200, 220, 240, 230, 200, 210, 230, 240, 260, 230, 220]
        }],
        chart: {
            type: 'line',
            height: 300,
            toolbar: {
                show: false
            }
        },
        colors: ['#818CF8', '#F472B6'],
        stroke: {
            curve: 'smooth',
            width: 2
        },
        grid: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Sept', 'Oct', 'Nov', 'Dec'],
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            show: true,
            labels: {
                formatter: function(val) {
                    return val;
                }
            }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center'
        },
        fill: {
            type: 'gradient',
            
        }
    };

    // Création des graphiques
    const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
    const inscriptionsChart = new ApexCharts(document.querySelector("#inscriptionsChart"), inscriptionsOptions);

    // Rendu des graphiques
    revenueChart.render();
    inscriptionsChart.render();
});
</script>
@endpush
@endsection
