@extends('layouts.app')
@section('content')
<meta name="csrf_token" content="{{ csrf_token() }}" />
@include('include.toastr')
@include('include.datatables')
<script type="text/javascript" src="/js/offers/list.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-secondary text-white clearfix">
        LISTA OFFERTE
        <br><br>
        <div class="btn-group float-left">
            <button type='button' class='btn btn-warning btn-sm' onclick='window.open("/offers/create", "_self");'>
                <i class='fa fa-btn fa-plus'></i> Nuova Offerta
            </button>
        </div>
        <div class="btn-group float-right">
            <select id="years" name="years" class="form-control form-control-sm">
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
        <table class="table table-condensed table-sm" id="offers_table">
            <thead class="thead-dark">
                <tr>
                    <th> Data </th>
                    <th> Descrizione </th>
                    <th> Totale </th>
                    <th> </th>
                    <th> </th>
                    <th> </th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
