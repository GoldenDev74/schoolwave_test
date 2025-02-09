@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Suivi de mes cours</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        @if($hasAccess)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matiere-filter">Filtrer par matière:</label>
                                <select id="matiere-filter" class="form-control">
                                    <option value="">Sélectionnez une matière</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="suivis-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Titre</th>
                                    <th>Résumé</th>
                                    <th>Observation</th>
                                    <th>Type</th>
                                    <th>Enseignant</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@if($hasAccess)
    @push('page_scripts')
        <script>
            $(document).ready(function() {
                // Charger les matières au chargement de la page
                $.ajax({
                    url: "{{ route('suiviCoursEleves.getMatieres') }}",
                    method: 'GET',
                    success: function(data) {
                        var matiereSelect = $('#matiere-filter');
                        data.forEach(function(matiere) {
                            matiereSelect.append(new Option(matiere.libelle, matiere.id));
                        });
                    },
                    error: function() {
                        console.error('Erreur lors du chargement des matières');
                    }
                });

                // Initialiser DataTable
                var table = $('#suivis-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('suiviCoursEleves.index') }}",
                        data: function(d) {
                            d.matiere = $('#matiere-filter').val() || '';
                        }
                    },
                    columns: [
                        {data: 'date', name: 'date'},
                        {data: 'titre', name: 'titre'},
                        {data: 'resume', name: 'resume'},
                        {data: 'observation', name: 'observation'},
                        {data: 'type_cours', name: 'type_cours',
                         render: function(data) {
                             return data == 1 ? 'Cours' : 'TD';
                         }
                        },
                        {data: 'enseignant', name: 'enseignant'}
                    ],
                    order: [[0, 'desc']],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json',
                        emptyTable: "Veuillez sélectionner une matière"
                    }
                });

                // Gérer le changement de matière
                $('#matiere-filter').change(function() {
                    table.draw();
                });
            });
        </script>
    @endpush
@endif
