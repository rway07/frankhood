/**
 * @file util.js
 * @author kain - rway07@gmail.com
 */

/**
 * @param {date} date
 * @return {string}
 */
function convertDate(date) {
    var tokens = date.split('-');
    return tokens[2] + '/' + tokens[1] + '/' + tokens[0];
}

/**
 * @param {String} msg
 */
function showToastSuccess(msg) {
    toastr.options = {
        'closeButton': true,
        'newestOnTop': true,
        'positionClass': 'toast-top-right',
        'showDuration': '300',
        'hideDuration': '1000',
        'timeOut': '5000',
        'extendedTimeOut': '1000',
        'showEasing': 'swing',
        'hideEasing': 'linear',
        'showMethod': 'fadeIn',
        'hideMethod': 'fadeOut',
    },
    toastr.success(msg);
}

/**
 * @param {String} msg
 */
function showToastError(msg) {
    toastr.options = {
        'closeButton': true,
        'newestOnTop': true,
        'positionClass': 'toast-top-right',
        'showDuration': '300',
        'hideDuration': '1000',
        'timeOut': '1000',
        'extendedTimeOut': '2000',
        'showEasing': 'swing',
        'hideEasing': 'linear',
        'showMethod': 'fadeIn',
        'hideMethod': 'fadeOut',
    },
    toastr.error(msg, 'Foolishness Dante');
}
