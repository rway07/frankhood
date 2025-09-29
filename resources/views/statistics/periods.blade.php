<div class="row pb-3">
    <h6> Periodo precedente al <span id="period_title">??</span></h6>
</div>
<table class="table table-sm table-hover">
    <thead class="table-dark">
    <tr>
        <th class="col-md"> Anno</th>
        <th class="col-md text-end"> Persone Contanti </th>
        <th class="col-md text-end"> Persone Bonifico</th>
        <th class="col-md text-end"> Persone Totali </th>
        <th class="col-md text-end"> Numero ricevute contanti</th>
        <th class="col-md text-end"> Numero ricevute bonifico</th>
        <th class="col-md text-end"> Numero ricevute</th>
        <th class="col-md text-end"> Totale Contanti</th>
        <th class="col-md text-end"> Totale Bonifico</th>
        <th class="col-md text-end"> Totale</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
        <tr>
            <td> {{ $d->year }}</td>
            <td class="text-end"> {{ $d->num_cash }}</td>
            <td class="text-end"> {{ $d->num_bank }}</td>
            <td class="text-end"> {{ $d->num_total }}</td>
            <td class="text-end"> {{ $d->num_cash_receipts }}</td>
            <td class="text-end"> {{ $d->num_bank_receipts }}</td>
            <td class="text-end"> {{ $d->num_receipts }}</td>
            <td class="text-end"> {{ $d->total_cash }} &euro;</td>
            <td class="text-end"> {{ $d->total_bank }} &euro;</td>
            <td class="text-end"> {{ $d->total }} &euro;</td>
        </tr>
    @endforeach
    </tbody>
</table>
