@section('custom_assets')
     @include('include/validate')
     @vite(['resources/js/app/customers/create.js'])
@endsection

@extends('layouts.app')
@section('content')
<main class="container">
    @include('common.errors')
    <div class="d-flex bg-body align-items-center my-3 p-3 rounded shadow-sm">
        <h6 class="pb-1 mb-0">{{ isset($customers) ? 'MODIFICA SOCIO' : 'NUOVO SOCIO' }}</h6>
    </div>
    <div class="my-3 p-3 bg-body shadow-sm rounded">
        <form id="create_customer_form" method="post"
              action="{{ isset($customers) ?  '/customers/' . $customers->id . '/update' : '/customers/store'}}">
            {{ isset($customers) ? method_field('put') : '' }}
            @csrf

            <!-- Nome e Cognome -->
            <div class="row mb-3">
                <div id="first-name-div" class="col-md-6">
                    <label for="first_name" class="col-form-label-sm">Nome</label>
                    <input type="text" name="first_name" id="first_name" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->first_name : old('first_name')}}">
                 </div>
                <div id="last-name-div" class="col-md-6">
                    <label for="last_name" class="col-form-label-sm">Cognome</label>
                    <input type="text" name="last_name" id="last_name" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->last_name : old('last_name')}}">
                </div>
            </div>
            <!-- Alias -->
            <div class="row mb-3">
                <div id="alias-div" class="col-md">
                    <label for="alias" class="col-form-label-sm">Alias</label>
                    <input type="text" name="alias" id="alias" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->alias : old('alias') }}">
                </div>
            </div>
            <!-- Codice Fiscale -->
            <div class="row mb-3">
                <div id="cf-div" class="col-md">
                    <label for="cf" class="col-form-label-sm">Codice Fiscale</label>
                    <input type="text" name="cf" id="cf" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->cf : old('cf') }}">
                </div>
            </div>
            <!-- Sesso e data di nascits -->
            <div class="row mb-3">
                <div id="birth-date-div" class="col-md-6">
                    <label for="date" class="col-form-label-sm">Data Nascita</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->birth_date : old('birth_date') }}">
                </div>
                <div id="gender-div" class="col-md-6">
                    <label for="gender" class="col-form-label-sm">Sesso</label>
                    <select id="gender" name="gender" class="form-control form-control-sm form-select form-select-sm">
                        @if (isset($customers))
                            <option {{ ($customers->gender == 'M') ? 'selected' : '' }}>M</option>
                            <option {{ ($customers->gender == 'F') ? 'selected' : '' }}>F</option>
                        @else
                            <option>M</option>
                            <option>F</option>
                        @endif
                    </select>
                </div>
            </div>
            <!-- Luogo e provincia di nascita -->
            <div class="row mb-3">
                <div id="birth-place-div" class="col-md-6">
                    <label for="birth_place" class="col-form-label-sm">Luogo di Nascita</label>
                    <input type="text" name="birth_place" id="birth_place" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->birth_place : old('birth_place')}}">
                </div>
                <div id="birth-province-div" class="col-md-6">
                    <label for="birth_province" class="col-form-label-sm"> Provincia di Nascita</label>
                    <input type="text" name="birth_province" id="birth_province" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->birth_province : old('birth_province') }}">
                </div>
            </div>
            <!-- Indirizzo -->
            <div class="row mb-3">
                <div id="address-div" class="col-md">
                    <label for="address" class="col-form-label-sm">Indirizzo</label>
                    <input type="text" name="address" id="address" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->address : old('address') }}">
                </div>
             </div>
            <!-- Comune -->
            <div class="row mb-3">
                <div id="municipality-div" class="col-md-6">
                    <label for="municipality" class="col-form-label-sm">Comune</label>
                    <input type="text" name="municipality" id="municipality" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->municipality : old('municipality') }}">
                </div>
                <div id="cap-div" class="col-md-3">
                    <label for="cap" class="col-form-label-sm">CAP</label>
                    <input type="text" name="cap" id="cap" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->CAP : old('CAP') }}">
                </div>
                <div id="province-div" class="col-md-3">
                    <label for="province" class="col-form-label-sm">Provincia</label>
                    <input type="text" name="province" id="province" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->province : old('province') }}">
                </div>
            </div>
            <!-- Telefoni -->
            <div class="row mb-3">
                <div id="phone-div" class="col-md-4">
                    <label for="phone" class="col-form-label-sm">Telefono</label>
                    <input type="text" name="phone" id="phone" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->phone : old('phone') }}">
                </div>
                <div id="mobile-phone-div" class="col-md-4">
                    <label for="mobile_phone" class="col-form-label-sm">Cellulare</label>
                    <input type="text" name="mobile_phone" id="mobile_phone" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->mobile_phone : old('mobile_phone') }}">
                 </div>
                <div id="email-div" class="col-md-4">
                    <label class="col-form-label-sm">e-mail</label>
                    <input type="text" name="email" id="email" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->email : old('email') }}">
                </div>
            </div>
            <!-- Date -->
            <div class="row mb-3">
                <div id="enrollment-year-div" class="col-md-4">
                    <label for="enrollment_year" class="col-form-label-sm">Anno Iscrizione</label>
                    <input type="text" name="enrollment_year" id="enrollment_year" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->enrollment_year : old('enrollment_year') }}">
                </div>
                <div id="death-date-div" class="col-md-4">
                    <label for="death_date" class="col-form-label-sm">Data Morte</label>
                    <input type="date" name="death_date" id="death_date" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->death_date : old('death_date') }}">
                </div>
                <div id="revocation-date-div" class="col-md-4">
                    <label for="revocation_date" class="col-form-label-sm">Data Revoca</label>
                    <input type="date" name="revocation_date" id="revocation_date" class="form-control form-control-sm"
                           value="{{ isset($customers) ? $customers->revocation_date : old('revocation_date') }}">
                </div>
            </div>
            <!-- Priorato -->
            <div class="row mb-3">
                <div class="col-md">
                    <label for="priorato" class="form-check-label">Priorato</label>
                    @if (isset($customers))
                        @if ($customers->priorato)
                            <input type="checkbox" name="priorato" id="priorato" class="form-check-input" checked>
                        @else
                            <input type="checkbox" name="priorato" id="priorato" class="form-check-input">
                        @endif
                    @else
                        <input type="checkbox" name="priorato" id="priorato" class="form-check-input">
                    @endif
                </div>
            </div>
            <br>
            <!-- Pulsante -->
            <div class="row mb-3">
                <div class="col-md">
                    <button id="customer-button" type="submit"
                            class="btn btn-primary btn-sm float-end text-nowrap" disabled>
                        @if (isset($customers))
                            <i class="fa fa-edit"></i> Modifica Socio
                        @else
                            <i class="fa fa-plus"></i> Aggiungi Socio
                        @endif
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection
