@extends('layouts.app')
@section('content')
@include('include.toastr')
@include('include.datatables')
@include('customers.util.info')
<script type="text/javascript" src="/js/customers/list.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-secondary text-white">
        LISTA SOCI
    </div>
    @include('common.errors')
    @include('common.status')
    <div class="card-body">
        <h6>
            Legenda:
            <span class="badge badge-warning">Soci Revocati</span>
            <span class="badge badge-danger">Soci Deceduti</span>
        </h6>
        <hr>
        <table class="table table-sm" id="customers_table">
            <thead class="thead-dark">
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
</div>
@endsection
