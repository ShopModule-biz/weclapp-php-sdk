<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Responses\OrderResponse;
use ShopModule\WeclappApi\Traits\Requests\HasResourceId;
use ShopModule\WeclappApi\Traits\Requests\IsSalesOrderRequest;

class SalesOrderGetRequest extends GetRequest
{
    use HasResourceId;
    use IsSalesOrderRequest;
    
    protected $responseClass = OrderResponse::class;
}