<!-- Libelle Field -->
<div class="col-sm-12">
    {!! Form::label('libelle', 'Libelle:') !!}
    <p>{{ $examen->libelle }}</p>
</div>

<!-- Type Examen Field -->
<div class="col-sm-12">
    {!! Form::label('type_examen', 'Type Examen:') !!}
    <p>{{ $examen->type_examen }}</p>
</div>

<!-- Note Field -->
<div class="col-sm-12">
    {!! Form::label('note', 'Note:') !!}
    <p>{{ $examen->note }}</p>
</div>

<!-- Eleve Field -->
<div class="col-sm-12">
    {!! Form::label('eleve', 'Eleve:') !!}
    <p>{{ $examen->eleve }}</p>
</div>

<!-- Affectation Field -->
<div class="col-sm-12">
    {!! Form::label('affectation', 'Affectation:') !!}
    <p>{{ $examen->affectation }}</p>
</div>

