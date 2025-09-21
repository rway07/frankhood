<table class="table table-sm table-hover">
    <thead class="table-dark">
    <tr>
        <th class="col-md-3"> Anno </th>
        <th class="col-md-3"> Morti </th>
        <th class="col-md-3"> Nuovi </th>
        <th class="col-md-3"> Revocati</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
        <tr>
            <td>{{ $d->year }}</td>
            <td>{{ $d->deceased_number }}</td>
            <td>{{ $d->enrolled_number }}</td>
            <td>{{ $d->revocated_number }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
