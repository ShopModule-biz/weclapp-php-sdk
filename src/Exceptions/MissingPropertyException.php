<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Exceptions;

class MissingPropertyException extends Exception
{
    /**
     * Creates an instance of itself with the appropriate error message and returns it.
     *
     * @param string $class
     * @param string $property
     * @return self
     */
    static public function create(string $class, string $property): self
    {
        $message = sprintf(
            'Missing property `%s` of class `%s`',
            $property,
            $class
        );
        
        return new self($message);
    }
}