<div id="quote_div">
    <h5>Quote</h5>
    <table class="table table-hover table-sm" id="customers_table">
        <thead>
        <tr>
            <th class="table-w-forty"> Data</th>
            <th class="table-w-twenty text-end"></th>
            <th class="table-w-twenty text-end"> Persone Paganti </th>
            <th class="table-w-twenty text-end"> Totale </th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
            <tr>
                <td class="date table-w-forty">{{ date("d/m/Y", strtotime($d->date))  }}</td>
                <td class="table-w-twenty text-end"></td>
                <td class="num_total table-w-twenty text-end">{{ $d->num_total }}</td>
                <td class="total table-w-twenty text-end">{{ $d->total }} &euro;</td>
            </tr>
            @if($d->num_bank != 0)
                <tr>
                    <td class="table-w-forty"></td>
                    <td class="table-w-twenty text-end"> Contanti</td>
                    <td class="table-w-twenty text-end">{{ $d->num_cash }}</td>
                    <td class="table-w-twenty text-end">{{ $d->total_cash }} &euro;</td>
                </tr>
                <tr>
                    <td class="table-w-forty"></td>
                    <td class="table-w-twenty text-end"> Bonifico</td>
                    <td class="table-w-twenty text-end">{{ $d->num_bank }}</td>
                    <td class="table-w-twenty text-end">{{ $d->total_bank }} &euro;</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td> <b>SOMMA CONTANTI:</b></td>
            <td></td>
            <td class="table-w-twenty text-end"> <b>{{ $final->people_cash }}</b></td>
            <td class="table-w-twenty text-end"> <b>{{ $final->total_cash }} &euro;</b></td>
        </tr>
        <tr>
            <td> <b>SOMMA BONIFICI:</b></td>
            <td></td>
            <td class="table-w-twenty text-end"> <b>{{ $final->people_bank }}</b></td>
            <td class="table-w-twenty text-end"> <b>{{ $final->total_bank }} &euro;</b></td>
        </tr>
        <tr>
            <td> <b>TOTALE:</b></td>
            <td></td>
            <td class="table-w-twenty text-end"> <b>{{ $final->people }}</b></td>
            <td class="table-w-twenty text-end"> <b>{{ $final->total }} &euro;</b></td>
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
                        <th class="table-w-forty"> Data </th>
                        <th class="table-w-twenty text-end"></th>
                        <th class="table-w-twenty text-end"></th>
                        <th class="table-w-twenty text-end"> Totale</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($offersData as $d)
                        <tr>
                            <td colspan="3">{{ date("d/m/Y", strtotime($d->date)) }}</td>
                            <td class="table-w-twenty text-end"> {{ $d->total }} &euro;</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td> <b>TOTALE:</b></td>
                        <td></td>
                        <td></td>
                        <td class="table-w-twenty text-end"> <b>{{ $offersFinal->total }} &euro;</b></td>
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
                        <th class="table-w-forty"> Data </th>
                        <th class="table-w-twenty text-end"></th>
                        <th class="table-w-twenty text-end"></th>
                        <th class="table-w-twenty text-end"> Totale</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expensesData as $d)
                        <tr>
                            <td colspan="3">{{ date("d/m/Y", strtotime($d->date)) }}</td>
                            <td class="table-w-twenty text-end"> {{ $d->total }} &euro;</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td> <b>TOTALE:</b></td>
                        <td></td>
                        <td></td>
                        <td class="table-w-twenty text-end"> <b>{{ $expensesFinal->total }} &euro;</b></td>
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
                    <td> <b>TOTALE:</b></td>
                    <td></td>
                    <td></td>
                    <td class="table-w-twenty text-end"> <b>{{ $summary }} &euro;</b></td>
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
