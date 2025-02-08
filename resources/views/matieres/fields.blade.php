<!-- Libelle Field -->
<div class="form-group col-sm-6">
    {!! Form::label('libelle', 'Libelle:') !!}
    {!! Form::text('libelle', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Categorie Matiere Field -->
<div class="form-group col-sm-6">
    {!! Form::label('categorie_matiere', 'Categorie Matière:') !!}
    {!! Form::select('categorie_matiere', $categorie_matiere, null, ['class' => 'form-control', 'required']) !!}
</div>

