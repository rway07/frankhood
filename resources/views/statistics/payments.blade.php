<table class="table table-sm table-hover">
    <thead class="table-dark">
    <tr>
        <th class="col-md-1"> Anno </th>
        <th class="col-md-2"> Persone paganti totali </th>
        <th class="col-md-2"> Persone paganti con contanti</th>
        <th class="col-md-2"> Persone paganti con bonifico</th>
        <th class="col-md-2"> Totale</th>
        <th class="col-md-2"> Totale contanti</th>
        <th class="col-md-2"> Totale paganti</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
        <tr>
            <td>{{ $d->year }}</td>
            <td>{{ $d->people }}</td>
            <td>{{ $d->people_cash }}</td>
            <td>{{ $d->people_bank }}</td>
            <td>&euro; {{ $d->total }}</td>
            <td>&euro; {{ $d->total_cash }}</td>
            <td>&euro; {{ $d->total_bank }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
