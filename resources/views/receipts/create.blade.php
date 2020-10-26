@extends('layouts.app')
@section('content')
<!-- Selectize.js -->
<link href="/css/common/selectize/selectize.bootstrap3.css" rel="stylesheet" />
<link href="/css/common/selectize/selectize.css" rel="stylesheet" />
<script src="/js/common/selectize/selectize.js"></script>
<!-- Custom -->
<script type="text/javascript" src="/js/receipts/create.js"></script>
<div class="card card-primary">
    <div class="card-header bg-secondary text-white">
        @if (isset($receipts))
            MODIFICA RICEVUTA
        @else
            NUOVA RICEVUTA
        @endif
    </div>

    <div class="card-body">
        @include('common.errors')

        @if (isset($receipts))
            <form id="create_receipt_form" action="/receipts/{{ $receipts->number }}/{{ $receipts->year }}/update" method="POST" target="_blank"
                  class="form-horizontal">
                {{ method_field('PUT') }}
            <input id="receipt_number" name="receipt_number" type="hidden" value="{{ $receipts->number }}">
        @else
            <form id="create_receipt_form" action="/receipts/store" method="POST" target="_blank" class="form-horizontal">
                <input id="receipt_number" name="receipt_number" type="hidden" value="0">
        @endif

            {{ csrf_field() }}

            <!-- new section -->
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="col-form-label-sm">Data Emissione</label>
                    @if (isset($receipts))
                        <input type="date" name="issue_date" id="issue_date" class="form-control form-control-sm" value="{{ $receipts->date }}">
                    @else
                        <input type="date" name="issue_date" id="issue_date" class="form-control form-control-sm" value="{{ $date }}">
                    @endif
                </div>
                <div id="rates_div" class="col-md-2">
                    <label class="col-form-label-sm"> Anno </label>
                    @if (count($rates) > 0)
                        <select id="rates" name="rates" class="form-control form-control-sm">
                            @if (isset($receipts))
                                @foreach($rates as $rate)
                                    @if ($receipts->rates_id == $rate->id)
                                        <option value="{{ $rate->id }}" selected>{{ $rate->year }}</option>
                                    @else
                                        <option value="{{ $rate->id }}">{{ $rate->year }}</option>
                                    @endif
                                @endforeach
                            @else
                                @foreach($rates as $rate)
                                    @if (date("Y") == $rate->year)
                                        <option value="{{ $rate->id }}" selected>{{ $rate->year }}</option>
                                    @else
                                        <option value="{{ $rate->id }}">{{ $rate->year }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    @else
                        <div class="alert alert-danger" role="alert"> Nessun Anno trovato! </div>
                    @endif
                </div>
                <div class="col-md-2">
                    <label for="payment_type" class="col-form-label-sm"> Tipo di Pagamento </label>
                    <select id="payment_type" name="payment_type" class="form-control form-control-sm">
                        @foreach($types as $t)
                            @if(isset($receipts))
                                @if($receipts->payment_type_id == $t->id)
                                    <option value="{{ $t->id }}" selected>{{ $t->description }}</option>
                                @else
                                    <option value="{{ $t->id }}">{{ $t->description }}</option>
                                @endif
                            @else
                                <option value="{{ $t->id }}">{{ $t->description }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label-sm"> Tipo Quota </label>
                    <select id="quota_type" name="quota_type" class="custom-select custom-select-sm">
                        @if(isset($receipts))
                            @if($receipts->custom_quotas == true)
                                <option value="0">Normale</option>
                                <option value="1" selected>Alternative</option>
                            @else
                                <option value="0" selected>Normale</option>
                                <option value="1">Alternative</option>
                            @endif
                        @else
                            <option value="0" selected>Normale</option>
                            <option value="1">Alternative</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label-sm"> Quota </label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&euro;</div>
                        </div>
                        <input type="text" id="quota" name="quota" class="form-control form-control-sm" value="" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label-sm">Totale</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&euro;</div>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="total" name="total" placeholder="Totale" value="" readonly>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="col-form-label-sm"> Destinatario </label>
                    @if (isset($receipts))
                        <select id="edit_recipient" hidden>
                            <option value="{{ $receipts->customers_id }}" selected> </option>
                        </select>
                    @endif
                    <select id="recipient" name="recipient" class="form-control form-control-sm">
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="col-form-label-sm"> Persone </label>
                    <input type="text" id="people" name="people" class="form-control form-control-sm" />
                </div>
            </div>
            <div id="quote_alternative_div">

            </div>
            <br>
            <div class="mb-3">
                <button id="receipt_button" type="submit" class="btn btn-primary btn-sm float-right">
                    @if (isset($receipts))
                        <i id="button_icon" class="fa fa-edit"></i> Modifica Ricevuta
                    @else
                        <i id="button_icon" class="fa fa-plus"></i> Aggiungi Ricevuta
                    @endif
                </button>
                <span id="alert" class="label"> </span>
            </div>
            <!-- -->
            <div id="error_div" class="col-sm-offset-2 col-sm-7">

            </div>
        </form>
        <form id="done_form" action="/receipts/done" method="POST" target="_self">
            {{ csrf_field() }}
            <input type="hidden" id="status" name="status" value="idle">
        </form>
    </div>
</div>
@endsection
