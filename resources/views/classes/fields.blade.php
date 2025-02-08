<!-- Libelle Field -->
<div class="form-group col-sm-6">
    {!! Form::label('libelle', 'Libelle:') !!}
    {!! Form::text('libelle', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Type Cours Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type_cours', 'Type Cours:') !!}
    {!! Form::select('type_cours', $typeCours, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Salle Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salle', 'Salle:') !!}
    {!! Form::select('salle', $salles, null, ['class' => 'form-control', 'required']) !!}
</div>