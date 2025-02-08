<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th>
                @foreach($jours as $jour)
                    <th>{{ $jour->libelle }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($horaires as $horaire)
                <tr>
                    <td>{{ $horaire->libelle }}</td>
                    @foreach($jours as $jour)
                        <td>
                            @php
                                $affectation = $affectations->first(function($aff) use ($horaire, $jour) {
                                    return $aff->horaire == $horaire->id && $aff->jour_semaine == $jour->id;
                                });
                            @endphp
                            @if($affectation)
                                {{ optional($affectation->matiere)->libelle }}<br>
                                {{ optional($affectation->enseignant)->nom_prenom }}
                                @if($affectation->debut && $affectation->fin)
                                    <br>
                                    <small>
                                        Début: {{ \Carbon\Carbon::parse($affectation->debut)->format('d/m/Y') }}<br>
                                        Fin: {{ \Carbon\Carbon::parse($affectation->fin)->format('d/m/Y') }}
                                    </small>
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
