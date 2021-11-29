<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Requests/PaymentMethodGetRequest.php

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Requests\GetRequest;

use ShopModule\WeclappApi\Traits\Requests\IsPaymentMethodRequest;

use ShopModule\WeclappApi\Responses\PaymentMethodResponse;

class PaymentMethodGetRequest extends GetRequest
{
    use IsPaymentMethodRequest;
    
    protected $responseClass = PaymentMethodResponse::class;
}