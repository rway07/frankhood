@extends('layouts.app')
@section('content')
<script src="/js/closure/yearly/index.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">

<div class="card">
    <div class="card-header bg-secondary text-white">
        <div class="row">
            <div class="col-md-8">
                <h5> CHIUSURA ANNUALE PER L'ANNO <span id="title_year"></span></h5>
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
    </div>
    <div class="card-body">
        <div id="data_container">

        </div>
    </div>
</div>
@endsection
