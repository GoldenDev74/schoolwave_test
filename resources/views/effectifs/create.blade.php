@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Ajout d'un effectif
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'effectifs.store']) !!}

            <div class="card-body">

                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('annee_scolaire', 'Année Scolaire:') !!}
                        {!! Form::select('annee_scolaire', [$current_annee_scolaire->id => $current_annee_scolaire->libelle], null, ['class' => 'form-control', 'disabled' => true]) !!}
                        {!! Form::hidden('annee_scolaire', $current_annee_scolaire->id) !!}
                    </div>


                    <div class="form-group col-sm-6">
                        {!! Form::label('classe', 'Classe:') !!}
                        {!! Form::select('classe', $classes, null, ['class' => 'form-control', 'placeholder' => 'Sélectionnez une classe', 'required']) !!}
                    </div>


                    <div class="form-group col-sm-6">
                        {!! Form::label('eleve', 'Élève:') !!}
                        {!! Form::select('eleve', $eleves, null, ['class' => 'form-control', 'placeholder' => 'Sélectionnez un élève']) !!}
                    </div>
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('effectifs.index') }}" class="btn btn-default"> Annuler </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
