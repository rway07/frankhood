@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/customers/create.js"></script>
<div class="">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            @if (isset($customers))
                MODIFICA SOCIO
            @else
                NUOVO SOCIO
            @endif
        </div>

        <div class="card-body">
            @include('common.errors')

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        @if (isset($customers))
                            <form id="create_customer_form" action="/customers/{{ $customers->id }}/update" method="POST" class="form-horizontal">
                                {{ method_field('PUT') }}
                        @else
                            <form id="create_customer_form" action="/customers/store" method="POST" class="form-horizontal">
                        @endif

                            {{ csrf_field() }}
                            <!-- Nome e Cognome -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="col-form-label-sm">Nome</label>
                                    @if (isset($customers))
                                        <input type="text" name="first_name" id="first_name" class="form-control form-control-sm" value="{{ $customers->first_name }}">
                                    @else
                                        <input type="text" name="first_name" id="first_name" class="form-control form-control-sm" value="{{ old('first_name') }}">
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="col-form-label-sm">Cognome</label>
                                    @if (isset($customers))
                                        <input type="text" name="last_name" id="last_name" class="form-control form-control-sm" value="{{ $customers->last_name }}">
                                    @else
                                        <input type="text" name="last_name" id="last_name" class="form-control form-control-sm" value="{{ old('last_name') }}">
                                    @endif
                                </div>
                            </div>
                            <!-- Alias -->
                            <div class="mb-3">
                                <label for="alias" class="col-form-label-sm">Alias</label>
                                @if (isset($customers))
                                    <input type="text" name="alias" id="alias" class="form-control form-control-sm" value="{{ $customers->alias }}">
                                @else
                                    <input type="text" name="alias" id="alias" class="form-control form-control-sm" value="{{ old('alias') }}">
                                @endif
                            </div>
                            <!-- Codice Fiscale -->
                            <div class="mb-3">
                                <label for="cf" class="col-form-label-sm">Codice Fiscale</label>
                                @if (isset($customers))
                                    <input type="text" name="cf" id="cf" class="form-control form-control-sm" value="{{ $customers->cf }}">
                                @else
                                    <input type="text" name="cf" id="cf" class="form-control form-control-sm" value="{{ old('cf') }}">
                                @endif
                            </div>
                            <!-- Sesso e data di nascits -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date" class="col-form-label-sm">Data Nascita</label>
                                    @if (isset($customers))
                                        <input type="date" name="birth_date" id="birth_date" class="form-control form-control-sm" value="{{ $customers->birth_date }}">
                                    @else
                                        <input type="date" name="birth_date" id="birth_date" class="form-control form-control-sm" value="{{ old('birth_date') }}">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="col-form-label-sm">Sesso</label>
                                    <select id="gender" name="gender" class="form-control form-control-sm">
                                        @if (isset($customers))
                                            @if ($customers->gender == 'M')
                                                <option selected>M</option>
                                                <option>F</option>
                                            @else
                                                <option>M</option>
                                                <option selected>F</option>
                                            @endif
                                        @else
                                            <option>M</option>
                                            <option>F</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- Luogo e provincia di nascita -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="birth_place" class="col-form-label-sm">Luogo di Nascita</label>
                                    @if (isset($customers))
                                        <input type="text" name="birth_place" id="birth_place" class="form-control form-control-sm" value="{{ $customers->birth_place }}">
                                    @else
                                        <input type="text" name="birth_place" id="birth_place" class="form-control form-control-sm" value="{{ old('birth_place') }}">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="birth_province" class="col-form-label-sm"> Provincia di Nascita</label>
                                    @if (isset($customers))
                                        <input type="text" name="birth_province" id="birth_province" class="form-control form-control-sm" value="{{ $customers->birth_province }}">
                                    @else
                                        <input type="text" name="birth_province" id="birth_province" class="form-control form-control-sm" value="{{ old('birth_province') }}">
                                    @endif
                                </div>
                            </div>
                            <!-- Indirizzo -->
                            <div class="mb-3">
                                <label for="address" class="col-form-label-sm">Indirizzo</label>
                                @if (isset($customers))
                                    <input type="text" name="address" id="address" class="form-control form-control-sm" value="{{ $customers->address }}">
                                @else
                                    <input type="text" name="address" id="address" class="form-control form-control-sm" value="{{ old('address') }}">
                                @endif
                            </div>
                            <!-- Comune -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="municipality" class="col-form-label-sm">Comune</label>
                                    @if (isset($customers))
                                        <input type="text" name="municipality" id="municipality" class="form-control form-control-sm" value="{{ $customers->municipality }}">
                                    @else
                                        <input type="text" name="municipality" id="municipality" class="form-control form-control-sm" value="{{ old('municipality') }}">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label for="CAP" class="col-form-label-sm">CAP</label>
                                    @if (isset($customers))
                                        <input type="text" name="CAP" id="CAP" class="form-control form-control-sm" value="{{ $customers->CAP }}">
                                    @else
                                        <input type="text" name="CAP" id="CAP" class="form-control form-control-sm" value="{{ old('CAP') }}">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label for="province" class="col-form-label-sm">Provincia</label>
                                    @if (isset($customers))
                                        <input type="text" name="province" id="province" class="form-control form-control-sm" value="{{ $customers->province }}">
                                    @else
                                        <input type="text" name="province" id="province" class="form-control form-control-sm" value="{{ old('province') }}">
                                    @endif
                                </div>
                            </div>
                            <!-- Telefoni -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="phone" class="col-form-label-sm">Telefono</label>
                                    @if (isset($customers))
                                        <input type="text" name="phone" id="phone" class="form-control form-control-sm" value="{{ $customers->phone }}">
                                    @else
                                        <input type="text" name="phone" id="phone" class="form-control form-control-sm" value="{{ old('phone') }}">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="mobile_phone" class="col-form-label-sm">Cellulare</label>
                                    @if (isset($customers))
                                        <input type="text" name="mobile_phone" id="mobile_phone" class="form-control form-control-sm" value="{{ $customers->mobile_phone }}">
                                    @else
                                        <input type="text" name="mobile_phone" id="mobile_phone" class="form-control form-control-sm" value="{{ old('mobile_phone') }}">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label-sm">e-mail</label>
                                    @if (isset($customers))
                                        <input type="text" name="email" id="email" class="form-control form-control-sm" value="{{ $customers->email }}">
                                    @else
                                        <input type="text" name="email" id="email" class="form-control form-control-sm" value="{{ old('email') }}">
                                    @endif
                                </div>
                            </div>
                            <!-- Date -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="enrollment_year" class="col-form-label-sm">Anno Iscrizione</label>
                                    @if (isset($customers))
                                        <input type="text" name="enrollment_year" id="enrollment_year" class="form-control form-control-sm" value="{{ $customers->enrollment_year }}">
                                    @else
                                        <input type="text" name="enrollment_year" id="enrollment_year" class="form-control form-control-sm" value="{{ old('enrollment_year') }}">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="death_date" class="col-form-label-sm">Data Morte</label>
                                    @if (isset($customers))
                                        <input type="date" name="death_date" id="death_date" class="form-control form-control-sm" value="{{ $customers->death_date }}">
                                    @else
                                        <input type="date" name="death_date" id="death_date" class="form-control form-control-sm" value="{{ old('death_date') }}">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="revocation_date" class="col-form-label-sm">Data Revoca</label>
                                    @if (isset($customers))
                                        <input type="date" name="revocation_date" id="revocation_date" class="form-control form-control-sm" value="{{ $customers->revocation_date }}">
                                    @else
                                        <input type="date" name="revocation_date" id="revocation_date" class="form-control form-control-sm" value="{{ old('revocation_date') }}">
                                    @endif
                                </div>
                            </div>
                            <!-- Priorato -->
                            <div class="mb-3">
                                <label for="priorato" class="col-form-label-sm">Priorato</label>
                                @if (isset($customers))
                                    @if ($customers->priorato == true)
                                        <input type="checkbox" name="priorato" id="priorato" class="form-control form-control-sm" checked>
                                    @else
                                        <input type="checkbox" name="priorato" id="priorato" class="form-control form-control-sm">
                                    @endif
                                @else
                                    <input type="checkbox" name="priorato" id="priorato" class="form-control form-control-sm">
                                @endif
                            </div>
                            <br>
                            <!-- Pulsante -->
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-sm float-right">
                                    @if (isset($customers))
                                        <i class="fa fa-edit"></i> Modifica Socio
                                    @else
                                        <i class="fa fa-plus"></i> Aggiungi Socio
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection