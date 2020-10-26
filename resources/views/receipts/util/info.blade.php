<div class="modal fade" id="receipt_details_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Dettagli Ricevuta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="receipt_details" class="modal-body">
                <div class="row row-cols-3 mb-2">
                    <div class="col">
                        <label class="col-form-label-sm">Data Emissione</label>
                        <h6 id="date"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm">Numero</label>
                        <h6 id="number"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm">Anno</label>
                        <h6 id="year"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm">Quota</label>
                        <h6 id="quota"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm">Totale</label>
                        <h6 id="total"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm">Pagato con</label>
                        <h6 id="payment"></h6>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="col-form-label-sm">Destinatario</label>
                        <h6 id="head"></h6>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="col-form-label-sm">Persone</label>
                        <div id="customers">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>