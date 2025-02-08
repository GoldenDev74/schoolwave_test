@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Suivi Cours Enseignant</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('suiviCoursEnseignant.create') }}">
                        Ajouter
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="suivi-cours-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Titre</th>
                                <th>Résumé</th>
                                <th>Observation</th>
                                <th>Affection Matière</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($suiviCoursEnseignant as $suivi)
                            <tr>
                                <td>{{ $suivi->date }}</td>
                                <td>{{ $suivi->titre }}</td>
                                <td>{{ $suivi->resume }}</td>
                                <td>{{ $suivi->observation }}</td>
                                <td>{{ $suivi->affection_matiere }}</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href="{{ route('suiviCoursEnseignant.show', [$suivi->id]) }}"
                                           class='btn btn-default btn-xs'>
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suiviCoursEnseignant.edit', [$suivi->id]) }}"
                                           class='btn btn-default btn-xs'>
                                            <i class="far fa-edit"></i>
                                        </a>
                                        {!! Form::open(['route' => ['suiviCoursEnseignant.destroy', $suivi->id], 'method' => 'delete', 'class' => 'd-inline']) !!}
                                        <button type="submit" class='btn btn-danger btn-xs' onclick="return confirm('Êtes-vous sûr ?')">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $suiviCoursEnseignant->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
