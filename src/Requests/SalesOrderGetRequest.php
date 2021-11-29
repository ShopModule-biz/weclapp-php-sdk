<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Requests/SalesOrderGetRequest.php

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Requests\GetRequest;

use ShopModule\WeclappApi\Traits\Requests\HasResourceId;
use ShopModule\WeclappApi\Traits\Requests\IsSalesOrderRequest;

use ShopModule\WeclappApi\Responses\OrderResponse;

class SalesOrderGetRequest extends GetRequest
{
    use HasResourceId;
    use IsSalesOrderRequest;
    
    protected $responseClass = OrderResponse::class;
}