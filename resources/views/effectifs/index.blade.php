@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Effectifs</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('effectifs.create') }}">
                        Nouveau
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="classe">Classe:</label>
                        <select id="classe" class="form-control">
                            <option value="">Choisir une classe</option>
                            @foreach(\App\Models\Classe::all() as $classe)
                                <option value="{{ $classe->id }}" {{ request('classe') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 offset-md-4">
                        <label for="recherche">Rechercher:</label>
                        <input type="text" id="recherche" class="form-control" placeholder="Rechercher un élève...">
                    </div>
                </div>

                @include('effectifs.show_fields')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/effectifs/index.js') }}"></script>
@endpush
