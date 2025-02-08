@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Mis à jour 
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($typeExamen, ['route' => ['typeExamens.update', $typeExamen->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('type_examens.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('typeExamens.index') }}" class="btn btn-default"> Annuler </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
