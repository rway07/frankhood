var table = null;
$(document).ready(function() {
    table = $("#receipts_table").DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        ajax: "/receipts/0/0/data",
        columns: [
            { data: 'receipt_number', name: 'receipt_number'},
            { data: 'date', name: 'receipts.date' },
            { data: 'name', name: 'name'},
            { data: 'year', name: 'rates.year'},
            { data: 'total', name: 'receipts.total'},
            { data: 'description', name: 'payment_types.description'},
            { data: 'Info', name: 'Info', orderable:false, searchable:false},
            { data: 'Stampa', name: 'Stampa', orderable:false, searchable:false},
            { data: 'Modifica', name: 'Modifica', orderable:false, searchable:false},
            { data: 'Elimina', name: 'Elimina', orderable:false, searchable:false},
        ],
        columnDefs: [
            {
                targets: 6,
                className: "fit",
            },
            {
                targets: 7,
                className: "fit",
            },
            {
                targets: 8,
                className: "fit",
            },
            {
                targets: 9,
                className: "fit",
            },
        ],
    });

    $("#years").change(function() {
        var year = $('#years').val();
        var type = $('#payment_types').val();

        var url = '/receipts/' + year + '/' + type + '/data';

        $('#receipts_table').DataTable().ajax.url(url).load();
    });

    $('#payment_types').change(function() {
        var type = $('#payment_types').val();
        var year = $('#years').val();

        var url = '/receipts/' + year + '/' + type + '/data';

        $('#receipts_table').DataTable().ajax.url(url).load();
    });

    checkPageStatus();
});

function edit(number, year){
    var url = '/receipts/' + number + '/' + year + '/edit';
    window.open(url, "_blank")
}

function print(number, year){
    var url = '/api/receipts/' + number + '/' + year + '/print';
    window.open(url, "_blank")
}

function info(number, year){
    var url = '/api/receipts/' + number + '/' + year + '/info';

    $.get(url, function (data) {
        var customQuotas = data['receipt']['custom_quotas'];

        $('#date').text(convertDate(data['receipt']['date']));
        $('#number').text(data['receipt']['number']);
        $('#year').text(data['receipt']['year']);
        if (customQuotas == true) {
            $('#quota').text('Alternativa');
        } else {
            $('#quota').text(data['receipt']['quota']).append(" &euro;");
        }
        $('#total').text(data['receipt']['total']).append(" &euro;");
        $('#payment').text(data['receipt']['description']);
        $('#head').text(data['receipt']['first_name'] + ' ' + data['receipt']['last_name']);

        $('#customers').html('');
        data['customers'].forEach((element) => {
            $('#customers').append(
                '<h6>' + element['first_name'] + ' ' + element['last_name'] +
                ((customQuotas == true) ? ' (' + element['quota'] + ' &euro;)' : '') +'</h6>'
            );
        });

        $('#receipt_details_modal').modal('show');
    });
}

function destroy(number, year){
    $.ajax({
        url: '/receipts/' + number + '/' + year + '/delete',
        type: 'DELETE',

        beforeSend: function (xhr) {
            var token = $('meta[name="csrf_token"]').attr('content');
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        error: function (data) {
            console.log('Error:', data);
            var msg = 'Errore interno! (' + data.status + ' : ' + data.statusText + ')';
            showToastError(msg);
        },
        success: function(data) {
            if (data.status != 'error') {
                showToastSuccess('Ricevuta eliminata.');
                var button = '#' + number + '_' + year;
                $(button).closest('tr').remove();
            } else {
                showToastError('Errore nell\'eliminazione della ricevuta.')
            }
        }
    });
}

function checkPageStatus() {
    var status = $('#status').val();

    if (status == 'updated') {
        showToastSuccess('Ricevuta aggiornata!');
    } else if (status == 'saved') {
        showToastSuccess('Ricevuta salvata!')
    }
}
