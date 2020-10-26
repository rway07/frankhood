<div class="card">
    <div class="card-header bg-secondary text-white">
        CHIUSURA ANNUALE PER L'ANNO {{ $year }}
    </div>

    <div class="card-body">
        <table class="table table-condensed table-sm" id="customers_table">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th> Numero </th>
                    <th> Entrate </th>
                    <th> Uscite </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr class="alert-success">
                    <td> Numero soci</td>
                    <td> {{ $customers->numPeople }} </td>
                    <td> {{ $customers->amount }} &euro; </td>
                    <td> - </td>
                </tr>
                <tr>
                    <td> - Numero rinnovi</td>
                    <td> {{ $customers->numPeople - $newCustomers->numPeople }}</td>
                    <td> {{ $customers->amount - $newCustomers->amount }} &euro; </td>
                    <td> - </td>
                </tr>
                <tr>
                    <td> - Numero nuovi soci</td>
                    <td> {{ $newCustomers->numPeople }}</td>
                    <td> {{ $newCustomers->amount }} &euro; </td>
                    <td> - </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr class="alert-success">
                    <td> Numero soci morosi</td>
                    <td> {{ $lateCustomers->numPeople }}</td>
                    <td> - </td>
                    <td> - </td>
                </tr>
                <tr>
                    <td>Numero morti post-tesseramento</td>
                    <td>{{ $postDeceasedNumber }}</td>
                    <td> - </td>
                    <td> - </td>
                </tr>
                <tr class="alert-info">
                    <td> Numero soci totali </td>
                    <td> {{ $customers->numPeople + $lateCustomers->numPeople - $postDeceasedNumber }}</td>
                    <td> - </td>
                    <td> - </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr class="alert-success">
                    <td> Numero soci deceduti</td>
                    <td> {{ $deceasedCustomers->total }}</td>
                    <td> - </td>
                    <td> {{ $totalFuneral }} &euro; </td>
                </tr>
                <tr>
                    <td> - Soci deceduti pre-tesseramento </td>
                    <td> {{ $preDeceasedNumber }}</td>
                    <td> - </td>
                    <td> {{ $preTotalFuneral }} &euro; </td>
                </tr>
                @if($exceptions->pre > 0)
                    <tr>
                        <td> -- di cui funerali con costo diverso</td>
                        <td> {{ $exceptions->pre }}</td>
                        <td> - </td>
                        <td> {{ $exceptions->pre_cost }} &euro;</td>
                    </tr>
                @endif
                <tr>
                    <td> - Soci deceduti post-tesseramento </td>
                    <td> {{ $postDeceasedNumber }}</td>
                    <td> - </td>
                    <td> {{ $postTotalFuneral }} &euro; </td>
                </tr>
                @if($exceptions->post > 0)
                    <tr>
                        <td> -- di cui funerali con costo diverso</td>
                        <td> {{ $exceptions->post }}</td>
                        <td> - </td>
                        <td> {{ $exceptions->post_cost }} &euro;</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr class="alert-success">
                    <td> Numero soci revocati</td>
                    <td> {{ $revocatedCustomers->numPeople }}</td>
                    <td> - </td>
                    <td> - </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr class="alert-success">
                    <td> Offerte </td>
                    <td> - </td>
                    <td> {{ $offers }} &euro;</td>
                    <td> - </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr class="alert-success">
                    <td> Spese</td>
                    <td> - </td>
                    <td> - </td>
                    <td> {{ $expenses}} &euro; </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr class="alert-danger">
                    <td colspan="2"> <b>TOTALE:</b> </td>
                    <td> <b> {{ $customers->amount + $offers }} &euro;</b></td>
                    <td> <b> {{ ($totalFuneral) + $expenses }} &euro; </b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
