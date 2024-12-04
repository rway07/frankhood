/**
 * @file revocated/index.js
 * @author kain rway07@gmail.com
 */

import { YEAR_START, YEAR_END } from '../../common/util.js';
import { showModal, showGuruModal } from '../../common/notifications.js';

$(() => {
    const yearsSelector = $('#years');
    yearsSelector.on('change', (event) => {
        loadData(event.currentTarget.value);
    });

    loadData(yearsSelector.val());
});

/**
 *
 * @param {number} year
 */
function loadData(year) {
    if (!v8n().numeric().between(YEAR_START, YEAR_END).test(year)) {
        return;
    }

    $.ajax({
        url: `/report/customers/revocated/${year}/list`,
        type: 'get',
        error(response) {
            showGuruModal(response.status, response.statusText, response.responseJSON.message);
        },
        success(response) {
            if ('error' in response) {
                showModal(response.error.message);
                return false;
            }

            if ('data' in response) {
                $('#title_year').text(response.data.year);
                $('#num_people_title').text(response.data.num_revocated);
                $('#data_container').html('').append(response.data.view);

                return true;
            }

            return false;
        },
    });
}
