@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Modifier le Suivi de Cours</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">
            {!! Form::model($suiviCours, ['route' => ['suiviCoursEnseignant.update', $suiviCours->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    <!-- Date Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('date', 'Date:') !!}
                        {!! Form::date('date', null, ['class' => 'form-control', 'required']) !!}
                    </div>

                    <!-- Titre Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('titre', 'Titre:') !!}
                        {!! Form::text('titre', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
                    </div>

                    <!-- Resume Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('resume', 'Résumé:') !!}
                        {!! Form::text('resume', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
                    </div>

                    <!-- Observation Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('observation', 'Observation:') !!}
                        {!! Form::text('observation', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
                    </div>

                    <!-- Affectation Matiere Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('affection_matiere', 'Classe et Matière:') !!}
                        {!! Form::select('affection_matiere', $affectationMatieres, null, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('suiviCoursEnseignant.index') }}" class="btn btn-default">Annuler</a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
