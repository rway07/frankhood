/**
 *  @file offers/list.js
 *  @author kain rway07@gmail.com
 */
$(() => {
    $('#offers_table').DataTable({
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
                targets: 3,
                className: 'fit',
            },
            {
                targets: 4,
                className: 'fit',
            },
            {
                targets: 5,
                className: 'fit',
            },
        ],
    });

    $('#years').on('change', () => {
        const year = $('#years').val();
        const url = `/expenses/${year}/data`;

        $('#expenses_table').DataTable().ajax.url(url).load();
    });

    checkPageStatus();
});
