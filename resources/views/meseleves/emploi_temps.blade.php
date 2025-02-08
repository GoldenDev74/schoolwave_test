<div class="emploi-du-temps">
    @foreach($eleve->effectifs as $effectif)
        <div class="classe">
            <h4>Classe: {{ $effectif->classes->libelle ?? 'Non spécifiée' }}</h4>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Horaire/Jour</th>
                        @foreach($jours as $jour)
                            <th>{{ $jour->libelle }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($effectif->horaires as $horaire)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($horaire->debut)->format('H:i') }}<br>
                                à<br>
                                {{ \Carbon\Carbon::parse($horaire->fin)->format('H:i') }}
                            </td>
                            @foreach($jours as $jour)
                                @php
                                    $cours = $effectif->emploiDuTemps[$horaire->id][$jour->id] ?? null;
                                @endphp
                                <td>
                                    @if($cours)
                                        <strong>{{ $cours->matiere->libelle }}</strong><br>
                                        {{ $cours->enseignant->nom_prenom }}<br>
                                        Salle: {{ $cours->classe->salle->libelle ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>

<style>
    .emploi-du-temps table {
        font-size: 0.9rem;
    }
    .emploi-du-temps td {
        min-width: 120px;
        vertical-align: top;
    }
</style>