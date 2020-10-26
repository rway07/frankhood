@extends('layouts.app')
@section('content')
<script src="/js/closure/daily/index.js"></script>
<script src="/js/common/chartjs/Chart.min.js"></script>
<link href="/css/common/chartjs/Chart.min.css" rel="stylesheet" type="text/css">
<link href="/css/print.css" rel="stylesheet" type="text/css">
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="d-print-none">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            CHIUSURE GIORNALIERE
        </div>
        <div class="card-body">
            @include('common.errors')
            <div class="form-inline">
                <div class="form-group mr-4">
                    <label for="years" class="col-form-label-sm mr-2"> Selezionare anno </label>
                    <select id="years" name="years" class="form-control custom-select-sm">
                        @foreach($years as $y)
                            <option value="{{ $y->year }}">{{ $y->year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button id="show_graph" class="btn btn-info btn-sm">
                        <span id="show_graph_text">Mostra grafico nella stampa</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div id="data_container">

</div>
@endsection