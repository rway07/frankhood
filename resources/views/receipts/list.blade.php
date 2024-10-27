@extends('layouts.app')
@section('content')
@include('include.datatables')
@include('receipts.util.info')
<meta name="csrf_token" content="{{ csrf_token() }}" />
<script type="text/javascript" src="/js/receipts/list.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<main class="container-fluid">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row mb-2 align-items-center">
            <div class="col-md-6">
                <h5 class="pb-1 mb-0"> LISTA RICEVUTE </h5>
            </div>
            <div class="col-md-6 d-flex flex-lg-row flex-column justify-content-end gap-1">
                <button type='button' class='btn btn-warning btn-sm text-nowrap' onclick='window.open("/receipts/create", "_self");'>
                    <i class='fa fa-btn fa-plus'></i> Nuova Ricevuta
                </button>
                <select id="payment_types" name="payment_types" class="form-control form-control-sm form-select form-select-sm w-auto">
                    <option value="0" selected>Tutti i pagamenti</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}">{{ $t->description }}</option>
                    @endforeach
                </select>
                <select id="years" name="years" class="form-control form-control-sm form-select form-select-sm w-auto">
                    <option value="0" selected>Tutti gli anni</option>
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-2">
            @include('common.errors')
            @include('common.status')
        </div>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <table class="table table-striped table-hover table-sm" id="receipts_table">
            <thead class="table-dark">
            <tr>
                <th> Numero </th>
                <th> Data Emissione </th>
                <th> Nome </th>
                <th> Anno </th>
                <th> Totale </th>
                <th> Pagato </th>
                <th> </th>
                <th> </th>
                <th> </th>
                <th> </th>
            </tr>
            </thead>
        </table>
    </div>
</main>
@endsection
