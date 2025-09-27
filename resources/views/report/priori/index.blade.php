@section('custom_assets')
    @vite(['resources/js/chart.js', 'resources/js/app/report/priori/index.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
    <main class="container-fluid">
        @include('common.errors')
        @include('common.status')
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="row pb-2">
                <div class="col-md-8">
                    <h5 class="pb-1 mb-0">LISTA PRIORI</h5>
                </div>
                <div class="col-md-4 d-flex justify-content-end d-print-none gap-1">

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
