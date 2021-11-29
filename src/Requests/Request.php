<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Requests/Request.php

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Traits\Requests\HasResourceId;

use ShopModule\WeclappApi\Exceptions\MissingPropertyException;

use ShopModule\WeclappApi\Responses\Response;

abstract class Request
{
    protected $method;
    
    protected $responseClass = Response::class;
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getResponseClass()
    {
        return $this->responseClass;
    }
    
    public function getPath()
    {
        if ( ! isset($this->resource) || ! $this->resource) {
            throw MissingPropertyException::create(self::class, 'resource');
        }
        
        $pieces = [
            $this->resource,
        ];
        
        if ($this->usesTrait(HasResourceId::class)) {
            $pieces[] = 'id';
            $pieces[] = $this->getId();
        }
        
        return implode('/', $pieces);
    }
    
    public function usesTrait($trait)
    {
        foreach (class_uses($this) as $uses) {
            if ($uses === $trait || $this->classBasename($uses) === $trait) {
                return true;
            }
        }
        return false;
    }
    
    
    private function classBasename($classname)
    {
        $pieces = explode('\\', $classname);
        return array_pop($pieces);
    }
    
  
  
  
}