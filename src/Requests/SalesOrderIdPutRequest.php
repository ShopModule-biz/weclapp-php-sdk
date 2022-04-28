<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Responses\OrderResponse;
use ShopModule\WeclappApi\Traits\Requests\HasData;
use ShopModule\WeclappApi\Traits\Requests\IgnoresMissingProperties;
use ShopModule\WeclappApi\Traits\Requests\HasResourceId;
use ShopModule\WeclappApi\Traits\Requests\HasUrlParameter;
use ShopModule\WeclappApi\Traits\Requests\IsSalesOrderIdRequest;

class SalesOrderIdPutRequest extends PutRequest
{
    use HasData;
    use IgnoresMissingProperties;
    use HasUrlParameter;
    use HasResourceId;
    use IsSalesOrderIdRequest;

    protected $responseClass = OrderResponse::class;
}