/**
 * @file statistics/index.js
 */

import { showGuruModal, showModal } from '../common/notifications.js';

/**
 *
 */
export function loadData(section) {
    const url = `/statistics/${  section  }/data`;

    return fetch(url)
        .then((response) => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            return response.json();
        })
        .then(response => {
            if ('error' in response) {
                showModal(response.error.message);
                return [];
            }

            if ('data' in response) {
                document.getElementById('data_container').innerHTML = response.data.view;
                return response.data;
            }

            return [];
        })
        .catch(error => {
            if (error instanceof Response) {
                error.json().then(data => {
                    showGuruModal(error.status, error.statusText, data.error.message);
                })
            }
        });
}
