/**
 * @file late/index.js
 * @author kain rway07@gmail.com
 */
$(document).ready(function() {
    loadData($('#years').val());

    $('#years').change(function() {
        loadData($(this).val());
    });
});

/**
 *
 * @param {number} year
 */
function loadData(year) {
    $.ajax({
        url: '/report/customers/late/' + year + '/list',
        type: 'get',
        error: function(data) {
            console.log('Error:', data);
        },
        success: function(data) {
            $('#title_year').text(data.year);
            $('#num_people_title').text(data.num_late);
            $('#data_container')
                .html('')
                .append(data.view);
        },
    });
}
