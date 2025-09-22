/**
 * @file statistics/payments.js
 */

import { loadData } from '../statistics/index.js';

$(() => {
    drawPage();
})

/**
 *
 * @returns {Promise<void>}
 */
async function drawPage()
{
    const section = $('#section').val();
    const pageData = await loadData(section);

    if ('data' in pageData) {
        loadChart(pageData.data)
    }
}

/**
 *
 * @param graphData
 */
function loadChart(graphData)
{
    const years = graphData.map(item => item.year);
    const people = graphData.map(item => item.people);
    const cash = graphData.map(item => item.people_cash);
    const bank = graphData.map(item => item.people_bank);

    const chartData = {
        labels: years,
        datasets: [
            {
                label: 'Persone totali',
                data: people,
                barPercentage: 0.6,
                backgroundColor: 'rgba(9, 90, 20, 0.2)',
                borderColor: 'rgb(9, 90, 20)',
                borderWidth: 1
            },
            {
                label: 'Contanti',
                data: cash,
                barPercentage: 0.6,
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                borderColor: 'rgb(217,30,30)',
                borderWidth: 1
            },
            {
                label: 'Bonifico',
                data: bank,
                barPercentage: 0.6,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
    }
    const config = {
        type: 'bar',
        data: chartData,
        options: {
            layout: {
                padding: 50
            },
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
        },
    };

    const ctx = document.getElementById('myChart').getContext('2d');

    if (ctx) {
        new Chart(ctx, config);
    }
}
