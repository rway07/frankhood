/**
 * @file common/common.js
 * @author kain - rway07@gmail.com
 */

import { showGuruModal, showModal } from '@/app/common/notifications.js';

/**
 *  Apre il link per editare una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
export function edit(subject, idSubject) {
    const url = `/${subject}/${idSubject}/edit`;
    window.open(url, '_self');
}

/**
 *  Stampa una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
export function print(subject, idSubject) {
    const url = `/api/${subject}/${idSubject}/print`;
    window.open(url, '_blank');
}

/**
 *  Richiesta AJAX per eliminare una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
export function destroy(subject, idSubject) {
    $.ajax({
        url: `/${subject}/${idSubject}/delete`,
        type: 'DELETE',
        beforeSend(xhr) {
            const token = $("meta[name='csrf_token']").attr('content');
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        error(data) {
            ajaxDeleteError(data);
        },
        success(data) {
            ajaxDeleteSuccess(data, idSubject);
        },
    });
}

export function loadView(url) {
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
