<!-- Libelle Field -->
{!! Form::label('libelle', 'Jour de la Semaine:') !!}
{!! Form::select('libelle', $jours, null, ['class' => 'form-control', 'required']) !!}