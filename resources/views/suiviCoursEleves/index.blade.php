@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Suivi de mes cours</h1>
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

                $('#matiere-filter').on('change', function() {
                    var matiere = $(this).val();
                    table.column(1).search(matiere ? matiere : '').draw();
                });
            });
        </script>
    @endpush
@endif
