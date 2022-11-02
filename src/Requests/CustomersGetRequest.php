<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Responses\CustomersResponse;
use ShopModule\WeclappApi\Traits\Requests\HasPages;
use ShopModule\WeclappApi\Traits\Requests\IsCustomersRequest;

class CustomersGetRequest extends GetRequest
{
    use HasPages;
    use IsCustomersRequest;

    protected $responseClass = CustomersResponse::class;
}