@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Mise à jour Parent
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($parent, ['route' => ['parents.update', $parent->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('parents.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('parents.index') }}" class="btn btn-default"> Annuler </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
