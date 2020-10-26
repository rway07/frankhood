var dates = [];
var num_people = [];
var totals = [];

$(document).ready(function() {
    loadData($('#years').val());

    $('#years').change(function() {
        loadData($(this).val());
    });

    $('#show_graph').click(function() {
        showGraph();
    });
});

function showGraph() {
    var div = $('#graph_div');

    if (div.hasClass('d-print-none')) {
        div.removeClass('d-print-none');
        $('#show_graph_text').text('Nascondi grafico nella stampa');
    } else {
        div.addClass('d-print-none');
        $('#show_graph_text').text('Mostra grafico nella stampa');
    }
}

function loadChart() {
    $('.date').each(function () {
        dates.push($(this).text());
    });

    $('.num_total').each(function () {
        num_people.push($(this).text());
    });

    $('.total').each(function() {
        var temp = $(this).text();
        temp = temp.replace('€', '').replace('&euro;', '');
        totals.push(temp);
    });

    var chartData = {
        labels: dates,
        datasets: [{
            label: 'Numero persone',
            borderColor: 'rgba(255, 0, 0, 1)',
            backgroundColor: 'rgba(255, 0, 0, 1)',
            fill: false,
            data: num_people,
            borderWidth: 2,
            yAxisID: 'y-axis-1',
        }, {
            label: 'Totale pagato €',
            borderColor: 'rgba(0, 0, 255, 1)',
            backgroundColor: 'rgba(0, 0, 255, 1)',
            fill: false,
            data: totals,
            borderWidth: 2,
            yAxisID: 'y-axis-2',
        }]
    };

    var ctx = document.getElementById('test').getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                hoverMode: 'index',
                stacked: false,
                scales: {
                    yAxes: [{
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'left',
                        id: 'y-axis-1',
                    }, {
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'y-axis-2',

                        // grid line settings
                        gridLines: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    }],
                }
            }
        });
    }
}

function loadData(year) {
    $.ajax({
        url: '/closure/daily/' + year + '/list',
        type: 'get',

        error: function (data) {
            console.log('Error:', data);
        },
        success: function (data) {
            $('#data_container')
                .html('')
                .append(data);
            loadChart();
        }
    });
}
