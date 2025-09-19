/**
 * @file daily/index.js
 * @author kain rway07@gmail.com
 */

import { YEAR_START, YEAR_END } from '../../common/util.js';
import { showModal, showGuruModal } from '../../common/notifications.js';

let dates = [];
let numPeople = [];
let totals = [];

$(() => {
    const years = $('#years');

    years.on('change', (event) => {
        loadData(event.currentTarget.value);
    });

    $('#show_graph').on('click', () => {
        showGraph();
    });

    $('#show_extra').on('click', () => {
        showExtra();
    });

    $('#show_deliveries').on('click', () => {
        showDeliveries();
    })

    loadData(years.val());
});

function showSection(section, message) {
    const div = $(`#${  section  }_div`);
    const button = $(`#show_${  section  }`);
    const text = $(`#show_${  section  }_text`);

    if (div.hasClass('d-print-none')) {
        div.removeClass('d-print-none');
        button.removeClass('btn-danger').addClass('btn-success');
        text.text(`Mostra ${message} nella stampa`);
    } else {
        div.addClass('d-print-none');
        button.removeClass('btn-success').addClass('btn-danger');
        text.text(`Nascondi ${message} nella stampa`);
    }
}

/**
 *
 */
function showDeliveries() {
    showSection('deliveries', 'consegne');
}

/**
 * Abilita / Disabilita la sezione extra nella stampa
 */
function showExtra() {
    showSection('extra', 'sezione extra');
}

/**
 * Abilita / Disabilita la visualizzazione del grafico nella stampa
 */
function showGraph() {
    showSection('graph', 'grafico');
}

/**
 * Carica il grafico
 */
function loadChart() {
    dates = [];
    $('.date').each((index, element) => {
        dates.push(element.textContent);
    });

    numPeople = [];
    $('.num_total').each((index, element) => {
        numPeople.push(element.textContent);
    });

    totals = [];
    $('.total').each((index, element) => {
        let temp = element.textContent;
        temp = temp.replace('€', '').replace('&euro;', '');
        totals.push(temp);
    });

    const chartData = {
        labels: dates,
        datasets: [
            {
                label: 'Numero persone',
                borderColor: 'rgba(255, 0, 0, 1)',
                backgroundColor: 'rgba(255, 0, 0, 1)',
                fill: false,
                data: numPeople,
                borderWidth: 2,
                yAxisID: 'y-axis-1',
            },
            {
                label: 'Totale pagato €',
                borderColor: 'rgba(0, 0, 255, 1)',
                backgroundColor: 'rgba(0, 0, 255, 1)',
                fill: false,
                data: totals,
                borderWidth: 2,
                yAxisID: 'y-axis-2',
            },
        ],
    };

    const ctx = document.getElementById('test').getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                interaction: {
                  mode: 'index',
                  intersect: false,
                },
                stacked: false,
                scales: {
                    'y-axis-1': {
                            type: 'linear',
                            display: true,
                            position: 'left',
                    },
                    'y-axis-2': {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            id: 'y-axis-2',

                            // Grid line settings
                            grid: {
                                drawOnChartArea: false,
                            },
                    },
                },
            },
        });
    }
}

/**
 * Carica i dati della chiusura giornaliera
 *
 * @param year
 */
function loadData(year) {
    if (!v8n().numeric().between(YEAR_START, YEAR_END).test(year)) {
        return;
    }

    $.ajax({
        url: `/closure/daily/${year}/list`,
        type: 'get',
        error(response) {
            showGuruModal(response.status, response.statusText, response.responseJSON.message);
        },
        success(response) {
            // Controllo se la risposta contiene errori
            if ('error' in response) {
                showModal(response.error.message);
                return false;
            }

            // Controllo se la risposta contiene dati
            if ('data' in response) {
                $('#data_container').html('').append(response.data.view);
                $('#title_year').text(response.data.year);
                loadChart();

                return true;
            }

            return false;
        },
    });
}
