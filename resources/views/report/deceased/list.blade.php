<?php
    $count = 1;
?>
<table class="table table-sm table-hover">
    <thead class="table-dark">
        <tr>
            <th class="col-md-1"> # </th>
            <th class="col-md-4"> Nome </th>
            <th class="col-md-1"> Data Nascita </th>
            <th class="col-md-1"> Data Decesso </th>
            <th class="col-md-1"> Numero tessera </th>
            <th class="col-md-1"> Anno Iscrizione </th>
            <th class="col-md-1"> Costo </th>
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
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6"> <b>TOTALE:</b> </td>
            <td>{{ $total }} &euro; </td>
        </tr>
    </tfoot>
</table>
