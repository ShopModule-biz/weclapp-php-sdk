<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Traits/IsSingleton.php

namespace ShopModule\WeclappApi\Traits;

trait IsSingleton
{
    protected static $instance;
    
    final public static function getInstance()
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static;
    }
    
}
