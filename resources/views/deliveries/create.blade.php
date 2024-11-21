@section('custom_assets')
    @include('include.validate')
    <script type="text/javascript" src="{{ mix('/js/app/deliveries/save.js') }}"></script>
@endsection

@extends('layouts.app')
@section('content')
<main class="container">
    @include('common.errors')
    <form id="save_delivery_form" action="/deliveries/store" method="post">
        @csrf

        <div class="bg-body p-3 my-3 rounded shadow-sm">
            <div class="row pb-1">
                <h5 class="pb-1 mb-0">NUOVA CONSEGNA CONTANTI</h5>
            </div>
            @if($lastDate != '')
                <div class="row pb-1">
                    <h6> Data ultima consegna: {{ strftime("%d/%m/%Y", strtotime($lastDate)) }}</h6>
                </div>
            @endif
        </div>
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex text-body-secondary pt-2 pb-2 row">
                <div id="date-div" class="col-md-3">
                    <label for="date" class="col-form-label-sm">Data</label>
                    <input type="date" name="date" id="date" class="form-control form-control-sm"
                           value="{{ old('date')}}"
                            min="{{ $lastDate }}">
                </div>
                <div id="amount-div" class="col-md-3">
                    <label for="amount" class="col-form-label-sm">Cifra</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">&euro;</span>
                        <input type="text" class="form-control form-control-sm" id="amount" name="amount"
                               placeholder="Cifra" value="{{ old('amount')}}">
                    </div>
                </div>
                <div id="total-div" class="col-md-3">
                    <label for="total" class="col-form-label-sm">Totale</label>
                    <input type="text" class="form-control-plaintext form-control-sm text-end" id="total" name="total"
                        value="0" readonly>
                </div>
                <div id="remaining-div" class="col-md-3">
                    <label for="remaining" class="col-form-label-sm">Rimanente</label>
                    <input type="text" class="form-control-plaintext form-control-sm text-end" id="remaining"
                           name="remaining" value="0" readonly>
                </div>
            </div>
            <div class="d-flex text-body-secondary pt-3 row">
                <div id="error-div" class="col-md-10">
                    <h5><span id="alert" class="badge"></span></h5>
                </div>
                <div class="col-md-2">
                    <button id="delivery-button" type="submit"
                            class="btn btn-primary btn-sm float-end text-nowrap" disabled>
                        <i class="fa fa-edit"></i> Aggiungi Consegna
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
