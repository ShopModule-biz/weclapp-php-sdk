<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Models;

class ArticlePrice extends Model
{
    /**
     * @param string $date
     * @return bool
     */
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

    /**
     * @param string $channel
     * @return bool
     */
    public function isSalesChannel(string $channel): bool
    {
        return (string) $this->salesChannel === $channel;
    }

    /**
     * @param float $quantity
     * @return bool
     */
    public function isForQuantity(float $quantity): bool
    {
        if ( ! isset($this->priceScaleType) || ! isset($this->priceScaleValue)) {
            return true;
        }

        switch ($this->priceScaleType) {
            case 'SCALE_FROM':
                if ($this->priceScaleValue > $quantity) {
                    return false;
                }
                break;

            case 'SCALE_TO':
                if ($this->priceScaleValue < $quantity) {
                    return false;
                }
                break;
        }

        return true;
    }

}