@extends('layouts.app')
@section('content')
<script src="/js/util.js"></script>
<script src="/js/report/statistics/index.js"></script>
<link href="/css/tables.css" rel="stylesheet" type="text/css">
<div class="d-print-none">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            STATISTICHE
        </div>
        <div class="card-body">
            <div class="row row-cols-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 Soci più anziani</h5>
                        <br>
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Data nascita</th>
                                </tr>
                            </thead>
                            <tbody id="oldest_table">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Deceduti nel tempo</h5>
                        <br>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Over the years</h5>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
