{!! Form::open(['route' => ['jourSemaines.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('jourSemaines.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => 'return confirm("'.__('Êtes-vous sûr de supprimer cet enregistrement ?').'")'

    ]) !!}
</div>
{!! Form::close() !!}
