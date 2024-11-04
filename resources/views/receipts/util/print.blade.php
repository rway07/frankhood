@extends('layouts.print')
@section('content')
<style media="print">
    @page {
        size: auto;   /* auto is the initial value */
    }
</style>
<div>
    <div id="title">
        <h2>CONFRATERNITA MADONNA DELLA DIFESA ONLUS</h2>
        <h5>Via Sassari 121 - 07040 Stintino - Codice Fiscale: 92106010900</h5>
        <hr>
    </div>
    <div id="body">
        <div class="row">
            <div class="col-md-12">
                <h4>ATTESTAZIONE DI VERSAMENTO CONTRIBUTIVO</h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-5">
                Numero: <strong>{{ $receipts->number . '/' . $receipts->year }}</strong>
            </div>
            <div class="col-md-5">
                Data: <b>{{ strftime("%d/%m/%Y", strtotime($receipts->date)) }}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Rilasciato a: <b>{{ $receipts->first_name . ' ' . $receipts->last_name }}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Pagato con: <b>{{ $receipts->description }}</b>
            </div>
        </div>
        <br>
        <table class="table">
            <thead>
                <th>Nome</th>
                <th>Data Nascita</th>
                <th>Quota</th>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        <td> {{ $d->first_name . ' '. $d->last_name }}</td>
                        <td> {{ strftime("%d/%m/%Y", strtotime($d->birth_date)) }} </td>
                        <td> {{ number_format($d->quota, 2, ',', '') }} &#128;</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        {{ number_format($receipts->total, 2, ',', '') }} &#128;
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>
    <div id="footer">
        <div>
            <h6>
            Nella prossima dichiarazione dei redditi devolvi il 5 PER MILLE alla Confraternita Madonna Della Difesa
            Onlus. Nell'apposito spazio dedaticato al 5 x Mille &#232; necessario firmare il riquadro dedicato al sostegno del
            volontariato delle associazioni non lucrative ONLUS. Specificare il Codice fiscale:
            Confraternita Madonna della Difesa via Sassari 121, Stintino, CF: 92106010900
            </h6>
        </div>
        <div>
            <h6>
            DOVERI DEL CONFRATELLO IN VIRTU' DEL REGOLAMENTO<br>
            Al compimento del diciottesimo anno d'et&#224 deve provvedere a farsi confezionare l'abito personale;
            Quando libero da impegni deve indossare l'abito e partecipare alle funzioni che la confraternita &#232;
            chiamata a svolgere. Dovr&#224 fare l'Obriere quando eletto. Chi ingiustificatamente non accetta la carica viene
            cancellato dalla confraternita. Entro il mese di Settembre di ogni anno dovr&#224 versare la quota
            contributiva presso la sede della Confraternita. La mancata offerta contributiva annuale comporta la
            cancellazione dall'associazione.
            </h6>
        </div>
        <div>
            <h6>
                DIRITTI DEL CONFRATELLO IN VIRTU' DEL REGOLAMENTO<br>
                Al Confratello passato a miglior vita vengono garantite le spese delle onoranze funebri entro il
                territorio compreso tra Stintino e Sassari. La ditta convezionata con la Confraternita a tale scopo &#232;
                la ditta "Onoranze Funebri Sechi S.a.s. di Agus Giancarlo Bruno, Largo Porta Nuova 12, Sassari
                (079-233438)". Per ulteriori dettagli si prega di consultare uno dei membri del consiglio direttivo in
                carica.
            </h6>
        </div>
        <div>
            <h6>
                NEL CASO DI PAGAMENTO CON BONIFICO BANCARIO:<br>
                Confraternita Madonna della Difesa Onlus<br>
                Via Sassari 121 - 07040 Stintino - Codice Fiscale: 92106010900<br>
                IBAN: IT38B0101565550000070135831
            </h6>
        </div>
    </div>
</div>

@endsection
