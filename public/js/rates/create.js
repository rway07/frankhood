/**
 * @file rates/create.js
 * @author kain rway07@gmail.com
 */
$(() => {
    const selector = document.querySelector('#rates-button');
    const validator = new JustValidate('#create_rate', {
        errorFieldCssClass: 'error-field',
        errorLabelCssClass: 'error-label',
        submitFormAutomatically: true,
    });

    validator
        .addField(
            '#year',
            [
                {
                    rule: 'required',
                    errorMessage: "Inserire l'anno",
                },
                {
                    rule: 'number',
                    errorMessage: "L'anno deve essere un numero",
                },
                {
                    rule: 'minLength',
                    value: MIN_YEAR_LEN,
                    errorMessage: `L'anno deve esssere di ${MIN_YEAR_LEN} cifre`,
                },
                {
                    rule: 'maxLength',
                    value: MAX_YEAR_LEN,
                    errorMessage: `L'anno deve esssere di ${MAX_YEAR_LEN} cifre`,
                },
                {
                    rule: 'minNumber',
                    value: YEAR_START,
                    errorMessage: `L'anno deve partire almeno dal ${YEAR_START}`,
                },
                {
                    rule: 'maxNumber',
                    value: YEAR_END,
                    errorMessage: `L'anno non deve superare il ${YEAR_END}`,
                },
            ],
            {
                errorsContainer: '#year-div',
            },
        )
        .addField(
            '#quota',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire la quota',
                },
                {
                    rule: 'number',
                    errorMessage: 'La quota deve essere un numero',
                },
                {
                    rule: 'minNumber',
                    value: MIN_QUOTA,
                    errorMessage: `La quota deve essere maggiore di ${MIN_QUOTA}`,
                },
            ],
            {
                errorsContainer: '#quota-div',
            },
        )
        .addField(
            '#funeral_cost',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il costo del funerale',
                },
                {
                    rule: 'number',
                    errorMessage: 'Il costo del funerale deve essere un numero',
                },
                {
                    rule: 'minNumber',
                    value: MIN_FUNERAL_COST,
                    errorMessage: `Il costo del funerale deve essere almeno di ${MIN_FUNERAL_COST}`,
                },
            ],
            {
                errorsContainer: '#funeral-cost-div',
            },
        );

    selector.removeAttribute('disabled');
});
