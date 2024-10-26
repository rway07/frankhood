/**
 * @file expenses/create.js
 * @author kain rway07@gmail.com
 */

$(() => {
    const selector = document.querySelector('#expense-button');
    const validator = new JustValidate('#create_expense_form', {
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
                    errorMessage: 'Inserire la cifra della spesa',
                },
                {
                    rule: 'number',
                    errorMessage: 'La spesa deve essere un numero',
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
