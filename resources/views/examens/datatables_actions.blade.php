{!! Form::open(['route' => ['examens.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('examens.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => 'return confirm("Etes-vous sûr de supprimer cet Enregistrement?")'

    ]) !!}
</div>
{!! Form::close() !!}
