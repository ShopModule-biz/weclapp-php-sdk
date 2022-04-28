<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Responses;

class OrderResponse extends ObjectResponse
{
    const CANCELLED = 'CANCELLED';
    const CLOSED = 'CLOSED';
    const MANUALLY_CLOSED = 'MANUALLY_CLOSED';
    const ORDER_CONFIRMATION_PRINTED = 'ORDER_CONFIRMATION_PRINTED';
    const ORDER_ENTRY_IN_PROGRESS = 'ORDER_ENTRY_IN_PROGRESS';
}