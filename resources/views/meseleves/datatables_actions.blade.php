<div class='btn-group'>
    <!-- Bouton Détails -->
    <button class="btn btn-primary btn-sm" 
            onclick="showDetails({{ $id }}, '{{ addslashes($nom_prenom) }}')"
            data-bs-toggle="modal" 
            data-bs-target="#detailsModal">
        <i class="fas fa-eye"></i>
    </button>

    <!-- Bouton Suppression avec Modal -->
    <button type="button" class="btn btn-danger btn-xs" 
            data-bs-toggle="modal" 
            data-bs-target="#deleteModal{{ $id }}">
        <i class="fa fa-trash"></i>
    </button>
</div>

<!-- Modal de Suppression -->
<div class="modal fade" id="deleteModal{{ $id }}" data-bs-backdrop="static" 
     data-bs-keyboard="false" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog">
        {!! Form::open(['route' => ['meseleves.destroy', $id], 'method' => 'delete']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $id }}">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cet élève ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                {!! Form::button('<i class="fa fa-trash"></i> Supprimer', [
                    'type' => 'submit',
                    'class' => 'btn btn-danger'
                ]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>