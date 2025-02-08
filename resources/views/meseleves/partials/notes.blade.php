<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Matière</th>
                <th>Type d'examen</th>
                <th>Note</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @if(count($notes) > 0)
                @foreach($notes as $note)
                    <tr>
                        <td>{{ $note['matiere'] }}</td>
                        <td>{{ $note['type_examen'] }}</td>
                        <td>{{ $note['note'] }}/20</td>
                        <td>{{ $note['created_at'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">Aucune note disponible</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
