@extends('layouts.app')
@section('content')
@include('include.toastr')
<link href='/css/tables.css' rel='stylesheet' type='text/css'>
<script type='text/javascript' src='/js/rates/index.js'></script>
<div class='card'>
    <div class='card-header bg-secondary text-white'>
        TARIFFE CORRENTI
    </div>
    <div class='card-body'>
        @include('common.errors')
        @include('common.status')
        <table class='table table-condensed table-sm'>
            <thead class='thead-dark'>
                <th></th>
                <th>Anno</th>
                <th>Quota</th>
                <th>Costo Funerale</th>
                <th></th>
            </thead>
            <tbody>
                @if (count($rates) > 0)
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
                            <td class='fit'>
                                <button type='submit' class='btn btn-info btn-sm'
                                        onclick='edit("rates", {{ $rate->id }})'>
                                    <i class='fa fa-btn fa-edit'></i> Modifica
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <div class='alert alert-success' role='alert'>
                        Nessuna rata presente.
                    </div>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
