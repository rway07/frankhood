<div class="modal fade" id="customer_details_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">
                    Dettagli Socio <span id="extra_info" class="badge invisible"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="receipt_details" class="modal-body">
                <div class="row row-cols-2 mb-2">
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Nome</strong></label>
                        <h6 id="name"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Alias</strong></label>
                        <h6 id="alias"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Nato il</strong></label>
                        <h6 id="birth_date"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>A</strong></label>
                        <h6 id="birth_place"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Residente in</strong></label>
                        <h6 id="address"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Città</strong></label>
                        <h6 id="country"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Telefono</strong></label>
                        <h6 id="phone"></h6>
                    </div>
                    <div class="col">
                        <label class="col-form-label-sm"><strong>Email</strong></label>
                        <h6 id="email"></h6>
                    </div>
                </div>
                <div class="row row-cols-1 mb-2">
                    <div class="col">
                        <label id="special_date_label" class="col-form-label-sm"></label>
                        <h6 id="special_date_data"></h6>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
