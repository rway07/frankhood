<div class="card">
    <div class="card-header bg-secondary text-white">
        CHIUSURE GIORNALIERE PER L'ANNO {{ $years }}
    </div>

    <div class="card-body">
        <table class="table table-condensed table-sm" id="customers_table">
            <thead>
            <tr>
                <th> Data</th>
                <th></th>
                <th> Persone Paganti </th>
                <th> Totale </th>
            </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        <td class="date" colspan="2">{{ strftime("%d/%m/%Y", strtotime($d->date))  }}</td>
                        <td class="num_total">{{ $d->num_total }}</td>
                        <td class="total">{{ $d->total }} &euro;</td>
                    </tr>
                    @if($d->num_bank != 0)
                        <tr>
                            <td></td>
                            <td> Contanti</td>
                            <td>{{ $d->num_cash }}</td>
                            <td>{{ $d->total_cash }} &euro;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td> Bonifico</td>
                            <td>{{ $d->num_bank }}</td>
                            <td>{{ $d->total_bank }} &euro;</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td> <b>TOTALE:</b></td>
                    <td></td>
                    <td> <b>{{ $final->people_total }}</b></td>
                    <td> <b>{{ $final->total }} &euro;</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<br>
<div id="graph_div" class="panel panel-info d-print-none">
    <div class="card-body">
        <canvas id="test">

        </canvas>
    </div>
</div>
