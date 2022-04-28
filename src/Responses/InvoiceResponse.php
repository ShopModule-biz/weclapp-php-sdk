<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Responses;

class InvoiceResponse extends ObjectResponse
{
    const APPROVED = 'APPROVED';
    const BOOKED = 'BOOKED';
    const BOOKING_APPROVED = 'BOOKING_APPROVED';
    const BOOKING_ERROR = 'BOOKING_ERROR';
    const DOCUMENT_CREATED = 'DOCUMENT_CREATED';
    const ENTRY_COMPLETED = 'ENTRY_COMPLETED';
    const INVOICE_CHECKED = 'INVOICE_CHECKED';
    const INVOICE_VERIFICATION = 'INVOICE_VERIFICATION';
    const NEW = 'NEW';
    const QUERY_INVOICE = 'QUERY_INVOICE';
    const SENT = 'SENT';
    const VOID = 'VOID';
}