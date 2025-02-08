<!-- Nom Prenom Field -->
<div class="form-group col-sm-3">
    {!! Form::label('nom_prenom', 'Nom Prenom:') !!}
    {!! Form::text('nom_prenom', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Date Naissance Field -->
<div class="form-group col-sm-3">
    {!! Form::label('date_naissance', 'Date Naissance:') !!}
    {!! Form::text('date_naissance', null, ['class' => 'form-control','id'=>'date_naissance']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_naissance').datepicker()
    </script>
@endpush

<!-- Date Engagement Field -->
<div class="form-group col-sm-3">
    {!! Form::label('date_engagement', 'Date Engagement:') !!}
    {!! Form::text('date_engagement', null, ['class' => 'form-control','id'=>'date_engagement']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_engagement').datepicker()
    </script>
@endpush

<!-- Date Diplome Field -->
<div class="form-group col-sm-3">
    {!! Form::label('date_diplome', 'Date Diplôme:') !!}
    {!! Form::text('date_diplome', null, ['class' => 'form-control','id'=>'date_diplome']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_diplome').datepicker()
    </script>
@endpush

<!-- Diplome Field -->
<div class="form-group col-sm-3">
    {!! Form::label('diplome', 'Diplôme:') !!}
    {!! Form::select('diplome', $diplomes, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Filiere Field -->
<div class="form-group col-sm-3">
    {!! Form::label('filiere', 'Filière:') !!}
    {!! Form::select('filiere', $filieres, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Sexe Field -->
<div class="form-group col-sm-3">
    {!! Form::label('sexe', 'Sexe:') !!}
    {!! Form::select('sexe', $sexes, null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Type Cours Field -->
<div class="form-group col-sm-3">
    {!! Form::label('type_cours', 'Type Cours:') !!}
    {!! Form::select('type_cours', $typeCours, null, ['class' => 'form-control', 'required']) !!}
</div>


<!-- Nationalite Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nationalite', 'Nationalite:') !!}
    {!! Form::number('nationalite', null, ['class' => 'form-control']) !!}
</div>

<!-- Enseignant Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('enseignant', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('enseignant', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('enseignant', 'Enseignant', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- Administration Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('administration', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('administration', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('administration', 'Administration', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- Type Personnel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type_personnel', 'Type Personnel:') !!}
    {!! Form::number('type_personnel', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>