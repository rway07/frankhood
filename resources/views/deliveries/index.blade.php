@section('meta')
    <meta name="csrf_token" content="{{ csrf_token() }}" />
@endsection

@section('custom_assets')
    @vite(['resources/js/app/deliveries/index.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
<main class="container-fluid">
    @include('common.errors')
    @include('common.status')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row pb-1">
            <div class="col-md-8">
                <h5 id="report_title" class="pb-1 mb-0">LISTA CONSEGNE CONTANTI <span id="title_year"></span> </h5>
            </div>
            <div class="col-md-4 d-flex justify-content-end d-print-none gap-1">
                <button id="new-delivery" class="btn btn-sm btn-warning text-nowrap"
                        onclick="window.open('/deliveries/create', '_self')">
                    <i class='fa fa-plus'></i> Nuova Consegna
                </button>
                <button id="delete-all" class="btn btn-sm btn-danger text-nowrap">
                    Elimina tutto
                </button>
                <button id="delete-last" class="btn btn-sm btn-warning text-nowrap">
                    Elimina ultima
                </button>
                <select id="years" name="years" class="form-control form-control-sm form-select form-select-sm w-auto">
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row pb-3">
            <h6>Totale consegnato: &euro; <span id="totalAmount">??</span></h6>
        </div>
        <div class="row pt-2">
            <div id="data_container" class="col-md">

            </div>
        </div>
    </div>
</main>
@endsection
