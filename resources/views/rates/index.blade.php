@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/rates/index.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="">
    @if (count($rates) > 0)
        <div class="card">
            <div class="card-header bg-secondary text-white">
                TARIFFE CORRENTI
            </div>
            <div class="card-body">
                <table class="table table-condensed table-sm">
                    <thead class="thead-dark">
                        <th></th>
                        <th>Anno</th>
                        <th>Quota</th>
                        <th>Costo Funerale</th>
                        <th></th>
                    </thead>
                    <tbody>
                    @foreach ($rates as $rate)
                        <tr>
                            <td></td>
                            <td>
                                {{ $rate->year }}
                            </td>
                            <td>
                                 {{ $rate->quota }} &euro;
                            </td>
                            <td>
                                {{ $rate->funeral_cost }} &euro;
                            </td>
                            <td class="fit">
                                <button type="submit" class="btn btn-info btn-sm" onclick="edit({{ $rate->id }})">
                                    <i class="fa fa-btn fa-edit"></i> Modifica
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection