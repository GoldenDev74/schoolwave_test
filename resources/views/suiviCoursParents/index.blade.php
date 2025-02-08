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
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="enfant">Filtrer par enfant:</label>
                                {!! Form::select('enfant', $enfants, null, ['class' => 'form-control', 'placeholder' => 'Tous les enfants', 'id' => 'enfant-filter']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="matiere">Filtrer par matière:</label>
                                {!! Form::select('matiere', $matieres, null, ['class' => 'form-control', 'placeholder' => 'Toutes les matières', 'id' => 'matiere-filter']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="suivis-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Classe</th>
                                    <th>Matière</th>
                                    <th>Titre</th>
                                    <th>Résumé</th>
                                    <th>Observation</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($suivis as $suivi)
                                <tr>
                                    <td>{{ $suivi->date }}</td>
                                    <td>{{ $suivi->classe }}</td>
                                    <td>{{ $suivi->matiere }}</td>
                                    <td>{{ $suivi->titre }}</td>
                                    <td>{{ $suivi->resume }}</td>
                                    <td>{{ $suivi->observation }}</td>
                                    <td>{{ $suivi->type_cours == 1 ? 'Cours' : 'TD' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
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
                var table = $('#suivis-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
                    }
                });

                $('#enfant-filter').on('change', function() {
                    var classe = $(this).find('option:selected').text();
                    table.column(1).search(classe ? classe : '').draw();
                });

                $('#matiere-filter').on('change', function() {
                    var matiere = $(this).find('option:selected').text();
                    table.column(2).search(matiere ? matiere : '').draw();
                });
            });
        </script>
    @endpush
@endif
