@if(count($deliveries) > 0)
    <table id="deliveries_table" class="table table-sm table-hover">
        <thead>
        <tr>
            <th class="table-w-forty">Data</th>
            <th class="table-w-twenty text-end">Cifra iniziale</th>
            <th class="table-w-twenty text-end">Cifra consegnata</th>
            <th class="table-w-twenty text-end">Rimanente</th>
        </tr>
        </thead>
        <tbody>
        @foreach($deliveries as $delivery)
            <tr>
                <td class="table-w-forty">{{ date("d/m/Y", strtotime($delivery->date)) }}</td>
                <td class="table-w-twenty text-end">&euro; {{ $delivery->total }}</td>
                <td class="table-w-twenty text-end">&euro; {{ $delivery->amount }}</td>
                <td class="table-w-twenty text-end">&euro; {{ $delivery->remaining }}</td>
            </tr>
        @endforeach
        <tr>
            <td><b>TOTALE:</b></td>
            <td></td>
            <td class="table-w-twenty text-end"><b>&euro; {{ $deliveriesTotal }}</b></td>
            <td></td>
        </tr>
        </tbody>
    </table>
@else
    <h6> Nessun dato salvato</h6>
@endif
