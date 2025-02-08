<!-- Nom Prenom Field -->
<div class="col-sm-12">
    {!! Form::label('nom_prenom', 'Nom Prenom:') !!}
    <p>{{ $enseignant->nom_prenom }}</p>
</div>

<!-- Date Naissance Field -->
<div class="col-sm-12">
    {!! Form::label('date_naissance', 'Date Naissance:') !!}
    <p>{{ $enseignant->date_naissance }}</p>
</div>

<!-- Date Engagement Field -->
<div class="col-sm-12">
    {!! Form::label('date_engagement', 'Date Engagement:') !!}
    <p>{{ $enseignant->date_engagement }}</p>
</div>

<!-- Date Diplome Field -->
<div class="col-sm-12">
    {!! Form::label('date_diplome', 'Date Diplome:') !!}
    <p>{{ $enseignant->date_diplome }}</p>
</div>

<!-- Diplome Field -->
<div class="col-sm-12">
    {!! Form::label('diplome', 'Diplome:') !!}
    <p>{{ $enseignant->diplome }}</p>
</div>

<!-- Filiere Field -->
<div class="col-sm-12">
    {!! Form::label('filiere', 'Filiere:') !!}
    <p>{{ $enseignant->filiere }}</p>
</div>

<!-- Sexe Field -->
<div class="col-sm-12">
    {!! Form::label('sexe', 'Sexe:') !!}
    <p>{{ $enseignant->sexe }}</p>
</div>

