@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Correspondances</h1>
            </div>
            <div class="col-sm-6">
                <button id="nouveauCorrespondanceBtn" class="btn btn-primary float-right" data-toggle="modal" data-target="#correspondanceModal">
                    <i class="fas fa-plus-circle"></i> Nouveau
                </button>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('flash::message')
    <div class="clearfix"></div>
    <div class="card">
        @include('correspondances.table')
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="correspondanceModal" tabindex="-1" role="dialog" aria-labelledby="correspondanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="correspondanceModalLabel">
                    <i class="fas fa-envelope"></i> Nouvelle correspondance
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('correspondances.fields', [
                'profils' => $profils,
                'classes' => $classes
                ])
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .success-alert {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(76, 175, 80, 0.9);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        text-align: center;
        display: none;
        z-index: 10000;
        animation: fadeInOut 1.5s ease-in-out;
    }

    #formSuccessAlert {
        position: sticky;
        top: 15px;
        z-index: 100;
        margin: -1rem -1rem 1rem -1rem;
        border-radius: 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#correspondanceForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const alert = $('#formSuccessAlert'); // Référence à l'alerte du formulaire

            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Envoi...').prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Afficher l'alerte dans le formulaire
                    alert.fadeIn().delay(2000).fadeOut();
                    
                    // Fermer la modal après 2s
                    setTimeout(() => {
                        $('#correspondanceModal').modal('hide');
                        
                        // Recharger le DataTable
                        if ($.fn.DataTable.isDataTable('.dataTable')) {
                            $('.dataTable').DataTable().ajax.reload(null, false);
                        }
                    }, 2000);
                },
                error: function(xhr) {
                    console.error('Erreur:', xhr.responseText);
                    alert('Une erreur est survenue');
                },
                complete: function() {
                    submitBtn.html('<i class="fas fa-check"></i> Envoyer').prop('disabled', false);
                }
            });
        });

        // Réinitialiser le formulaire quand la modal se ferme
        $('#correspondanceModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#formSuccessAlert').hide();
        });
    });
</script>
@endpush