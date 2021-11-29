<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Requests/TaxGetRequest.php

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Requests\GetRequest;

use ShopModule\WeclappApi\Traits\Requests\IsTaxRequest;

use ShopModule\WeclappApi\Responses\TaxResponse;

class TaxGetRequest extends GetRequest
{
    use IsTaxRequest;
    
    protected $responseClass = TaxResponse::class;
}