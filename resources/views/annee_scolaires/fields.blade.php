<!-- Libelle Field -->
<div class="form-group col-sm-6">
    {!! Form::label('libelle', 'Libelle:') !!}
    {!! Form::text('libelle', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- En Cours Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('en_cours', 1, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('en_cours', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('en_cours', 'En Cours', ['class' => 'form-check-label']) !!}
    </div>
</div>