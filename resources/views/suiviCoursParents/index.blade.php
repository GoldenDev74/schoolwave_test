@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Suivi des cours de mes enfants</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @if($hasAccess)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enfant-filter">Filtrer par enfant:</label>
                                <select id="enfant-filter" class="form-control">
                                    <option value="">Tous les enfants</option>
                                    @foreach($enfants as $id => $nom)
                                        <option value="{{ $id }}">{{ $nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matiere-filter">Filtrer par matière:</label>
                                <select id="matiere-filter" class="form-control" disabled>
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
                                    <th>Classe</th>
                                    <th>Titre</th>
                                    <th>Résumé</th>
                                    <th>Observation</th>
                                    <th>Type</th>
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
                // Initialiser DataTable
                var table = $('#suivis-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('suiviCoursParents.index') }}",
                        data: function(d) {
                            d.eleve = $('#enfant-filter').val() || '';
                            d.matiere = $('#matiere-filter').val() || '';
                        }
                    },
                    columns: [
                        {data: 'date', name: 'date'},
                        {data: 'classe', name: 'classe'},
                        {data: 'titre', name: 'titre'},
                        {data: 'resume', name: 'resume'},
                        {data: 'observation', name: 'observation'},
                        {data: 'type_cours', name: 'type_cours',
                         render: function(data) {
                             return data == 1 ? 'Cours' : 'TD';
                         }
                        }
                    ],
                    order: [[0, 'desc']],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json',
                        emptyTable: "Veuillez sélectionner un enfant et une matière"
                    }
                });

                // Initialiser le select de matière comme désactivé
                $('#matiere-filter').prop('disabled', true);

                // Gérer le changement d'élève
                $('#enfant-filter').change(function() {
                    var eleveId = $(this).val();
                    var matiereSelect = $('#matiere-filter');
                    
                    // Réinitialiser le select matière
                    matiereSelect.empty().append('<option value="">Sélectionnez une matière</option>');
                    
                    if (eleveId) {
                        // Activer le chargement
                        matiereSelect.prop('disabled', true);
                        
                        // Charger les matières de l'élève
                        $.ajax({
                            url: "{{ route('suiviCoursParents.getMatieres') }}",
                            data: { eleve: eleveId },
                            method: 'GET',
                            success: function(data) {
                                // Remplir le select avec les matières
                                data.forEach(function(matiere) {
                                    matiereSelect.append(new Option(matiere.libelle, matiere.id));
                                });
                                // Activer le select
                                matiereSelect.prop('disabled', false);
                            },
                            error: function() {
                                console.error('Erreur lors du chargement des matières');
                                matiereSelect.prop('disabled', true);
                            }
                        });
                    } else {
                        // Si aucun élève n'est sélectionné, désactiver le select matière
                        matiereSelect.prop('disabled', true);
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
