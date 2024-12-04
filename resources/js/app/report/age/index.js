/**
 * @file index.js
 * @author kain
 */

import { MIN_AGE, MAX_AGE } from '../../common/util.js';

$(() => {
    $('#age').on('input', (event) => {
        const age = event.currentTarget.value;
        loadData(age);
    });
});

/**
 *
 * @param age
 * @returns {boolean}
 */
function checkAge(age) {
    const spanSelector = document.getElementById('error-span');

    if (v8n().empty().test(age)) {
        spanSelector.innerText = "L'età non può essere nulla";
        return false;
    }

    if (!v8n().numeric().test(age)) {
        spanSelector.innerText = "L'età deve essere un numero";
        return false;
    }

    if (!v8n().between(MIN_AGE, MAX_AGE).test(age)) {
        spanSelector.innerText = 'Età in un range non valido';
        return false;
    }

    spanSelector.innerText = '';

    return true;
}

/**
 *
 * @param age
 */
function loadData(age) {
    const container = document.getElementById('container');

    if (!checkAge(age)) {
        return;
    }

    $.ajax({
        url: `/report/customers/age/data/${age}`,
        type: 'get',
        success(response) {
            if ('error' in response) {
                container.innerHTML = response.error.message;
            }

            if ('data' in response) {
                container.innerHTML = response.data.view;
            }
        },
    });
}
