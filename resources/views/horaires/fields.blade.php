<!-- Libelle Field -->
<div class="form-group col-sm-6">
    {!! Form::label('libelle', 'Libelle:') !!}
    {!! Form::text('libelle', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Debut Field -->
<div class="form-group col-sm-6">
    {!! Form::label('debut', 'Début:') !!}
    {!! Form::time('debut', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Fin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fin', 'Fin:') !!}
    {!! Form::time('fin', null, ['class' => 'form-control', 'required']) !!}
</div>


<!-- Type Cours Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type_cours', 'Type Cours:') !!}
    {!! Form::select('type_cours', $typeCours, null, ['class' => 'form-control', 'required']) !!}
</div>