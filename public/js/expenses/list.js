var table = null;
$(document).ready(function() {
    table = $("#expenses_table").DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        ajax: '/expenses/0/data',
        columns: [
            { data: 'date', name: 'date'},
            { data: 'description', name: 'description' },
            { data: 'amount', name: 'amount'},
            { data: 'Modifica', name: 'Modifica', orderable:false, searchable:false},
            { data: 'Elimina', name: 'Elimina', orderable:false, searchable:false},
        ],
    });

    $("#years").change(function() {
        var year = $('#years').val();
        var url = '/expenses/' + year + '/data';

        $('#expenses_table').DataTable().ajax.url(url).load();
    });
});

function edit(id){
    var url = '/expenses/' + id + '/edit';
    window.open(url, '_self');
}

function destroy(id) {
    $.ajax({
        url: '/expenses/' + id + '/delete',
        type: 'DELETE',

        beforeSend: function (xhr) {
            var token = $('meta[name="csrf_token"]').attr('content');
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        error: function (data) {
            console.log('Error:', data);
            if (data.status == "error") {
                alert('Error! Please ask for guru mediation! Message: ' + data.message);
            }
        },
        success: function(data) {
            console.log('Result:', data);
            if (data.status == 'error') {
                $("#error_div").html("").append(
                    "<div class='alert alert-danger alert-dismissable' role='alert'>"
                    + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"
                    + data.message + "</div>");
            } else {
                $("#error_div").html("").append(
                    "<div class='alert alert-success alert-dismissable' role='alert'>"
                    + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"
                    + data.message + "</div>");
            }
            var button = '#ex_' + id;
            $(button).closest('tr').remove();
        }
    });
}