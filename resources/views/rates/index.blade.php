@section('custom_assets')
    @vite(['resources/js/app/rates/index.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
<main class="container">
    @include('common.errors')
    @include('common.status')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h6 class="pb-1 mb-0"> TARIFFE CORRENTI</h6>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <table class='table table-hover table-sm'>
            <thead class='table-dark'>
            <tr>
                <th class="col-md-4">Anno</th>
                <th class="col-md-3">Quota</th>
                <th class="col-md-3">Costo Funerale</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if (count($rates) > 0)
                @foreach ($rates as $rate)
                    <tr>
                        <td>
                            {{ $rate->year }}
                        </td>
                        <td>
                            {{ $rate->quota }} &euro;
                        </td>
                        <td>
                            {{ $rate->funeral_cost }} &euro;
                        </td>
                        <td class='table-column-fit'>
                            <button type='submit' class='btn btn-info btn-sm'
                                    onclick='edit("rates", {{ $rate->id }})'>
                                <i class='fa  fa-edit'></i> Modifica
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
</main>
@endsection
