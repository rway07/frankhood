/**
 * @file yearly/index.js
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
 * @returns {boolean}
 */
function loadData(year) {
    if (isNaN(year)) {
        return false;
    }

    $.ajax({
        url: `/closure/yearly/${year}/list`,
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
                $('#data_container').html('').append(response.data.view);
                $('#title_year').text(response.data.year);

                return true;
            }

            return false;
        },
    });

    return true;
}
