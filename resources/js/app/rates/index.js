/**
 * @file rates/index.js
 * @author kain rway07@gmail.com
 */

import {checkPageStatus} from '../common/notifications.js';
import { edit } from '../common/common.js';

window.edit = edit;

$(() => {
    checkPageStatus();
});
