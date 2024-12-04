/**
 * @file deliveries/index.js
 * @author kain
 */

import {YEAR_START, YEAR_END} from '../common/util.js';
import { checkPageStatus, showGuruModal, showModal, showToastError, showToastSuccess } from '../common/notifications.js';

function enableButtons() {
    document.getElementById('delete-last').removeAttribute('disabled');
    document.getElementById('delete-all').removeAttribute('disabled');
}

function disableButtons() {
    document.getElementById('delete-last').setAttribute('disabled', 'disabled');
    document.getElementById('delete-all').setAttribute('disabled', 'disabled');
}

function loadDeliveries(year) {
    if (!v8n().numeric().between(YEAR_START, YEAR_END).test(year)) {
        return;
    }

    const url = `/deliveries/${year}/data`;

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
                document.getElementById('title_year').innerText = year;
                document.getElementById('totalAmount').innerText = response.data.totalAmount;
                document.getElementById('data_container').innerHTML = response.data.view;

                if (response.data.rows === 0) {
                    disableButtons();
                } else {
                    enableButtons();
                }
            }
        })
        .catch(error => {
            if (error instanceof Response) {
                error.json().then(data => {
                    showGuruModal(error.status, error.statusText, data.error.message)
                });
            }
        });
}

function deleteRequest(url) {
    const csrfToken = document.querySelector("[name~=csrf_token][content]").content;

    fetch(url,
        {
            method: 'delete',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then((response) => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            return response.json();
        })
        .then((response) => {
            if ('error' in response) {
                showModal(response.error.message);
                return;
            }

            if ('data' in response) {
                if (response.data.rows === 0) {
                    showToastError('Nessuna consegna eliminata.');
                    return;
                }

                showToastSuccess(response.data.message);
            }
        })
        .catch(error => {
            if (error instanceof Response) {
                error.json().then(data => {
                    showGuruModal(error.status, error.statusText, data.error.message);
                });
            }
        });

    const year = document.getElementById('years').value;
    loadDeliveries(year);
}

function deleteLastDelivery(year) {
    const url = `/deliveries/delete/${year}/last`;

    return deleteRequest(url);
}

function deleteAllDeliveries(year) {
    const url = `/deliveries/delete/${year}/all`;

    return deleteRequest(url);
}

$(() => {
    const yearsSelector = document.getElementById('years');

    yearsSelector.addEventListener('change', (event) =>  {
        loadDeliveries(event.currentTarget.value);
    });

    document.getElementById('delete-all').addEventListener('click', (event) => {
        deleteAllDeliveries(yearsSelector.value);
    });

    document.getElementById('delete-last').addEventListener('click', (event) => {
        deleteLastDelivery(yearsSelector.value);
    })

    loadDeliveries(yearsSelector.value);
    checkPageStatus();
});

