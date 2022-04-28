<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Responses\InvoiceResponse;
use ShopModule\WeclappApi\Traits\Requests\HasResourceId;
use ShopModule\WeclappApi\Traits\Requests\IsSalesInvoiceIdRequest;

class SalesInvoiceIdGetRequest extends GetRequest
{
    use HasResourceId;
    use IsSalesInvoiceIdRequest;

    protected $responseClass = InvoiceResponse::class;
}