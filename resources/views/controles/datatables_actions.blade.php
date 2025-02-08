@php
    // Récupérer les IDs des contrôles depuis la colonne controle_ids
    $controleIds = explode(',', $controle->controle_ids);
@endphp

<div class='btn-group'>
    
    @can('update', $controle)
    <a href="{{ route('controles.edit', $controle->id) }}" class='btn btn-default btn-sm'>
        <i class="fas fa-edit"></i>
    </a>
    @endcan
    @can('delete', $controle)
    {!! Form::open(['route' => ['controles.destroy', $controle->id], 'method' => 'delete']) !!}
    {!! Form::button('<i class="fas fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-sm',
        'onclick' => "return confirm('Etes vous sur de supprimer cet enregistrement?')"
    ]) !!}
    {!! Form::close() !!}
    @endcan
</div>