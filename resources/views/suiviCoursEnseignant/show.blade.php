@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails du Suivi de Cours</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('suiviCoursEnseignant.index') }}">
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Date Field -->
                    <div class="col-sm-12">
                        <strong>Date:</strong>
                        {{ $suiviCours->date }}
                    </div>

                    <!-- Titre Field -->
                    <div class="col-sm-12">
                        <strong>Titre:</strong>
                        {{ $suiviCours->titre }}
                    </div>

                    <!-- Resume Field -->
                    <div class="col-sm-12">
                        <strong>Résumé:</strong>
                        {{ $suiviCours->resume }}
                    </div>

                    <!-- Observation Field -->
                    <div class="col-sm-12">
                        <strong>Observation:</strong>
                        {{ $suiviCours->observation }}
                    </div>

                    <!-- Affectation Matiere Field -->
                    <div class="col-sm-12">
                        <strong>Affectation Matière:</strong>
                        {{ $suiviCours->affectation_display }}
                    </div>

                    <!-- Created At Field -->
                    <div class="col-sm-12">
                        <strong>Créé le:</strong>
                        {{ $suiviCours->created_at }}
                    </div>

                    <!-- Updated At Field -->
                    <div class="col-sm-12">
                        <strong>Mis à jour le:</strong>
                        {{ $suiviCours->updated_at }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
