/**
 *  @file new/index.js
 *  @author kain rway07@gmail.com
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
        url: '/report/customers/new/' + year + '/list',
        type: 'get',
        error: function(data) {
            console.log('Error:', data);
        },
        success: function(data) {
            $('#title_year').text(data.year);
            $('#num_people_title').text(data.num_new);
            $('#data_container')
                .html('')
                .append(data.view);
        },
    });
}
