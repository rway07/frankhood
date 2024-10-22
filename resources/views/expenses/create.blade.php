@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/expenses/create.js"></script>
<main class="container">
    @include('common.errors')

    <form id="create_expense_form"
          action="{{ isset($expense) ? '/expenses/' . $expense->id . '/update' : '/expenses/store' }}" method="POST">
        {{ isset($expense) ? method_field('put') : '' }}
        @csrf

        <div class="d-flex bg-body align-items-center p-3 my-3 rounded shadow-sm">
            <h6 class="pb-1 mb-0">{{ isset($expense) ? 'MODIFICA SPESA' : 'NUOVA SPESA' }}</h6>
        </div>
        <div class="my-3 p-3 bg-body shadow-sm rounded">
            <div class="d-flex text-body-secondary pt-2 row">
                <div class="col-md-2">
                    <label for="date" class="col-form-label-sm">Data</label>
                    <input type="date" name="date" id="date" class="form-control form-control form-control-sm"
                           value="{{ isset($expense) ? $expense->date : old('date') }}">
                </div>
                <div class="col-md-2">
                    <label for="amount" class="col-form-label-sm">Totale</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">&euro;</span>
                        <input type="text" class="form-control form-control form-control-sm" id="amount" name="amount"
                               placeholder="Totale" value="{{ isset($expense) ? $expense->amount : old('amount') }}">
                    </div>
                </div>
                <div class="col-md-8">
                    <label for="description" class="col-form-label-sm">Descrizione</label>
                    <input type="text" name="description" id="description" class="form-control form-control-sm"
                           value="{{ isset($expense) ? $expense->description : old('description') }}">
                </div>
            </div>
            <div class="d-flex text-body-secondary pt-3 row">
                <div class="col-md">
                    <button type="submit" class="btn btn-primary btn-sm float-end">
                        <i class="fa fa-edit"></i> {{ isset($expense) ? 'Modifica Spesa' : 'Aggiungi Spesa' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
