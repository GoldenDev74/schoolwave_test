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

<!-- Lieu Naissance Field -->
<div class="form-group col-sm-3">
    {!! Form::label('lieu_naissance', 'Lieu Naissance:') !!}
    {!! Form::text('lieu_naissance', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Nationalite Field -->
<div class="form-group col-sm-3">
    {!! Form::label('nationalite', 'Nationalite:') !!}
    {!! Form::select('nationalite', $nationalite, null, ['class' => 'form-control']) !!}
</div>

<!-- Adresse Field -->
<div class="form-group col-sm-3">
    {!! Form::label('adresse', 'Adresse:') !!}
    {!! Form::text('adresse', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Ville Field -->
<div class="form-group col-sm-3">
    {!! Form::label('ville', 'Ville:') !!}
    {!! Form::text('ville', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Pays Residence Field -->
<div class="form-group col-sm-3">
    {!! Form::label('pays_residence', 'Pays Residence:') !!}
    {!! Form::select('pays_residence', $pays_residence, null, ['class' => 'form-control']) !!}
</div>

<!-- Telephone Field -->
<div class="form-group col-sm-3">
    {!! Form::label('telephone', 'Telephone:') !!}
    {!! Form::text('telephone', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-3">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Lien Eleve Field -->
<div class="form-group col-sm-3">
    {!! Form::label('lien_eleve', 'Lien Eleve:') !!}
    {!! Form::select('lien_eleve', $lienEleves, null, ['class' => 'form-control']) !!}
</div>