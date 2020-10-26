/**
 * @file index.js
 * @author kain
 */
$(document).ready(function() {
    $('#list_by_age_form').validate({
        rules: {
            age: {required: true, digits: true},
        },
        messages: {
            age: {required: 'Inserire l\'età'},
        },
    });

    $('#age').on('input', function() {
        loadData();
    });

    loadData();
});

/**
 *
 */
function loadData() {
    var age = parseInt($('#age').val());

    if (!isNaN(age)) {
        $.ajax({
            url: '/report/customers/age/data/' + age,
            type: 'get',
            success: function(data) {
                $data = $(data);
                $('#container').html($data);
            },
        });
    } else {
        $('#container').html('');
    }
}
