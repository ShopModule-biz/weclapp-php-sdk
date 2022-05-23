<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Responses\WarehouseStockResponse;
use ShopModule\WeclappApi\Traits\Requests\HasPages;
use ShopModule\WeclappApi\Traits\Requests\IsWarehouseStocksRequest;

class WarehouseStocksGetRequest extends GetRequest
{
    use HasPages;
    use IsWarehouseStocksRequest;

    protected $responseClass = WarehouseStockResponse::class;
}