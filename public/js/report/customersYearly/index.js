var bookPrintStyle = false;
var showLate = true;

$(document).ready(function() {
    loadData();

    $('#years').change(function() {
        loadData();
    });

    $('#version').change(function() {
        loadData();
    });

    $('#style_button').click(function() {
        toggleStyle();
    });

    $('#late_button').click(function() {
        toggleLate();
    });
});

function toggleStyle() {
    if (bookPrintStyle == false) {
        bookPrintStyle = true;
        $('#style_text').text('Con margini');
        $('#custom_style').html(
            '<style> @media print { @page :left { margin-left: 0; margin-right: 2cm; } ' +
            '@page :right { margin-left: 2cm; margin-right: 0; }} </style>'
        );
    } else {
        bookPrintStyle = false;
        $('#style_text').text('Senza margini');
        $('#custom_style').html('');
    }
}

function toggleLate() {
    if (showLate == false) {
        showLate = true;
        $('#late_text').text('Mostra morosi');
    } else {
        showLate = false;
        $('#late_text').text('Nascondi morosi');
    }

    loadData();
}

function loadData() {
    showLoading();
    var year = $('#years').val();
    var url = '';

    if (showLate == true) {
        url = '/report/customers/yearly/' + year + '/' + 1 + '/extended/list';
    } else {
        url = '/report/customers/yearly/' + year + '/' + 0 + '/extended/list';
    }


    $.ajax({
        url: url,
        type: 'get',
        error: function (data) {
            console.log('Error:', data);
        },
        success: function (data) {
            $('#data_container')
                .html('')
                .append(data);

            showLoadComplete();
        }
    });
}

function showLoading() {
    $('#loading_button').removeClass().addClass('btn btn-danger btn-sm');
    $('#loading_text').text('Caricamento...');
    $('#loading_icon').removeClass().addClass('fa fa-cog fa-spin fa-1x fa-fw');
}

function showLoadComplete() {
    $('#loading_button').removeClass().addClass('btn btn-success btn-sm');
    $('#loading_text').text('Pronto! CTRL+P per stampare!');
    $('#loading_icon').removeClass().addClass('fa fa-check');
}