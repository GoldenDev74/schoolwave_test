@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de l'Élève</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('effectifs.index') }}">
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Nom et Prénom</th>
                            <td>{{ $effectif->eleves->nom_prenom }}</td>
                        </tr>
                        <tr>
                            <th>Sexe</th>
                            <td>{{ optional($effectif->eleves->sexes)->libelle }}</td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td>{{ $effectif->eleves->telephone }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $effectif->eleves->email }}</td>
                        </tr>
                        <tr>
                            <th>Parent</th>
                            <td>{{ optional($effectif->eleves->parents)->nom_prenom }}</td>
                        </tr>
                        <tr>
                            <th>Classe</th>
                            <td>{{ optional($effectif->classes)->libelle }}</td>
                        </tr>
                        <tr>
                            <th>Année Scolaire</th>
                            <td>{{ optional($effectif->anneeScolaires)->libelle }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
