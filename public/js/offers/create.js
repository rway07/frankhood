/**
 *  @file offers/create.js
 *  @author kain rway07@gmail.com
 */
$(() => {
    const selector = document.querySelector('#offer-button');
    const validator = new JustValidate('#create_offer_form', {
        errorFieldCssClass: 'error-field',
        errorLabelCssClass: 'error-label',
        submitFormAutomatically: true,
    });

    validator
        .addField(
            '#description',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire la descrizione',
                },
            ],
            {
                errorsContainer: '#description-div',
            },
        )
        .addField(
            '#amount',
            [
                {
                    rule: 'required',
                    errorMessage: "Inserire la cifra dell'offerta",
                },
                {
                    rule: 'number',
                    errorMessage: "L'offerta deve essere un numero",
                },
            ],
            {
                errorsContainer: '#amount-div',
            },
        )
        .addField(
            '#date',
            [
                {
                    plugin: JustValidatePluginDate(() => ({
                        required: true,
                        format: 'yyyy-MM-dd',
                    })),
                    errorMessage: 'Data nel formato sbagliato',
                },
            ],
            {
                errorsContainer: '#date-div',
            },
        );

    selector.removeAttribute('disabled');
});
