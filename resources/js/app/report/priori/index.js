/**
 * @file report/priori/index.js
 */

import { loadView } from '../../common/common.js';

$(() => {
   loadData();
});

/**
 *
 */
async function loadData() {
    const url = `/report/customers/priori/data`;

    const response = await loadView(url);
    loadChart(response.data);
}

function loadChart(graphData) {
    const years = graphData.map(item => item.election_year).filter(item => item !== null);
    const votes = graphData.map(item => item.votes);
    const totalVotes = graphData.map(item => item.total_votes);


    const chartData = {
        labels: years,
        datasets: [
            {
                label: 'Voti Priore',
                data: votes,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            },
            {
                label: 'Voti Totali',
                data: totalVotes,
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                borderColor: 'rgb(217,30,30)',
                borderWidth: 1
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
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                }
            },
            elements: {
                bar: {
                    categoryPercentage: 1,
                    barPercentage: .5
                }
            }
        },
    };

    const ctx = document.getElementById('myChart').getContext('2d');

    if (ctx) {
        new Chart(ctx, config);
    }
}
