$(document).ready(function() {
    loadData($('#years').val());

    $('#years').change(function() {
        loadData($(this).val());
    });
});

function loadData(year) {
    $.ajax({
        url: '/closure/yearly/' + year + '/list',
        type: 'get',

        error: function (data) {
            console.log('Error:', data);
        },
        success: function (data) {
            $('#data_container')
                .html('')
                .append(data);
        }
    });
}