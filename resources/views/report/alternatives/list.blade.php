<?php
    $count = 1;
    $total = 0;
    $totalPeople = 0;
    $regular = 0;
?>
<table class="table table-hover table-sm">
    <thead class="table-dark">
    <tr>
        <th> # </th>
        <th> Nr. Ricevuta</th>
        <th> Nome </th>
        <th> Data</th>
        <th> Numero Persone</th>
        <th> Quota Recupero</th>
        <th> Totale </th>
    </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $d->number }}</td>
                <td>{{ $d->first_name . ' ' . $d->last_name }}</td>
                <td>{{ strftime("%d/%m/%Y", strtotime($d->date)) }}</td>
                <td>{{ $d->num_people }}</td>
                <td>{{ $d->total - $d->quota }} &euro; </td>
                <td>{{ $d->total }} &euro; </td>
            </tr>
            <?php
                $count++;
                $regular += $d->quota;
                $total += $d->total;
                $totalPeople += $d->num_people;
            ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6"><b>TOTALE REGOLARE:</b></td>
            <td>{{ $regular }} &euro;</td>
        </tr>
        <tr>
            <td colspan="6"><b>TOTALE RECUPERO:</b></td>
            <td>{{ $total - $regular }} &euro;</td>
        </tr>
        <tr>
            <td colspan="4"><b>TOTALE:</b></td>
            <td>{{ $totalPeople }}</td>
            <td></td>
            <td>{{ $total }} &euro;</td>
        </tr>
    </tfoot>
</table>
