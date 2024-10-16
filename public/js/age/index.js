/**
 * @file index.js
 * @author kain
 */
$(() => {
    $('#list_by_age_form').validate({
        rules: {
            age: { required: true, digits: true },
        },
        messages: {
            age: {
                required: "Inserire l'età",
                digits: 'Il campo deve contenere solo numeri',
            },
        },
    });

    $('#age').on('input', () => {
        loadData();
    });

    loadData();
});

/**
 *
 */
function loadData() {
    const age = parseInt($('#age').val(), 10);

    if (isNaN(age)) {
        $('#container').html('');

        return false;
    }

    $.ajax({
        url: `/report/customers/age/data/${age}`,
        type: 'get',
        success(response) {
            if ('error' in response) {
                $('#container').html(response.error.message);
            }

            if ('data' in response) {
                $('#container').html(response.data.view);
            }
        },
    });

    return true;
}
