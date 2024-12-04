@section('custom_assets')
    @include('include.datatables')
    @vite(['resources/js/app/customers/list.js', 'resources/css/tables.css'])
@endsection

@extends('layouts.app')
@section('content')
@include('customers.util.info')
<main class="container-fluid">
    @include('common.errors')
    @include('common.status')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row">
            <div class="col-md-9">
                <h5 class="pb-2 mb-0">LISTA SOCI</h5>
            </div>
            <div class="col-md-3 d-flex flex-lg-row flex-column justify-content-end">
                <button type="button" class="btn btn-sm btn-warning text-nowrap w-auto"
                        onclick="window.open('/customers/create', '_self')">
                    <i class="fa fa-plus"></i> Nuovo Socio
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <h6>
                    Legenda:
                    <span class="badge text-bg-warning">Soci Revocati</span>
                    <span class="badge text-bg-danger">Soci Deceduti</span>
                </h6>
            </div>
        </div>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <table class="table table-sm table-hover" id="customers_table">
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
