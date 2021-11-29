<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Requests/CustomerGetRequest.php

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Requests\GetRequest;

use ShopModule\WeclappApi\Traits\Requests\HasResourceId;
use ShopModule\WeclappApi\Traits\Requests\IsCustomerRequest;

use ShopModule\WeclappApi\Responses\CustomerResponse;

class CustomerGetRequest extends GetRequest
{
    use HasResourceId;
    use IsCustomerRequest;
    
    protected $responseClass = CustomerResponse::class;
}