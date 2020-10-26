$(document).ready(function() {
    $('#create_exception').validate({
        rules: {
            dead_customers: {required: true},
            quota: {required: true},
            year: {required: true},
        },
        messages: {
            year: {required: 'Inserire l\'anno'},
            quota: {required: 'Inserire il costo del funerale'},
            dead_customers: 'Inserire il socio deceduto',
        },
    });

    //getCustomers();

    $('#year').change(function () {
        loadCustomers($(this).val());
    });
});

/**
 *
 */
function getCustomers() {
    var year = $('#year').val();

    if (year != null) {
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
        url: '/api/rates/exceptions/' + year + '/customers',
        type: 'get',
        error: function (data) {
            console.log(data);
        },
        success: function (data) {
            $('#dead_customers').empty();
            $.each(data, function(i, item) {
                $('#dead_customers').append(new Option(item.first_name + ' ' + item.last_name, item.id));
            });
        },
    });
}
