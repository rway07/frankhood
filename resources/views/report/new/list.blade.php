<?php
    $count = 1;
    $total = 0;
?>
<table class="table table-condensed table-sm" id="customers_table">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th> Nome </th>
            <th> Data Nascita </th>
            <th> Nr. Ricevuta </th>
            <th> Quota </th>
            <th> Telefono</th>
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
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="5"><b>TOTALE:</b></td>
            <td>{{ $total }} &euro;</td>
        </tr>
    </tbody>
</table>
