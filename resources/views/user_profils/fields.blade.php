<!-- Personnel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('personnel', 'Personnel:') !!}
    {!! Form::number('personnel', null, ['class' => 'form-control']) !!}
</div>

<!-- Profil Field -->
<div class="form-group col-sm-6">
    {!! Form::label('profil', 'Profil:') !!}
    {!! Form::number('profil', null, ['class' => 'form-control']) !!}
</div>

<!-- Parent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent', 'Parent:') !!}
    {!! Form::number('parent', null, ['class' => 'form-control']) !!}
</div>

<!-- Eleve Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eleve', 'Eleve:') !!}
    {!! Form::number('eleve', null, ['class' => 'form-control']) !!}
</div>

<!-- User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user', 'User:') !!}
    {!! Form::number('user', null, ['class' => 'form-control', 'required']) !!}
</div>