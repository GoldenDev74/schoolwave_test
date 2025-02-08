@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Container pour les alertes -->
    

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fr.js"></script>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Affectation des Matières</h1>
                    <div class="alert-container position-relative top-0 end-0 z-3 mt-2 me-2" style="min-width: 300px;">
                    <!-- Les alertes seront injectées ici par JavaScript -->
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div>
            @include('flash::message')
            
        </div>
        <div class="card">
        
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="enseignant">Enseignant</label>
                            <select class="form-control" id="enseignant" name="enseignant">
                                <option value="">Sélectionner un enseignant</option>
                                @forelse($enseignants as $enseignant)
                                    <option value="{{ $enseignant['id'] }}"
                                            data-type-cours="{{ $enseignant['type_cours'] }}"
                                            data-type-cours-libelle="{{ $enseignant['type_cours_libelle'] }}">
                                        {{ $enseignant['nom_prenom'] }}
                                    </option>
                                @empty
                                    <!-- Pas d'enseignants disponibles -->
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="classe">Classe</label>
                            <select class="form-control" id="classe" name="classe">
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe['id'] }}" data-type-cours="{{ $classe['type_cours'] }}">
                                        {{ $classe['libelle'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mt-4" id="emploi-du-temps-container" style="display: none;">
                        <div id="no-selection-message" class="alert alert-info text-center text-muted">
                            Veuillez sélectionner une classe ou un professeur pour afficher l'emploi du temps.
                        </div>
                        <table class="table table-bordered">
                        @if($jours->isNotEmpty())
                                <thead>
                                    <tr>
                                        <th></th>
                                        @foreach($jours as $jour)
                                            <th>{{ $jour->libelle }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                            @endif

                            <tbody>
                                @forelse($horaires as $horaire)
                                    <tr>
                                        <td>{{ $horaire->libelle }}</td>
                                        @forelse($jours as $jour)
                                            <td class="time-slot position-relative"
                                                data-jour-id="{{ $jour->id }}"
                                                data-horaire-id="{{ $horaire->id }}"
                                                style="cursor: pointer; min-height: 80px; padding: 10px;">
                                            </td>
                                        @empty
                                            @for($i = 1; $i <= 5; $i++)
                                                <td class="time-slot position-relative"
                                                    data-jour-id="{{ $i }}"
                                                    data-horaire-id="{{ $horaire->id }}"
                                                    style="cursor: pointer; min-height: 80px; padding: 10px;">
                                                </td>
                                            @endfor
                                        @endforelse
                                    </tr>
                                @empty
                                    @for($h = 1; $h <= 5; $h++)
                                        <tr>
                                            <td>{{ $h }}H</td>
                                            @for($j = 1; $j <= 5; $j++)
                                                <td class="time-slot position-relative"
                                                    data-jour-id="{{ $j }}"
                                                    data-horaire-id="{{ $h }}"
                                                    style="cursor: pointer; min-height: 80px; padding: 10px;">
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- Modal pour l'ajout d'affectation -->
    @include('affectation_matieres.modal')

@endsection

 