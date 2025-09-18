/**
 * @file receipts/list.js
 * @author kain rway07@gmail.com
 */

import { ajaxDeleteError, ajaxDeleteSuccess, checkPageStatus } from '../common/notifications.js';
import { edit } from '../common/common.js';
import { receiptInfo } from '../common/info.js'

const ID_YEAR_START = 0;
const ID_YEAR_END = 4;
const ID_NUMBER_START = 5;

$(() => {
    const yearsSelector = $('#years');
    const currentYear = yearsSelector.val();

    const table = new DataTable('#receipts_table', {
        processing: true,
        serverSide: true,
        pageLength: 100,
        ajax: `/receipts/${currentYear}/0/data`,
        columns: [
            { data: 'receipt_number', name: 'receipt_number' },
            { data: 'date', name: 'receipts.date' },
            { data: 'name', name: 'name' },
            { data: 'year', name: 'rates.year' },
            { data: 'total', name: 'receipts.total' },
            {
                data: 'description',
                name: 'payment_types.description',
                searchable: false,
            },
            { data: 'Info', name: 'Info', orderable: false, searchable: false },
            {
                data: 'Stampa',
                name: 'Stampa',
                orderable: false,
                searchable: false,
            },
            {
                data: 'Modifica',
                name: 'Modifica',
                orderable: false,
                searchable: false,
            },
            {
                data: 'Elimina',
                name: 'Elimina',
                orderable: false,
                searchable: false,
            },
        ],
        columnDefs: [
            {
                targets: 3,
                className: 'dt-body-left dt-head-left',
            },
            {
                targets: 4,
                className: 'dt-body-left dt-head-left',
            },
            {
                targets: 6,
                width: '1%',
                className: 'dt-nowrap',
            },
            {
                targets: 7,
                width: '1%',
                className: 'dt-nowrap',
            },
            {
                targets: 8,
                width: '1%',
                className: 'dt-nowrap',
            },
            {
                targets: 9,
                width: '1%',
                className: 'dt-nowrap',
            },
        ],
    });

    yearsSelector.on('change', () => {
        const year = $('#years').val();
        const type = $('#payment_types').val();
        const url = `/receipts/${year}/${type}/data`;

        table.ajax.url(url).load();
    });

    $('#payment_types').on('change', () => {
        const type = $('#payment_types').val();
        const year = $('#years').val();
        const url = `/receipts/${year}/${type}/data`;

        table.ajax.url(url).load();
    });

    checkPageStatus();
});

/**
 *
 * @param {Number} number
 * @param {Number} year
 */
window.printReceipt = (number, year) => {
    const url = `/api/receipts/${number}/${year}/print`;
    window.open(url, '_blank');
}

/**
 *  Elimina una ricevuta
 *
 *  Per permettere l'uso di una vista unificata comune, il numero e l'anno
 *  della ricevuta sono combinati in unico ID che viene separato successivamente
 *
 * @param {String} combinedIdentifier
 */
window.destroyReceipt = (combinedIdentifier) => {
    const year = combinedIdentifier.slice(ID_YEAR_START, ID_YEAR_END);
    const number = combinedIdentifier.slice(ID_NUMBER_START);

    $.ajax({
        url: `/receipts/${number}/${year}/delete`,
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
            const idRow = `${year}-${number}`;
            ajaxDeleteSuccess(data, idRow);
        },
    });
}

window.receiptInfo = receiptInfo;
window.edit = edit;
