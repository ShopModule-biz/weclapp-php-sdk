<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Requests/GetRequest.php

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Requests\Request;

abstract class GetRequest extends Request
{
    protected $method = 'GET';
    
}