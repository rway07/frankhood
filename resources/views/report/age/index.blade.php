@extends('layouts.app')
@section('content')
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<script src="/js/age/index.js" type="text/javascript"></script>
<div class="d-print-none card">
    <div class="card-header bg-secondary text-white">
        LISTA PER ETA'
    </div>
    <div class="card-body">
        <div class="col-sm-5 form-inline">
            <label class="col-form-label-sm" for="age">Età: </label>
            <input type="text" class="form-control form-control-sm mx-2" id="age" name="age" style="width: 30%">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-print"></i> CTRL+P per stampare
            </button>
        </div>
    </div>
</div>
<br>
<div id="container" class="horizontal_container">

</div>
@endsection
