/**
 * @file receipts/create.js
 * @author kain - rway07@gmail.com
 */

import { Action, QuotaChangeDirection, QuotaTypes, UIStatus } from './data.js';
import {
    drawAlternativeQuoteContainer,
    drawAlternativeQuoteCustomer,
    drawPeopleItem,
    drawPersonOption,
    drawRecipientItem,
    drawSingleAlternateQuota,
} from './draw.js';

const ZERO = 0;
const ONE = 1;

let $people = null;
let $recipient = null;
let peopleNum = 0;
let rateQuota = 0;
let total = 0;
const status = new UIStatus();

// Let's go
$(() => {
    const $recipientSelector = $('#recipient');
    const $receiptFormSelector = $('#create_receipt_form');
    // Minimum data validation
    $receiptFormSelector.validate({
        rules: {
            'issue-date': { required: true },
            recipient: { required: true },
            rates: { required: true },
            total: { required: true, number: true },
        },
        messages: {
            'issue-date': { required: 'Inserire la data' },
            recipient: { required: 'Inserire il destinatario' },
            rates: { required: 'Inserire la quota' },
            total: { required: 'Inserire il totale' },
        },
    });

    // Events handlers
    // Load a new rate on year change
    $('#rates').on('change', () => {
        loadRate();
    });

    // On recipient change, check if he has any receipt and load his family group
    $recipientSelector.on('change', (event) => {
        recipientChange(event.currentTarget.value);
    });

    // On type of quota change
    $('#quota_type').on('change', () => {
        changeQuotaType();
    });

    // Form submit
    $receiptFormSelector.on('submit', (event) => {
        // Always validate
        if (!validateInput()) {
            event.preventDefault();

            return false;
        }

        saveReceipt(event);

        return true;
    });

    // Selectize the recipient textbox
    $recipient = $recipientSelector.selectize({
        valueField: 'id',
        searchField: ['first_name', 'last_name', 'alias'],
        persist: false,
        create: false,
        closeAfterSelect: true,
        render: {
            option(item, escape) {
                return drawPersonOption(item, escape);
            },
            item(item, escape) {
                return drawRecipientItem(item, escape);
            },
        },
        load(query, callback) {
            loadPerson(query, callback);
        },
    });

    // Selectize the people textbox
    $people = $('#people').selectize({
        valueField: 'id',
        searchField: ['first_name', 'last_name', 'alias'],
        create: false,
        delimiter: ',',
        hideSelected: true,
        plugins: ['remove_button'],
        persist: false,
        maxOptions: 15,
        openOnFocus: false,
        render: {
            option(item, escape) {
                return drawPersonOption(item, escape);
            },
            item(item, escape) {
                return drawPeopleItem(item, escape);
            },
        },
        onItemAdd(value) {
            peopleNum += ONE;
            // Check for alternate quota and always update the total
            checkSingleQuotaType(Action.Add, value);
        },
        onItemRemove(value) {
            peopleNum -= ONE;
            // Reset the UI if the user removes all the people
            if (peopleNum === ZERO) {
                resetUI();
            }
            // Come sopra
            checkSingleQuotaType(Action.Remove, value);
        },
        load(query, callback) {
            loadPerson(query, callback);
        },
    });

    // Init page
    initStatus();
});

/**
 * Validate the user input before form submit
 *
 * @returns {boolean}
 */
function validateInput() {
    // Invalid rate quota
    if (rateQuota === ZERO) {
        disableForm('Quota uguale a zero');
        return false;
    }

    // Invalid total
    if (total === ZERO) {
        disableForm('Totale uguale a zero');
        return false;
    }

    // Invalid recipient
    const idRecipient = $('#recipient').val();
    if (idRecipient === '') {
        disableForm('Destinatario mancante');
        return false;
    }

    // Invalid people number
    if (peopleNum === ZERO) {
        disableForm('Nessun gruppo caricato');
        return false;
    }

    // Check if all the alternative quotas are filled and valid
    let isQuotaValid = true;
    if (status.isQuotaAlternate()) {
        $('.quotas').each((index, element) => {
            const quotaValue = element.value;
            if (quotaValue === '' || isNaN(quotaValue)) {
                isQuotaValid = false;
                // FIXME check
                // disableForm(`Quota alternativa n. ${index} non valida`);

                return false;
            }
        });
    }

    return isQuotaValid;
}

/**
 * Save a receipt or update an existing one
 *
 * @param event
 */
function saveReceipt(event) {
    const receiptsData = $('#create_receipt_form').serialize();
    const $receiptButtonSelector = $('#receipt_button');
    const $buttonIconSelector = $('#button_icon');
    let method = 'post';
    let url = '/receipts/store';

    // Fix for double click double receipt issue
    $receiptButtonSelector.prop('disabled', true);
    if (status.isEditModeActive()) {
        const year = $('#rates option:selected').text();
        const number = $('#receipt_number').val();
        url = `/receipts/${number}/${year}/update`;
        method = 'put';
    }

    $.ajax({
        url,
        type: method,
        dataType: 'json',
        data: receiptsData,
        error(response) {
            showGuruModal(response);
        },
        success(response) {
            if ('error' in response) {
                $buttonIconSelector.removeClass().addClass('fa fa-warning');
                showModal(response.error.message);
                disableForm(response.error.message);

                return false;
            }

            if ('data' in response) {
                // Reset error div
                resetWarnings();

                // Display success on submit button
                $receiptButtonSelector.removeClass('btn-primary').addClass('btn-success');
                $buttonIconSelector.removeClass().addClass('fa fa-check');

                // Open the receipt pdf
                const printUrl = `/api/receipts/${response.data.number}/${response.data.year}/print`;
                window.open(printUrl, '_blank');

                // Set the status message
                $('#status').val(response.data.message);

                // Trigger the done form
                $('#done_form').trigger('submit');

                return true;
            }

            return false;
        },
    });

    // Display the spinning wheel on the submit button and disable the form until AJAX response arrive
    $receiptButtonSelector.attr('disabled', 'disabled');
    $buttonIconSelector.removeClass().addClass('fa fa-cog fa-spin fa-1x fa-fw');
    event.preventDefault();
}

/**
 *  Update the total amount of the receipt
 */
function updateGroupTotal() {
    if (peopleNum === ZERO || rateQuota === ZERO) {
        initTotal();
        return false;
    }

    total = ZERO;
    if (status.isQuotaAlternate()) {
        $('.quotas').each((index, element) => {
            const quota = parseFloat(element.value);
            if (isNaN(quota)) {
                disableForm('Input non valido');
                return;
            }

            total += quota;
        });
    } else {
        total = peopleNum * rateQuota;
    }

    $('#total').val(total);

    if (total !== ZERO) {
        enableForm();
    }

    return true;
}

/**
 *
 */
function recipientChange(value) {
    // If the user remove the recipient, reset the UI
    if (value === '') {
        resetUI();

        return;
    }

    // For the new recipient, check if he already has a receipt for the current year
    checkReceiptForCurrentYear();
    // Load his family group regardless
    loadGroup();
}

// Load data
/**
 * Check for customers names given the user input
 *
 * @param query
 * @param callback
 * @returns {*}
 */
function loadPerson(query, callback) {
    if (!query.length) {
        return callback();
    }

    const year = $('#rates option:selected').text();
    const exclude = parseInt($('#receipt_number').val(), 10);

    $.ajax({
        url: `/api/customers/${year}/${exclude}/names`,
        type: 'GET',
        dataType: 'json',
        data: {
            name: query,
        },
        error(error) {
            showGuruModal(error);
            callback();
        },
        success(response) {
            if ('error' in response) {
                showModal(response.error.message);
                return;
            }

            if ('data' in response) {
                callback(response.data.names);
            }
        },
    });
}

/**
 * Load a new rate on year change
 */
function loadRate() {
    const idRate = $('#rates').val();
    $.ajax({
        url: `/api/rates/${idRate}/quota`,
        type: 'GET',
        async: false,
        dataType: 'json',
        error(response) {
            showGuruModal(response);
        },
        success(response) {
            if ('error' in response) {
                showModal(response.error.message);
                return false;
            }

            if ('data' in response) {
                // Set the rate quota
                rateQuota = response.data.quota;
                $('#quota').val(rateQuota);

                // If the user is changing year when he has already loaded a group
                // Check if the recipient already has a receipt
                checkReceiptForCurrentYear();

                // Update the total given the new rate
                updateGroupTotal();

                return true;
            }

            return false;
        },
    });
}

/**
 * Load the family's group
 */
function loadGroup() {
    const idRecipient = $('#recipient').val();
    const year = $('#rates option:selected').text();
    const peopleControl = $people[0].selectize;

    // If the recipient is somehow not set, exit
    if (idRecipient === '') {
        return false;
    }

    // Reset the people selectize and number
    resetPeople();

    // Set the group loading flag
    status.setGroupLoading(true);
    $.ajax({
        url: `/api/customers/${idRecipient}/${year}/${status.isEditModeActive()}/group`,
        type: 'GET',
        dataType: 'json',
        error(error) {
            showGuruModal(error);
        },
        success(result) {
            if ('error' in result) {
                showModal(result.error.message);
                status.setGroupLoading(false);
                return false;
            }

            if ('data' in result) {
                // For each person, it adds it to the people selectize control
                const group = Array.from(result.data.groups);
                group.forEach((entry) => {
                    peopleControl.addOption(entry);
                    peopleControl.addItem(entry.id, false);
                });

                // Check the type of quota
                checkGroupQuotaType();

                // Unset the group loading flag
                status.setGroupLoading(false);

                // Enable input on people selectize control
                enablePeopleInput();

                return true;
            }

            return false;
        },
    });

    return true;
}

/**
 *  Set edit mode
 */
function setEditMode() {
    const action = $('#create_receipt_form').attr('action');
    const editFlag = action !== '/receipts/store';

    if (editFlag) {
        status.enableEditMode();
    }

    if (status.isEditModeActive()) {
        const ratesSelector = $('#rates');
        ratesSelector.prop('disabled', true);
        const rateValue = ratesSelector.val();
        $('#rates_div').append(`<input type="hidden" id="rates" name="rates" value="${rateValue}">`);

        loadEditData();
    }
}

/**
 * Load data if the edit mode is active
 */
function loadEditData() {
    if (!status.isEditModeActive()) {
        return;
    }

    const idRecipient = $('#recipient_id').val();
    $.ajax({
        url: `/api/customers/${idRecipient}/recipient`,
        type: 'GET',
        dataType: 'json',
        error(result) {
            showGuruModal(result);
        },
        success(result) {
            if ('error' in result) {
                showModal(result.error.message);
                return false;
            }

            if ('data' in result) {
                const recipientSelectize = $recipient[0].selectize;
                recipientSelectize.addOption(result.data.recipient);
                recipientSelectize.addItem(result.data.recipient.id, false);

                return true;
            }

            return false;
        },
    });
}

/**
 *
 * @param idCustomer
 * @param number
 * @param year
 */
function getCustomerAlternateQuota(idCustomer, number, year) {
    $.ajax({
        url: `/api/receipts/${idCustomer}/${number}/${year}/quota`,
        type: 'GET',
        async: true,
        dataType: 'json',
        error(response) {
            showGuruModal(response);
        },
        success(response) {
            if ('error' in response) {
                showModal(response.error.message);
                return false;
            }

            if ('data' in response) {
                if (idCustomer === ZERO) {
                    response.data.forEach((item) => {
                        drawSingleAlternateQuota(item);
                    });
                } else {
                    drawSingleAlternateQuota(response.data);
                }

                updateGroupTotal();

                return true;
            }

            return false;
        },
    });
}

// Misc.
// ------------------------------------------------------------------
/**
 * Check if there is a receipt for the selected year
 * In the current state, it disables the form if the recipient already has a receipt
 * Edit mode is not supported from here
 * FIXME Add edit mode support
 */
function checkReceiptForCurrentYear() {
    const idRecipient = $('#recipient').val();

    // Perform the AJAX request only if the id is set
    if (idRecipient === '' || status.isEditModeActive()) {
        return false;
    }

    const year = $('#rates option:selected').text();

    $.ajax({
        url: `/api/receipts/years/${idRecipient}/${year}`,
        type: 'GET',
        dataType: 'json',
        error(error) {
            showGuruModal(error);
        },
        success(response) {
            if ('error' in response) {
                showModal(response.error.message);
                return false;
            }

            if ('data' in response) {
                if (Array.isArray(response.data) && response.data.length) {
                    disableForm("Ricevuta già presente per l'anno selezionato");
                }

                enableForm();

                return true;
            }

            return false;
        },
    });

    return true;
}

/**
 *  Logic after type of quota change
 */
function changeQuotaTypeAftermath() {
    const idRecipient = $('#recipient').val();

    removeAllQuoteAlternative();

    if (idRecipient === '') {
        return;
    }

    if (status.getDirection() === QuotaChangeDirection.NormalToAlternative) {
        addAllQuoteAlternative();
    }

    updateGroupTotal();
}

/**
 * Logic on type of quota change
 */
function changeQuotaType() {
    const newStatus = parseInt($('#quota_type').val(), 10);

    if (newStatus === status.getQuotaType()) {
        return;
    }

    if (newStatus === QuotaTypes.Alternative) {
        status.setDirectionNormalToAlternate();
    } else {
        status.setDirectionAlternateToNormal();
    }

    status.setQuotaType(newStatus);
    status.setQuotaTypeChanged(true);
    changeQuotaTypeAftermath();
    status.setQuotaTypeChanged(false);
}

/**
 *
 * @param action
 * @param idCustomer
 * @returns {boolean}
 */
function checkSingleQuotaType(action, idCustomer) {
    if (status.isGroupLoading()) {
        return false;
    }

    if (status.isQuotaAlternate()) {
        // Add/remove single customer
        if (action === Action.Add) {
            addSingleQuoteAlternative(idCustomer);

            return true;
        }
        removeSingleQuoteAlternative(idCustomer);
    }

    // Update total for a single customer add/remove
    updateGroupTotal();

    return true;
}

/**
 *  Enable/disable the UI elements for the alternative quota
 */
function checkGroupQuotaType() {
    if (!status.isGroupLoading()) {
        return false;
    }

    if (status.isQuotaAlternate()) {
        removeAllQuoteAlternative();
        addAllQuoteAlternative();

        return true;
    }

    updateGroupTotal();

    return true;
}

/**
 *
 * @param idCustomer
 */
function addSingleQuoteAlternative(idCustomer) {
    drawAlternativeQuoteContainer();

    const customerName = $(`#${idCustomer}`).attr('c-name');
    drawAlternativeQuoteCustomer(idCustomer, customerName, rateQuota);

    // Init custom quotas event handlers and validation rules
    addCustomerEventListener(idCustomer);

    // Exit if the user is not in edit mode
    if (!status.isEditModeActive()) {
        return;
    }

    // Load the alternate quota for the current customer
    const year = $('#rates option:selected').text();
    const number = $('#receipt_number').val();

    getCustomerAlternateQuota(idCustomer, number, year);
}

/**
 *  Add alternative Quota elements to the UI
 */
function addAllQuoteAlternative() {
    const quota = $('#quota').val();

    drawAlternativeQuoteContainer();

    // For each customer, add the necessary element
    $('.customers').each((index, element) => {
        const customerName = element.getAttribute('c-name');
        const idCustomer = element.getAttribute('data-value');

        drawAlternativeQuoteCustomer(idCustomer, customerName, quota);
    });

    // Add the necessary event listeneres and validation rules
    initQuotas();

    // If we are in edit mode, load the customers quota
    if (!status.isEditModeActive()) {
        return true;
    }

    const year = $('#rates option:selected').text();
    const number = $('#receipt_number').val();

    // Load the alternate quotas for the customer group
    getCustomerAlternateQuota(ZERO, number, year);

    return true;
}

// Enable / Disable
// ----------------------------------------------------------------------------

/**
 * Disable the main form and display an alert message
 *
 * @param message
 */
function disableForm(message) {
    const selector = $('#alert');

    $('#receipt_button').attr('disabled', 'disabled');
    selector.text(message);
    selector.addClass('text-bg-danger');
}

/**
 * Enable the form and reset all messages
 */
function enableForm() {
    resetWarnings();
    $('#receipt_button').removeAttr('disabled');
}

/**
 * Lock the people selectize input
 */
function disablePeopleInput() {
    //$("#people")[0].selectize.lock();
    $people[0].selectize.lock();
}

/**
 *
 */
function enablePeopleInput() {
    //$("#people")[0].selectize.unlock();
    $people[0].selectize.unlock();
}

// Init
// ----------------------------------------------------------------------------
/**
 * Init custom quotas event listeners and form validators
 */
function initQuotas() {
    addEventListener();
    // AddValidationRule();
}

/**
 * Set the inital status of the page
 */
function initStatus() {
    // Set the initial quota type, determined server-side
    const type = $('#quota_type').val();
    status.setQuotaType(type);

    // Disable people input
    disablePeopleInput();

    // Load the rates
    loadRate();

    // Check if the user is in edit mode
    setEditMode();

    // Initialize the event handlers for the custom quotas
    initQuotas();

    // Enable the main button
    $('#receipt_button').removeAttr('disabled');
}

/**
 *  Initialize the total letiable and field to 0
 */
function initTotal() {
    total = ZERO;
    $('#total').val(total);
}

// Reset
// ----------------------------------------------------------------------------
/**
 *
 */
function resetPeople() {
    $people[0].selectize.clearOptions();
    peopleNum = ZERO;
}

/**
 * Reset selectize UI elements and alternative quotas
 */
function resetUI() {
    $recipient[0].selectize.clearOptions();
    resetPeople();
    removeAllQuoteAlternative();
    disablePeopleInput();
    enableForm();
    initTotal();
}

/**
 *
 */
function resetWarnings() {
    const selector = $('#alert');
    selector.text('');
    selector.removeClass('text-bg-danger');
}

/**
 *  Remove alternative quotas UI elements
 */
function removeAllQuoteAlternative() {
    $('#quote_alternative_div').html('');
}

/**
 * Remove a single alternative quota form
 *
 * @param idCustomer
 */
function removeSingleQuoteAlternative(idCustomer) {
    const selector = `#customer-${idCustomer}`;
    $(selector).remove();
}

// Event listeners
// ----------------------------------------------------------------------------
/**
 *
 * @param idCustomer
 */
function addCustomerEventListener(idCustomer) {
    const selector = `#quotas-${idCustomer}`;
    $(selector).on('input change', () => {
        if (!status.isGroupLoading()) {
            updateGroupTotal();
        }
    });
}

/**
 * Add event listener for custom quota textbox
 */
function addEventListener() {
    $('.quotas').each((index, element) => {
        $(element).on('input change', (event) => {
            if (status.isGroupLoading()) {
                return;
            }

            const currentValue = event.currentTarget.value;
            if (isNaN(currentValue) || currentValue === '') {
                disableForm('Input non valido');
                return;
            }
            updateGroupTotal();
        });
    });
}
