{!! Form::open(['route' => ['diplomes.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('diplomes.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    <button type="button" class='btn btn-danger btn-xs' data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $id }}">
        <i class="fa fa-trash"></i>
    </button>
</div>

<!-- Modal avec fond statique -->
<div class="modal fade" id="staticBackdrop{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="staticBackdropLabel{{ $id }}">Message de confirmation</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  
                </button>
            </div>
            <div class="modal-body">
                Etes-vous sûr de supprimer cet Enregistrement?            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                {!! Form::button('Supprimer', [
                    'type' => 'submit',
                    'class' => 'btn btn-danger'
                ]) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
