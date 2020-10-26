@extends('layouts.app')
@section('content')
<script src="/js/closure/yearly/index.js"></script>
<link href="/css/print.css" rel="stylesheet" type="text/css">
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="d-print-none">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            CHIUSURA ANNUALE
        </div>
        <div class="card-body">
            @include('common.errors')
            <div class="form-horizontal form-group">
                <label class="col-sm-2 col-form-label-sm"> Selezionare anno </label>
                <div class="col-sm-2">
                    <select id="years" name="years" class="custom-select-sm form-control">
                        @foreach($years as $y)
                            <option value="{{ $y->year }}">{{ $y->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div id="data_container">

</div>
@endsection