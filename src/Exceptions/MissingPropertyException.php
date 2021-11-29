<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Exceptions;

use ShopModule\WeclappApi\Exceptions\Exception;

class MissingPropertyException extends Exception
{
    public function create($class, $property)
    {
        $message = sprintf(
            'Missing property `%s` of class `%s`',
            $property,
            $class
        );
        
        return new self($message);
    }
}