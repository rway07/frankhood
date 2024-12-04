@section('custom_assets')
    @vite(['resources/js/app/customers/summary.js'])
@endsection


@extends('layouts.app')
@section('content')
<main class="container-fluid">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row">
            <div class="col-md">
                <h5 class="pb-1 mb-0">RICEVUTE E GRUPPO FAMILIARE PER {{ $customer->name }}</h5>
            </div>
        </div>
    </div>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        @if (count($data) > 0)
            @include('receipts.util.info')
            @include('customers.util.info')

            <div class="container-fluid small">
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
                    <div class="row mb-3">
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
                                <button type="button" class="btn btn-sm btn-warning"
                                        onclick="edit('receipts', '{{ $item['number'] }}/{{ $item['year'] }}')">
                                    <i class="fa  fa-edit"></i> Modifica
                                </button>
                                <button type='button' class='btn btn-sm btn-danger'
                                        onclick="receiptInfo({{ $item['number'] }}, {{ $item['year'] }})">
                                    <i class='fa  fa-info'></i> Info
                                </button>

                        </div>
                    </div>
                    @foreach ($item['customers'] as $i)
                        <div class="row mb-1">
                            <div class="col-md-1"></div>
                            <div class="col-md-9">
                                @if($i->id == $item['head'])
                                    <span class="badge text-bg-success">Capo</span> {{ $i->name }} - ({{ $i->alias }})
                                @else
                                    {{ $i->name }} - ({{ $i->alias }})
                                @endif
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-info"
                                        onclick="edit('customers', {{ $i->id }})">
                                    <i class="fa  fa-edit"></i> Modifica
                                </button>
                                <button type='button' class='btn btn-primary btn-sm'
                                        onclick="customerInfo({{ $i->id }})">
                                    <i class='fa  fa-info'></i> Info
                                </button>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                Nessuno gruppo presente.
            </div>
        @endif
    </div>
</main>
@endsection
