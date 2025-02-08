@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
<<<<<<< HEAD
                    <h1>Mes Eleves</h1>
                </div>
             </div>
=======
                    <h1>Eleves</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('eleves.create') }}">
                        Nouveau
                    </a>
                </div>
            </div>
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
<<<<<<< HEAD
                

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($eleves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom et Prénom</th>
                                        <th>Date de naissance</th>
                                        <th>Lieu de naissance</th>
                                        <th>Nationalité</th>
                                        <th>Pays de résidence</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Sexe</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eleves as $eleve)
                                        <tr>
                                            <td>{{ $eleve->nom_prenom }}</td>
                                            <td>{{ $eleve->date_naissance ? $eleve->date_naissance->format('d/m/Y') : '' }}</td>
                                            <td>{{ $eleve->lieu_naissance }}</td>
                                            <td>{{ $eleve->nationalites ? $eleve->nationalites->libelle : 'Non spécifiée' }}</td>
                                            <td>{{ $eleve->paysResidence ? $eleve->paysResidence->libelle : 'Non spécifié' }}</td>
                                            <td>{{ $eleve->email }}</td>
                                            <td>{{ $eleve->telephone }}</td>
                                            <td>{{ $eleve->sexes ? $eleve->sexes->libelle : 'Non spécifié' }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" onclick="showEmploiTemps({{ $eleve->id }}, '{{ $eleve->nom_prenom }}')">
                                                    <i class="fas fa-calendar-alt"></i> Emploi du temps
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Modal pour l'emploi du temps -->
                        <div class="modal fade" id="emploiTempsModal" tabindex="-1" aria-labelledby="emploiTempsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="emploiTempsModalLabel">Emploi du temps</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="modalContent">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <p>Vous n'avez aucun élève enregistré.</p>
                    @endif
                </div>
            </div>
    </div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fr.js"></script>
<script>
    function showEmploiTemps(eleveId, nomPrenom) {
        // Mettre à jour le titre du modal
        $('#emploiTempsModalLabel').text(`Emploi du temps - ${nomPrenom}`);

        // Afficher le modal
        $('#emploiTempsModal').modal('show');

        

        // Charger les données avec AJAX
        $.ajax({
            url: `/meseleves/${eleveId}/emploi-du-temps`,
            method: 'GET',
            success: function(response) {
                if (response.error) {
                    throw new Error(response.error);
                }

                const emploiTemps = response;

                // Créer le tableau des affectations
                let html = '<table class="table table-bordered">';
                html += '<thead><tr><th></th>';
                emploiTemps.jours.forEach(jour => {
                    html += `<th>${jour.libelle}</th>`;
                });
                html += '</tr></thead><tbody>';

                emploiTemps.horaires.forEach(horaire => {
                    html += `<tr><td>${horaire.libelle}</td>`;
                    emploiTemps.jours.forEach(jour => {
                        const affectation = emploiTemps.affectations.find(aff => aff.horaire.id === horaire.id && aff.jour.id === jour.id);
                        if (affectation) {
                            html += `<td><i class="nav-icon fas fa-book-open"></i> ${affectation.matiere.libelle}<br> Mr ${affectation.enseignant.nom_prenom}`;
                            if (affectation.debut && affectation.fin) {
                                html += `<br><small>Début: ${moment(affectation.debut).format('DD/MM/YYYY')}<br>Fin: ${moment(affectation.fin).format('DD/MM/YYYY')}</small>`;
                            }
                            html += '</td>';
                        } else {
                            html += '<td></td>';
                        }
                    });
                    html += '</tr>';
                });

                html += '</tbody></table>';

                // Mettre à jour le contenu du modal
                $('#modalContent').html(html);
            },
            error: function(xhr, status, error) {
                console.error('Erreur:', error);
                $('#modalContent').html(`
                    <div class="alert alert-danger">
                        Une erreur est survenue lors du chargement de l'emploi du temps.
                    </div>
                `);
            }
        });
    }
</script>
@endpush



=======
            @include('eleves.table')
        </div>
    </div>

@endsection
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
