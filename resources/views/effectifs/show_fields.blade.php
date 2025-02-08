<div class="table-responsive">
    <table class="table" id="effectifs-table">
        <thead>
            <tr>
                <th>Nom et Prénom</th>
                <th>Sexe</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Parent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eleves as $eleve)
                <tr>
                    <td>{{ $eleve->nom_prenom }}</td>
                    <td>{{ $eleve->sexes->libelle }}</td>
                    <td>{{ $eleve->telephone }}</td>
                    <td>{{ $eleve->email }}</td>
                    <td>{{ $eleve->parents->nom_prenom }}</td>
                    <td>
                        @include('effectifs.datatables_actions', [
                            'id' => $eleve->effectifs->where('classe', request('classe'))->first()->id,
                            'eleve' => $eleve
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
