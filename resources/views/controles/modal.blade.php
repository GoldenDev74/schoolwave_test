<!-- resources/views/controles/modal.blade.php -->
<div class="modal fade" id="presenceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['id' => 'presenceForm', 'route' => 'controles.store']) !!}
            <div class="modal-header">
                <h5 class="modal-title">Gestion des Présences</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- resources/views/controles/modal.blade.php -->
                {!! Form::hidden('affectation_matiere', null, [
                'id' => 'affectation_matiere',
                'class' => 'affectation-field' // Ajoutez une classe pour le ciblage
                ]) !!}
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Présent</th>
                        </tr>
                    </thead>
                    <tbody id="presenceList">
                        <!-- Les élèves seront chargés ici -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>