/**
 * @file alternatives/index.js
 * @author kain rway07@gmail.com
 */
$(() => {
    const yearsSelector = $('#years');

    yearsSelector.on('change', (event) => {
        loadData(event.currentTarget.value);
    });

    loadData(yearsSelector.val());
});

/**
 *
 * @param year
 */
function loadData(year) {
    if (isNaN(year)) {
        return false;
    }

    $.ajax({
        url: `/report/alternatives/${year}/list`,
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
                $('#title_year').text(response.data.year);
                $('#num_people_title').text(response.data.number);
                $('#data_container').html('').append(response.data.view);

                return true;
            }

            return false;
        },
    });

    return true;
}
