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
<!-- Remplacez le champ 'affection_matiere' par : -->
<div class="col-sm-12">
    {!! Form::label('affectation', 'Affectation:') !!}
    <p>
        @php
            $affectation = App\Models\AffectationMatiere::find($suiviCours->affection_matiere);
            if ($affectation) {
                $classe = App\Models\Classe::find($affectation->classe)->libelle ?? 'Non défini';
                $matiere = App\Models\Matiere::find($affectation->matiere)->libelle ?? 'Non défini';
                $horaire = App\Models\Horaire::find($affectation->horaire)->libelle ?? 'Non défini';
                $typeCours = App\Models\TypeCours::find($affectation->type_cours)->libelle ?? 'Non défini';
                echo "$classe, $matiere ($horaire, $typeCours)";
            } else {
                echo 'Non défini';
            }
        @endphp
    </p>
</div>

