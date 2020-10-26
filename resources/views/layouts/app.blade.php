
<!DOCTYPE html>
<html>
<head>
    <title>{Project Mortanius}</title>

    <!-- jQuery 3.5.1 + Validate-->
    <script src="/js/common/jquery/jquery-3.5.1.min.js"></script>
    <script src="/js/common/jquery/jquery.validate.min.js"></script>
    <script src="/js/common/jquery/additional-methods.min.js"></script>

    <!-- Bootstrap 4.5.x -->
    <link href="/css/common/bootstrap4/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="/js/common/bootstrap4/bootstrap.bundle.min.js"></script>

    <!-- Datatables -->
    <link href="/css/common/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <script src="/js/common/datatables/jquery.dataTables.js"></script>
    <script src="/js/common/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Font related -->
    <link href="/css/common/fonts/lato.css" rel="stylesheet" type="text/css">
    <link href="/css/common/fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom -->
    <link href="/css/util.css" rel="stylesheet" type="text/css">
    <link href="/css/header.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--
    <div class="container-fluid hidden-print banner">
        <h1>Confraternita Madonna della Difesa</h1>
    </div>
    -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand mr-4" href="#">Confraternita</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Soci
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/customers/create">Nuovo Socio</a>
                        <a class="dropdown-item" href="/customers/index">Lista Soci</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ricevute
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/receipts/create">Nuova Ricevuta</a>
                        <a class="dropdown-item" href="/receipts/index">Lista Ricevute</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Spese
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/expenses/create">Nuova Spesa</a>
                        <a class="dropdown-item" href="/expenses/index">Lista Spese</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Offerte
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/offers/create">Nuova Offerta</a>
                        <a class="dropdown-item" href="/offers/index">Lista Offerte</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Report
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/report/customers/yearly/index">Lista Tutti i Soci per Anno</a>
                        <a class="dropdown-item" href="/report/customers/new/index">Lista Nuovi Soci</a>
                        <a class="dropdown-item" href="/report/customers/deceased/index">Lista Soci Deceduti</a>
                        <a class="dropdown-item" href="/report/customers/revocated/index">Lista Soci Revocati</a>
                        <a class="dropdown-item" href="/report/customers/late/index">Lista Soci Morosi</a>
                        <a class="dropdown-item" href="/report/customers/age/index">Lista Soci per Età</a>
                        <a class="dropdown-item" href="/report/customers/estimation/index">Preventivo Nuovi Soci</a>
                        <a class="dropdown-item" href="/report/customers/duplicates/index">Ricevute Duplicate</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Chiusure
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/closure/daily/index">Chiusure Giornaliere</a>
                        <a class="dropdown-item" href="/closure/yearly/index">Chiusure Annuali</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <br>
    <div class="container-fluid">
        @yield('content')
    </div>
</body>
</html>
