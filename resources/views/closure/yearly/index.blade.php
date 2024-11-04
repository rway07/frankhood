@section('custom_assets')
    <script src="{{ mix('/js/app/closure/yearly/index.js') }}"></script>
    <link href="{{ mix('/css/tables.css') }}" rel="stylesheet" type="text/css">
@endsection

@extends('layouts.app')
@section('content')
<main class="container-fluid">
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row pb-3 border-bottom">
            <div class="col-md-8">
                <h5 class="pb-1 mb-0"> CHIUSURA ANNUALE PER L'ANNO <span id="title_year"></span></h5>
            </div>
            <div class="col-md-4 d-flex justify-content-end d-print-none gap-1">
                <select id="years" name="years" class="form-control form-control-sm form-select form-select-sm w-auto">
                    @foreach($years as $y)
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row pt-1">
            <div id="data_container" class="col-md">

            </div>
        </div>
    </div>
</main>
@endsection
