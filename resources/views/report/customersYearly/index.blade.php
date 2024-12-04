@section('custom_assets')
    @vite(['resources/js/app/report/customersYearly/index.js', 'resources/css/yearly.css'])
@endsection

@extends('layouts.app')
@section('content')
<div id="custom_style">

</div>
<main class="container-fluid">
    @include('common.errors')
    <div class="my-3 p-3 bg-body rounded shadow-sm d-print-none">
        <div class="row pb-1">
            <div class="col-md-6 align-items-center">
                <h5 class="pt-1 mb-0">LISTA SOCI CORRENTI PER ANNO</h5>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-1">
                <button id="late_button" class="btn btn-success btn-sm text-nowrap">
                    <span id="late_text">Mostra morosi</span>
                </button>
                <button id="style_button" class="btn btn-danger btn-sm text-nowrap">
                    <span id="style_text">Senza margini</span>
                </button>
                <button id="loading_button" class="btn btn-danger btn-sm text-nowrap">
                    <span id="loading_text" role="status">Caricamento...</span>
                    <span id="loading_icon" class="spinner-border spinner-border-sm"></span>
                </button>
                <select id="years" name="years" class="form-control form-control-sm form-select form-select-sm w-auto">
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div id="data_container" class="container-fluid pt-3">

    </div>
</main>
@endsection
