<div class="modal fade" id="ajoutAffectationModal" tabindex="-1" role="dialog" aria-labelledby="ajoutAffectationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajoutAffectationModalLabel">Ajouter une Affectation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajoutAffectationForm">
                    <input type="hidden" id="modal-jour" name="jour" value="">
                    <input type="hidden" id="modal-horaire" name="horaire" value="">
                    <input type="hidden" name="mode_affection" value="1">

                    <div class="row">
                        <!-- Colonne gauche -->
                        <div class="col-md-6">
                            <div class="form-group" id="modal-enseignant-group">
                                <label for="modal-enseignant">Enseignant</label>
                                <select class="form-control" id="modal-enseignant" name="enseignant">
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

                            <div class="form-group">
                                <label for="modal-matiere">Matière</label>
                                <select class="form-control" id="modal-matiere" name="matiere">
                                    <option value="">Sélectionner une matière</option>
                                    @forelse($matieres as $id => $libelle)
                                        <option value="{{ $id }}">{{ $libelle }}</option>
                                    @empty
                                        <!-- Pas de matières disponibles -->
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <!-- Colonne droite -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-classe">Classe</label>
                                <select class="form-control" id="modal-classe" name="classe">
                                    <option value="">Sélectionner une classe</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe['id'] }}" data-type-cours="{{ $classe['type_cours'] }}">
                                            {{ $classe['libelle'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="modal-type-cours">Type de Cours</label>
                                <select class="form-control" id="modal-type-cours" name="type_cours">
                                    <option value="">Sélectionner un type de cours</option>
                                    @foreach($typeCourss as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['libelle'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Ligne des modes d'affectation -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="d-block">Mode d'affectation :</label>
                                @foreach($modesAffectation as $mode)
                                    <div class="form-check form-check-inline">
                                        <input type="radio"
                                               id="modal-mode_{{ $mode->id }}"
                                               name="mode_affection"
                                               class="form-check-input"
                                               value="{{ $mode->id }}"
                                               {{ $mode->id == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="modal-mode_{{ $mode->id }}">{{ $mode->libelle }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Dates (toujours cachées par défaut) -->
                    <div id="modal-dates_container" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modal-debut">Date de début</label>
                                    <input type="text" class="form-control datepicker" id="modal-debut" name="debut" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modal-fin">Date de fin</label>
                                    <input type="text" class="form-control datepicker" id="modal-fin" name="fin" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="enregistrerAffectation">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
<script>
$(document).ready(function() {
    moment.locale('fr');

    let selectedCell = null;
    let isCreationMode = false;
    let currentAffectationId = null;
    let isLoading = false;
    let isTableDisplayed = false;

    // Fonction pour afficher une alerte
    function showAlert(message, type = 'success') {
        const alertContainer = $('.alert-container');
        alertContainer.empty();
        const alertDiv = $('<div>').addClass(`alert alert-${type}`).text(message);

        alertContainer.append(alertDiv);
        setTimeout(() => alertDiv.fadeOut(500, () => $(this).remove()), 5000);
    }

    // Fonction pour réinitialiser le modal
    function resetModal() {
        // Supprimer les champs cachés
        $('#hidden-enseignant, #hidden-type-cours, #hidden-classe, input[name="affectation_id"]').remove();

        // Réinitialiser et afficher tous les champs
        $('#modal-enseignant-group, #modal-type-cours, #modal-classe').closest('.form-group').show();
        $('#ajoutAffectationForm')[0].reset();

        // Réinitialiser les options des selects
        $('#modal-classe option, #modal-enseignant option').show();

        // Réinitialiser les champs de date et les cacher
        $('#modal-debut, #modal-fin').val('').prop('required', false);
        $('#modal-dates_container').hide();

        // Réinitialiser le bouton radio sur "Static"
        $('input[name="mode_affection"][value="1"]').prop('checked', true);

        // Fermer le modal
        $('#ajoutAffectationModal').modal('hide');
    }

    // Fonction pour charger l'emploi du temps
    function loadEmploiDuTemps(type, id) {
        if (isLoading || isTableDisplayed) return;
        isLoading = true;

        $.ajax({
            url: '/affectationMatieres/emploiDuTemps',
            method: 'GET',
            data: { type: type, id: id },
            success: function(response) {
                isLoading = false;
                isTableDisplayed = true;

                // Afficher le tableau
                $('#emploi-du-temps-container').show();
                $('#no-selection-message').hide();

                // Vider le tableau existant
                $('table tbody tr').remove();

                // Reconstruire le tableau avec les nouvelles données
                const $tbody = $('table tbody');
                const jourIds = [@foreach($jours as $jour)'{{ $jour->id }}'@if(!$loop->last),@endif @endforeach];

                if (response.horaires.length === 0) {
                    const $noDataRow = $('<tr>').append(
                        $('<td>').attr('colspan', jourIds.length + 1)
                                 .text('Aucun horaire disponible pour ce type de cours')
                                 .addClass('text-center text-muted')
                    );
                    $tbody.append($noDataRow);
                } else {
                    response.horaires.forEach(function(horaire) {
                        const $row = $('<tr>');
                        $row.append($('<td>').text(horaire.libelle));

                        jourIds.forEach(function(jourId) {
                            const $timeSlotCell = $('<td>')
                                .addClass('time-slot')
                                .attr('data-jour-id', jourId)
                                .attr('data-horaire-id', horaire.id);
                            $row.append($timeSlotCell);
                        });

                        $tbody.append($row);
                    });
                }

                // Remplir les affectations
                response.affectations.forEach(function(affectation) {
                    const jourId = affectation.jour?.id || affectation.jour;
                    const horaireId = affectation.horaire?.id || affectation.horaire;
                    const selector = `.time-slot[data-jour-id="${jourId}"][data-horaire-id="${horaireId}"]`;
                    const $cell = $(selector);

                    if ($cell.length) {
                        const matiereId = affectation.matiere?.id || affectation.matiere;
                        const enseignantId = affectation.enseignant?.id || affectation.enseignant;
                        const classeId = affectation.classe?.id || affectation.classe;

                        const matiereName = affectation.matiere?.libelle ||  'Matière';
                        const enseignantName = affectation.enseignant?.nom_prenom || 'Enseignant';
                        const classeName = affectation.classe?.libelle || 'Classe';

                        const currentType = $('#classe').val() ? 'classe' : 'enseignant';

                        const deleteButton = $(`
                        <button class="btn btn-sm btn-danger delete-affectation-btn position-absolute bg-transparent border-0 text-red"
                                style="top: 5px; right: 5px; z-index: 10;"
                                data-affectation-id="${affectation.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        `);

                        const editButton = $(`
                        <button class="btn btn-sm btn-primary edit-btn position-absolute bg-transparent border-0 text-orange"
                                style="top: 5px; right: 35px; z-index: 10;"
                                data-affectation-id="${affectation.id}">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        `);

                        let cellContent = `
                            <div class="affectation-details">
                                <strong>${matiereName}</strong><br>
                        `;

                        if (currentType === 'enseignant') {
                            cellContent += `${classeName}<br>`;
                        } else {
                            cellContent += `${enseignantName}<br>`;
                        }

                        const isDynamicMode = affectation.mode_affection == 2;
                        const dateDebut = isDynamicMode ? affectation.debut : null;
                        const dateFin = isDynamicMode ? affectation.fin : null;

                        const today = moment();
                        const isDateFinPassed = dateFin ? moment(dateFin).isBefore(today) : false;

                        if (isDynamicMode) {
                            cellContent += `
                            <small>
                                <strong>Début:</strong> ${moment(dateDebut).format('DD/MM/YYYY')}
                                <br>
                                <strong>Fin:</strong>
                                <span class="${isDateFinPassed ? 'date-expired' : 'date-valid'}">
                                    ${moment(dateFin).format('DD/MM/YYYY')}
                                </span>
                            </small>
                            `;
                        }

                        cellContent += `</div>`;

                        $cell.html(cellContent).append(editButton).append(deleteButton);
                        $cell.addClass('has-affectation position-relative')
                             .attr('data-matiere-id', matiereId)
                             .attr('data-enseignant-id', enseignantId)
                             .attr('data-classe-id', classeId);

                        deleteButton.click(function(e) {
                            e.stopPropagation();
                            const affectationId = $(this).data('affectation-id');
                            if (confirm('Voulez-vous vraiment supprimer cette affectation ?')) {
                                deleteAffectation(affectationId);
                            }
                        });

                        editButton.click(function(e) {
                            e.stopPropagation();
                            const affectationId = $(this).data('affectation-id');
                            editAffectation(affectationId);
                        });
                    }
                });

                // Ajouter des boutons "+" dans les cellules vides
                $('.time-slot:not(.has-affectation)').each(function() {
                    $(this).append(`
                        <div class="hover-container d-flex justify-content-center align-items-center" style="width: 100%; height: 100%;">
                            <button class="btn btn-sm btn-success add-affectation-btn bg-transparent border-0 text-green hover-show"
                                    data-jour-id="${$(this).data('jour-id')}"
                                    data-horaire-id="${$(this).data('horaire-id')}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    `);
                });
            },
            error: function(xhr) {
                isLoading = false;
                console.error('Erreur AJAX:', xhr);
                showAlert('Erreur de chargement de l\'emploi du temps', 'error');
            }
        });
    }

    // Fonction pour supprimer une affectation
    function deleteAffectation(id) {
        $.ajax({
            url: `/affectationMatieres/annuler/${id}`,
            type: 'POST', // Utiliser POST pour la mise à jour
            data: {
                _token: '{{ csrf_token() }}',
                annulation: true // Envoyer la valeur à mettre à jour
            },
            success: function(response) {
                reloadCurrentSchedule();
                showAlert('Affectation annulée avec succès', 'success');
                resetModal(); // Réinitialiser le modal
            },
            error: function(xhr) {
                console.error('Erreur lors de l\'annulation:', xhr);
                showAlert(xhr.responseJSON?.message || 'Erreur lors de l\'annulation', 'error');
            }
        });
    }

    // Gestionnaire de clic pour les boutons de suppression
    $(document).off('click', '.delete-affectation-btn').on('click', '.delete-affectation-btn', function(e) {
        e.stopPropagation();
        const affectationId = $(this).data('affectation-id');

        if (confirm('Voulez-vous vraiment supprimer cette affectation ?')) {
            deleteAffectation(affectationId);
        }
    });

    // Fonction pour recharger l'emploi du temps selon le contexte actuel
    function reloadCurrentSchedule() {
        // Réinitialiser les variables de contrôle
        isTableDisplayed = false;
        isLoading = false;

        const enseignantId = $('#enseignant').val();
        const classeId = $('#classe').val();

        if (enseignantId) {
            loadEmploiDuTemps('enseignant', enseignantId);
        } else if (classeId) {
            loadEmploiDuTemps('classe', classeId);
        } else {
            // Si aucune sélection, cacher le tableau
            $('#emploi-du-temps-container').hide();
            $('#no-selection-message').show();
        }
    }

    $('#enregistrerAffectation').off('click').on('click', function() {
        const $submitButton = $(this);
        $submitButton.prop('disabled', true);

        // Vérifier les champs requis
        const requiredFields = {
            'matiere': 'Matière',
            'mode_affection': 'Mode d\'affectation'
        };

        const formData = new FormData($('#ajoutAffectationForm')[0]);
        let missingFields = [];

        // Vérifier chaque champ requis
        Object.entries(requiredFields).forEach(([field, label]) => {
            if (!formData.get(field)) {
                missingFields.push(label);
            }
        });

        // Vérifier les dates si mode dynamique
        if (formData.get('mode_affection') === '2') {
            if (!formData.get('debut')) missingFields.push('Date de début');
            if (!formData.get('fin')) missingFields.push('Date de fin');
        }

        // Si des champs sont manquants, afficher l'erreur
        if (missingFields.length > 0) {
            showAlert('Veuillez remplir tous les champs obligatoires : ' + missingFields.join(', '), 'error');
            $submitButton.prop('disabled', false);
            return;
        }

        // Récupérer l'ID de l'affectation si c'est une modification
        const affectationId = formData.get('affectation_id');

        // Construire l'URL en fonction du type d'opération
        const url = affectationId
            ? `/affectationMatieres/${affectationId}`
            : '/affectationMatieres';

        // S'assurer que le token CSRF est présent
        const token = $('meta[name="csrf-token"]').attr('content');
        if (!token) {
            showAlert('Erreur de sécurité : token CSRF manquant', 'error');
            $submitButton.prop('disabled', false);
            return;
        }
        formData.append('_token', token);

        // Si c'est une modification, ajouter la méthode PUT
        if (affectationId) {
            formData.append('_method', 'PUT');
        }

        // Log des données pour le debug
        console.log('Soumission du formulaire:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        $.ajax({
    url: url,
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    headers: {
        'X-CSRF-TOKEN': token
    },
    success: function(response) {
        console.log('Réponse succès:', response);
        if (response.success) {
            // Recharger l'emploi du temps avec un petit délai
            setTimeout(function() {
                reloadCurrentSchedule();
            }, 100);

            // Afficher le message de succès
            const message = affectationId
                ? 'Affectation modifiée avec succès'
                : 'Affectation créée avec succès';
            showAlert(message, 'success');
            resetModal(); // Réinitialiser le modal
        } else {
            console.error('Erreur dans la réponse:', response);
            if (response.alert) {
                // Fermer le modal avant d'afficher l'alerte
                $('#ajoutAffectationModal').modal('hide');
                // Afficher une boîte de dialogue avec le message d'alerte dans le modal
                alert(response.message);
                // Actualiser la page
                location.reload();
            } else {
                // Fermer le modal avant d'afficher le message d'erreur
                $('#ajoutAffectationModal').modal('hide');
                showAlert(response.message || 'Une erreur est survenue lors du traitement', 'error');
            }
        }
    },
    error: function(xhr, status, error) {
        console.error('Erreur AJAX:', {xhr, status, error});
        let errorMessage = 'Une erreur est survenue lors de la communication avec le serveur';

        if (xhr.responseJSON) {
            console.log('Réponse JSON d\'erreur:', xhr.responseJSON);
            if (xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
            }
        }

        // Fermer le modal avant d'afficher le message d'erreur
        $('#ajoutAffectationModal').modal('hide');
        showAlert(errorMessage, 'error');
    },
    complete: function() {
        $submitButton.prop('disabled', false);
    }
});

    });

    // Fonction pour gérer l'affichage du formulaire selon le contexte
   function setupModalFields(data, context) {
    // Réinitialiser le formulaire et les champs cachés
    $('#ajoutAffectationForm')[0].reset();
    $('#hidden-enseignant, #hidden-type-cours, #hidden-classe').remove();

    // Réinitialiser et afficher tous les champs
    $('#modal-classe, #modal-enseignant, #modal-type-cours, #modal-matiere')
        .val('')
        .prop('disabled', false)
        .prop('readonly', false)
        .removeClass('disabled-select');

    $('#modal-enseignant-group, #modal-type-cours, #modal-classe').closest('.form-group').show();
    $('#modal-classe option, #modal-enseignant option').show();

    // Pré-remplir les champs avec les données existantes
    if (data) {
    $('#modal-matiere').val(data.matiere?.id || data.matiere);

    const enseignantId = data.enseignant?.id || data.enseignant;
    const classeId = data.classe?.id || data.classe;
    const typeCours = data.type_cours?.id || data.type_cours;

    // Pré-remplir le jour et l'horaire
    $('#modal-jour').val(data.jour?.id || data.jour);
    $('#modal-horaire').val(data.horaire?.id || data.horaire);

    // Appliquer les valeurs
    $('#modal-enseignant').val(enseignantId);
    $('#modal-classe').val(classeId);
    $('#modal-type-cours').val(typeCours);

    // Mode dynamique/statique
    $(`input[name="mode_affection"][value="${data.mode_affection}"]`).prop('checked', true);
    if (data.mode_affection == 2) {
        $('#modal-dates_container').show();
        $('#modal-debut, #modal-fin').prop('required', true);

        // Utiliser moment.js pour formater les dates
        // Assurez-vous que moment.js est bien inclus dans votre projet
        const debutFormatted = moment(data.debut).format('YYYY-MM-DD');
        const finFormatted = moment(data.fin).format('YYYY-MM-DD');

        $('#modal-debut').val(debutFormatted);
        $('#modal-fin').val(finFormatted);
    }
}


    // Gérer l'affichage selon le contexte
    if (context === 'enseignant') {
        const activeEnseignantId = $('#enseignant').val();
        const enseignantOption = $(`#enseignant option[value="${activeEnseignantId}"]`);
        const enseignantTypeCours = enseignantOption.data('type-cours');

        // Masquer et pré-remplir l'enseignant
        $('#modal-enseignant-group').hide();
        $('#modal-enseignant').val(activeEnseignantId);

        // Créer le champ caché pour l'enseignant
        $('<input>')
            .attr({
                type: 'hidden',
                id: 'hidden-enseignant',
                name: 'enseignant'
            })
            .val(activeEnseignantId)
            .appendTo('#ajoutAffectationForm');

        if (enseignantTypeCours) {
            // Masquer et pré-remplir le type de cours
            $('#modal-type-cours')
                .val(enseignantTypeCours)
                .closest('.form-group')
                .hide();

            // Créer le champ caché pour le type de cours
            $('<input>')
                .attr({
                    type: 'hidden',
                    id: 'hidden-type-cours',
                    name: 'type_cours'
                })
                .val(enseignantTypeCours)
                .appendTo('#ajoutAffectationForm');

            // Filtrer les classes par type de cours
            $('#modal-classe option').each(function() {
                const classeTypeCours = $(this).data('type-cours');
                if (classeTypeCours != enseignantTypeCours) {
                    $(this).hide();
                }
            });
        }
    } else if (context === 'classe') {
        const activeClasseId = $('#classe').val();
        const classeOption = $(`#classe option[value="${activeClasseId}"]`);
        const classeTypeCours = classeOption.data('type-cours');

        // Masquer et pré-remplir la classe
        $('#modal-classe')
            .val(activeClasseId)
            .closest('.form-group')
            .hide();

        // Créer le champ caché pour la classe
        $('<input>')
            .attr({
                type: 'hidden',
                id: 'hidden-classe',
                name: 'classe'
            })
            .val(activeClasseId)
            .appendTo('#ajoutAffectationForm');

        if (classeTypeCours) {
            // Masquer et pré-remplir le type de cours
            $('#modal-type-cours')
                .val(classeTypeCours)
                .closest('.form-group')
                .hide();

            // Créer le champ caché pour le type de cours
            $('<input>')
                .attr({
                    type: 'hidden',
                    id: 'hidden-type-cours',
                    name: 'type_cours'
                })
                .val(classeTypeCours)
                .appendTo('#ajoutAffectationForm');

            // Filtrer les enseignants par type de cours
            $('#modal-enseignant option').each(function() {
                const enseignantTypeCours = $(this).data('type-cours');
                if (enseignantTypeCours != classeTypeCours) {
                    $(this).hide();
                }
            });
        }
    }
}


    // Fonction pour éditer une affectation (modifiée)
    function editAffectation(affectationId) {
        $.ajax({
            url: `/affectationMatieres/details/${affectationId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    // Déterminer le contexte actuel (classe ou enseignant)
                    const context = $('#enseignant').val() ? 'enseignant' : 'classe';
                    setupModalFields(response.data, context);

                    // Ajouter l'ID de l'affectation pour la mise à jour
                    $('#ajoutAffectationForm').append(
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'affectation_id',
                            value: affectationId
                        })
                    );

                    $('#ajoutAffectationModal').modal('show');
                }
            },
            error: function(xhr) {
                console.error('Erreur lors de la récupération des détails:', xhr);
                showAlert('Erreur lors de la récupération des détails de l\'affectation', 'error');
            }
        });
    }

    // Gestionnaire pour le bouton d'édition
    $(document).off('click', '.edit-affectation-btn').on('click', '.edit-affectation-btn', function(e) {
        e.stopPropagation();
        const affectationId = $(this).data('affectation-id');

        $.ajax({
            url: `/affectationMatieres/details/${affectationId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const context = $('#enseignant').val() ? 'enseignant' : 'classe';
                    setupModalFields(response.data, context);

                    // Ajouter l'ID de l'affectation pour la mise à jour
                    $('#ajoutAffectationForm').append(
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'affectation_id',
                            value: affectationId
                        })
                    );

                    $('#ajoutAffectationModal').modal('show');
                }
            },
            error: function(xhr) {
                console.error('Erreur lors de la récupération des détails:', xhr);
                showAlert('Erreur lors de la récupération des détails de l\'affectation', 'error');
            }
        });
    });

    // Gestionnaire pour le bouton d'ajout d'affectation
    $(document).off('click', '.add-affectation-btn').on('click', '.add-affectation-btn', function() {
        const jourId = $(this).data('jour-id');
        const horaireId = $(this).data('horaire-id');

        // Pré-remplir le jour et l'horaire
        $('#modal-jour').val(jourId);
        $('#modal-horaire').val(horaireId);

        // Déterminer le contexte actuel
        const context = $('#enseignant').val() ? 'enseignant' : 'classe';

        // Configurer les champs du modal
        setupModalFields(null, context);

        $('#ajoutAffectationModal').modal('show');
    });

    // Réinitialisation du modal lors de la fermeture
    $('#ajoutAffectationModal').on('hidden.bs.modal', function() {
        resetModal(); // Réinitialiser le modal
    });

    // Gestionnaire pour le bouton annuler
    $(document).off('click', '.btn-secondary[data-bs-dismiss="modal"]').on('click', '.btn-secondary[data-bs-dismiss="modal"]', function() {
        resetModal(); // Réinitialiser le modal
    });

    // Gestionnaire pour la croix en haut
    $(document).off('click', '.btn-close[data-bs-dismiss="modal"]').on('click', '.btn-close[data-bs-dismiss="modal"]', function() {
        resetModal(); // Réinitialiser le modal
    });

    // Configuration des datepickers
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        language: 'fr',
        startDate: new Date()
    });

    // Gestionnaire pour le mode d'affectation
    $('input[name="mode_affection"]').off('change').on('change', function() {
        const datesContainer = $('#modal-dates_container');
        if ($(this).val() == '2') { // Mode dynamique
            datesContainer.slideDown();
            $('#modal-debut, #modal-fin').prop('required', true);
        } else {
            datesContainer.slideUp();
            $('#modal-debut, #modal-fin').prop('required', false).val('');
        }
    });

    // Validation des dates
    $('#modal-fin').on('change', function() {
        const dateDebut = moment($('#modal-debut').val(), 'DD/MM/YYYY');
        const dateFin = moment($(this).val(), 'DD/MM/YYYY');

        if (dateDebut.isValid() && dateFin.isValid() && dateFin.isBefore(dateDebut)) {
            showAlert('La date de fin doit être postérieure à la date de début', 'error');
            $(this).val('');
        }
    });

    $('#modal-debut').on('change', function() {
        const dateDebut = moment($(this).val(), 'DD/MM/YYYY');
        const dateFin = moment($('#modal-fin').val(), 'DD/MM/YYYY');

        if (dateDebut.isValid() && dateFin.isValid() && dateFin.isBefore(dateDebut)) {
            showAlert('La date de début doit être antérieure à la date de fin', 'error');
            $(this).val('');
        }
    });

    // Fonction pour initialiser les gestionnaires de sélection
    function initializeSelectionHandlers() {
        // Gestionnaire pour le changement de classe
        $('#classe').off('change').on('change', function() {
            const classeId = $(this).val();

            // Réinitialiser la sélection de l'enseignant
            $('#enseignant').val('');

            // Réinitialiser l'affichage
            $('.time-slot').empty();

            if (classeId) {
                // Réinitialiser les variables de contrôle
                isTableDisplayed = false;
                isLoading = false;

                // Charger l'emploi du temps pour la classe sélectionnée
                loadEmploiDuTemps('classe', classeId);
            } else {
                // Cacher le tableau si aucune sélection
                $('#emploi-du-temps-container').hide();
                $('#no-selection-message').show();
            }
        });

        // Gestionnaire pour le changement d'enseignant
        $('#enseignant').off('change').on('change', function() {
            const enseignantId = $(this).val();

            // Réinitialiser la sélection de la classe
            $('#classe').val('');

            // Réinitialiser l'affichage
            $('.time-slot').empty();

            if (enseignantId) {
                // Réinitialiser les variables de contrôle
                isTableDisplayed = false;
                isLoading = false;

                // Charger l'emploi du temps pour l'enseignant sélectionné
                loadEmploiDuTemps('enseignant', enseignantId);
            } else {
                // Cacher le tableau si aucune sélection
                $('#emploi-du-temps-container').hide();
                $('#no-selection-message').show();
            }
        });
    }

    // Initialiser les gestionnaires au chargement de la page
    initializeSelectionHandlers()
});
</script>
@endpush
