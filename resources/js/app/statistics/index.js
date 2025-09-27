/**
 * @file statistics/index.js
 */

import { loadView } from '../common/common.js';
/**
 *
 */
export function loadData(section) {
    const url = `/statistics/${  section  }/data`;

    return loadView(url);
}
