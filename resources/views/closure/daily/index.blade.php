@section('custom_assets')
    @vite(['resources/js/chart.js', 'resources/js/app/closure/daily/index.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
<main class="container-fluid">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row pb-3 border-bottom align-items-center">
            <div class="col-md-8">
                <h5 class="pb-1 mb-0"> CHIUSURE GIORNALIERE PER L'ANNO <span id="title_year"></span></h5>
            </div>
            <div class="col-md-4 d-flex flex-lg-row flex-column justify-content-end d-print-none gap-1">
                <button id="show_graph" class="btn btn-danger btn-sm text-nowrap">
                    <span id="show_graph_text">Nascondi grafico nella stampa</span>
                </button>
                <button id="show_extra" class="btn btn-success btn-sm text-nowrap">
                    <span id="show_extra_text">Mostra sezione extra nella stampa</span>
                </button>
                <select id="years" name="years" class="form-control form-control-sm form-select form-select-sm w-auto">
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row pt-3">
            <div id="data_container" class="col-md">

            </div>
        </div>
    </div>
</main>
@endsection
