<!-- Libelle Field -->
<div class="form-group col-sm-3">
    {!! Form::label('libelle', 'Libelle:') !!}
    {!! Form::text('libelle', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Type Examen Field
    à changer par un select avec les types d'examens -->
<div class="form-group col-sm-3">
    {!! Form::label('type_examen', 'Type Examen:') !!}
    {!! Form::number('type_examen', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Note Field
    à changer par un select avec les notes -->
<div class="form-group col-sm-3">
    {!! Form::label('note', 'Note:') !!}
    {!! Form::number('note', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Eleve Field
    à changer par un select avec les eleves -->
<div class="form-group col-sm-3">
    {!! Form::label('eleve', 'Eleve:') !!}
    {!! Form::number('eleve', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Affectation Field
    à changer par un select avec les affectations -->
<div class="form-group col-sm-3">
    {!! Form::label('affectation', 'Affectation:') !!}
    {!! Form::number('affectation', null, ['class' => 'form-control', 'required']) !!}
</div>
