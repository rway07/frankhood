@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/report/estimation/index.js"></script>
<main class="container">
    @include('common.errors')

    <form id="estimation_form" action="/report/customers/estimation/print" method="POST"
          class="form-horizontal" target="_blank">
        @csrf

        <div class="d-flex bg-body align-items-center p-3 my-3 rounded shadow-sm">
            <h6 class="pb-1 mb-0">PREVENTIVO NUOVI SOCI</h6>
        </div>
        <div class="my-3 p-3 bg-body shadow-sm rounded">
            <div class="d-flex text-body-secondary pt-2 row">
                <div id="first-name-div" class="col-md-4">
                    <label for="first_name" class="col-form-label-sm"> Nome: </label>
                    <input id="first_name" name="first_name" type="text" class="form-control form-control-sm">
                </div>
                <div id="last-name-div" class="col-md-4">
                    <label for="last_name" class="col-form-label-sm"> Cognome: </label>
                    <input id="last_name" name="last_name" type="text" class="form-control form-control-sm">
                </div>
                <div id="birth-date-div" class="col-md-2">
                    <label for="birth_date" class="col-form-label-sm"> Data di Nascita: </label>
                    <input id="birth_date" name="birth_date" type="date" class="form-control form-control form-control-sm">
                </div>
                <div id="year-div" class="col-md-2">
                    <label for="years" class="col-form-label-sm"> Anno iscrizione: </label>
                    <select id="years" name="years" class="form-control form-select form-select-sm">
                        @foreach($years as $y)
                            @if (date("Y") == $y->year)
                                <option value="{{ $y->year }}" selected>{{ $y->year }}</option>
                            @else
                                <option value="{{ $y->year }}">{{ $y->year }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex text-body-secondary pt-3 row">
                <div class="col-md">
                    <button id="estimation-button" type="submit" class="btn btn-primary btn-sm float-end text-nowrap" disabled>
                        <i class="fa fa-print"></i> Stampa Preventivo
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
