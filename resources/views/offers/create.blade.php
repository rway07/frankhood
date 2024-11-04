@section('custom_assets')
    @include('include.validate')
    <script type="text/javascript" src="{{ mix('/js/app/offers/create.js') }}"></script>
@endsection

@extends('layouts.app')
@section('content')
<main class="container">
    @include('common.errors')
    <form id="create_offer_form" action="{{ isset($offer) ? '/offers/' . $offer->id . '/update' : '/offers/store' }}" method="post">
        {{ isset($offer) ? method_field('put') : '' }}
        @csrf

        <div class="d-flex bg-body align-items-center p-3 my-3 rounded shadow-sm">
            <h6 class="pb-1 mb-0">{{ isset($offer) ? 'MODIFICA OFFERTA' : 'NUOVA OFFERTA' }}</h6>
        </div>
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex text-body-secondary pt-2 row">
                <div id="#date-div" class="col-md-3">
                    <label for="date" class="col-form-label-sm">Data</label>
                    <input type="date" name="date" id="date" class="form-control form-control-sm"
                           value="{{ isset($offer) ? $offer->date : old('date')}}">
                </div>
                <div id="amount-div" class="col-md-3">
                    <label for="amount" class="col-form-label-sm">Totale</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">&euro;</span>
                        <input type="text" class="form-control form-control-sm" id="amount" name="amount"
                               placeholder="Totale" value="{{ isset($offer) ? $offer->amount : old('amount')}}">
                    </div>
                </div>
                <div id="description-div" class="col-md-6">
                    <label for="description" class="col-form-label-sm">Descrizione</label>
                    <input type="text" name="description" id="description" class="form-control form-control-sm"
                           value="{{ isset($offer) ? $offer->description : old('description') }}">
                </div>
            </div>
            <div class="d-flex text-body-secondary pt-3 row">
                <div class="col-md">
                    <button id="offer-button" type="submit"
                            class="btn btn-primary btn-sm float-end text-nowrap" disabled>
                        <i class="fa fa-edit"></i> {{ isset($offer) ? 'Modifica Offerta' : 'Aggiungi Offerta' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
