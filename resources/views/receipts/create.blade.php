@extends('layouts.app')
@section('content')
<!-- Selectize.js -->
@include('include.selectize')
<!-- Custom -->
<script type="module" src="/js/receipts/create/main.js" defer></script>
<main class="container-fluid">
    <div class="d-flex bg-body align-items-center p-3 my-3 rounded shadow-sm">
        <h6 class="pb-1 mb-0"> {{ isset($receipts) ? 'MODIFICA RICEVUTA' : 'NUOVA RICEVUTA' }}</h6>
    </div>
    <div class="my-3 p-3 bg-body shadow-sm rounded">
        @include('common.errors')
        <form id="create_receipt_form"
              action="{{ isset($receipts) ? '/receipts/' . $receipts->number . '/' . $receipts->year . '/update' : '/receipts/store' }}"
              method="POST" target="_blank">
            {{ isset($receipts) ? method_field('put') : '' }}
            <input id="receipt_number" name="receipt_number" type="hidden" value="{{ isset($receipts) ? $receipts->number : '0' }}">
            <input id="recipient_id" name="recipient_id" type="hidden" value="{{ isset($receipts) ? $receipts->customers_id : '0' }}"
            @csrf
            <!-- new section -->
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="col-form-label-sm">Data Emissione</label>
                    <input type="date" name="issue-date" id="issue-date" class="form-control form-control-sm"
                           value="{{ isset($receipts) ? $receipts->date : $date }}">
                </div>
                <div class="col-md-2" id="rates_div">
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
                    <select id="quota_type" name="quota_type" class="custom-select form-select form-select-sm">
                        @if(isset($receipts))
                            @if($receipts->custom_quotas)
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
                        <span class="input-group-text">&euro;</span>
                        <input type="text" id="quota" name="quota" class="form-control form-control-sm" value="" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label-sm">Totale</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">&euro;</span>
                        <input type="text" class="form-control form-control-sm" id="total" name="total"
                               placeholder="Totale" value="0" readonly>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="recipient" class="col-form-label-sm"> Destinatario </label>
                    <select id="recipient" name="recipient" class="form-control form-control-sm">
                    </select>
                </div>
                <div class="col-md-8">
                    <label for="people" class="col-form-label-sm"> Persone </label>
                    <input type="text" id="people" name="people" class="form-control form-control-sm"/>
                </div>
            </div>
            <div id="quote_alternative_div">

            </div>
            <br>
            <div class="row mb-2">
                <div id="warning_div" class="col-md-10">
                    <h5><span id="alert" class="badge"> </span></h5>
                </div>
                <div class="col-md-2">
                    <button id="receipt_button" type="submit" class="btn btn-primary btn-sm float-end text-nowrap" disabled>
                        <i id="button_icon" class="fa fa-edit"></i> {{ isset($receipts) ? 'Modifica Ricevuta' : 'Aggiungi Ricevuta' }}
                    </button>
                </div>
            </div>
        </form>
        <form id="done_form" action="/receipts/done" method="POST" target="_self">
            @csrf
            <input type="hidden" id="status" name="status" value="">
        </form>
    </div>
</main>
@endsection
