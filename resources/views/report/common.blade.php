@extends('layouts.app')
@section('content')
<script src="/js/report/{{ $script_prefix }}/index.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<main class="container-fluid">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row pb-1">
            <div class="col-md-8">
                <h5 id="report_title" class="pb-1 mb-0">{{ $title }} <span id="title_year"></span> </h5>
            </div>
            <div class="col-md-4 d-flex justify-content-end d-print-none gap-1">
                <select id="years" name="years" class="form-control form-control-sm form-select form-select-sm w-auto">
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row pb-3">
            <h6> Numero: <span id="num_people_title">??</span></h6>
        </div>
        <div class="row pt-2">
            <div id="data_container" class="col-md">

            </div>
        </div>
    </div>
</main>
@endsection
