@extends('layouts.app')
@section('content')
<script src="/js/report/late/index.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-secondary text-white">
        LISTA DUPLICATI
    </div>
    <div class="card-body">
        @include('common.errors')
        @if(count($list) > 0)
            @foreach($list as $l)
                <table class="table table-condensed table-sm">
                    <thead class="thead-dark">
                        <th>{{ $l['year'] }}</th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tr class="header">
                        <td> Numero tessera </td>
                        <td> Socio </td>
                        <td> Numero duplicati </td>
                    </tr>
                    @foreach($l['dup'] as $dup)
                        <tr>
                            <td> {{ $dup->customers_id }} </td>
                            <td> {{ $dup->alias }}</td>
                            <td> {{ $dup->count }} </td>
                        </tr>
                    @endforeach
                </table>
            @endforeach
        @else
            <div class="alert alert-success" role="alert">
                Nessun duplicato trovato.
            </div>
        @endif
    </div>
</div>
@endsection