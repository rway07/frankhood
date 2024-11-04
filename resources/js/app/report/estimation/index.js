/**
 * @file report/estimation/index.js
 * @author kain rway07@gmail.com
 */

$(() => {
    const selector = document.querySelector('#estimation-button');
    const validator = new JustValidate('#estimation_form', {
        errorFieldCssClass: 'error-field',
        errorLabelCssClass: 'error-label',
        submitFormAutomatically: true,
    });

    validator
        .addField(
            '#first_name',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il nome',
                },
            ],
            {
                errorsContainer: '#first-name-div',
            },
        )
        .addField(
            '#last_name',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il cognome',
                },
            ],
            {
                errorsContainer: '#last-name-div',
            },
        )
        .addField(
            '#birth_date',
            [
                {
                    plugin: JustValidatePluginDate(() => ({
                        required: true,
                        format: 'yyyy-MM-dd',
                        isAfter: '1800-01-01',
                    })),
                    errorMessage: 'Data nel formato sbagliato',
                },
            ],
            {
                errorsContainer: '#birth-date-div',
            },
        )
        .addField(
            '#years',
            [
                {
                    rule: 'required',
                    errorMessage: "Inserire l'anno",
                },
                {
                    rule: 'number',
                    errorMessage: "L'anno deve essere un numero",
                },
            ],
            {
                errorsContainer: '#year-div',
            },
        );

    selector.removeAttribute('disabled');
});
