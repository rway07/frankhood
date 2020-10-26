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
        </tr>
    </thead>
    <tbody>
        @foreach($new_customers as $n)
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $n->first_name . ' ' . $n->last_name }}</td>
                <td>{{ strftime("%d/%m/%Y", strtotime($n->birth_date)) }}</td>
                <td>{{ $n->number }}</td>
                <td>{{ $n->quota }} &euro; </td>
            </tr>
            <?php
                $count++;
                $total += $n->quota;
            ?>
        @endforeach
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="4"><b>TOTALE:</b></td>
            <td>{{ $total }} &euro;</td>
        </tr>
    </tbody>
</table>
