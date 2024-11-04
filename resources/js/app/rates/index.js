/**
 * @file rates/index.js
 * @author kain rway07@gmail.com
 */

import {checkPageStatus} from '../common/notifications';
import { edit } from '../common/common';

window.edit = edit;

$(() => {
    checkPageStatus();
});
