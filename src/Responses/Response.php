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
    protected stdClass|array $data;

    private array $headers;

    public function __construct(string $data, array $headers)
    {
        $this->data = $this->parseData($data);
        $this->headers = $headers;
    }

    protected function parseData(string $data): array|stdClass
    {
        return json_decode($data);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function hasError(): bool
    {
        return is_object($this->data)
            && property_exists($this->data, 'error');
    }

    public function getError(): string|null
    {
        $data = $this->getData();
        if (is_object($data) && property_exists($data, 'error')) {
            return $data->error;
        }
        return null;
    }

    public function getData(): array|stdClass
    {
        return $this->data;
    }
    
}