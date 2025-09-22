/**
 * @file statistics/oldest.js
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
    await loadData(section);

    document.getElementById('chart_container').innerHTML = '';
}
