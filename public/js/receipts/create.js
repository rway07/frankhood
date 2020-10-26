/**
 * @file create.js
 * @author kain - rway07@gmail.com
 */
var peopleNum = 0;
var rate = 0;
var $recipient = null;
var $people = null;
var edit = null;

// Let's go
$(document).ready(function() {
    // Form validation
    $('#create_receipt_form').validate({
        rules: {
            issue_date: {required: true},
            recipient: {required: true},
            rates: {required: true},
            total: {required: true, number: true},
        },
        messages: {
            first_name: {required: 'Inserire il Nome'},
        },
    });

    // Events handlers
    // ----------------------------------------------
    // Load new rate on year change
    $('#rates').change(function() {
        loadRate();
    });

    //
    $('#recipient').change(function() {
        checkYears();
        loadGroup();
    });

    /* Alternative quote logic */
    $('#quota_type').change(function() {
        alternativeQuoteCheck();
    });
    /* --- */

    // Logic on form submit
    $('#create_receipt_form').submit(function(event) {
        var receiptsData = $('#create_receipt_form').serialize();
        var url = '/receipts/store';
        var method = 'post';
        var year = null;
        var number = null;

        if (edit) {
            year = $('#rates option:selected').text();
            number = $('#receipt_number').val();
            url = '/receipts/' + number + '/' + year + '/update';
            method = 'put';
        }

        $.ajax({
            url: url,
            type: method,
            dataType: 'json',
            data: receiptsData,
            error: function(data) {
                $('#button_icon').removeClass().addClass('fa fa-warning');
                $('#error_div').html('').append(
                    '<div class="alert alert-danger" role="alert">' +
                    '<strong>Errore:</strong> ' + data.responseText +
                    '</div>');
            },
            success: function(data) {
                $('#error_div').html('');
                $('#receipt_button')
                    .removeClass('btn-primary')
                    .addClass('btn-success');

                $('#button_icon').removeClass().addClass('fa fa-check');
                var printUrl = '/api/receipts/' + data.number + '/' + data.year + '/print';

                window.open(printUrl, '_blank');
                (edit) ? $('#status').val('updated') : $('#status').val('saved');
                $('#done_form').submit();
            },
        });

        $('#receipt_button').attr('disabled', true);
        $('#button_icon')
            .removeClass()
            .addClass('fa fa-cog fa-spin fa-1x fa-fw');
        event.preventDefault();
    });

    $recipient = $('#recipient').selectize({
        valueField: 'id',
        searchField: ['first_name', 'last_name', 'alias'],
        persist: false,
        create: false,
        closeAfterSelect: true,
        render: {
            option: function(item, escape) {
                return '<div>' + escape(item.first_name) + ' ' +
                    escape(item.last_name) +
                    (item.alias ? ' (' + escape(item.alias) + ')' : '') +
                    ' (' + escape(item.birth_date) + ')' + '</div>';
            },
            item: function(item, escape) {
                return '<div>' +escape(item.first_name) + ' ' +
                    escape(item.last_name) +'</div>';
            },
        },
        load: function(query, callback) {
            if (!query.length) return callback();

            $.ajax({
                url: '/api/customers/names/' + $('#rates option:selected').text(),
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.data);
                },
            });
        },
    });

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
            option: function(item, escape) {
                return '<div><b>' +escape(item.first_name) + ' ' +
                    escape(item.last_name) +
                    '</b>  (' + escape(item.alias) + ')' +
                    ' (' + escape(item.birth_date) + ')' + '</div>';
            },
            item: function(item, escape) {
                return '<div c-name="'+ escape(item.first_name) + ' ' +
                    escape(item.last_name) +'" class="customers">' +
                    escape(item.first_name) + ' ' +
                    escape(item.last_name) + ' ' +
                    ' <a href="/customers/' + escape(item.id) + '/edit" ' +
                    'tabindex="-1" title="profile" target="_blank">' +
                    '<i class="fa fa-btn fa-edit"></i></a></div>';
            },
        },
        onItemAdd: function() {
            peopleNum += 1;
            alternativeQuoteCheck();
            updateTotal();
        },
        onItemRemove: function() {
            peopleNum -= 1;
            alternativeQuoteCheck();
            updateTotal();
        },
        load: function(query, callback) {
            if (!query.length) return callback();

            $.ajax({
                url: '/api/customers/names/' + $('#rates option:selected').text(),
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.data);
                },
            });
        },
    });

    setEditMode();
    loadRate();
    initializeTotal();
    loadData();
    addEventListener();
    addValidationRule();
});

/**
 * Add event listener for custom quota textbox
 */
function addEventListener() {
    $('.quotas').each(function() {
        $(this).on('input change', function() {
            updateTotal();
        });
    });
}

/**
 * Add validation rules for the custom quota textboxs
 */
function addValidationRule() {
    $('.quotas').each(function() {
        $(this).rules('add', {
            required: true,
            number: true,
        });
    });
}

/**
 * Load data if the edit mode is active
 */
function loadData() {
    var id = null;

    if (edit) {
        id = $('#edit_recipient option:selected').val();

        $.ajax({
            url: '/api/customers/recipient/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                var rec = $recipient[0].selectize;
                result.forEach(function(entry) {
                    rec.addOption(entry);
                    rec.addItem(entry.id);
                });
                loadGroup();
            },
        });
    }
}

/**
 * Load new rate on year change
 */
function loadRate() {
    var id = $('#rates').val();
    $.ajax({
        url: '/api/rates/' + id + '/quota',
        type: 'GET',
        dataType: 'json',
        error: function(error) {
            console.log(error);
        },
        success: function(res) {
            if ($recipient != null) {
                $recipient[0].selectize.clearOptions();
            }
            rate = res[0].quota;
            $('#quota').val(rate);
            updateTotal();
            checkYears();
        },
    });
}

/**
 * Check if there is a receipt for the selected year
 */
function checkYears() {
    var id = $('#recipient').val();
    var year = $('#rates option:selected').text();

    // perform the AJAX request only if the id is set
    if (id != '') {
        $.ajax({
            url: '/api/receipts/years/' + id + '/' + year,
            type: 'GET',
            dataType: 'json',
            error: function(error) {
              console.log(error);
              alert('Error! Look for guru mediation or check web console for help');
            },
            success: function(result) {
                // In normal mode, if there is a receipt I disable the form
                if (edit == false) {
                    if (result != '') {
                        disableForm();
                    } else {
                        enableForm();
                    }
                }
            },
        });
    }
}

/**
 * Disable the form
 */
function disableForm() {
    $('#receipt_button').attr('disabled', 'disabled');
    $('#alert').text('Ricevuta già presente per l\'anno selezionato');
    $('#alert').addClass('label-danger');
}

/**
 * Enable the form
 */
function enableForm() {
    $('#receipt_button').removeAttr('disabled', 'disabled');
    $('#alert').text('');
    $('#alert').removeClass('label-danger');
}

/**
 * Load the family's group
 */
function loadGroup() {
    var id = $('#recipient').val();
    var year = $('#rates option:selected').text();

    var control = $people[0].selectize;
    control.clear();
    peopleNum = 0;
    if (id != '') {
        $.ajax({
            url: '/api/customers/' + id + '/' + year + '/' + edit + '/getgroup',
            type: 'GET',
            dataType: 'json',
            error: function(error) {
                console.log(error);
                alert('Error! Look for guru mediation or check web console for help');
            },
            success: function(result) {
                result.forEach(function(entry) {
                    addItems(entry);
                });
                alternativeQuoteCheck();
            },
        });
    }
}

/**
 *  Enable/disable the UI elements for the alternative quota
 */
function alternativeQuoteCheck() {
    if ($('#quota_type').val() == 1) {
        removeQuoteAlternativeElements();
        addQuoteAlternativeElements();
    } else {
        removeQuoteAlternativeElements();
    }
    updateTotal();
}

/**
 *  Add alternative Quota elements to UI
 */
function addQuoteAlternativeElements() {
    var quota = $('#quota').val();
    var idList = [];

    $('#quote_alternative_div').append(
        '<div class="card">' +
        '<div class="card-body">' +
        '<h6 class="card-title">Quote Alternative</h6>' +
        '<div id="people_container" class="row row-cols-4 mb-4">' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    // For each customer, add the necessary element
    $('.customers').each(function() {
        var name = $(this).attr('c-name');
        var id = $(this).attr('data-value');
        idList.push(id);

        $('#people_container').append(
            '<div class="col">' +
            '<label id="' + id + '" class="col-form-label-sm">' + name + '</label>' +
            '<div class="input-group input-group-sm">' +
                '<div class="input-group-prepend"><div class="input-group-text">&euro;</div></div>' +
                '<input id="quotas-' + id + '" name="quotas-' + id + '" type="text" class="form-control form-control-sm quotas" value="' + quota +'"/>' +
            '</div>' +
            '</div>'
        );
    });

    // if we are in edit mode, load the customers quota
    if (edit) {
        var year = $('#rates option:selected').text();
        var number = $('#receipt_number').val();

        $('.quotas').each(function(i) {
            $.ajax({
                url: '/api/receipts/' + idList[i] + '/' +
                    number + '/' + year + '/quota',
                type: 'GET',
                dataType: 'json',
                success: function(result) {
                    var element = 'quotas-' + result[0].customers_id;
                    $('#' + element).val(result[0].quota);
                    updateTotal();
                },
            });
        });
    }

    // Add the necessary event listeneres and validation rules
    addEventListener();
    addValidationRule();
}

/**
 *  Remove alternative quotas UI elements
 */
function removeQuoteAlternativeElements() {
    $('#quote_alternative_div').html('');
}

/**
 * Add the customer to the selectize control
 *
 * @param {object} entry
 */
function addItems(entry) {
    var control = $people[0].selectize;
    control.addOption(entry);
    control.addItem(entry.id);
}

/**
 *  Initialize the total field to 0
 */
function initializeTotal() {
    $('#total').val(0);
}

/**
 *  Update the total amount of the receipt
 */
function updateTotal() {
    var total = 0;
    if ($('#quota_type').val() == 1) {
        $('.quotas').each(function() {
            var quota = parseFloat($(this).val());
            total += quota;
        });
    } else {
        total = peopleNum * rate;
    }

    $('#total').val(total);
}

/**
 *  set edit mode
 */
function setEditMode() {
    var action = $('#create_receipt_form').attr('action');

    edit = action != '/receipts/store';

    if (edit) {
        $('#rates').prop('disabled', true);
        var rateValue = $('#rates').val();
        $('#rates_div').append(
            '<input type="hidden" id="rates" name="rates" value="' + rateValue + '">'
        );
    }
}
