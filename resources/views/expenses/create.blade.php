@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/expenses/create.js"></script>
<div class="card">
    <div class="card-header bg-secondary text-white">
        @if (isset($expense))
            MODIFICA SPESA
        @else
            NUOVA SPESA
        @endif
    </div>

    <div class="card-body">
        @include('common.errors')

        @if (isset($expense))
            <form id="create_expense_form" action="/expenses/{{ $expense->id }}/update" method="POST" class="form-horizontal">
                {{ method_field('PUT') }}
        @else
            <form id="create_expense_form" action="/expenses/store" method="POST" class="form-horizontal">
        @endif

            {{ csrf_field() }}

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="date" class="col-form-label-sm">Data</label>
                    @if (isset($expense))
                        <input type="date" name="date" id="date" class="form-control form-control-sm" value="{{ $expense->date }}">
                    @else
                        <input type="date" name="date" id="date" class="form-control form-control-sm" value="{{ old('date') }}">
                    @endif
                </div>
                <div class="col-md-2">
                    <label class="col-form-label-sm">Totale</label>
                    @if (isset($expense))
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <div class="input-group-text">&euro;</div>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="amount" name="amount" placeholder="Totale" value="{{ $expense->amount }}">
                        </div>
                    @else
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <div class="input-group-text">&euro;</div>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="amount" name="amount" placeholder="Totale" value="{{ old('amount') }}">
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <label class="col-form-label-sm">Descrizione</label>
                    @if (isset($expense))
                        <input type="text" name="description" id="description" class="form-control form-control-sm" value="{{ $expense->description }}">
                    @else
                        <input type="text" name="description" id="description" class="form-control form-control-sm" value="{{ old('description') }}">
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-sm float-right">
                    @if (isset($expense))
                        <i class="fa fa-edit"></i> Modifica Spesa
                    @else
                        <i class="fa fa-plus"></i> Aggiungi Spesa
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection