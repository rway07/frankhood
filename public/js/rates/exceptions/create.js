/**
 * @file rates/exceptions/create.js
 * @author kain rway07@gmail.com
 */
const MIN_YEAR_LEN = 4;
const MAX_YEAR_LEN = 4;

$(() => {
    const selector = document.querySelector('#exception-button');
    const validator = new JustValidate('#create_exception', {
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
            ],
            {
                errorsContainer: '#year-div',
            },
        )
        .addField(
            '#dead_customers',
            [
                {
                    rule: 'required',
                },
            ],
            {
                errorsContainer: '#dead-customers-div',
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
                    value: 1000,
                    errorMessage: `La quota deve essere maggiore di 1000`,
                },
            ],
            {
                errorsContainer: '#quota-div',
            },
        );

    $('#year').on('change', (event) => {
        loadCustomers(event.currentTarget.value);
    });

    selector.removeAttribute('disabled');
});

/**
 *  Carica i soci deceduti in base all'anno
 */
function getCustomers() {
    const year = $('#year').val();

    // FIXME Check year
    if (!isNaN(year)) {
        loadCustomers(year);
    }
}

/**
 *  Carica la lista dei soci morti
 *
 * @param year
 */
function loadCustomers(year) {
    $.ajax({
        url: `/api/rates/exceptions/${year}/customers`,
        type: 'get',
        error(response) {
            showGuruModal(response);
        },
        success(response) {
            if ('error' in response) {
                showModal(response.error.message);
                return false;
            }
            if ('data' in response) {
                $('#dead_customers').empty();
                $.each(response.data.customers, (index, item) => {
                    $('#dead_customers').append(new Option(`${item.first_name} ${item.last_name}`, item.id));
                });

                return true;
            }

            return false;
        },
    });
}
