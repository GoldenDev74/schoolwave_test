<!-- Classe Field -->
<div class="form-group col-sm-3">
    {!! Form::label('classe', 'Classe:') !!}
    {!! Form::select('classe',$classes, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Annee Scolaire Field -->
<div class="form-group col-sm-3">
    {!! Form::label('annee_scolaire', 'Annee Scolaire:') !!}
    {!! Form::select('annee_scolaire',$anneeScolaires, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Matiere Field -->
<div class="form-group col-sm-3">
    {!! Form::label('matiere', 'Matiere:') !!}
    {!! Form::select('matiere',$matieres, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Enseignant Field -->
<div class="form-group col-sm-3">
    {!! Form::label('enseignant', 'Enseignant:') !!}
    {!! Form::select('enseignant',$enseignants, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Horaire Field -->
<div class="form-group col-sm-3">
    {!! Form::label('horaire', 'Horaire:') !!}
    {!! Form::select('horaire',$horaires, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Type Cours Field -->
<div class="form-group col-sm-3">
    {!! Form::label('type_cours', 'Type Cours:') !!}
    {!! Form::select('type_cours',$typeCours, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Jour Field -->
<div class="form-group col-sm-3">
    {!! Form::label('jour', 'Jour:') !!}
    {!! Form::select('jour',$jours, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Mode Affection Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mode_affection', 'Mode Affection:') !!}
    {!! Form::number('mode_affection', null, ['class' => 'form-control']) !!}
</div>

<!-- Debut Field -->
<div class="form-group col-sm-6">
    {!! Form::label('debut', 'Debut:') !!}
    {!! Form::text('debut', null, ['class' => 'form-control','id'=>'debut']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#debut').datepicker()
    </script>
@endpush

<!-- Fin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fin', 'Fin:') !!}
    {!! Form::text('fin', null, ['class' => 'form-control','id'=>'fin']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#fin').datepicker()
    </script>
@endpush