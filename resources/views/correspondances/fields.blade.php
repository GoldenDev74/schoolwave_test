@php
// Définition de la variable isEnseignant si elle n'existe pas
$isEnseignant = $isEnseignant ?? false;
@endphp

<form id="correspondanceForm" action="{{ route('correspondances.store') }}" method="POST">
    @csrf

    <!-- Alerte de succès -->
    <div id="formSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
        Message envoyé avec succès !
    </div>

    <div class="row">
        <!-- Cible Field -->
        <div class="form-group col-md-6">
            <label for="cible"><i class="fas fa-bullseye text-danger"></i> Cible:</label>
            {!! Form::select('cible', $profils, null, ['class' => 'form-control select2', 'required', 'id' => 'cible']) !!}
        </div>

        <!-- Classe Field -->
        <div class="form-group col-md-6" id="classeField" style="display: none;">
            <label for="classe_id"><i class="fas fa-chalkboard text-warning"></i> Classe:</label>
            @if(!$isEnseignant)
            {!! Form::select('classe_id', ['0' => 'Toutes les classes'] + $classes, null, ['class' => 'form-control select2', 'id' => 'classeParent']) !!}
            @else
            {!! Form::select('classe_id', $classes, null, ['class' => 'form-control select2', 'id' => 'classeParent']) !!}
            @endif
        </div>

        <!-- Destinataire Field -->
        <div class="form-group col-12">
            <label for="destinataire"><i class="fas fa-users text-info"></i> Destinataire:</label>
            {!! Form::text('destinataire', null, ['class' => 'form-control', 'readonly', 'maxlength' => 500]) !!}
        </div>

        <!-- Objet Field -->
        <div class="form-group col-12">
            <label for="objet"><i class="fas fa-tag text-primary"></i> Objet:</label>
            {!! Form::text('objet', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
        </div>

        <!-- Message Field -->
        <div class="form-group col-12">
            <label for="message"><i class="fas fa-comment-alt text-success"></i> Message:</label>
            {!! Form::textarea('message', null, ['class' => 'form-control', 'rows' => 4, 'required']) !!}
        </div>

        <!-- Transport Field -->
        <input type="hidden" name="transport" value="email">
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check"></i> Envoyer
        </button>
    </div>
</form>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* public/css/correspondance.css */
    .select2-container .select2-selection--single {
        height: 45px !important;
        padding: 8px 12px;
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px !important;
    }

    #formSuccessAlert {
        position: sticky;
        top: 15px;
        z-index: 100;
        margin: -1rem -1rem 1rem -1rem;
        border-radius: 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .select2-results__option[data-select2-id*="select2-classe_id-result"]:first-child {
        font-weight: bold;
        color: #2c7be5;
    }

    /* Ajoutez ce style pour différencer les sélections */
    #classeParent[disabled] {
        background-color: #f8f9fa;
        opacity: 0.7;
    }

    /* Ajoutez ceci dans la section style */
    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        //$('.select2').select2();

        // Initialisation de la variable isEnseignant depuis Blade
        let isEnseignant = @json(isset($isEnseignant) ? $isEnseignant : false);
        console.log(isEnseignant); // Devrait afficher true ou false

        function updateClasseField() {
            const selectedValue = $('#cible').val();
            const isEleve = selectedValue == '1';
            const isParent = selectedValue == '2';

            if (isEleve || isParent) {
                $('#classeField').show();
                // Correction ici : Activer le select pour Élève, désactiver pour Parent
                $('#classeParent').prop('enabled', isEleve ? false : true);
                $('#classeParent').trigger('change.select2'); // Rafraîchir Select2
            } else {
                $('#classeField').hide();
            }
        }

        // Fonction pour mettre à jour le champ destinataire via AJAX
        function updateDestinataireField() {
            // Récupérer les valeurs actuelles
            var cible = $('#cible').val();
            var transport = 'email'; // Fixé à email
            // Si le champ classe est visible, on envoie aussi sa valeur
            var classe_id = $('#classeField').is(':visible') ? $('[name="classe_id"]').val() : 0;

            $.ajax({
                url: '/get-recipients',
                type: 'GET',
                data: {
                    cible: cible,
                    transport: transport,
                    classe_id: classe_id
                },
                // Dans updateDestinataireField()
                success: function(response) {
                    const maxLength = 500;
                    const fullList = response.destinataires;
                    const displayText = fullList.length > maxLength ?
                        fullList.substring(0, maxLength) + '...' :
                        fullList;

                    $('[name="destinataire"]').val(displayText);

                    // Stockez la liste complète dans un champ caché si nécessaire
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'destinataires_complet',
                        value: fullList
                    }).appendTo('form');
                }
            });
        }


        function filterCible() {
            let cibleSelect = $('#cible');
            cibleSelect.find('option').show(); // Réinitialisation
            if (isEnseignant) {
                cibleSelect.find('option').each(function() {
                    let optionId = $(this).val();
                    // Pour les enseignants, on garde uniquement "Élève" (id = 1) et "Parent" (id = 2)
                    if (optionId !== '1' && optionId !== '2') {
                        $(this).hide();
                    }
                });
            }
            cibleSelect.trigger('change');
        }

        // Lors de l'ouverture de la modal, mise à jour du select de classe via AJAX
        $('#nouveauCorrespondanceBtn').on('click', function() {
            $.ajax({
                url: '/user-classes',
                type: 'GET',
                success: function(response) {
                    let classeSelect = $('[name="classe_id"]');
                    classeSelect.empty();
                    // Si l'utilisateur n'est pas enseignant, ajouter l'option "Toutes les classes"
                    if (!response.is_enseignant) {
                        classeSelect.append(new Option("Toutes les classes", 0));
                    }
                    // Ajout des classes récupérées
                    $.each(response.classes, function(id, libelle) {
                        classeSelect.append(new Option(libelle, id));
                    });
                    // On affiche le champ si la cible est "Élève"
                    updateClasseField();
                    updateDestinataireField();
                }
            });
        });

        // Mise à jour dynamique du champ classe quand la cible change
        $('#cible').on('change', function() {
            updateClasseField();
            updateDestinataireField();
        });

        // Déclencher updateDestinataireField lorsque la classe change
        $('[name="classe_id"]').on('change', function() {
            updateDestinataireField();
        });

        // Initialisation au chargement de la page
        updateClasseField();
        filterCible();
        updateDestinataireField();
    });
</script>
@endpush