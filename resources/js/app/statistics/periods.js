/**
 * @file statistics/periods.js
 */

import { loadDataURL } from '../statistics/index.js';

$(() => {
    $('#cutoff_date').on('change',(event) => {
        drawPage(event.target.value);
    });
})

/**
 *
 * @returns {Promise<void>}
 */
async function drawPage(cutoffDate)
{
    if (!isNaN(Date.parse(cutoffDate))) {
        const url = `/statistics/periods/${  cutoffDate  }/data`;
        const pageData = await loadDataURL(url);

        const date = new Date(cutoffDate);
        const month = date.toLocaleDateString('it-IT', {month: 'long', day: 'numeric'});

        $('#period_title').text(month);

        if ('data' in pageData) {
            loadChart(pageData.data);
        }
    }
}

/**
 *
 * @param graphData
 */
function loadChart(graphData)
{
    const years = graphData.map(item => item.year);
    const peopleNumber = graphData.map(item => item.num_total);
    const receiptsNumber= graphData.map(item => item.num_receipts);
    const total = graphData.map(item => item.total);

    const chartData = {
        labels: years,
        datasets: [
            {
                label: 'Numero Persone',
                data: peopleNumber,
                barPercentage: 0.6,
                backgroundColor: 'rgba(142,17,195,0.2)',
                borderColor: 'rgb(142,17,195)',
                borderWidth: 1,
                yAxisID: 'y-axis-1',
            },
            {
                label: 'Numero Ricevute',
                data: receiptsNumber,
                barPercentage: 0.6,
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                borderColor: 'rgb(217,30,30)',
                borderWidth: 1,
                yAxisID: 'y-axis-1',
            },
            {
                label: 'Totale Incassato',
                data: total,
                barPercentage: 0.6,
                backgroundColor: 'rgba(58,123,214,0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y-axis-2',
            }]
    }
    const config = {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 50
            },
            scales: {
                'y-axis-1': {
                    beginAtZero: true,
                    type: 'linear',
                    display: true,
                    position: 'left'
                },
                'y-axis-2': {
                    beginAtZero: true,
                    type: 'linear',
                    display: true,
                    position: 'right'
                }
            }
        },
    };

    const ctx = document.getElementById('myChart').getContext('2d');

    if (ctx) {
        new Chart(ctx, config);
    }
}
