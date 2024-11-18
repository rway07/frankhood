/**
 * @file util.js
 * @author kain - rway07@gmail.com
 */

/**
 * Visualizza un Toast di successo
 *
 * @param {String} msg
 */
function showToastSuccess(msg) {
    const notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
        dismissible: true,
    });

    notyf.success(msg);
}

/**
 * Visualizza un toast di errore
 *
 * @param {String} msg
 */
function showToastError(msg) {
    const notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
        dismissible: true,
    });

    notyf.warning(msg);
}

/**
 *  Permette di visualizzare l'avvenuto salvataggio/aggiornamento di una risorsa
 */
export function checkPageStatus() {
    const status = $('#status').val();

    if (status !== '') {
        showToastSuccess(status);

        // Cancella lo stato una volta utilizzato
        $('#status').val('');
    }
}

/**
 *  Gestisce un errore in una richiesta AJAX di eliminazione
 *
 * @param {Object} data
 */
export function ajaxDeleteError(data) {
    const msg = `Guru meditation: (${data.status} : ${data.statusText})`;
    showToastError(msg);
}

/**
 *  Gestisce un operazione AJAX di eliminazione completata con successo
 *
 * @param {Object} response
 * @param {String} idRow
 */
export function ajaxDeleteSuccess(response, idRow) {
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
 *
 * @param message
 */
export function showModal(message) {
    $('#message_modal_text').text(message);
    $('#message_modal').modal('show');
}

/**
 *
 * @param statusCode
 * @param statusText
 * @param message
 */
export function showGuruModal(statusCode, statusText, message) {
    $('#guru_status_code').text(statusCode);
    $('#guru_status_text').text(statusText)
    $('#guru_message').text(message);
    $('#guru_modal').modal('show');
}
