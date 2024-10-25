/**
 * @file index.js
 * @author kain
 */

const MIN_AGE = 12;
const MAX_AGE = 120;

$(() => {
    $('#age').on('input', (event) => {
        const age = event.currentTarget.value;
        if (checkAge(age)) {
            loadData(age);
        }
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
 * @returns {boolean}
 */
function loadData(age) {
    const container = document.getElementById('container');

    if (isNaN(age)) {
        container.innerHTML = '';

        return false;
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

    return true;
}
