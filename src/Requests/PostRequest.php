<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Requests\Request;

use ShopModule\WeclappApi\Traits\Requests\HasData;
use ShopModule\WeclappApi\Traits\Requests\HasJsonData;

abstract class PostRequest extends Request
{
    use HasData;
    use HasJsonData;
    
    protected $method = 'POST';
}