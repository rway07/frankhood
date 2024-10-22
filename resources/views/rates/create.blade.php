@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/rates/create.js"></script>
<main class="container">
    @include('common.errors')

    <form id="create_rate" action="{{ isset($rate) ? '/rates/' . $rate->id .'/update' : '/rates/store' }}" method="post">
       {{ isset($rate) ? method_field('put') : '' }}
        @csrf

        <div class="d-flex bg-body align-items-center p-3 my-3 rounded shadow-sm">
            <h6 class="pb-1 mb-0"> {{ isset($rate) ? 'MODIFICA TARIFFA' : 'NUOVA TARIFFA' }} </h6>
        </div>
        <div class="my-3 p-3 bg-body shadow-sm rounded">
            <div class="d-flex text-body-secondary pt-2 row">
                <div class="col-md-4">
                    <input type="text" id="year" name="year" class="form-control form-control-sm"
                           placeholder="Anno" value="{{ isset($rate) ? $rate->year : old('year')}}"
                           {{ isset($rate) ? 'readonly' : ''}}>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">&euro;</span>
                        <input type="text" class="form-control form-control-sm" id="quota" name="quota"
                               placeholder="Quota" value="{{ isset($rate) ? $rate->quota : old('quota') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">&euro;</span>
                        <input type="text" class="form-control form-control-sm" id="funeral_cost"
                               name="funeral_cost" placeholder="Costo funerale"
                               value="{{ isset($rate) ? $rate->funeral_cost : old('funeral_cost')}}">
                     </div>
                </div>
            </div>
            <div class="d-flex text-body-secondary pt-3 row">
                <div class="col-md">
                    <button type="submit" class="btn btn-primary btn-sm float-end">
                        <i class="fa fa-edit"></i> {{ isset($rate) ? 'Modifica Tariffa' : 'Aggiungi Tariffa' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
