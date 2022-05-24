<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Responses\InvoiceResponse;
use ShopModule\WeclappApi\Traits\Requests\IsSalesInvoiceRequest;

class SalesInvoiceGetRequest extends GetRequest
{
    use IsSalesInvoiceRequest;
    
    protected $responseClass = InvoiceResponse::class;
}