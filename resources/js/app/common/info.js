/**
 * @file common/info.js
 * @author kain - rway07@gmail.com
 */

import { convertDate } from './util';

/**
 *
 * @param {Number} number
 * @param {Number} year
 */
export function receiptInfo (number, year) {
    const url = `/api/receipts/${number}/${year}/info`;

    $.get(url, (response) => {
        const customQuotas = Boolean(parseInt(response.data.receipt.custom_quotas, 10));

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
                `<p class="mb-1">${element.first_name} ${element.last_name}${
                    customQuotas === true ? ` (${element.quota} &euro;)` : ''
                }</p>`,
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
        $('#extra_info').removeClass('invisible text-bg-warning').addClass('text-bg-danger').text('DECEDUTO');
    } else if (data.revocation_date) {
        $('#special_date_label').text('Data revoca');
        $('#special_date_data').text(convertDate(data.revocation_date));
        $('#extra_info').removeClass('invisible text-bg-danger').addClass('text-bg-warning').text('REVOCATO');
    } else {
        $('#special_date_data').text('');
        $('#special_date_label').text('');
        $('#extra_info').removeClass('text-bg-danger text-bg-warning').addClass('invisible').text('');
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

    const priorato = parseInt(data.priorato, 10);
    if (!!priorato) {
        $('#priorato_label').text('Priorato');
        $('#priotato_text').text('Si');
    }

    $('#customer_details_modal').modal('show');
}

/**
 *
 * @param {Number} idCustomer
 */
export function customerInfo(idCustomer) {
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
