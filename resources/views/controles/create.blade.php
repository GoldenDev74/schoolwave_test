@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                    Faire Un Controle
                    </h1>
                </div>
            </div>
        </div>
    </section>
    

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">
        @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


            {!! Form::open(['route' => 'controles.store']) !!}

            <div class="card-body">

                <div class="row">
                    @include('controles.fields')
                </div>

            </div>

            

            {!! Form::close() !!}

        </div>
    </div>


    <style>
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }
</style>

@endsection
