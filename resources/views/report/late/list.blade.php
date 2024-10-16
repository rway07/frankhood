<?php
$count = 1;
$i = 0;
?>
<table class="table table-condensed table-sm">
    <thead class="thead-dark">
    <tr>
        <th></th>
        <th> Nome </th>
        <th> Data Nascita </th>
        <th> Numero di telefono</th>
        <th> Anno Iscrizione</th>
        <th> Ultima Ricevuta</th>
    </tr>
    </thead>
    <tbody>
    @foreach($late as $n)
        <tr>
            <td>{{ $count }}</td>
            <td>{{ $n->last_name . ' ' . $n->first_name . ' (' . $n->alias . ')' }}</td>
            <td>{{ strftime("%d/%m/%Y", strtotime($n->birth_date)) }}</td>
            <td>
                @if($n->phone != "")
                    {{$n->phone}} -
                @endif
                @if($n->mobile_phone != "")
                    {{ $n->mobile_phone }}
                @endif

            </td>
            <td>{{ $n->enrollment_year }}</td>
            <td>
                @if($last[$i]['number'] != null)
                    {{ $last[$i]['number'] . '/' . $last[$i]['year'] }}
                @else
                    -
                @endif
            </td>
        </tr>
        <?php
        $count++;
        $i++;
        ?>
    @endforeach
    </tbody>
</table>
