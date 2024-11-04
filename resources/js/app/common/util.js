/**
 * @file util.js
 * @author kain - rway07@gmail.com
 */

export const YEAR_START = 1800;
export const YEAR_END = 2100;
export const MIN_YEAR_LEN = 4;
export const MAX_YEAR_LEN = 4;
export const MIN_QUOTA = 10;
export const MIN_FUNERAL_COST = 1000;
export const MIN_AGE = 12;
export const MAX_AGE = 120;

/**
 * Converte una data dal formato yyyy-mm-dd
 * nel formato dd-mm-yyyy
 *
 * @param {date} date
 * @return {string}
 */
export function convertDate(date) {
    const tokens = date.split('-');
    return `${tokens[2]}/${tokens[1]}/${tokens[0]}`;
}
