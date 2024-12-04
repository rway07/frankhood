/**
 *  @file offers/list.js
 *  @author kain rway07@gmail.com
 */
import { destroy, edit, print} from '../common/common.js';
import { checkPageStatus } from '../common/notifications.js';

window.destroy = destroy;
window.edit = edit;
window.print = print;

$(() => {
    const table = new DataTable('#offers_table', {
        processing: true,
        serverSide: true,
        pageLength: 100,
        ajax: '/offers/0/data',
        columns: [
            { data: 'date', name: 'date' },
            { data: 'description', name: 'description' },
            { data: 'amount', name: 'amount' },
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
                targets: 2,
                className: 'dt-body-left dt-head-left',
            },
            {
                targets: 3,
                width: '1%',
                className: 'dt-nowrap',
            },
            {
                targets: 4,
                width: '1%',
                className: 'dt-nowrap',
            },
            {
                targets: 5,
                width: '1%',
                className: 'dt-nowrap',
            },
        ],
    });

    $('#years').on('change', () => {
        const year = $('#years').val();
        const url = `/offers/${year}/data`;

        table.ajax.url(url).load();
    });

    checkPageStatus();
});
