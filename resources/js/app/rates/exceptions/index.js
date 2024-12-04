/**
 * @file rates/exceptions/index.js
 * @author kain rway07@gmail.com
 */

import { checkPageStatus } from '../../common/notifications.js';
import { edit, destroy } from '../../common/common.js';

window.edit = edit;
window.destroy = destroy;

$(() => {
    checkPageStatus();
});
