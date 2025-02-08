@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Mon Emploi du Temps</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="clearfix"></div>
    <div class="card">
        <div class="card-body">
            @if(count($affectations) > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach($jours as $jour)
                            <th>{{ ucfirst($jour->libelle) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($horaires as $horaire)
                        <tr>
                            <td class="horaire bg-light text-center font-weight-bold">{{ $horaire->libelle }}</td>
                            @foreach($jours as $jour)
                            <td class="p-2">
                                @php
                                $affectation = $affectations->first(function($aff) use ($horaire, $jour) {
                                return $aff->horaire == $horaire->id && $aff->jour == $jour->id;
                                });
                                @endphp

                                @if($affectation)
                                <div class="course-block border-left border-secondary p-3 shadow-sm h-100 d-flex flex-column justify-content-between">
                                    <div class="course-header">
                                        <strong class="text-dark"> {{ $affectation->matiere->libelle }}</strong>
                                    </div>
                                    <div class="course-details">
                                        <div class="class-info d-flex flex-wrap gap-2 align-items-center">
                                            <span class="badge bg-light text-dark border">{{ $affectation->classe->libelle }}</span>
                                            @if($affectation->classe->salle)
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-door-open me-1"></i>
                                                Salle {{ $affectation->classe->salle }}
                                            </span>
                                            @endif
                                        </div>
                                        @if($affectation->debut || $affectation->fin)
                                        <div class="date-info text-muted small mt-2">
                                            @if($affectation->debut)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                Du {{ \Carbon\Carbon::parse($affectation->debut)->format('d/m/Y') }}
                                            </div>
                                            @endif
                                            @if($affectation->fin)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-check me-2"></i>
                                                Au {{ \Carbon\Carbon::parse($affectation->fin)->format('d/m/Y') }}
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    <div class="action-icons d-flex justify-content-end align-items-center mt-2">
                                        @if($affectation)
                                        <!-- Icône Suivi de cours -->
                                        <button class="btn btn-link p-0" data-toggle="modal" data-target="#suiviCoursModal" data-affectation-id="{{ $affectation->id }}" data-classe-id="{{ $affectation->classe->id }}">
                                            <i class="fas fa-clipboard-check text-secondary mx-2"></i>
                                        </button>

                                        <!-- Icône Présence -->
                                        <button class="btn btn-link p-0" data-toggle="modal" data-target="#presenceModal" data-affectation-id="{{ $affectation->id }}" data-classe-id="{{ $affectation->classe->id }}">
                                            <i class="fas fa-calendar-alt text-secondary mx-2"></i>
                                        </button>

                                        <!-- Icône Examens -->
                                        <button class="btn btn-link p-0" data-toggle="modal" data-target="#examensModal" data-affectation-id="{{ $affectation->id }}" data-classe-id="{{ $affectation->classe->id }}">
                                            <i class="fas fa-pencil-alt text-secondary mx-2"></i>
                                        </button>
                                        @else
                                        <span class="text-danger">Données incomplètes</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                Aucune affectation trouvée pour votre emploi du temps.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

@include('suivi_cours.modal')
@include('controles.modal')
@include('examens.modal')
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('[data-toggle="modal"]').on('click', function() {
            const modal = $(this.dataset.target);
            const affectationId = this.dataset.affectationId;
            const classeId = this.dataset.classeId;

            modal.find('#affectation_matiere').val(affectationId);
            console.log('Modal ID:', this.dataset.target); // Debug

            // Pour tous les modaux, vérifiez le champ caché approprié.
            // Ici, pour le modal suivi de cours, on met à jour l'input ayant l'id 'affection_matiere'
            if (this.dataset.target === '#suiviCoursModal') {
                modal.find('#affection_matiere').val(affectationId);
            } else {
                // Pour les autres modaux, si vous utilisez 'affectation_matiere' par exemple
                modal.find('#affectation_matiere').val(affectationId);
            }

            if (this.dataset.target === '#presenceModal') {
                loadEleves(classeId, '#presenceList', '#presenceModal');
            } else if (this.dataset.target === '#examensModal') {
                loadEleves(classeId, '#elevesListExamens', '#examensModal'); // Correction ici
            } else if (this.dataset.target === '#suiviCoursModal') {
                modal.find('#affection_matiere').val(affectationId);
                // Pour le modal de suivi de cours, aucun chargement de liste n'est requis.
                // Vous pouvez ajouter ici tout code spécifique si nécessaire.
            }
            console.log('Modal ID:', this.dataset.target); // Debug

        });

        function loadEleves(classeId, targetSelector, modalId) {
            // ... code de chargement des élèves ...
            $.ajax({
                url: `/eleves-par-classe/${classeId}`,
                beforeSend: function() {
                    $(targetSelector).html(`
                <tr>
                    <td colspan="2" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Chargement...</span>
                        </div>
                    </td>
                </tr>
            `);
                },
                success: function(data) {
                    console.log('Réponse du serveur:', data);
                    let html = '';
                    if (data.length > 0) {
                        html = data.map(eleve => {
                            if (modalId === '#presenceModal') {
                                return `
                                <tr>
                        <td>${eleve.nom_prenom}</td>
                        <td>
                            <input type="checkbox" 
                                   name="eleves[]" 
                                   value="${eleve.id}"
                                   class="form-check-input">
                        </td>
                    </tr>`;
                            } else if (modalId === '#examensModal') { // Bonne condition
                                return `<tr>
                        <td>${eleve.nom_prenom}</td>
                        <td>
                            <input type="number" 
                                   step="0.01" 
                                   name="notes[${eleve.id}]" 
                                   class="form-control"
                                   required>
                        </td>
                    </tr>`;
                            }
                        }).join('');

                    } else {
                        html = `<tr><td colspan="2" class="text-center">Aucun élève trouvé</td></tr>`;
                    }
                    $(targetSelector).html(html);
                },
                error: function(xhr) {
                    console.error('Erreur:', xhr.responseJSON);
                    $(targetSelector).html(`
                <tr>
                    <td colspan="2" class="text-center text-danger">
                        Erreur de chargement des données
                    </td>
                </tr>
            `);
                }
            });
        }

        function forceCloseModal(modalId) {
            // Essayer de fermer le modal avec la méthode Bootstrap
            $(modalId).modal('hide');
            // Après un court délai, retirer manuellement les classes et éléments qui empêchent la fermeture visuelle
            setTimeout(function() {
                $(modalId).removeClass('show').css('display', 'none');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }, 300);
        }


        // Remplacez l'ancien gestionnaire par ceci :
        $(document).on('submit', '#presenceForm', function(e) {
            e.preventDefault();

            const formData = {
                affectation_matiere: $(this).find('.affectation-field').val(),
                eleves: $(this).find('input[name="eleves[]"]:checked').map(function() {
                    return this.value;
                }).get()
            };

            console.log('Envoi du formulaire de présence avec les données:', formData);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Réponse succès pour presenceForm:', response);
                    // Utilisation de la fonction brute force pour fermer le modal
                    forceCloseModal('#presenceModal');
                    toastr.success('Présences enregistrées');
                },
                error: function(xhr) {
                    console.error('Erreur pour presenceForm:', xhr.responseJSON);
                    toastr.error('Erreur technique');
                }
            });
        });




        // Gestion de la soumission des examens
        $(document).on('submit', '#examensForm', function(e) {
            e.preventDefault();

            const notes = {};
            $(this).find('[name^="notes"]').each(function() {
                const eleveId = this.name.match(/\[(.*?)\]/)[1];
                notes[eleveId] = this.value;
            });

            const formData = {
                affectation_matiere: $(this).find('#affectation_matiere').val(),
                libelle: $(this).find('[name="libelle"]').val(),
                type_examen: $(this).find('[name="type_examen"]').val(),
                notes: notes
            };


            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Réponse succès pour examensForm:', response);
                    // Utilisation de la fonction brute force pour fermer le modal
                    forceCloseModal('#examensModal');
                    toastr.success('Notes enregistrées');
                },
                error: function(xhr) {
                    toastr.error('Erreur lors de l\'enregistrement');
                    console.error(xhr.responseJSON);
                }
            });
        });

        $(document).on('submit', '#suiviCoursForm', function(e) {
            e.preventDefault();

            // Sérialisation des données du formulaire
            const formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Réponse succès pour presenceForm:', response);
                    // Utilisation de la fonction brute force pour fermer le modal
                    // Ferme le modal et affiche un message de succès
                    forceCloseModal('#suiviCoursModal');
                    toastr.success(response.message || 'Suivi de cours enregistré avec succès.');
                },
                error: function(xhr) {
                    toastr.error('Erreur lors de l\'enregistrement');
                    console.error('Erreur lors de l\'enregistrement du suivi de cours:', xhr.responseJSON);
                }
            });
        });


    });
</script>
@endpush


@push('styles')
<style>
    #liste-eleves tbody tr {
        line-height: 1.2;
        /* Réduit l'espacement entre les lignes */
    }

    #liste-eleves tbody td {
        padding: 0.2rem 0.5rem;
        /* Réduit l'espacement à l'intérieur des cellules */
    }
</style>
@endpush