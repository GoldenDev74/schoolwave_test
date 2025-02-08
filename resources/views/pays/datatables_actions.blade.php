{!! Form::open(['route' => ['pays.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('pays.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => 'return confirm("'.__('Etes-vous sûr de supprimer cet pays?').'")'

    ]) !!}
</div>
{!! Form::close() !!}
