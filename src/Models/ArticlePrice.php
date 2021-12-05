<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Models;

class ArticlePrice extends Model
{
    public function isValid(string $date = 'now'): bool
    {
        $time = strtotime($date) . '000';

        // Price is out of date
        if (isset($this->endDate) && $this->endDate < $time) {
            return false;
        }

        // Price is not yet valid
        if (isset($this->startDate) && $this->startDate > $time) {
            return false;
        }

        return true;
    }
}