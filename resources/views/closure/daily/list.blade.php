<div id="quote_div">
    <h5>Quote</h5>
    <table class="table table-hover table-sm" id="customers_table">
        <thead>
        <tr>
            <th class="w-40"> Data</th>
            <th class="w-15 text-end"></th>
            <th class="w-15 text-end"> Persone Paganti </th>
            <th class="w-15 text-end"> Numero Ricevute </th>
            <th class="w-15 text-end"> Totale </th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
            <tr>
                <td class="date w-40">{{ date("d/m/Y", strtotime($d->date))  }}</td>
                <td class="w-15 text-end"></td>
                <td class="num_total w-15 text-end">{{ $d->num_total }}</td>
                <td class="num_total w-15 text-end">{{ $d->num_receipts }}</td>
                <td class="total w-15 text-end">{{ $d->total }} &euro;</td>
            </tr>
            @if($d->num_bank != 0)
                <tr>
                    <td class="w-40"></td>
                    <td class="w-15 text-end"> Contanti</td>
                    <td class="w-15 text-end">{{ $d->num_cash }}</td>
                    <td class="w-15 text-end">{{ $d->num_cash_receipts }}</td>
                    <td class="w-15 text-end">{{ $d->total_cash }} &euro;</td>
                </tr>
                <tr>
                    <td class="w-40"></td>
                    <td class="w-15 text-end"> Bonifico</td>
                    <td class="w-15 text-end">{{ $d->num_bank }}</td>
                    <td class="w-15 text-end">{{ $d->num_bank_receipts }}</td>
                    <td class="w-15 text-end">{{ $d->total_bank }} &euro;</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td class="w-40"> <b>SOMMA CONTANTI:</b></td>
            <td class="w-15"></td>
            <td class="w-15 text-end"> <b>{{ $final->people_cash }}</b></td>
            <td class="w-15 text-end"> <b>{{ $final->num_cash_receipts }}</b></td>
            <td class="w-20 text-end"> <b>{{ $final->total_cash }} &euro;</b></td>
        </tr>
        <tr>
            <td class="w-40"> <b>SOMMA BONIFICI:</b></td>
            <td class="w-15"></td>
            <td class="w-15 text-end"> <b>{{ $final->people_bank }}</b></td>
            <td class="w-15 text-end"> <b>{{ $final->num_bank_receipts }}</b></td>
            <td class="w-15 text-end"> <b>{{ $final->total_bank }} &euro;</b></td>
        </tr>
        <tr>
            <td class="w-40"> <b>TOTALE:</b></td>
            <td class="w-15"></td>
            <td class="w-15 text-end"> <b>{{ $final->people }}</b></td>
            <td class="w-15 text-end"> <b>{{ $final->num_receipts }}</b></td>
            <td class="w-15 text-end"> <b>{{ $final->total }} &euro;</b></td>
        </tr>
        </tbody>
    </table>
</div>
<div id="extra_div">
    <div class="row pt-2 pb-3 border-bottom">
        <div class="col-md">
            <h5>Offerte</h5>
            @if(count($offersData) > 0)
                <table class="table table-hover table-sm" id="offers_table">
                    <thead>
                    <tr>
                        <th class="w-40"> Data </th>
                        <th class="w-15"></th>
                        <th class="w-15"></th>
                        <th class="w-15"></th>
                        <th class="w-15 text-end"> Totale</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($offersData as $d)
                        <tr>
                            <td colspan="4">{{ date("d/m/Y", strtotime($d->date)) }}</td>
                            <td class="w-15 text-end"> {{ $d->total }} &euro;</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"><b>TOTALE:</b></td>
                        <td class="w-15 text-end"> <b>{{ $offersFinal->total }} &euro;</b></td>
                    </tr>
                    </tbody>
                </table>
            @else
                Nessun Offerta
            @endif
        </div>
    </div>
    <div class="row pt-3 pb-3 border-bottom">
        <div class="col-md">
            <h5>Spese</h5>
            @if(count($expensesData) > 0)
                <table class="table table-hover table-sm" id="expenses_table">
                    <thead>
                    <tr>
                        <th colspan="4"> Data </th>
                        <th class="w-20 text-end"> Totale</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expensesData as $d)
                        <tr>
                            <td colspan="4">{{ date("d/m/Y", strtotime($d->date)) }}</td>
                            <td class="w-15 text-end"> {{ $d->total }} &euro;</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"> <b>TOTALE:</b></td>
                        <td class="w-15 text-end"> <b>{{ $expensesFinal->total }} &euro;</b></td>
                    </tr>
                    </tbody>
                </table>
            @else
                Nessuna spesa
            @endif
        </div>
    </div>
    <div class="row pt-3 pb-3 border-bottom">
        <div class="col-md">
            <h5> Riepilogo</h5>
            <table class="table table-sm table-borderless">
                <tr>
                    <td colspan="4"> <b>TOTALE:</b></td>
                    <td class="w-15 text-end"> <b>{{ $summary }} &euro;</b></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="deliveries_div">
    <div class="row pt-3 pb-3 border-bottom">
        <div class="col-md">
            <h5> Consegne: </h5>
            @include('deliveries.closure')
        </div>
    </div>
</div>
<div id="graph_div" class="d-print-none pt-4 chart-container">
    <canvas id="test">
    </canvas>
</div>
