<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Responses;

use stdClass;

class Response
{
    /**
     * @var array|stdClass
     */
    protected $data;

    /**
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $this->parseData($data);
    }

    /**
     * @param string $data
     * @return array|stdClass
     */
    protected function parseData(string $data)
    {
        return json_decode($data);
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return property_exists($this->data, 'error');
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        $data = $this->getData();
        if (is_object($data) && property_exists($data, 'error')) {
            return $data->error;
        }
        return null;
    }

    /**
     * @return array|stdClass
     */
    public function getData()
    {
        return $this->data;
    }
    
}