@php
    $count = 1;
    $total = 0;
@endphp
<table class="table table-hover table-sm" id="customers_table">
    <thead class="table-dark">
        <tr>
            <th class="col-md-1"> # </th>
            <th class="col-md-5"> Nome </th>
            <th class="col-md-1"> Data Nascita </th>
            <th class="col-md-1"> Nr. Ricevuta </th>
            <th class="col-md-3"> Telefono </th>
            <th class="col-md-1"> Quota</th>
        </tr>
    </thead>
    <tbody>
        @foreach($new_customers as $n)
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $n->first_name . ' ' . $n->last_name }}</td>
                <td>{{ strftime("%d/%m/%Y", strtotime($n->birth_date)) }}</td>
                <td>{{ $n->number }}</td>
                <td>
                    <?php
                        echo ($n->phone != "" ? $n->phone : "*");
                        echo " - ";
                        echo ($n->mobile_phone != "" ? $n->mobile_phone : "*");
                    ?>
                </td>
                <td>{{ $n->quota }} &euro; </td>
            </tr>
            <?php
                $count++;
                $total += $n->quota;
            ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5"><b>TOTALE:</b></td>
            <td>{{ $total }} &euro;</td>
        </tr>
    </tfoot>
</table>
