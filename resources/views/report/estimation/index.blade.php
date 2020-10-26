@extends('layouts.app')
@section('content')
<script type="text/javascript">
$(document).ready(function() {
    $('#estimation_form').validate({
        rules: {
            first_name: { required:true },
            last_name: { required:true },
            birth_date: { required:true }
        },
        messages: {
            first_name: { required: "Inserire il nome" },
            last_name: { required: "Inserire il cognome" },
            birth_date: { required: "Inserire la data di nascita" }
        }
    });
});
</script>
<div class="card">
    <div class="card-header bg-secondary text-white">
        PREVENTIVO NUOVI SOCI
    </div>
    <div class="card-body">
        @include('common.errors')
        <form id="estimation_form" action="/report/customers/estimation/print" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="years" class="col-form-label-sm"> Anno iscrizione: </label>
                    <select id="years" name="years" class="form-control custom-select-sm">
                        @foreach($years as $y)
                            @if (date("Y") == $y->year)
                                <option value="{{ $y->year }}" selected>{{ $y->year }}</option>
                            @else
                                <option value="{{ $y->year }}">{{ $y->year }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="first_name" class="col-form-label-sm"> Nome: </label>
                    <input name="first_name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label for="last_name" class="col-form-label-sm"> Cognome: </label>
                    <input name="last_name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label for="birth_date" class="col-form-label-sm"> Data di Nascita: </label>
                    <input name="birth_date" type="date" class="form-control form-control-sm">
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" formtarget="_blank" class="btn btn-primary btn-sm float-right">
                    <i class="fa fa-print"></i> Stampa Preventivo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection