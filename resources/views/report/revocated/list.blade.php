<?php $count = 1; ?>
<table class="table table-hover table-sm">
    <thead class="table-dark">
    <tr>
        <th class="col-md-1"> # </th>
        <th class="col-md-7"> Nome </th>
        <th class="col-md-2"> Data Nascita </th>
        <th class="col-md-2"> Numero tessera </th>
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
