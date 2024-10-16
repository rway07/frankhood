<?php $count = 1; ?>
<table class="table table-condensed table-sm">
    <thead class="thead-dark">
    <tr>
        <th></th>
        <th> Nome </th>
        <th> Data Nascita </th>
        <th> Numero tessera </th>
    </tr>
    </thead>
    <tbody>
    @foreach($revocated as $n)
        <tr>
            <td>{{ $count }}</td>
            <td>{{ $n->first_name . ' ' . $n->last_name }}</td>
            <td>{{ strftime("%d/%m/%Y", strtotime($n->birth_date)) }}</td>
            <td>{{ $n->id }}</td>
        </tr>
        <?php $count++; ?>
    @endforeach
    </tbody>
</table>
