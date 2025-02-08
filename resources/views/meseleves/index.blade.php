@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Mes enfants</h4>
                        <a href="{{ route('meseleves.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($eleves->count() > 0)
                        <div class="card">
                            @include('meseleves.table')
                        </div>


                        <!-- Modal pour les détails -->
                        <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailsModalLabel">Espace détails - <span id="modalEleveName"></span></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-4">
                                            <label for="detailType" class="form-label">Sélectionner le type de détails :</label>
                                            <select class="form-select" id="detailType" onchange="switchDetails()">
                                                <option value="">Sélectionner...</option>
                                                <option value="emploiTemps">Emploi du temps</option>
                                                <option value="notes">Notes</option>
                                            </select>
                                        </div>
                                        <div id="emploiTempsContent" style="display: none;"></div>
                                        <div id="notesContent" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <p>Vous n'avez aucun élève enregistré.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        padding: 1.5rem;
    }

    .modal-header .btn-close {
        color: white;
        opacity: 1;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-select {
        border-radius: 8px;
        padding: 12px;
        border: 2px solid #e3e6f0;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .form-label {
        font-weight: 600;
        color: #4e73df;
        margin-bottom: 0.5rem;
    }

    .spinner-border {
        color: #4e73df;
        width: 3rem;
        height: 3rem;
    }

    .table {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead th {
        background: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #4e73df;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 1rem;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e3e6f0;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
    }

    .loading-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 200px;
    }

    .loading-spinner {
        position: relative;
    }

    .loading-spinner::after {
        content: 'Chargement...';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 1rem;
        color: #4e73df;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fr.js"></script>
    <script>
        let currentEleveId = null;
        let currentEleveName = null;

        window.showDetails = function(eleveId, eleveName) {
            currentEleveId = eleveId;
            currentEleveName = eleveName;
            
            document.getElementById('detailType').value = '';
            document.getElementById('emploiTempsContent').style.display = 'none';
            document.getElementById('notesContent').style.display = 'none';
            document.getElementById('modalEleveName').textContent = eleveName;
            
            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
        }

        window.switchDetails = function() {
            const detailType = document.getElementById('detailType').value;
            const emploiTempsContent = document.getElementById('emploiTempsContent');
            const notesContent = document.getElementById('notesContent');

            // Cacher les deux contenus
            emploiTempsContent.style.display = 'none';
            notesContent.style.display = 'none';

            if (detailType === 'emploiTemps') {
                emploiTempsContent.style.display = 'block';
                loadEmploiTemps(currentEleveId, currentEleveName);
            } else if (detailType === 'notes') {
                notesContent.style.display = 'block';
                loadNotes(currentEleveId, currentEleveName);
            }
        }

        function loadEmploiTemps(eleveId, eleveName) {
        const emploiTempsContent = document.getElementById('emploiTempsContent');
        emploiTempsContent.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </div>
        `;

        fetch(`/get-emploi-temps/${eleveId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error('Erreur lors de la récupération des données');

                // Création du tableau
                const table = document.createElement('table');
                table.className = 'table table-bordered';
                
                // En-tête avec les jours
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                headerRow.innerHTML = '<th>Horaires</th>'; // Colonne pour les horaires
                
                data.jours.forEach(jour => {
                    const th = document.createElement('th');
                    th.textContent = jour.libelle;
                    headerRow.appendChild(th);
                });
                
                thead.appendChild(headerRow);
                table.appendChild(thead);

                // Corps du tableau
                const tbody = document.createElement('tbody');
                
                data.horaires.forEach(horaire => {
                    const row = document.createElement('tr');
                    
                    // Cellule horaire
                    const tdHoraire = document.createElement('td');
                    const debut = moment(horaire.debut, 'HH:mm:ss').format('HH:mm');
                    const fin = moment(horaire.fin, 'HH:mm:ss').format('HH:mm');
                    tdHoraire.textContent = `${debut} - ${fin}`;
                    row.appendChild(tdHoraire);

                    // Cellules pour chaque jour
                    data.jours.forEach(jour => {
                        const td = document.createElement('td');
                        const cours = data.emploiDuTemps[horaire.id]?.[jour.id];
                        
                        if (cours) {
                            td.innerHTML = `
                                <strong>${cours.matiere}</strong><br>
                                <em>${cours.enseignant}</em>
                            `;
                        }
                        
                        row.appendChild(td);
                    });

                    tbody.appendChild(row);
                });

                table.appendChild(tbody);
                emploiTempsContent.innerHTML = '';
                emploiTempsContent.appendChild(table);
            })
            .catch(error => {
                emploiTempsContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Erreur lors du chargement : ${error.message}
                    </div>
                `;
                console.error(error);
            });
    }

    function loadNotes(eleveId, eleveName) {
        const notesContent = document.getElementById('notesContent');
        
        notesContent.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </div>
        `;

        fetch(`/get-notes/${eleveId}`)
            .then(response => response.text())
            .then(html => {
                notesContent.innerHTML = html;
            })
            .catch(error => {
                notesContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Erreur lors du chargement des notes
                    </div>
                `;
                console.error('Erreur:', error);
            });
    }
    </script>
@endpush

@endsection
