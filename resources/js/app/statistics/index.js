/**
 * @file statistics/index.js
 */

import { showGuruModal, showModal } from '../common/notifications.js';

$(() => {
   loadData();
});

/**
 *
 */
function loadData() {
    const section = $('#section').val();
    const url = `/statistics/${  section  }/data`;

    fetch(url)
        .then((response) => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            return response.json();
        })
        .then(response => {
            if ('error' in response) {
                showModal(response.error.message);
                return;
            }

            if ('data' in response) {
                document.getElementById('data_container').innerHTML = response.data.view;
            }
        })
        .catch(error => {
            if (error instanceof Response) {
                error.json().then(data => {
                    showGuruModal(error.status, error.statusText, data.error.message);
                })
            }
        });
}
