@extends('layouts.app')
@section('content')
<script src="/js/report/customersYearly/index.js"></script>
<link rel="stylesheet" href="/css/yearly/list.css">
<div id="custom_style">

</div>
<div class="d-print-none">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            LISTA SOCI CORRENTI PER ANNO
        </div>
        <div class="card-body">
            @include('common.errors')
            <div class="form-inline">
                <div class="form-group col-sm-3">
                    <label for="years" class="col-form-label mr-2"> Selezionare anno </label>
                    <select id="years" name="years" class="form-control custom-select-sm">
                        @foreach($years as $y)
                            <option value="{{ $y->year }}">{{ $y->year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-sm">
                    <button id="loading_button" class="btn btn-danger btn-sm float-right">
                        <span id="loading_text">Caricamento...</span>
                        <i id="loading_icon" class="fa fa-cog fa-spin fa-1x fa-fw"></i>
                    </button>
                    <button id="late_button" class="btn btn-warning btn-sm">
                        <span id="late_text">Mostra morosi</span>
                    </button>
                    <button id="style_button" class="btn btn-info btn-sm">
                        <span id="style_text">Senza margini</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div id="data_container" class="container-fluid">

</div>
@endsection
