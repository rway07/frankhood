<div class="modal fade" id="receipt_details_modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">Dettagli Ricevuta</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div id="receipt_details" class="modal-body">
                <div class="row row-cols-3 mb-2">
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Data Emissione</strong></label>
                        <p id="date"></p>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Numero</strong></label>
                        <p id="number"></p>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Anno</strong></label>
                        <p id="year"></p>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Quota</strong></label>
                        <p id="quota"></p>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Totale</strong></label>
                        <p id="total"></p>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Pagato con</strong></label>
                        <p id="payment"></p>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-form-label-sm"><strong>Destinatario</strong></label>
                    <div id="head" class="col"></div>
                </div>
                <div class="row mb-2">
                    <label class="col-form-label-sm"><strong>Persone</strong></label>
                    <div id="customers" class="col"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
