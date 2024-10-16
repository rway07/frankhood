@extends('layouts.app')
@section('content')
@include('include.toastr')
@include('include.datatables')
@include('receipts.util.info')
<meta name="csrf_token" content="{{ csrf_token() }}" />
<script src="/js/common/util.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/receipts/list.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header text-white bg-secondary clearfix">
        <h5> LISTA RICEVUTE </h5>
        <br>
        <div class="btn-group float-left">
            <button type='button' class='btn btn-warning btn-sm' onclick='window.open("/receipts/create", "_self");'>
                <i class='fa fa-btn fa-plus'></i> Nuova Ricevuta
            </button>
        </div>
        <div class="btn-group-sm float-right">
            <select id="payment_types" name="payment_types" class="form-control custom-select-sm">
                <option value="0" selected>Tutti i pagamenti</option>
                @foreach($types as $t)
                    <option value="{{ $t->id }}">{{ $t->description }}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-group-sm float-right">
            <select id="years" name="years" class="form-control custom-select-sm">
                <option value="0" selected>Tutti gli anni</option>
                @foreach($years as $y)
                    <option value="{{ $y->year }}">{{ $y->year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @include('common.errors')
    @include('common.status')
    <div class="card-body">
        <table class="table table-striped table-sm" id="receipts_table">
            <thead class="thead-dark">
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
</div>
@endsection
