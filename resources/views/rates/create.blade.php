@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/rates/create.js"></script>
<div class="card">
    <div class="card-header bg-secondary text-white">
        @if (isset($current))
            MODIFICA TARIFFA
        @else
            NUOVA TARIFFA
        @endif
    </div>
     <div class="card-body">
        <!-- Display Validation Errors -->
        @include('common.errors')

        @if (isset($current))
            <form id="create_rate" action="/rates/{{ $current->id }}/update" method="POST" class="form-horizontal">
                {{ method_field('PUT') }}
        @else
            <form id="create_rate" action="/rates/store" method="POST" class="form-horizontal">
        @endif

            {{ csrf_field() }}

            <div class="row mb-3">
                <div class="col-md-3">
                    @if (isset($current))
                        <input type="text" id="year" name="year" class="form-control form-control-sm" placeholder="Anno" value="{{ $current->year }}" readonly>
                    @else
                        <input type="text" id="year" name="year" class="form-control form-control-sm" placeholder="Anno" value="{{ old('year') }}">
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&euro;</div>
                        </div>
                        @if (isset($current))
                            <input type="text" class="form-control form-control-sm" id="quota" name="quota" placeholder="Quota" value="{{ $current->quota }}">
                        @else
                            <input type="text" class="form-control form-control-sm" id="quota" name="quota" placeholder="Quota" value="{{ old('quota') }}">
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&euro;</div>
                        </div>
                        @if (isset($current))
                            <input type="text" class="form-control form-control-sm" id="funeral_cost" name="funeral_cost" placeholder="Costo funerale" value="{{ $current->funeral_cost }}">
                        @else
                            <input type="text" class="form-control form-control-sm" id="funeral_cost" name="funeral_cost" placeholder="Costo funerale" value="{{ old('quota') }}">
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        @if (isset($current))
                            <i class="fa fa-edit"></i> Modifica Tariffa
                        @else
                            <i class="fa fa-plus"></i> Aggiungi Tariffa
                        @endif
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection