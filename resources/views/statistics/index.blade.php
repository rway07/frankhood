@section('custom_assets')
    @vite(['resources/js/app/statistics/index.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
    <main class="container-fluid">
        @include('common.errors')
        @include('common.status')
        <input id="section" name="section" type="hidden" value="{{ $section }}">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="row pb-2">
                <div class="col-md-8">
                    <h5 id="statistic_title" class="pb-1 mb-0">{{ $title }}</h5>
                </div>
                <div class="col-md-4 d-flex justify-content-end d-print-none gap-1">

                </div>
            </div>
            <div class="row pt-2">
                <div id="data_container" class="col-md">

                </div>
            </div>
        </div>
    </main>
@endsection
