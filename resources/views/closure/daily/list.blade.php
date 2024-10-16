<div class="card">
    <div class="card-body">
        <h5 class="card-title">Quote</h5>
        <table class="table table-condensed table-sm" id="customers_table">
            <thead>
            <tr>
                <th class="fiftyfive"> Data</th>
                <th class="fifteen"></th>
                <th class="fifteen"> Persone Paganti </th>
                <th class="fifteen"> Totale </th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $d)
                <tr>
                    <td class="date fiftyfive">{{ strftime("%d/%m/%Y", strtotime($d->date))  }}</td>
                    <td class="fifteen"></td>
                    <td class="num_total fifteen">{{ $d->num_total }}</td>
                    <td class="total fifteen">{{ $d->total }} &euro;</td>
                </tr>
                @if($d->num_bank != 0)
                    <tr>
                        <td class="fiftyfive"></td>
                        <td class="fifteen"> Contanti</td>
                        <td class="fifteen">{{ $d->num_cash }}</td>
                        <td class="fifteen">{{ $d->total_cash }} &euro;</td>
                    </tr>
                    <tr>
                        <td class="fiftyfive"></td>
                        <td class="fifteen"> Bonifico</td>
                        <td class="fifteen">{{ $d->num_bank }}</td>
                        <td class="fifteen">{{ $d->total_bank }} &euro;</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td> <b>SOMMA CONTANTI:</b></td>
                <td></td>
                <td class="fifteen"> <b>{{ $final->people_cash }}</b></td>
                <td class="fifteen"> <b>{{ $final->total_cash }} &euro;</b></td>
            </tr>
            <tr>
                <td> <b>SOMMA BONIFICI:</b></td>
                <td></td>
                <td class="fifteen"> <b>{{ $final->people_bank }}</b></td>
                <td class="fifteen"> <b>{{ $final->total_bank }} &euro;</b></td>
            </tr>
            <tr>
                <td> <b>TOTALE:</b></td>
                <td></td>
                <td class="fifteen"> <b>{{ $final->people }}</b></td>
                <td class="fifteen"> <b>{{ $final->total }} &euro;</b></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="extra_div">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Offerte</h5>
                @if(count($offersData) > 0)
                    <table class="table table-condensed table-sm" id="offers_table">
                        <thead>
                        <tr>
                            <th class="fiftyfive"> Data </th>
                            <th class="fifteen"></th>
                            <th class="fifteen"></th>
                            <th class="fifteen"> Totale</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($offersData as $d)
                            <tr>
                                <td colspan="3">{{ strftime("%d/%m/%Y", strtotime($d->date)) }}</td>
                                <td class="fifteen"> {{ $d->total }} &euro;</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td> <b>TOTALE:</b></td>
                            <td></td>
                            <td></td>
                            <td class="fifteen"> <b>{{ $offersFinal->total }} &euro;</b></td>
                        </tr>
                        </tbody>
                    </table>
                @else
                    Nessun Offerta
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Spese</h5>
                @if(count($expensesData) > 0)
                    <table class="table table-condensed table-sm" id="expenses_table">
                        <thead>
                        <tr>
                            <th class="fiftyfive"> Data </th>
                            <th class="fifteen"></th>
                            <th class="fifteen"></th>
                            <th class="fifteen"> Totale</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expensesData as $d)
                            <tr>
                                <td colspan="3">{{ strftime("%d/%m/%Y", strtotime($d->date)) }}</td>
                                <td class="fifteen"> {{ $d->total }} &euro;</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td> <b>TOTALE:</b></td>
                            <td></td>
                            <td></td>
                            <td class="fifteen"> <b>{{ $expensesFinal->total }} &euro;</b></td>
                        </tr>
                        </tbody>
                    </table>
                @else
                    Nessuna spesa
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"> Riepilogo</h5>
                <table class="table table-sm">
                    <tr>
                        <td> <b>TOTALE:</b></td>
                        <td></td>
                        <td></td>
                        <td class="fifteen"> <b>{{ $summary }} &euro;</b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <br>
    <div id="graph_div" class="panel panel-info d-print-none">
        <div class="card-body">
            <canvas id="test">

            </canvas>
        </div>
    </div>
</div>
