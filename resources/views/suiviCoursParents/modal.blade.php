<div class="modal fade" id="suiviCoursModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suivi de Cours</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'suivi_cours.store', 'id' => 'suiviCoursForm']) !!}
                <!-- Utilisez le nom 'affection_matiere' de manière cohérente -->
                {!! Form::hidden('affection_matiere', null, ['id' => 'affection_matiere']) !!}
                
                <div class="row">
                    @include('suivi_cours.fields')
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="submit" form="suiviCoursForm" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
