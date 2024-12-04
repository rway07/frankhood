import { Chart, registerables } from 'chart.js';

window.Chart = Chart
Chart.register(...registerables)
