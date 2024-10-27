@extends('layouts.app')
@section('content')
@include('include.datatables')
@include('customers.util.info')
<script type="text/javascript" src="/js/customers/list.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<main class="container-fluid">
    @include('common.errors')
    @include('common.status')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row">
            <div class="col-md">
                <h5 class="pb-2 mb-0">LISTA SOCI</h5>
            </div>
        </div>
        <div class="row">
            <h6>
                Legenda:
                <span class="badge text-bg-warning">Soci Revocati</span>
                <span class="badge text-bg-danger">Soci Deceduti</span>
            </h6>
        </div>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <table class="table table-sm" id="customers_table">
            <thead class="table-dark">
            <tr>
                <th> Nr. </th>
                <th> Nome </th>
                <th> Alias </th>
                <th> Data Nascita </th>
                <th> Anno Iscrizione</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
        </table>
    </div>
</main>
@endsection
