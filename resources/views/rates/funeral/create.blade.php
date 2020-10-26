@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/rates/exceptions/create.js"></script>
<div class="card">
    <div class="card-header bg-secondary text-white">
        @if (isset($exception))
            MODIFICA ECCEZIONE
        @else
            NUOVA ECCEZIONE
        @endif
    </div>

    <div class="card-body">
        <!-- Display Validation Errors -->
        @include('common.errors')
        @if (isset($exception))
            <form id="create_exception" action="/rates/exceptions/{{ $exception->id }}/update" method="POST" class="form-horizontal">
                {{ method_field('PUT') }}
        @else
            <form id="create_exception" action="/rates/exceptions/store" method="POST" class="form-horizontal">
        @endif

            {{ csrf_field() }}

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="year" class="control-label">Anno</label>
                    <select id="year" name="year" class="form-control custom-select-sm">
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
                <div class="col-md-4">
                    <label for="dead_customers" class="control-label">Socio deceduto</label>
                    <select id="dead_customers" name="dead_customers" class="form-control custom-select-sm">
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
                <div class="col-md-4">
                    <label for="quota" class="control-label">Costo</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&euro;</div>
                        </div>
                        @if (isset($exception))
                            <input type="text" class="form-control form-control-sm" id="quota" name="quota" placeholder="Quota" value="{{ $exception->cost }}">
                        @else
                            <input type="text" class="form-control form-control-sm" id="quota" name="quota" placeholder="Quota" value="{{ old('quota') }}">
                        @endif
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="btn btn-primary btn-sm float-right">
                    @if (isset($exception))
                        <i class="fa fa-edit"></i> Modifica Eccezione
                    @else
                        <i class="fa fa-plus"></i> Aggiungi Eccezione
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection