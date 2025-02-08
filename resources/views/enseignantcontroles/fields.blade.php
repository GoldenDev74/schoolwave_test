<!-- Enseignant -->
<!--<div class="form-group col-sm-6">
    {!! Form::label('enseignant', 'Enseignant:') !!}
    {!! Form::select('enseignant', $enseignants, null, ['class' => 'form-control', 'id' => 'enseignant-select', 'placeholder' => 'Choisissez un enseignant', 'required']) !!}
</div>-->
<!-- Enseignant -->
<div class="form-group col-sm-6">
    {!! Form::label('enseignant', 'Enseignant:') !!}
    {!! Form::select('enseignant', [], null, ['class' => 'form-control', 'id' => 'enseignant-select', 'placeholder' => 'Sélectionnez un enseignant', 'required']) !!}
</div>

<!-- Classe -->
<div class="form-group col-sm-6">
    {!! Form::label('classe', 'Classe:') !!}
    {!! Form::select('classe', $classes, null, ['class' => 'form-control', 'id' => 'classe-select', 'placeholder' => 'Sélectionnez une classe', 'required']) !!}
</div>

<!-- Bouton Valider -->
<div class="form-group col-sm-6">
    <button type="button" id="valider-btn" class="btn btn-primary">Valider</button>
</div>


<!-- Élèves -->
<div class="form-group col-sm-12">
    {!! Form::label('eleves', 'Présence des élèves:') !!}
    <div id="eleves-container"></div>
</div>

<div class="card-footer">
    {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('controles.index') }}" class="btn btn-default"> Annuler </a>
</div>


@push('page_scripts')

<script>
    $(document).ready(function() {
        // Quand une classe est sélectionnée
        $('#classe-select').on('change', function () {
            const classeId = $(this).val();
            console.log('Classe sélectionnée :', classeId); // Debugging

            if (classeId) {
                const url = "{{ route('controles.enseignantsByClasse', ':id') }}".replace(':id', classeId);
                $.get(url, function (data) {
                    console.log('Enseignants reçus :', data); // Debugging
                    let options = '<option value="">Sélectionnez un enseignant</option>';
                    data.forEach(enseignant => {
                        options += `<option value="${enseignant.id}">${enseignant.nom_prenom}</option>`;
                    });
                    $('#enseignant-select').html(options).trigger('change');
                }).fail(function () {
                    console.error('Erreur lors de la récupération des enseignants.');
                });
            } else {
                $('#enseignant-select').html('<option value="">Sélectionnez un enseignant</option>');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Gestion du clic sur le bouton "Valider"
        $('#valider-btn').on('click', function() {
            const classeId = $('#classe-select').val();
            const enseignantId = $('#enseignant-select').val();

            // Vérifier si un enseignant est sélectionné
            if (!enseignantId) {
                alert('Veuillez sélectionner un enseignant avant de valider.');
                return; // Stopper l'exécution si aucun enseignant sélectionné
            }

            // Vérifier si une classe est sélectionnée
            if (!classeId) {
                alert('Veuillez sélectionner une classe avant de valider.');
                return; // Stopper l'exécution si aucune classe sélectionnée
            }

            // Requête AJAX pour récupérer les élèves de la classe
            const url = "{{ route('controles.elevesByClasse', ':id') }}".replace(':id', classeId);

            $.get(url, function(data) {
                console.log('Élèves reçus :', data); // Debugging

                // Afficher les élèves dans le conteneur
                let elevesHtml = '<ul>';
                data.forEach(eleve => {
                    elevesHtml += `
                        <li>
                            <label>
                                <input type="checkbox" name="eleves[]" value="${eleve.id}">
                                ${eleve.nom_prenom}
                            </label>
                        </li>
                    `;
                });
                elevesHtml += '</ul>';
                $('#eleves-container').html(elevesHtml);

            }).fail(function() {
                console.error('Erreur lors de la récupération des élèves.');
                alert('Erreur lors du chargement des élèves. Veuillez réessayer.');
            });
        });
    });
</script>

@endpush


