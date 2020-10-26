/**
 *
 * @param id
 */
function edit(id) {
    var url = '/rates/exceptions/' + id + '/edit';
    window.open(url, "_self")
}

/**
 *
 * @param id
 */
function destroy(id) {
    $.ajax({
        url: '/rates/exceptions/' + id + '/delete',
        type: 'DELETE',
        beforeSend: function (xhr) {
            var token = $('meta[name="csrf_token"]').attr('content');
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        error: function (data) {
            console.log('Error:', data);
            if (data.status == "500") {
                $('#error_div').append(data.responseText);
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

            $('#' + id).remove();
        }
    });
}