<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $suiviCours->date }}</p>
</div>

<!-- Titre Field -->
<div class="col-sm-12">
    {!! Form::label('titre', 'Titre:') !!}
    <p>{{ $suiviCours->titre }}</p>
</div>

<!-- Resume Field -->
<div class="col-sm-12">
    {!! Form::label('resume', 'Resume:') !!}
    <p>{{ $suiviCours->resume }}</p>
</div>

<!-- Observation Field -->
<div class="col-sm-12">
    {!! Form::label('observation', 'Observation:') !!}
    <p>{{ $suiviCours->observation }}</p>
</div>

<!-- Affection Matiere Field -->
<div class="col-sm-12">
    {!! Form::label('affection_matiere', 'Affection Matiere:') !!}
    <p>{{ $suiviCours->affection_matiere }}</p>
</div>

