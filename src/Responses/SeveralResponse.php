<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Responses;

use ShopModule\WeclappApi\Traits\Responses\HasModelBinding;
use stdClass;

abstract class SeveralResponse extends Response
{
    use HasModelBinding;

    /**
     * @param string $data
     * @return array|stdClass
     */
    protected function parseData(string $data)
    {
        $data = parent::parseData($data);

        if ( ! ($data instanceof stdClass)
            || ! property_exists($data, 'result')
            || ! is_array($data->result)
        ) {
            return $data;
        }

        $parsed = [];
        foreach ($data->result as $item) {
            $parsed[] = $this->parseItem($item);
        }

        return $parsed;
    }

    /**
     * @param stdClass $data
     * @return mixed
     */
    protected function parseItem(stdClass $data)
    {
        return $this->bindModel($data);
    }

}
