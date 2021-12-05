<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Models;

use stdClass;
use ShopModule\WeclappApi\Traits\Models\IsCastable;

abstract class Model
{
    use IsCastable;

    function __call($method, $parameter)
    {
        // skip existing functions
        if ( ! method_exists($this, $method)) {
            if (preg_match('#^get([A-Z][A-Za-z]*)#', $method, $matches)) {
                $property = lcfirst($matches[1]);
                if (property_exists($this, $property)) {
                    return $this->$property;
                }
            }
        }

        return call_user_func_array([$this, $method], $parameter);
    }

    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        $this->$name = $value;
        return $this;
    }

}