/**
 * @file rates/exceptions/create.js
 * @author kain rway07@gmail.com
 */
$(() => {
    $('#create_exception').validate({
        rules: {
            dead_customers: { required: true },
            quota: { required: true, number: true },
            year: { required: true, digits: true },
        },
        messages: {
            year: { required: "Inserire l'anno" },
            quota: { required: 'Inserire il costo del funerale' },
            dead_customers: 'Inserire il socio deceduto',
        },
    });

    $('#year').on('change', (event) => {
        loadCustomers(event.currentTarget.value);
    });
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
