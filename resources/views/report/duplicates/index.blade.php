@section('custom_assets')
    @vite(['resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
<main class="container-fluid">
    @include('common.errors')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row pb-3">
            <h5 class="pb-1 mb-0"> LISTA DUPLICATI </h5>
        </div>
        <div class="row pb-3">
            @if(count($list) > 0)
                @foreach($list as $l)
                    <table class="table table-hover table-sm">
                        <thead class="table-dark">
                        <tr>
                            <th class="col-md-8">{{ $l['year'] }}</th>
                            <th class="col-md-2"></th>
                            <th class="col-md-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="header">
                            <td> Socio </td>
                            <td> Numero tessera </td>
                            <td> Numero duplicati </td>
                        </tr>
                        @foreach($l['dup'] as $dup)
                            <tr>
                                <td> {{ $dup->alias }}</td>
                                <td> {{ $dup->customers_id }} </td>
                                <td> {{ $dup->count }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
            @else
                <div class="alert alert-success" role="alert">
                    Nessun duplicato trovato.
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
