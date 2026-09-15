<table class="table table-sm table-hover">
    <thead class="table-dark">
    <tr>
        <th class="col-md"> Anno </th>
        <th class="col-md"> Persone paganti totali </th>
        <th class="col-md"> Persone paganti con contanti</th>
        <th class="col-md"> Persone paganti con bonifico</th>
        <th class="col-md"> Numero ricevute</th>
        <th class="col-md"> Numero ricevute con contanti</th>
        <th class="col-md"> Numero bonifici</th>
        <th class="col-md"> Totale</th>
        <th class="col-md"> Totale contanti</th>
        <th class="col-md"> Totale paganti</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
        <tr>
            <td>{{ $d->year }}</td>
            <td>{{ $d->people }}</td>
            <td>{{ $d->people_cash }}</td>
            <td>{{ $d->people_bank }}</td>
            <td>{{ $d->num_receipts  }}</td>
            <td>{{ $d->num_cash  }}</td>
            <td>{{ $d->num_bank  }}</td>
            <td>&euro; {{ $d->total }}</td>
            <td>&euro; {{ $d->total_cash }}</td>
            <td>&euro; {{ $d->total_bank }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
