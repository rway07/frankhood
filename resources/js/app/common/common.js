/**
 * @file common/common.js
 * @author kain - rway07@gmail.com
 */

/**
 *  Apre il link per editare una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
export function edit(subject, idSubject) {
    const url = `/${subject}/${idSubject}/edit`;
    window.open(url, '_self');
}

/**
 *  Stampa una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
export function print(subject, idSubject) {
    const url = `/api/${subject}/${idSubject}/print`;
    window.open(url, '_blank');
}

/**
 *  Richiesta AJAX per eliminare una risorsa
 *
 * @param {String} subject
 * @param {String} idSubject
 */
export function destroy(subject, idSubject) {
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
