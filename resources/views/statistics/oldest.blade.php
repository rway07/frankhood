<table class="table table-sm table-hover">
    <thead class="table-dark">
    <tr>
        <th class="col-md-1"> #</th>
        <th class="col-md-5"> Nome </th>
        <th class="col-md-3"> Data di nascita </th>
        <th class="col-md-3"> Età</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
        <tr>
            <td>{{ $d->row_number }}</td>
            <td>{{ $d->first_name . ' ' . $d->last_name }}</td>
            <td>{{ date('d/m/Y', strtotime($d->birth_date)) }}</td>
            <td>{{ $d->age }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
