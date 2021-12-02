<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Traits\Requests\HasResourceId;

use ShopModule\WeclappApi\Exceptions\MissingPropertyException;

use ShopModule\WeclappApi\Responses\Response;

abstract class Request
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $responseClass = Response::class;

    /**
     * Returns the HTTP method of the request.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Returns the name of the return class.
     *
     * @return string
     */
    public function getResponseClass(): string
    {
        return $this->responseClass;
    }

    /**
     * Generates the URL path for the request.
     *
     * @return string
     * @throws MissingPropertyException
     */
    public function getPath(): string
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

    /**
     * Checks whether this class uses the transferred trait.
     *
     * @param string $trait
     * @return bool
     */
    public function usesTrait(string $trait): bool
    {
        foreach (class_uses($this) as $uses) {
            if ($uses === $trait || $this->classBasename($uses) === $trait) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the base name of a class.
     *
     * @param string $classname
     * @return string
     */
    private function classBasename(string $classname): string
    {
        $pieces = explode('\\', $classname);
        return array_pop($pieces);
    }
    
}