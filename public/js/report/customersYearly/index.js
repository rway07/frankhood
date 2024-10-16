/**
 * @file report/customersYearly/index.js
 * @author kain - rway07@gmail.com
 */

const urlConstant = {
    NORMAL: 0,
    LATE: 1,
};

let bookPrintStyle = false;
let showLate = true;

$(() => {
    loadData();

    $('#years').on('change', () => {
        loadData();
    });

    $('#version').on('change', () => {
        loadData();
    });

    $('#style_button').on('click', () => {
        toggleStyle();
    });

    $('#late_button').on('click', () => {
        toggleLate();
    });
});

/**
 *
 */
function toggleStyle() {
    if (bookPrintStyle === false) {
        bookPrintStyle = true;
        $('#style_text').text('Con margini');
        $('#custom_style').html(
            '<style> @media print { @page :left { margin-left: 0; margin-right: 2cm; } ' +
                '@page :right { margin-left: 2cm; margin-right: 0; }} </style>',
        );
    } else {
        bookPrintStyle = false;
        $('#style_text').text('Senza margini');
        $('#custom_style').html('');
    }
}

/**
 *
 */
function toggleLate() {
    if (showLate === false) {
        showLate = true;
        $('#late_text').text('Mostra morosi');
    } else {
        showLate = false;
        $('#late_text').text('Nascondi morosi');
    }

    loadData();
}

/**
 *
 * @returns {boolean}
 */
function loadData() {
    showLoading();
    const year = $('#years').val();
    let dataUrl = `/report/customers/yearly/${year}/${urlConstant.NORMAL}/extended/list`;

    if (showLate === true) {
        dataUrl = `/report/customers/yearly/${year}/${urlConstant.LATE}/extended/list`;
    }

    $.ajax({
        url: dataUrl,
        type: 'get',
        error(response) {
            showGuruModal(response);
        },
        success(response) {
            if ('error' in response) {
                showModal(response.error.message);
                return false;
            }

            if ('data' in response) {
                $('#data_container').html('').append(response.data.view);
                showLoadComplete();

                return true;
            }

            return false;
        },
    });
}

/**
 *
 */
function showLoading() {
    $('#loading_button').removeClass().addClass('btn btn-danger btn-sm');
    $('#loading_text').text('Caricamento...');
    $('#loading_icon').removeClass().addClass('fa fa-cog fa-spin fa-1x fa-fw');
}

/**
 *
 */
function showLoadComplete() {
    $('#loading_button').removeClass().addClass('btn btn-success btn-sm');
    $('#loading_text').text('Pronto! CTRL+P per stampare!');
    $('#loading_icon').removeClass().addClass('fa fa-check');
}
