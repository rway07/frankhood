@extends('layouts.app')
@section('content')
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<script src="/js/age/index.js" type="text/javascript"></script>
<main class="container-fluid">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row pb-2 border-bottom d-print-none">
            <div class="col-md-11">
                <h5 class="pb-1 mb-0 float-start">LISTA PER ETA'</h5>
            </div>
            <div class="col-md-1">
                <input type="text" class="form-control form-control-sm float-end"
                       placeholder="Età" id="age" name="age">
            </div>
        </div>
        <div class="row pt-3 pb-3">
            <div id="container">

            </div>
        </div>
    </div>
</main>
@endsection
