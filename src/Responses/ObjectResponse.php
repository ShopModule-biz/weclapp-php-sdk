<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Responses;

use ShopModule\WeclappApi\Exceptions\MissingDataException;
use ShopModule\WeclappApi\Traits\Responses\HasModelBinding;

abstract class ObjectResponse extends Response
{
    use HasModelBinding;

    protected $notFound = false;
  
    public function __construct($data)
    {
        parent::__construct($data);
        
        if (isset($this->data->error) && 'not found' === $this->data->error) {
            $this->notFound = true;
        }
    }

    /**
     * @param string $data
     * @return mixed
     */
    protected function parseData(string $data)
    {
        return $this->bindModel(parent::parseData($data));
    }

    public function notFound(): bool
    {
        return $this->notFound;
    }
    
    public function getId()
    {
        if ( ! isset($this->data->id)) {
            throw MissingDataException::create(get_class($this), 'id');
        }
        
        return $this->data->id;
    }
}