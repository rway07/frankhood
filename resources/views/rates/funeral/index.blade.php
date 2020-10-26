@extends('layouts.app')
@section('content')
<meta name="csrf_token" content="{{ csrf_token() }}" />
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/js/rates/exceptions/index.js"></script>
<div class="card">
    <div class="card-header bg-secondary text-white">
        LISTA ECCEZIONI
    </div>
    <div id="error_div">
        @include('common.errors')
    </div>
    <div class="card-body">
        @if (count($exceptions) > 0)
            <table class="table table-condensed table-sm">
                <thead>
                    <th>Anno</th>
                    <th>Socio</th>
                    <th>Costo Funerale</th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                @foreach ($exceptions as $e)
                    <tr id="{{ $e->id }}">
                        <td>
                            {{ $e->year }}
                        </td>
                        <td>
                            {{ $e->first_name }}  {{ $e->last_name }}
                        </td>
                        <td>
                            {{ $e->cost }} &euro;
                        </td>
                        <td class="fit">
                            <button type="submit" class="btn btn-info btn-sm" onclick="edit({{ $e->id }})">
                                <i class="fa fa-btn fa-edit"></i> Modifica
                            </button>
                        </td>
                        <td class="fit">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="destroy({{ $e->id }})">
                                <i class="fa fa-btn fa-trash"></i> Elimina
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-success" role="alert">
                Nessuna eccezione presente.
            </div>
        @endif
    </div>
</div>
@endsection