@extends('layouts.app')
@section('content')
<script src="/js/closure/daily/index.js"></script>
<script src="/js/common/chartjs/Chart.min.js"></script>
<link href="/css/common/chartjs/Chart.min.css" rel="stylesheet" type="text/css">
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-secondary text-white">
        <div class="row">
            <div class="col-md-7">
                <h5> CHIUSURE GIORNALIERE PER L'ANNO <span id="title_year"></span></h5>
            </div>
            <div class="d-flex col-md-5 justify-content-end">
                <div class="form-inline d-print-none">
                    <div class="form-group mr-2">
                        <button id="show_graph" class="btn btn-info btn-sm">
                            <span id="show_graph_text">Mostra grafico nella stampa</span>
                        </button>
                    </div>
                    <div class="form-group mr-2">
                        <button id="show_extra" class="btn btn-info btn-sm">
                            <span id="show_extra_text">Nascondi sezione extra nella stampa</span>
                        </button>
                    </div>
                    <div class="btn-group-sm">
                        <select id="years" name="years" class="form-control custom-select-sm">
                            @foreach($years as $y)
                                <option value="{{ $y->year }}">{{ $y->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="data_container">

    </div>
</div>
@endsection
