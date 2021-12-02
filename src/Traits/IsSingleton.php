<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Traits;

trait IsSingleton
{
    /**
     * @var self|null
     */
    protected static $instance = null;

    /**
     * Always returns the same instance of itself.
     *
     * @return self
     */
    final public static function getInstance(): self
    {
        return static::$instance ?? static::$instance = new static;
    }
    
}
