<!-- resources/views/suivi_cours/fields.blade.php -->




<!-- @push('page_scripts')
    <script type="text/javascript">
        $('#date').datepicker();
    </script>
@endpush -->

<!-- Titre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('titre', 'Titre:') !!}
    {!! Form::text('titre', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
</div>

<!-- Observation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('observation', 'Observation:') !!}
    {!! Form::text('observation', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
</div>

<!-- Resume Field -->
<div class="form-group col-sm-6">
    {!! Form::label('resume', 'Resume:') !!}
    {!! Form::textarea('resume', null, ['class' => 'form-control', 'required', 'maxlength' => 500, 'rows' => 4]) !!}
</div>



<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::hidden('date', now()->format('Y-m-d H:i:s'), ['id' => 'date']) !!}
</div>

