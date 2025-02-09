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
                        @include('suiviCoursParents.table')
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@if($hasAccess)
    @push('page_scripts')
        <script>
            $(function() {
                $('#enfant-filter, #matiere-filter').change(function() {
                    window.LaravelDataTables['dataTableBuilder'].draw();
                });
            });
        </script>
    @endpush
@endif