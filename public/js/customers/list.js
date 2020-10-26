/**
 * @file list.js
 * @author kain - rway07@gmail.com
 */
$(document).ready(function() {
    var tableData = null;
    $('#panel_text').text('Caricamento...');
    $.getJSON('/customers/data', function(data) {
        tableData = data;
        var table = $('#customers_table').DataTable({
            processing: true,
            pageLength: 100,
            data: tableData,
            order: [
                [1, 'asc'],
            ],
            createdRow: function(row, data) {
                if (data['death_date'] != '') {
                    $(row).addClass('table-danger');
                } else if (data['revocation_date'] != '') {
                    $(row).addClass('table-warning');
                }
            },
            columnDefs: [
                {
                    targets: 3,
                    render: function (data) {
                        return convertDate(data);
                    }
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
                    className: 'fit',
                    defaultContent: '<button type=\'button\' class=\'btn btn-info btn-sm\'> ' +
                        '<i class=\'fa fa-btn fa-edit\'></i> Modifica</button>',
                },
                {
                    targets: 8,
                    data: null,
                    className: 'fit',
                    defaultContent: '<button type=\'button\' class=\'btn btn-primary btn-sm\'> ' +
                        '<i class=\'fa fa-btn fa-users\'></i> Storico</button>',
                },
            ],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'alias', name: 'alias'},
                {data: 'birth_date', name: 'birth_date'},
                {data: 'enrollment_year', name: 'enrollment_year', searchable: false},
                {data: 'death_date', name: 'death_date', searchable: false},
                {data: 'revocation_date', name: 'revocation_date', searchable: false},
                {data: 'Modifica', name: 'Modifica', orderable: false, searchable: false},
                {data: 'Gruppo', name: 'Gruppo', orderable: false, searchable: false},
            ],
        });

        $('#customers_table').on('draw.dt', function() {
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
 *
 * @param table
 */
function addButtonEventListeners(table) {
    $('.btn-primary').off('click').on('click', function() {
        var data = table.row( $(this).parents('tr') ).data();
        group(data['id']);
    });

    $('.btn-info').off('click').on('click', function() {
        var data = table.row( $(this).parents('tr') ).data();
        edit(data['id']);
    });
}

/**
 *
 * @param table
 */
function addMouseEventListeners(table) {
    $('#customers_table > tbody > tr.table-warning').on('mouseover', function () {
        var data = table.row($(this)).data();
        if (data != null) {
            $(this).popover({
                title: 'Data Revoca',
                content: convertDate(data['revocation_date']),
                placement: 'top'
            });
            $(this).popover('show');
        }
    });

    $('#customers_table > tbody > tr.table-warning').on('mouseout', function () {
        $(this).popover('hide');
    });

    $('#customers_table > tbody > tr.table-danger').on('mouseover', function () {
        var data = table.row($(this)).data();
        if (data != null) {
            $(this).popover({
                title: 'Data Decesso',
                content: convertDate(data['death_date']),
                placement: 'top'
            });
            $(this).popover('show');
        }
    });

    $('#customers_table > tbody > tr.table-danger').on('mouseout', function () {
        $(this).popover('hide');
    });
}

/**
 * Richiesta AJAX per l'eliminazione di un socio
 *
 * @param {number} id
 */
function destroy(id) {
  $.ajax({
    type: 'DELETE',
    url: '/customers/' + id + '/delete',

    beforeSend: function(xhr) {
      var token = $('meta[name=\'csrf_token\']').attr('content');
      if (token) {
        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
      }
    },
    success: function() {
      window.location.reload();
    },
  });
}

/**
 * Apre il link per la modifica di un socio
 *
 * @param id
 */
function edit(id) {
  var url = '/customers/' + id + '/edit';
  window.open(url, '_blank');
}

/**
 * Apre il link con la vista del gruppo familiare per socio
 *
 * @param id
 */
function group(id) {
  var url = '/customers/' + id + '/summary';
  window.open(url, '_blank');
}

/**
 *
 */
function checkPageStatus() {
    var status = $('#status').val();

    if (status == 'updated') {
        showToastSuccess('Socio aggiornato!');
    } else if (status == 'saved') {
        showToastSuccess('Socio salvato!');
    }
}
