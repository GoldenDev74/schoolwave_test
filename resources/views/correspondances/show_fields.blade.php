<!-- Objet Field -->
<div class="col-sm-12">
    {!! Form::label('objet', 'Objet:') !!}
    <p>{{ $correspondance->objet }}</p>
</div>

<!-- Destinataire Field -->
<div class="col-sm-12">
    {!! Form::label('destinataire', 'Destinataire:') !!}
    <p>{{ $correspondance->destinataire }}</p>
</div>

<!-- Message Field -->
<div class="col-sm-12">
    {!! Form::label('message', 'Message:') !!}
    <p>{{ $correspondance->message }}</p>
</div>

<!-- Expediteur Field -->
<div class="col-sm-12">
    {!! Form::label('expediteur', 'Expediteur:') !!}
    <p>{{ $correspondance->expediteurUser->name ?? 'N/A' }}</p>
</div>

<!-- Cible Field -->
<div class="col-sm-12">
    {!! Form::label('cible', 'Cible:') !!}
    <p>{{ $correspondance->cibleProfil->libelle ?? 'N/A' }}</p>
</div>

