{!! Form::open(['route' => ['correspondances.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <!--<a href="{{ route('correspondances.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('correspondances.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a>-->
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Êtes-vous sûr de supprimer cet enregistrement ?')"

    ]) !!}
</div>
{!! Form::close() !!}
