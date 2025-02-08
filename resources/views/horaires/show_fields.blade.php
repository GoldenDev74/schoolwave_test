<!-- Libelle Field -->
<div class="col-sm-12">
    {!! Form::label('libelle', 'Libelle:') !!}
    <p>{{ $horaire->libelle }}</p>
</div>

<!-- Debut Field -->
<div class="col-sm-12">
    {!! Form::label('debut', 'Debut:') !!}
    <p>{{ $horaire->debut }}</p>
</div>

<!-- Fin Field -->
<div class="col-sm-12">
    {!! Form::label('fin', 'Fin:') !!}
    <p>{{ $horaire->fin }}</p>
</div>

<!-- Type Cours Field -->
<div class="col-sm-12">
    {!! Form::label('type_cours', 'Type Cours:') !!}
    <p>{{ $horaire->type_cours }}</p>
</div>

