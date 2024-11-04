/**
 * @file rates/exceptions/index.js
 * @author kain rway07@gmail.com
 */

import { checkPageStatus } from '../../common/notifications';
import { edit, destroy } from '../../common/common';

window.edit = edit;
window.destroy = destroy;

$(() => {
    checkPageStatus();
});
