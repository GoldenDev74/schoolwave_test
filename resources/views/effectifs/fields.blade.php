<!-- Annee Scolaire Field -->
<div class="form-group col-sm-6">
    {!! Form::label('annee_scolaire', 'Annee Scolaire:') !!}
    {!! Form::select('annee_scolaire', $annees_scolaires, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Classe Field -->
<div class="form-group col-sm-6">
    {!! Form::label('classe', 'Classe:') !!}
    {!! Form::select('classe', $classes, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Eleve Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eleve', 'Eleve:') !!}
    {!! Form::select('eleve', $eleves, null, ['class' => 'form-control', 'required']) !!}
</div>
