@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header bg-secondary text-white">
        RICEVUTA
    </div>

    <div class="card-body">
        <h4> Ricevuta emessa!</h4>
        <button type='button' class='btn btn-warning btn-sm' onclick='window.open("/receipts/create", "_self");'>
            <i class='fa fa-btn fa-plus'></i> Nuova Ricevuta
        </button>
        <button type='button' class='btn btn-warning btn-sm' onclick='window.open("/receipts/index", "_self");'>
            <i class='fa fa-btn fa-list'></i> Lista Ricevuta
        </button>
    </div>
</div>
@endsection