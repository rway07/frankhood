<?php
    $count = 1;
?>
<table class="table table-condensed table-sm">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th> Nome </th>
            <th> Data Nascita </th>
            <th> Data Decesso </th>
            <th> Numero tessera </th>
            <th> Anno Iscrizione </th>
            <th> Costo </th>
        </tr>
    </thead>
    <tbody>
        @foreach($deceased as $n)
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $n->first_name . ' ' . $n->last_name }}</td>
                <td>{{ strftime("%d/%m/%Y", strtotime($n->birth_date)) }}</td>
                <td>{{ strftime("%d/%m/%Y", strtotime($n->death_date))}}</td>
                <td>{{ $n->id }}</td>
                <td>{{ $n->enrollment_year }}</td>
                <td>
                    @if($n->cost != 0)
                        {{ $n->cost }} &euro;
                    @else
                        {{ $funeralCost }} &euro;
                    @endif
                </td>
            </tr>
            <?php $count++; ?>
        @endforeach
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"> <b>TOTALE:</b> </td>
            <td>{{ $total }} &euro; </td>
        </tr>
    </tbody>
</table>
