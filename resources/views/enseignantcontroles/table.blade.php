@push('third_party_stylesheets')
    <style>
        .progress-bar { min-width: 3em; }
        table.dataTable td { vertical-align: middle; }
    </style>
@endpush

<div class="card-body px-4">
    {!! $dataTable->table(['class' => 'table table-hover table-striped align-middle', 'style' => 'width:100%']) !!}
</div>

@push('third_party_scripts')
    {!! $dataTable->scripts() !!}
@endpush