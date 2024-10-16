<div class="card">
    <div class="card-header bg-secondary text-white">
        LISTA SOCI OVER {{ $age }}
    </div>
    <div class="card-body">
        <table class="table table-condensed table-sm" id="customers_table">
            <thead class="thead-dark">
                <th></th>
                <th> Nome </th>
                <th> Anno nascita </th>
                <th> Età </th>
                <th> Comune </th>
                <th> Telefono </th>
            </thead>
            <tbody>
            @foreach($data as $index=>$d)
                <tr>
                    <td> {{ $index+1 }}</td>
                    <td> {{ $d->last_name }} {{ $d->first_name }}</td>
                    <td> {{ $d->birth_year}}</td>
                    <td> {{ date('Y') - $d->birth_year }}</td>
                    <td> {{ $d->municipality}}</td>
                    <td>
                        {{ $d->phone }}
                        {{ (($d->phone != null) && ($d->mobile_phone != null))? "-" : "" }}
                        {{ $d->mobile_phone }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
