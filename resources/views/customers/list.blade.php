@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/customers/list.js"></script>
<script type="text/javascript" src="/js/common/util.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 <div class="card">
    <div class="card-header bg-secondary text-white">
        LISTA SOCI
    </div>
    @include('common.errors')
    @if(session('status'))
        <input id="status" type="hidden" value="{{ session('status') }}">
    @else
        <input id="status" type="hidden" value="idle">
    @endif
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
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
