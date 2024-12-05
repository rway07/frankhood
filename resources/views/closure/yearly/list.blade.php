<table class="table table-hover table-sm">
    <thead class="table-dark">
        <tr>
            <th class="w-50"></th>
            <th class="table-w-ten"> Numero </th>
            <th class="table-w-twenty"> Entrate </th>
            <th class="table-w-twenty"> Uscite </th>
        </tr>
    </thead>
    <tbody>
        <tr class="">
            <td> [A] Numero soci regolari</td>
            <td> {{ $customers->numPeople }} </td>
            <td> {{ $customers->amount }} &euro; </td>
            <td> - </td>
        </tr>
        <tr class="">
            <td> [B] Numero rinnovi </td>
            <td> {{ $customers->numPeople - $newCustomers->numPeople }}</td>
            <td> {{ $customers->amount - $newCustomers->amount }} &euro; </td>
            <td> - </td>
        </tr>
        <tr>
            <td> [B1] Numero rinnovi quote regolari</td>
            <td>{{ $customers->numRegular - $newCustomers->numRegular }}</td>
            <td>{{ $customers->amountRegular - $newCustomers->amountRegular}} &euro; </td>
            <td> - </td>
        </tr>
        <tr>
            <td> [B2] Numero rinnovi quote alternative</td>
            <td>{{ $customers->numAlt - $newCustomers->numAlt}}</td>
            <td>{{ $customers->amountAlt - $newCustomers->amountAlt}} &euro; </td>
            <td> - </td>
        </tr>
        <tr class="">
            <td> [C] Numero nuovi soci</td>
            <td> {{ $newCustomers->numPeople }}</td>
            <td> {{ $newCustomers->amount }} &euro; </td>
            <td> - </td>
        </tr>
        <tr>
            <td> [C1] Numero nuovi soci quote regolari</td>
            <td> {{ $newCustomers->numRegular }}</td>
            <td> {{ $newCustomers->amountRegular }} &euro; </td>
            <td> - </td>
        </tr>
        <tr>
            <td> [C2] Numero nuovi soci quote alternative</td>
            <td> {{ $newCustomers->numAlt }}</td>
            <td> {{ $newCustomers->amountAlt }} &euro; </td>
            <td> - </td>
        </tr>
        <tr class="">
            <td>[D1] Numero soci quote regolari (rinnovi e nuovi)</td>
            <td> {{ $customers->numRegular  }}</td>
            <td> {{ $customers->amountRegular }} &euro;</td>
            <td> - </td>
        </tr>
        <tr class="">
            <td>[D2] Numero soci quote alternative (rinnovi e nuovi)</td>
            <td> {{ $customers->numAlt }}</td>
            <td> {{ $customers->amountAlt }} &euro;</td>
            <td> - </td>
        </tr>
        <tr class="">
            <td> [E] Numero soci morosi</td>
            <td> {{ $lateCustomers->numPeople }}</td>
            <td> - </td>
            <td> - </td>
        </tr>
        <tr>
            <td> [F] Numero morti post-tesseramento</td>
            <td>{{ $postDeceasedNumber }}</td>
            <td> - </td>
            <td> - </td>
        </tr>
        <tr class="">
            <td> [G] Numero soci totali (compresi morosi)</td>
            <td> {{ $customers->numPeople + $lateCustomers->numPeople - $postDeceasedNumber }}</td>
            <td> - </td>
            <td> - </td>
        </tr>
        <tr class="">
            <td> [H] Numero soci deceduti</td>
            <td> {{ $deceasedCustomers->total }}</td>
            <td> - </td>
            <td> {{ $totalFuneral }} &euro; </td>
        </tr>
        <tr>
            <td> [H1] Soci deceduti pre-tesseramento </td>
            <td> {{ $preDeceasedNumber }}</td>
            <td> - </td>
            <td> {{ $preTotalFuneral }} &euro; </td>
        </tr>
        @if($exceptions->pre > 0)
            <tr>
                <td> [H1.1] Soci deceduti pre-tesseramento con costo standard</td>
                <td> {{ $preDeceasedNumber - $exceptions->pre }}</td>
                <td> - </td>
                <td> {{ $preTotalFuneral - $exceptions->pre_cost }} &euro;</td>
            </tr>
            <tr>
                <td> [H1.2] Soci deceduti pre-tesseramento con costo diverso</td>
                <td> {{ $exceptions->pre }}</td>
                <td> - </td>
                <td> {{ $exceptions->pre_cost }} &euro;</td>
            </tr>
        @endif
        <tr>
            <td> [H2] Soci deceduti post-tesseramento </td>
            <td> {{ $postDeceasedNumber }}</td>
            <td> - </td>
            <td> {{ $postTotalFuneral }} &euro; </td>
        </tr>
        @if($exceptions->post > 0)
            <tr>
                <td> [H2.1] Soci deceduti post-tesseramento con costo standard</td>
                <td> {{ $postDeceasedNumber - $exceptions->post }}</td>
                <td> - </td>
                <td> {{ $postTotalFuneral - $exceptions->post_cost }} &euro;</td>
            </tr>
            <tr>
                <td> [H2.2] Soci deceduti post-tesseramento con costo diverso</td>
                <td> {{ $exceptions->post }}</td>
                <td> - </td>
                <td> {{ $exceptions->post_cost }} &euro;</td>
            </tr>
        @endif
        <tr class="">
            <td> [I] Numero soci revocati</td>
            <td> {{ $revocatedCustomers->numPeople }}</td>
            <td> - </td>
            <td> - </td>
        </tr>
        <tr class="">
            <td> [J] Offerte </td>
            <td> - </td>
            <td> {{ $offers }} &euro;</td>
            <td> - </td>
        </tr>
        <tr class="">
            <td> [K] Spese</td>
            <td> - </td>
            <td> - </td>
            <td> {{ $expenses}} &euro; </td>
        </tr>
        <tr class="">
            <td colspan="2"> <b>TOTALE:</b> </td>
            <td> <b> {{ $customers->amount + $offers }} &euro;</b></td>
            <td> <b> {{ $totalFuneral + $expenses }} &euro; </b></td>
        </tr>
    </tbody>
</table>
<table class="table table-hover table-sm">
    <thead>
    <tr>
        <th class="w-50">Legenda</th>
        <th class="w-25"></th>
        <th class="w-25"></th>
    </tr>
    </thead>
    <tbody>
    <tr class="">
        <td>Quota anno {{ $year }}</td>
        <td></td>
        <td>{{ $rates->quota }} &euro;</td>
    </tr>
    <tr class="">
        <td>Costo funerale {{ $year }}</td>
        <td></td>
        <td>{{ $rates->funeral_cost }} &euro;</td>
    </tr>
    <tr class="">
        <td>[G] = [A] + [E] - [F]</td>
        <td>
            {{ $customers->numPeople + $lateCustomers->numPeople - $postDeceasedNumber }} =
            {{ $customers->numPeople }} + {{ $lateCustomers->numPeople }} - {{ $postDeceasedNumber }}
        </td>
        <td></td>
    </tr>
    <tr class="">
        <td>[A] = [B] + [C]</td>
        <td>
            {{ $customers->numPeople }} =
            {{ $customers->numPeople - $newCustomers->numPeople }} + {{ $newCustomers->numPeople }}
        </td>
        <td>
            {{ $customers->amount }} &euro; = {{ $customers->amount - $newCustomers->amount }} &euro;
            + {{ $newCustomers->amount }} &euro;
        </td>
    </tr>
    <tr class="">
        <td>[B] = [B1] + [B2]</td>
        <td>
            {{ $customers->numPeople - $newCustomers->numPeople }} =
            {{ $customers->numRegular - $newCustomers->numRegular }} +
            {{ $customers->numAlt - $newCustomers->numAlt}}
        </td>
        <td>
            {{ $customers->amount - $newCustomers->amount }} &euro; =
            {{ $customers->amountRegular - $newCustomers->amountRegular }} &euro; +
            {{ $customers->amountAlt - $newCustomers->amountAlt}} &euro;
        </td>
    </tr>
    <tr>
        <td>[B1] = [Numero] * [Quota]</td>
        <td></td>
        <td>
            {{ $customers->amountRegular - $newCustomers->amountRegular }} &euro; =
            {{ $customers->numRegular - $newCustomers->numRegular }} * {{ $rates->quota }} &euro;
        </td>
    </tr>
    <tr class="">
        <td>[C] = [C1] + [C2]</td>
        <td>
            {{ $newCustomers->numPeople }} =
            {{ $newCustomers->numRegular }} +
            {{ $newCustomers->numAlt }}
        </td>
        <td>
            {{ $newCustomers->amount }} &euro; =
            {{ $newCustomers->amountRegular }} &euro; +
            {{ $newCustomers->amountAlt }} &euro;
        </td>
    </tr>
    <tr>
        <td>[C1] = [Numero] * [Quota]</td>
        <td></td>
        <td>
            {{ $newCustomers->amountRegular }} &euro; =
            {{ $newCustomers->numRegular}} * {{ $rates->quota }} &euro;
        </td>
    </tr>
    <tr class="">
        <td>[D1] = [B1] + [C1]</td>
        <td>
            {{ $customers->numRegular }} =
            {{ $customers->numRegular - $newCustomers->numRegular }} + {{ $newCustomers->numRegular }}
        </td>
        <td>
            {{ $customers->amountRegular }} &euro; =
            {{ $customers->amountRegular - $newCustomers->amountRegular}} &euro;
            + {{ $newCustomers->amountRegular }} &euro;
        </td>
    </tr>
    <tr class="">
        <td>[D2] = [B2] + [C2]</td>
        <td>
            {{ $customers->numAlt }} =
            {{ $customers->numAlt - $newCustomers->numAlt }} + {{ $newCustomers->numAlt }}
        </td>
        <td>
            {{ $customers->amountAlt }} &euro; =
            {{ $customers->amountAlt - $newCustomers->amountAlt }} &euro; +
            {{ $newCustomers->amountAlt }} &euro;
        </td>
    </tr>
    </tbody>
</table>
