@section('meta')
    <meta name="csrf_token" content="{{ csrf_token() }}" />
@endsection

@section('custom_assets')
    @vite(['resources/js/app/rates/exceptions/index.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
<main class="container">
    @include('common.errors')
    @include('common.status')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h6 class="pb-1 mb-0">LISTA ECCEZIONI</h6>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        @if (count($exceptions) > 0)
            <table class='table table-hover table-sm'>
                <thead>
                <tr>
                    <th>Anno</th>
                    <th>Socio</th>
                    <th>Costo Funerale</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($exceptions as $e)
                    <tr id='{{ $e->id }}'>
                        <td>
                            {{ $e->year }}
                        </td>
                        <td>
                            {{ $e->first_name }}  {{ $e->last_name }}
                        </td>
                        <td>
                            {{ $e->cost }} &euro;
                        </td>
                        <td class='table-column-fit'>
                            <button type='submit' class='btn btn-info btn-sm text-nowrap'
                                    onclick='edit("rates/exceptions", {{ $e->id }})'>
                                <i class='fa  fa-edit'></i> Modifica
                            </button>
                        </td>
                        <td class='table-column-fit'>
                            <button type='submit' class='btn btn-danger btn-sm text-nowrap'
                                    onclick='destroy("rates/exceptions", {{ $e->id }})'>
                                <i class='fa  fa-trash'></i> Elimina
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class='alert alert-success' role='alert'>
                Nessuna eccezione presente.
            </div>
        @endif
    </div>
</main>
@endsection
