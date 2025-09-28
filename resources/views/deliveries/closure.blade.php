@if(count($deliveries) > 0)
    <table id="deliveries_table" class="table table-sm table-hover">
        <thead>
        <tr>
            <th colspan="2" class="w-40">Data</th>
            <th class="w-15 text-end">Cifra iniziale</th>
            <th class="w-15 text-end">Cifra consegnata</th>
            <th class="w-15 text-end">Rimanente</th>
        </tr>
        </thead>
        <tbody>
        @foreach($deliveries as $delivery)
            <tr>
                <td colspan="2" class="w-40">{{ date("d/m/Y", strtotime($delivery->date)) }}</td>
                <td class="w-15 text-end">&euro; {{ $delivery->total }}</td>
                <td class="w-15 text-end">&euro; {{ $delivery->amount }}</td>
                <td class="w-15 text-end">&euro; {{ $delivery->remaining }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3"><b>TOTALE:</b></td>
            <td class="w-15 text-end"><b>&euro; {{ $deliveriesTotal }}</b></td>
            <td></td>
        </tr>
        </tbody>
    </table>
@else
    <h6> Nessun dato salvato</h6>
@endif
