/**
 * @file customers/list.js
 * @author kain - rway07@gmail.com
 */

import { checkPageStatus } from '../common/notifications';
import { convertDate} from '../common/util';
import { customerInfo} from '../common/info';
import { edit } from '../common/common';

$(() => {
    /**
     * La tabella dei soci è l'unica che viene popolata manualmente.
     * Questo per permettere di aggiungere i MouseOver/MouseOut sulle righe
     * dei soci revocati/deceduti.
     */
    $('#panel_text').text('Caricamento...');
    $.getJSON('/customers/data', (response) => {
        const tableData = response;
        const selector = $('#customers_table');
        const table = new DataTable('#customers_table', {
            processing: true,
            pageLength: 100,
            data: tableData,
            order: [[1, 'asc']],
            // Evidenzia i soci deceduti/revocati
            createdRow(row, data) {
                if (data.death_date !== '') {
                    $(row).addClass('table-danger');
                } else if (data.revocation_date !== '') {
                    $(row).addClass('table-warning');
                }
            },
            columnDefs: [
                {
                    targets: 3,
                    render(data) {
                        return convertDate(data);
                    },
                },
                {
                    targets: 5,
                    visible: false,
                },
                {
                    targets: 6,
                    visible: false,
                },
                {
                    targets: 7,
                    data: null,
                    width: '1%',
                    className: 'dt-nowrap',
                    defaultContent:
                        "<button type='button' class='btn btn-primary btn-sm customer-info'> " +
                        "<i class='fa  fa-info'></i> Info</button>",
                },
                {
                    targets: 8,
                    data: null,
                    width: '1%',
                    className: 'dt-nowrap',
                    defaultContent:
                        "<button type='button' class='btn btn-info btn-sm customer-edit'> " +
                        "<i class='fa  fa-edit'></i> Modifica</button>",
                },
                {
                    targets: 9,
                    data: null,
                    width: '1%',
                    className: 'dt-nowrap',
                    defaultContent:
                        "<button type='button' class='btn btn-secondary btn-sm customer-history'> " +
                        "<i class='fa  fa-users'></i> Storico</button>",
                },
            ],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'alias', name: 'alias' },
                { data: 'birth_date', name: 'birth_date' },
                {
                    data: 'enrollment_year',
                    name: 'enrollment_year',
                    searchable: false,
                },
                { data: 'death_date', name: 'death_date', searchable: false },
                {
                    data: 'revocation_date',
                    name: 'revocation_date',
                    searchable: false,
                },
                {
                    data: 'Info',
                    name: 'Info',
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
                    data: 'Gruppo',
                    name: 'Gruppo',
                    orderable: false,
                    searchable: false,
                },
            ],
        });

        selector.on('draw.dt', () => {
            addMouseEventListeners(table);
            addButtonEventListeners(table);
        });

        addMouseEventListeners(table);
        addButtonEventListeners(table);
        $('#panel_text').text('LISTA SOCI');
    });

    checkPageStatus();
});

/**
 *  Aggiunge gli Event Listeners ai pulsanti [Modifica] e [Gruppo]
 *
 * @param {Object} table
 */
function addButtonEventListeners(table) {
    $('.customer-history')
        .off('click')
        .on('click', (event) => {
            const data = table.row($(event.currentTarget).parents('tr')).data();
            if (data !== null) {
                group(data.id);
            }
        });

    $('.customer-edit')
        .off('click')
        .on('click', (event) => {
            const data = table.row($(event.currentTarget).parents('tr')).data();
            if (data !== null) {
                edit('customers', data.id);
            }
        });

    $('.customer-info')
        .off('click')
        .on('click', (event) => {
            const data = table.row($(event.currentTarget).parents('tr')).data();
            if (data !== null) {
                $(event.currentTarget).parents('tr').popover('hide');
                customerInfo(data.id);
            }
        });
}

/**
 *  Aggiunge gli Event Listeners per le righe dei soci revocati e
 *  deceduti. Permette di visualizzare i popup con le date correlate.
 *
 * @param {Object} table
 */
function addMouseEventListeners(table) {
    const revocatedRowsSelector = $('#customers_table > tbody > tr.table-warning');
    const deceasedRowsSelector = $('#customers_table > tbody > tr.table-danger');

    // MouseOver per i soci revocati
    revocatedRowsSelector.on('mouseover', (event) => {
        const data = table.row(event.currentTarget).data();
        if (data !== null) {
            $(event.currentTarget).popover({
                title: 'Data Revoca',
                content: convertDate(data.revocation_date),
                placement: 'top',
            });
            $(event.currentTarget).popover('show');
        }
    });

    // MouseOut per i soci revocati
    revocatedRowsSelector.on('mouseout', (event) => {
        $(event.currentTarget).popover('hide');
    });

    // MouseOver per i soci deceduti
    deceasedRowsSelector.on('mouseover', (event) => {
        const data = table.row(event.currentTarget).data();
        if (data !== null) {
            $(event.currentTarget).popover({
                title: 'Data Decesso',
                content: convertDate(data.death_date),
                placement: 'top',
            });
            $(event.currentTarget).popover('show');
        }
    });

    // MouseOut per i soci deceduti
    deceasedRowsSelector.on('mouseout', (event) => {
        $(event.currentTarget).popover('hide');
    });
}

/**
 * Apre il link con la vista del gruppo familiare per socio
 *
 * @param {Number} id
 */
function group(id) {
    const url = `/customers/${id}/summary`;
    window.open(url, '_blank');
}
