/**
 * @file util.js
 * @author kain - rway07@gmail.com
 */

/**
 * Converte una data dal formato yyyy-mm-dd
 * nel formato dd-mm-yyyy
 *
 * @param {date} date
 * @return {string}
 */
function convertDate(date) {
    const tokens = date.split('-');
    return `${tokens[2]}/${tokens[1]}/${tokens[0]}`;
}

/**
 * Visualizza un Toast di successo
 *
 * @param {String} msg
 */
function showToastSuccess(msg) {
    toastr.options = {
        closeButton: true,
        newestOnTop: true,
        positionClass: 'toast-top-right',
        showDuration: '300',
        hideDuration: '1000',
        timeOut: '5000',
        extendedTimeOut: '1000',
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
    };
    toastr.success(msg);
}

/**
 * Visualizza un toast di errore
 *
 * @param {String} msg
 */
function showToastError(msg) {
    toastr.options = {
        closeButton: true,
        newestOnTop: true,
        positionClass: 'toast-top-right',
        showDuration: '300',
        hideDuration: '1000',
        timeOut: '5000',
        extendedTimeOut: '2000',
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
    };
    toastr.error(msg, 'Foolishness Dante');
}

/**
 *  Permette di visualizzare l'avvenuto salvataggio/aggiornamento di una risorsa
 */
function checkPageStatus() {
    const status = $('#status').val();

    if (status !== '') {
        showToastSuccess(status);

        // Cancella lo stato una volta utilizzato
        $('#status').val('');
    }
}

/**
 *  Apre il link per editare una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
function edit(subject, idSubject) {
    const url = `/${subject}/${idSubject}/edit`;
    window.open(url, '_self');
}

/**
 *  Stampa una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
function print(subject, idSubject) {
    const url = `/api/${subject}/${idSubject}/print`;
    window.open(url, '_blank');
}

/**
 *  Gestisce un errore in una richiesta AJAX di eliminazione
 *
 * @param {Object} data
 */
function ajaxDeleteError(data) {
    const msg = `Guru meditation: (${data.status} : ${data.statusText})`;
    showToastError(msg);
}

/**
 *  Gestisce un operazione AJAX di eliminazione completata con successo
 *
 * @param {Object} response
 * @param {String} idRow
 */
function ajaxDeleteSuccess(response, idRow) {
    if ('error' in response) {
        showToastError(`Errore nell'eliminazione dell'elemento: ${response.error.message}`);

        return false;
    }

    if ('data' in response) {
        showToastSuccess(response.data.message);
        const button = `#${idRow}`;
        $(button).closest('tr').remove();

        return true;
    }

    return false;
}

/**
 *  Richiesta AJAX per eliminare una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
function destroy(subject, idSubject) {
    $.ajax({
        url: `/${subject}/${idSubject}/delete`,
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
            ajaxDeleteSuccess(data, idSubject);
        },
    });
}

/**
 *
 * @param {Number} number
 * @param {Number} year
 */
function receiptInfo(number, year) {
    const url = `/api/receipts/${number}/${year}/info`;

    $.get(url, (response) => {
        const customQuotas = Boolean(response.data.receipt.custom_quotas);

        $('#date').text(convertDate(response.data.receipt.date));
        $('#number').text(response.data.receipt.number);
        $('#year').text(response.data.receipt.year);
        if (customQuotas === true) {
            $('#quota').text('Alternativa');
        } else {
            $('#quota').text(response.data.receipt.quota).append(' &euro;');
        }
        $('#total').text(response.data.receipt.total).append(' &euro;');
        $('#payment').text(response.data.receipt.description);
        $('#head').text(`${response.data.receipt.first_name} ${response.data.receipt.last_name}`);

        $('#customers').html('');
        response.data.customers.forEach((element) => {
            $('#customers').append(
                `<h6>${element.first_name} ${element.last_name}${
                    customQuotas === true ? ` (${element.quota} &euro;)` : ''
                }</h6>`,
            );
        });

        $('#receipt_details_modal').modal('show');
    });
}

/**
 *
 * @param phoneNumber
 * @param mobilePhoneNumber
 * @returns {string}
 */
function getPhoneInfo(phoneNumber, mobilePhoneNumber) {
    let phone = '--';

    if (phoneNumber !== '' && phoneNumber !== null) {
        phone = phoneNumber;
        if (mobilePhoneNumber !== '' && mobilePhoneNumber !== null) {
            phone = `${phone} - ${mobilePhoneNumber}`;
        }
    } else if (mobilePhoneNumber !== '' && mobilePhoneNumber !== null) {
        phone = mobilePhoneNumber;
    }

    return phone;
}

/**
 *
 * @param data
 */
function writeCustomerInfo(data) {
    if (data.death_date) {
        $('#special_date_label').text('Data decesso');
        $('#special_date_data').text(convertDate(data.death_date));
        $('#extra_info').removeClass('invisible', 'badge-warning').addClass('badge-danger').text('DECEDUTO');
    } else if (data.revocation_date) {
        $('#special_date_label').text('Data revoca');
        $('#special_date_data').text(convertDate(data.revocation_date));
        $('#extra_info').removeClass('invisible', 'badge-danger').addClass('badge-warning').text('REVOCATO');
    } else {
        $('#special_date_data').text('');
        $('#special_date_label').text('');
        $('#extra_info').removeClass('badge-danger', 'badge-warning').addClass('invisible').text('');
    }

    $('#name').text(`${data.first_name} ${data.last_name}`);
    $('#alias').text(data.alias);
    $('#birth_date').text(convertDate(data.birth_date));
    $('#birth_place').text(`${data.birth_place} (${data.birth_province})`);
    $('#address').text(data.address);
    $('#country').text(`${data.municipality}, ${data.CAP} (${data.province})`);

    const phone = getPhoneInfo(data.phone, data.mobile_phone);
    $('#phone').text(phone);
    if (data.email !== '' && data.email !== null) {
        $('#email').text(data.email);
    } else {
        $('#email').text('--');
    }

    $('#customer_details_modal').modal('show');
}

/**
 *
 * @param {Number} idCustomer
 */
function customerInfo(idCustomer) {
    const url = `/api/customers/${idCustomer}/info`;

    $.get(url, (response) => {
        if ('error' in response) {
            showModal(response.error.message);

            return false;
        }

        if ('data' in response) {
            writeCustomerInfo(response.data.info[0]);

            return true;
        }

        return false;
    });
}

/**
 *
 * @param message
 */
function showModal(message) {
    $('#message_modal_text').text(message);
    $('#message_modal').modal('show');
}

/**
 *
 * @param data
 */
function showGuruModal(data) {
    const serverText = `${data.status} - ${data.statusText}`;
    const message = `${data.responseJSON.error.message}`;
    $('#guru_modal_server_error').text(serverText);
    $('#guru_modal_message_text').text(message);
    $('#guru_modal').modal('show');
}
