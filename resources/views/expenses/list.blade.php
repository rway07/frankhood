@extends('layouts.app')
@section('content')
@include('include.datatables')
<script type="text/javascript" src="/js/expenses/list.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<meta name="csrf_token" content="{{ csrf_token() }}" />
<main class="container-fluid">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="pb-1 mb-0"> LISTA SPESE </h5>
            </div>
            <div class="col-md-4 d-flex flex-lg-row flex-column justify-content-end gap-1">
                <button type='button' class='btn btn-warning btn-sm text-nowrap' onclick='window.open("/expenses/create", "_self");'>
                    <i class='fa fa-btn fa-plus'></i> Nuova Spesa
                </button>
                <select id="years" name="years" class="form-control form-control-sm form-select form-select-sm w-auto">
                    <option value="0" selected>Tutti gli anni</option>
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            @include('common.errors')
            @include('common.status')
        </div>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <table class="table table-hover table-sm" id="expenses_table">
            <thead class="table-dark">
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
</main>
@endsection
