@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/rates/exceptions/create.js"></script>
<main class="container">
    @include('common.errors')
    @if (isset($exception))
        <form id="create_exception" action="/rates/exceptions/{{ $exception->id }}/update" method="POST" class="form-horizontal">
            {{ method_field('PUT') }}
    @else
        <form id="create_exception" action="/rates/exceptions/store" method="POST" class="form-horizontal">
    @endif
        {{ csrf_field() }}

        <div class="d-flex bg-body p-3 my-3 rounded shadow-sm">
            @if (isset($exception))
                <h6 class="pb-1 mb-0">MODIFICA ECCEZIONE</h6>
            @else
                <h6 class="pb-1 mb-0">NUOVA ECCEZIONE</h6>
            @endif
        </div>
        <div class="bg-body p-3 my-3 rounded shadow-sm">
            <div class="d-flex text-body-secondary pt-3 row">
                <div id="year-div" class="col-md-4">
                    <label for="year" class="control-label">Anno</label>
                    <select id="year" name="year" class="form-control form-control-sm form-select form-select form-select-sm">
                        @foreach($years as $y)
                            @if(isset($exception))
                                @if($exception->year == $y->year)
                                    <option value="{{ $y->year }}" selected>{{ $y->year }}</option>
                                @else
                                    <option value="{{ $y->year }}">{{ $y->year }}</option>
                                @endif
                            @else
                                @if($y->year == date('Y'))
                                    <option value="{{ $y->year }}" selected>{{ $y->year }}</option>
                                @else
                                    <option value="{{ $y->year }}">{{ $y->year }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
                <div id="dead-customers-div" class="col-md-4">
                    <label for="dead_customers" class="control-label">Socio deceduto</label>
                    <select id="dead_customers" name="dead_customers" class="form-control form-control-sm form-select form-select form-select-sm">
                        @foreach($customers as $c)
                            @if(isset($exception))
                                @if($exception->customer_id == $c->id)
                                    <option value="{{ $c->id }}" selected>{{ $c->first_name }} {{ $c->last_name }}</option>
                                @else
                                    <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>
                                @endif
                            @else
                                <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div id="quota-div" class="col-md-4">
                    <label for="quota" class="control-label">Costo</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">&euro;</span>
                        @if (isset($exception))
                            <input type="text" class="form-control form-control-sm" id="quota" name="quota" placeholder="Quota" value="{{ $exception->cost }}">
                        @else
                            <input type="text" class="form-control form-control-sm" id="quota" name="quota" placeholder="Quota" value="{{ old('quota') }}">
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex text-body-secondary pt-3 row">
                <div class="col-md">
                    <button id="exception-button" type="submit"
                            class="btn btn-primary btn-sm float-end text-nowrap" disabled>
                        @if (isset($exception))
                            <i class="fa fa-edit"></i> Modifica Eccezione
                        @else
                            <i class="fa fa-plus"></i> Aggiungi Eccezione
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
