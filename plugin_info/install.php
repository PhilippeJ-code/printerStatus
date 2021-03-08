<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement après l'installation du plugin
//
function printerStatus_install() 
{
    config::save('functionality::cron::enable', 0, 'viessmann');
    config::save('functionality::cron5::enable', 0, 'viessmann');
    config::save('functionality::cron10::enable', 0, 'viessmann');
    config::save('functionality::cron15::enable', 0, 'viessmann');
    config::save('functionality::cron30::enable', 1, 'viessmann');
    config::save('functionality::cronHourly::enable', 0, 'viessmann');
    config::save('functionality::cronDaily::enable', 0, 'viessmann');
}

// Fonction exécutée automatiquement après la mise à jour du plugin
//
function printerStatus_update() 
{
    if (config::byKey('functionality::cron::enable', 'viessmann', -1) == -1) {
        config::save('functionality::cron::enable', 0, 'viessmann');
    }

    if (config::byKey('functionality::cron5::enable', 'viessmann', -1) == -1) {
        config::save('functionality::cron5::enable', 0, 'viessmann');
    }

    if (config::byKey('functionality::cron10::enable', 'viessmann', -1) == -1) {
        config::save('functionality::cron10::enable', 0, 'viessmann');
    }

    if (config::byKey('functionality::cron15::enable', 'viessmann', -1) == -1) {
        config::save('functionality::cron15::enable', 0, 'viessmann');
    }

    if (config::byKey('functionality::cron30::enable', 'viessmann', -1) == -1) {
        config::save('functionality::cron30::enable', 1, 'viessmann');
    }

    if (config::byKey('functionality::cronHourly::enable', 'viessmann', -1) == -1) {
        config::save('functionality::cronHourly::enable', 0, 'viessmann');
    }

    if (config::byKey('functionality::cronDaily::enable', 'viessmann', -1) == -1) {
        config::save('functionality::cronDaily::enable', 0, 'viessmann');
    }

    foreach (printerStatus::byType('printerStatus') as $printerStatus) {
        $printerStatus->save();
    }
}

// Fonction exécutée automatiquement après la suppression du plugin
//
function printerStatus_remove() 
{
}

?>
