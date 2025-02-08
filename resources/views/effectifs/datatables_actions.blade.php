{!! Form::open(['route' => ['effectifs.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('effectifs.edit', $id) }}"
       class='btn btn-default btn-xs'>
        <i class="far fa-edit"></i>
    </a>
    {!! Form::button('<i class="far fa-trash-alt"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Êtes-vous sûr de vouloir retirer cet élève de la classe?')"
    ]) !!}
</div>
{!! Form::close() !!}
