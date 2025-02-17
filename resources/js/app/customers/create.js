/**
 * @file customers/create.js
 * @author kain rway07@gmail.com
 */
const CF_LEN = 16;
const CAP_LEN = 5;

$(() => {
    const selector = document.querySelector('#customer-button');
    const validator = new JustValidate('#create_customer_form', {
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
            '#cf',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il codice fiscale',
                },
                {
                    rule: 'minLength',
                    value: CF_LEN,
                    errorMessage: `Il codice fiscale deve essere di ${CF_LEN} caratteri`,
                },
                {
                    rule: 'maxLength',
                    value: CF_LEN,
                    errorMessage: `Il codice fiscale deve essere di ${CF_LEN} caratteri`,
                },
            ],
            {
                errorsContainer: '#cf-div',
            },
        )
        .addField(
            '#birth_date',
            [
                {
                    plugin: JustValidatePluginDate(() => ({
                        required: true,
                        format: 'yyyy-MM-dd',
                        isAfter: '1900-01-01',
                    })),
                    errorMessage: 'Data di nascita in formato non valido',
                },
            ],
            {
                errorsContainer: '#birth-date-div',
            },
        )
        .addField(
            '#gender',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il sesso',
                },
            ],
            {
                errorsContainer: '#gender-div',
            },
        )
        .addField(
            '#birth_place',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il luogo di nascita',
                },
            ],
            {
                errorsContainer: '#birth-place-div',
            },
        )
        .addField(
            '#birth_province',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire la provincia di nascita',
                },
            ],
            {
                errorsContainer: '#birth-province-div',
            },
        )
        .addField(
            '#address',
            [
                {
                    rule: 'required',
                    errorMessage: "Inserire l'indirizzo",
                },
            ],
            {
                errorsContainer: '#address-div',
            },
        )
        .addField(
            '#municipality',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il comune',
                },
            ],
            {
                errorsContainer: '#municipality-div',
            },
        )
        .addField(
            '#cap',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire il CAP',
                },
                {
                    rule: 'number',
                    errorMessage: 'Il CAP deve essere numerico',
                },
                {
                    rule: 'maxLength',
                    value: CAP_LEN,
                    errorMessage: `Il CAP deve essere massimo di ${CAP_LEN} numeri`,
                },
            ],
            {
                errorsContainer: '#cap-div',
            },
        )
        .addField(
            '#province',
            [
                {
                    rule: 'required',
                    errorMessage: 'Inserire la provincia',
                },
                {
                    rule: 'maxLength',
                    value: 2,
                    errorMessage: 'La provincia deve essere di 2 caratteri',
                },
            ],
            {
                errorsContainer: '#province-div',
            },
        )
        .addField(
            '#phone',
            [
                {
                    rule: 'number',
                    errorMessage: 'Il numero di telefono deve essere composto solo da nummeri',
                },
            ],
            {
                errorsContainer: '#phone-div',
            },
        )
        .addField(
            '#mobile_phone',
            [
                {
                    rule: 'number',
                    errorMessage: 'Il numero di cellulare deve essere composto solo da numeri',
                },
            ],
            {
                errorsContainer: '#address-div',
            },
        )
        .addField(
            '#email',
            [
                {
                    rule: 'email',
                    errorMessage: 'E-mail non valida',
                },
            ],
            {
                errorsContainer: '#email-div',
            },
        )
        .addField(
            '#enrollment_year',
            [
                {
                    plugin: JustValidatePluginDate(() => ({
                        required: true,
                        format: 'yyyy',
                        isAfter: '1800',
                    })),
                    errorMessage: 'Anno di iscrizione non valido',
                },
            ],
            {
                errorsContainer: '#enrollment-year-div',
            },
        )
        .addField(
            '#death_date',
            [
                {
                    plugin: JustValidatePluginDate(() => ({
                        format: 'yyyy-MM-dd',
                        isAfter: '1800-01-01',
                    })),
                    errorMessage: 'Anno di morte non valido',
                },
            ],
            {
                errorsContainer: '#death-date-div',
            },
        )
        .addField(
            '#revocation_date',
            [
                {
                    plugin: JustValidatePluginDate(() => ({
                        format: 'yyyy-MM-dd',
                        isAfter: '1900-01-01',
                    })),
                    errorMessage: 'Anno di revoca non valido',
                },
            ],
            {
                errorsContainer: '#revocation-date-div',
            },
        );

    selector.removeAttribute('disabled');
});
