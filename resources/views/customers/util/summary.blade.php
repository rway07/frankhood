@extends('layouts.app')
@section('content')
<script src="/js/customers/summary.js" type="text/javascript"></script>
<link href="/css/util.css" rel="stylesheet" type="text/css">
@if (count($data) > 0)
    <div class="card">
        <div class="card-header bg-secondary text-white">
            RICEVUTE E GRUPPO FAMILIARE PER {{ $customer->name }}
        </div>
        <div class="card-body">
            <div class="container-fluid small_text">
                <div class="row header">
                    <div class="col-md-3">
                        Anno
                    </div>
                    <div class="col-md-3">
                        Numero Ricevuta
                    </div>
                    <div class="col-md-2">
                        Data Emissione
                    </div>
                    <div class="col-md-2">
                        Totale
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <hr>
                @foreach($data as $item)
                    <div class="row">
                        <div class="col-md-3">
                            <h6>{{ $item['year'] }}</h6>
                        </div>
                        <div class="col-md-3">
                            <h6>{{ $item['number'] }}</h6>
                        </div>
                        <div class="col-md-2">
                            <h6>{{ strftime("%d/%m/%Y", strtotime($item['date'])) }}</h6>
                        </div>
                        <div class="col-md-2">
                            <h6>{{ $item['total'] }} &euro;</h6>
                        </div>
                        <div class="col-md-2">
                            <h6>
                                <button type='button' class='btn btn-primary btn-sm' onclick="receiptInfo({{ $item['number'] }}, {{ $item['year'] }})">
                                    <i class='fa fa-btn fa-info'></i> Info
                                </button>
                            </h6>
                        </div>
                    </div>
                    @foreach ($item['customers'] as $i)
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-9 small_text">
                                @if($i->id == $item['head'])
                                    <span class="badge badge-success">Capo</span> {{ $i->name }} - ({{ $i->alias }})
                                @else
                                    {{ $i->name }} - ({{ $i->alias }})
                                @endif
                            </div>
                            <div class="col-md-2">
                                <button type='button' class='btn btn-info btn-sm' onclick="customerInfo({{ $i->id }})">
                                    <i class='fa fa-btn fa-info'></i> Info
                                </button>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-11">
                            <hr>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning" role="alert">
        Nessuno gruppo presente.
    </div>
 @endif
@endsection