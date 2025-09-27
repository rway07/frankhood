
<!DOCTYPE html>
<html lang="en" data-bs-theme="">
<head>
    <title>{Project Frankhood}</title>

    <!-- App base import -->
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])

    @yield('custom_assets')
    @yield('meta')
</head>
<body class="bg-body-tertiary">
    <div class="d-print-none">
        <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand me-4" href="#">Confraternita</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Home </a>
                        </li>
                        <li class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Soci
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="/customers/create">Nuovo Socio</a></li>
                                <li><a class="dropdown-item" href="/customers/index">Lista Soci</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Ricevute
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/receipts/create">Nuova Ricevuta</a>
                                <a class="dropdown-item" href="/receipts/index">Lista Ricevute</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Tariffe
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/rates/create">Nuova Tariffa</a>
                                <a class="dropdown-item" href="/rates/index">Lista Tariffe</a>
                                <a class="dropdown-item" href="/rates/exceptions/create">Nuova Eccezione Funerale</a>
                                <a class="dropdown-item" href="/rates/exceptions/index">Lista Eccezioni</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Spese
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/expenses/create">Nuova Spesa</a>
                                <a class="dropdown-item" href="/expenses/index">Lista Spese</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Offerte
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/offers/create">Nuova Offerta</a>
                                <a class="dropdown-item" href="/offers/index">Lista Offerte</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Report
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/report/customers/yearly/index">Lista Tutti i Soci per Anno</a>
                                <a class="dropdown-item" href="/report/customers/new/index">Lista Nuovi Soci</a>
                                <a class="dropdown-item" href="/report/customers/deceased/index">Lista Soci Deceduti</a>
                                <a class="dropdown-item" href="/report/customers/revocated/index">Lista Soci Revocati</a>
                                <a class="dropdown-item" href="/report/customers/late/index">Lista Soci Morosi</a>
                                <a class="dropdown-item" href="/report/customers/age/index">Lista Soci per Età</a>
                                <a class="dropdown-item" href="/report/customers/priori/index">Lista Priori</a>
                                <a class="dropdown-item" href="/report/alternatives/index">Lista Ricevute con Quote Alternative</a>
                                <a class="dropdown-item" href="/report/customers/estimation/index">Preventivo Nuovi Soci</a>
                                <a class="dropdown-item" href="/report/customers/duplicates/index">Ricevute Duplicate</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Consegne
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/deliveries/create">Nuova Consegna</a>
                                <a class="dropdown-item" href="/deliveries/index">Lista Consegne</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Chiusure
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/closure/daily/index">Chiusure Giornaliere</a>
                                <a class="dropdown-item" href="/closure/yearly/index">Chiusure Annuali</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Statistiche
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/statistics/peoplenumber/index">Numero soci</a>
                                <a class="dropdown-item" href="/statistics/payments/index">Pagamenti</a>
                                <a class="dropdown-item" href="/statistics/oldest/index">Soci più anziani</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <span class="nav-link text-light">{{ env('APP_NAME') }} v{{ env('APP_VERSION') }}</span>
            </div>
        </nav>
    </div>
    <br>
    <div class="container-fluid">
        @yield('content')
        @include('common.guru')
        @include('common.modal')
    </div>
</body>
</html>
