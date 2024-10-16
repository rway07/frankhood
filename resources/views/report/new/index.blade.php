@extends('layouts.app')
@section('content')
<script src="/js/report/new/index.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-secondary text-white">
        <div class="row">
            <div class="col-md-8">
                <h5> LISTA NUOVI SOCI PER L'ANNO <span id="title_year"></span> </h5>
            </div>
            <div class="col-md-4">
                <div class="btn-group-sm float-right d-print-none">
                    <select id="years" name="years" class="form-control custom-select-sm">
                        @foreach($years as $y)
                            <option value="{{ $y->year }}">{{ $y->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <h6> Numero: <span id="num_people_title">??</span></h6>
    </div>
    <?php
        $count = 1;
        $total = 0;
    ?>
    <div class="card-body">
        <div id="data_container">

        </div>
    </div>
</div>
@endsection
