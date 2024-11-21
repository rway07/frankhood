@if(count($deliveries) > 0)
    <table id="deliveries_table" class="table table-sm table-hover">
        <thead class="table-dark">
            <tr>
                <td>Data</td>
                <td>Cifra consegnata</td>
                <td>Totale in cassa</td>
                <td>Rimanente</td>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $delivery)
                <tr>
                    <td>{{ strftime("%d/%m/%Y", strtotime($delivery->date)) }}</td>
                    <td>&euro; {{ $delivery->amount }}</td>
                    <td>&euro; {{ $delivery->total }}</td>
                    <td>&euro; {{ $delivery->remaining }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <h6> Nessun dato salvato</h6>
@endif
