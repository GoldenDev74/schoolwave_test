<!-- resources/views/controles/modal.blade.php -->
<div class="modal fade" id="examensModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'examens.store', 'id' => 'examensForm']) !!}
            <div class="modal-header">
            <h5 class="modal-title">Gestion des Examens</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Libelle Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('libelle', 'Libelle:') !!}
                        {!! Form::text('libelle', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
                    </div>

                    <!-- Type Examen Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('type_examen', 'Type Examen:') !!}
                        {!! Form::select('type_examen',$typeExamens, null, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <!-- Champ caché pour l'affectation_matiere -->
                {!! Form::hidden('affectation_matiere', null, [
                'id' => 'affectation_matiere',
                'class' => 'affectation-field' // Ajoutez une classe pour le ciblage
                ]) !!}
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Élève</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <!-- Modification du tableau -->
                        <tbody id="elevesListExamens">
                            <!-- Le JS peuplera dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>