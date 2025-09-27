<table class="table table-sm table-hover">
    <thead class="table-dark">
    <tr>
        <th class="col-md-1"> Periodo </th>
        <th class="col-md-2"> Nome </th>
        <th class="col-md-2"> Voti Priore</th>
        <th class="col-md-2"> Voti Totali</th>
        <th class="col-md-2"> Percentuale voti</th>
    </tr>
    </thead>
    <tbody>
    @foreach($priori as $d)

        <tr>
            <td>
                @if($d->election_year == null)
                    --
                @else
                    {{ $d->election_year . '/' . $d->election_year + 1}}
                @endif
            </td>
            <td>
                {{ $d->first_name . ' ' . $d->last_name }}
                <a href="/customers/{{ $d->id }}/edit" target="_blank"><i class="fa fa-pencil d-print-none"></i></a>
            </td>
            <td>{{ ($d->votes == 0) ? '--' : $d->votes }}</td>
            <td>{{ ($d->total_votes == 0) ? '--' : $d->total_votes }}</td>
            <td>
                @if($d->votes == 0)
                    --
                @else
                    {{ number_format((($d->votes * 100) / $d->total_votes), 2, ',') }} %
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
