/**
 * @file receipts/create/draw.js
 * @author kain - rway07@gmail.com
 */

/**
 *
 * @param item
 */
export function drawSingleAlternateQuota(item) {
    const element = `quotas-${item.customers_id}`;
    $(`#${element}`).val(item.quota);
}

/**
 *
 */
export function drawAlternativeQuoteContainer() {
    const qaDiv = $('#quote_alternative_div');

    if (qaDiv.html() !== '') {
        return;
    }

    qaDiv.append(
        '<div class="card">' +
            '<div class="card-body">' +
            '<h6 class="card-title">Quote Alternative</h6>' +
            '<div id="people_container" class="row row-cols-4 mb-4">' +
            '</div>' +
            '</div>' +
            '</div>',
    );
}

/**
 *
 * @param idCustomer
 * @param customerName
 * @param quota
 */
export function drawAlternativeQuoteCustomer(idCustomer, customerName, quota) {
    $('#people_container').append(
        `<div id="customer-${idCustomer}" class="col mb-4">` +
            `<label id="${idCustomer}" class="col-form-label-sm">${customerName}</label>` +
            `<div class="input-group input-group-sm">` +
            `<span class="input-group-text">&euro;</span>` +
            `<input id="quotas-${idCustomer}" name="quotas-${idCustomer}" type="text" class="form-control form-control-sm quotas" value="${quota}"/>` +
            `</div>` +
            `</div>`,
    );
}

/**
 *
 * @param item
 * @param escape
 * @returns {string}
 */
export function drawPersonOption(item, escape) {
    return (
        `<div><b>${escape(item.first_name)} ${escape(item.last_name)}</b>  (${escape(item.alias)})` +
        ` (${escape(item.birth_date)})` +
        `</div>`
    );
}

/**
 *
 * @param item
 * @param escape
 * @returns {string}
 */
export function drawRecipientItem(item, escape) {
    return `<div>${escape(item.first_name)} ${escape(item.last_name)}</div>`;
}

/**
 *
 * @param item
 * @param escape
 * @returns {string}
 */
export function drawPeopleItem(item, escape) {
    let classText = 'customers';
    if (item.late === true) {
        classText += ' text-danger';
    }

    return (
        `<div id="${escape(item.id)}" c-name="${escape(item.first_name)} ${escape(
            item.last_name,
        )}" class="${escape(classText)}">${escape(item.first_name)} ${escape(item.last_name)} ` +
        ` <a href="/customers/${escape(item.id)}/edit" ` +
        `tabindex="-1" title="profile" target="_blank">` +
        `<i class="fa fa-pencil"></i></a> </div>`
    );
}
