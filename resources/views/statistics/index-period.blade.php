@section('custom_assets')
    @vite(['resources/js/chart.js', 'resources/js/app/statistics/' . $section . '.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
    <main class="container-fluid">
        <input id="section" name="section" type="hidden" value="{{ $section }}">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="row pb-1">
                <div class="col-md-10">
                    <h5 id="report_title" class="pb-1 mb-0">{{ $title }} </h5>
                </div>
                <div class="col-md-2 d-flex justify-content-end d-print-none gap-1">
                    <input type="date" id="cutoff_date" name="cutoff_date" class="form-control form-control-sm">
                </div>
            </div>

            <div class="row pt-2">
                <div id="data_container" class="col-md">

                </div>
            </div>
            <div class="row pt-2">
                <div id="chart_container" class="col chart-container">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </main>
@endsection
