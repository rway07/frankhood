/**
 * @file deliveries/save.js
 * @author kain rway07@gmail.com
 */

import { DeliveryController } from './delivery.js';

let controller = null;

$(() => {
    const dateSelector = document.getElementById('date');
    const amountSelector = document.getElementById('amount');
    const validator = new JustValidate('#save_delivery_form', {
        errorFieldCssClass: 'error-field',
        errorLabelCssClass: 'error-label',
        submitFormAutomatically: true,
    });

    validator.addField(
        '#date',
        [
            {
                plugin: JustValidatePluginDate(() => ({
                    required: true,
                    format: 'yyyy-MM-dd'
                })),
                errorMessage: 'Data non valida'
            },
        ],
        {
            errorsContainer: '#date-div'
        }
    )
        .addField(
            '#amount',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire la cifra della consegna'
                },
                {
                    rule: 'number',
                    errorMessage: 'La consegna deve essere un numero'
                },
                {
                    rule: 'minNumber',
                    value: 1,
                    errorMessage: 'La consegna deve essere maggiore di zero'
                }
            ],
            {
                errorsContainer: '#amount-div'
            }
        );

    dateSelector.addEventListener('change', (event) => {
        const date = event.currentTarget.value;
        controller.setDate(date);
    });

    amountSelector.addEventListener('input', (event) => {
        controller.setAmount(event.currentTarget.value);
    });

    controller = new DeliveryController();
});
