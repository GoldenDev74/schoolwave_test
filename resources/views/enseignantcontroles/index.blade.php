@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>📅 Historique de vos contrôles de présence</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <!-- Filtres avancés -->
    <div class="card shadow-lg">
        <div class="card-header bg-lightblue">
            <h3 class="card-title mb-0">Filtres avancés</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Select pré-rempli pour l'enseignant (désactivé) -->
                <div class="col-md-3">
                    <label>Enseignant</label>
                    <select id="filter-enseignant" class="form-control select2" disabled>
                        <option value="{{ $enseignant->id }}" selected>{{ $enseignant->nom_prenom }}</option>
                    </select>
                </div>
                <!-- Select pour les classes dans lesquelles il intervient -->
                <div class="col-md-3">
                    <label>Classe</label>
                    <select id="filter-classe" class="form-control select2">
                        <option value="">Toutes</option> <!-- Ceci doit être présent -->
                        @foreach($classes as $id => $libelle)
                        <option value="{{ $id }}">{{ $libelle }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-3">
                    <label>Date de</label>
                    <input type="date" id="filter-date-start" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Date à</label>
                    <input type="date" id="filter-date-end" class="form-control">
                </div>
            </div>
        </div>
    </div>

    @include('flash::message')

    <!-- Table des contrôles -->
    <div class="card shadow mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                @include('controles.table')
            </div>
        </div>
    </div>
</div>

<!-- Champ caché pour transmettre l'id de l'enseignant -->
<input type="hidden" id="logged_teacher_id" value="{{ $enseignant->id }}">

<!-- Modal Détails -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title" id="detailsModalLabel">Détails du contrôle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Présents (<span id="presentCount">0</span>)</h6>
                        <ul id="presentList" class="list-group"></ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Absents (<span id="absentCount">0</span>)</h6>
                        <ul id="absentList" class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        // Initialisation de Select2
        $('.select2').select2({
            theme: 'bootstrap-4',
            width: '100%',
        });

        

        // Récupération de l'instance DataTable
        var table = window.LaravelDataTables['controles-table'];

        // Rafraîchissement du DataTable lors d'un changement de filtre
        $('#filter-enseignant, #filter-classe, #filter-date-start, #filter-date-end').on('change', function() {
            table.draw();
        });

        // Gestion du clic sur le bouton "Détails"
        $(document).on('click', '.btn-details', function() {
            const affectationId = $(this).data('affectation');
            const dateControle = $(this).data('date');

            $.ajax({
                url: '{{ route("controles.details") }}',
                method: 'GET',
                data: {
                    affectation_id: affectationId,
                    date_controle: dateControle
                },
                success: function(response) {
                    // Remplissage des listes
                    fillList('presentList', response.presents);
                    fillList('absentList', response.absents);

                    // Mise à jour des compteurs
                    $('#presentCount').text(response.presents.length);
                    $('#absentCount').text(response.absents.length);

                    // Affichage du modal
                    $('#detailsModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Erreur:', xhr.responseText);
                    alert('Une erreur est survenue');
                }
            });
        });

        function fillList(listId, data) {
            const $list = $('#' + listId).empty();
            data.forEach(eleve => {
                $list.append(
                    `<li class="list-group-item d-flex justify-content-between align-items-center">
                        ${eleve.nom_prenom}
                        <span class="badge badge-${listId === 'presentList' ? 'success' : 'danger'}">
                            ${listId === 'presentList' ? 'Présent' : 'Absent'}
                        </span>
                    </li>`
                );
            });
        }
    });
</script>
@endpush