<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Exceptions/MissingDataException.php

namespace ShopModule\WeclappApi\Exceptions;

use ShopModule\WeclappApi\Exceptions\Exception;

class MissingDataException extends Exception
{
    public function create($class, $property)
    {
        $message = sprintf(
            'Missing data `%s` in `%s`',
            $property,
            $class
        );
        
        return new self($message);
    }
}