/**
 * @file summary.js
 * @author kain - rway07@gmail.com
 */

/**
 *
 * @param {int} id
 */
function customerInfo(id) {
    var url = '/customers/' + id + '/edit';
    window.open(url, '_self');
}

/**
 *
 * @param number
 * @param year
 */
function receiptInfo(number, year) {
    var url = '/receipts/' + number + '/' + year + '/edit';
    window.open(url, '_blank');
}
