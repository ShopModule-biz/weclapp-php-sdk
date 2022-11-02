<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use ShopModule\WeclappApi\Client;
use ShopModule\WeclappApi\Requests\ArticleExtraInfoForAppGetRequest;
use ShopModule\WeclappApi\Requests\ArticleGetRequest;
use ShopModule\WeclappApi\Requests\ArticlesGetRequest;
use ShopModule\WeclappApi\Requests\CustomerGetRequest;
use ShopModule\WeclappApi\Requests\CustomersGetRequest;
use ShopModule\WeclappApi\Requests\MoneyTransactionGetRequest;
use ShopModule\WeclappApi\Requests\OpenItemGetRequest;
use ShopModule\WeclappApi\Requests\OpenItemMarkAsPaidPostRequest;
use ShopModule\WeclappApi\Requests\SalesChannelActiveSalesChannelsGetRequest;
use ShopModule\WeclappApi\Requests\SalesInvoiceGetRequest;
use ShopModule\WeclappApi\Requests\SalesInvoiceIdGetRequest;
use ShopModule\WeclappApi\Requests\SalesInvoiceIdPutRequest;
use ShopModule\WeclappApi\Requests\SalesOrderGetRequest;
use ShopModule\WeclappApi\Requests\SalesOrderIdPutRequest;
use ShopModule\WeclappApi\Requests\ShipmentGetRequest;
use ShopModule\WeclappApi\Requests\ShipmentsGetRequest;
use ShopModule\WeclappApi\Requests\WarehouseStocksGetRequest;
use ShopModule\WeclappApi\Responses\Response;

error_reporting(E_ALL);
ini_set('display_errors', true);

function getDefault(string $key): ?string
{
    switch ($key) {
        case 'tenant':
            return 'YOUR_WECLAPP_TENANT';
        case 'token':
            return 'YOUR_WECLAPP_TOKEN';
    }
    return null;
}

function getPostValue(string $key, ?bool $getDefault = false, $filter = FILTER_DEFAULT)
{
    if (filter_has_var(INPUT_POST, $key)) {
        $value =  filter_input(INPUT_POST, $key, $filter);
        return $value ?: null;
    }
    if ($getDefault === true) {
        return getDefault($key);
    }
    return null;
}

function getClient(): Client
{
    $tenant = getPostValue('tenant');
    $token  = getPostValue('token');

    return new Client($tenant, $token);
}

function getErrorOutput(Client $client): string
{
    return implode("\n", [
        'HTTP-Code: ' . $client->getHttpStatus(),
        'CURL-Error: ' . $client->getCurlError(),
        'CURL-Error-Nr: ' . $client->getCurlErrno(),
    ]);
}

function getResponseOutput(Client $client, ?Response $response = null): string
{
    if (null === $response) {
        return getErrorOutput($client);
    }

    $output = implode("\n", [
        'Request URL: ' . $client->getRequestUrl(),
        'HTTP-Code: ' . $client->getHttpStatus(),
    ]);

    if ( ! is_array($response) || 0 < count($response)) {
        $output .= "\n\n" . print_r($response, true);
    }

    return $output;
}

function getArticles(): string
{
    $page = getPostValue('getArticles-page');
    $pageSize = getPostValue('getArticles-pageSize');

    $client = getClient();
    $request = (new ArticlesGetRequest)
        ->setPage($page ?? 1)
        ->setPageSize($pageSize ?? 100);

    $i = 0;
    do {
        if (null === getPostValue('getArticles-filter-' . $i . '-property')
            || null === getPostValue('getArticles-filter-' . $i . '-operator')
        ) {
            break;
        }

        $request->filter(
            getPostValue('getArticles-filter-' . $i . '-property'),
            getPostValue('getArticles-filter-' . $i . '-operator'),
            getPostValue('getArticles-filter-' . $i . '-query')
        );

        $i++;
    } while (1);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getArticle(): string
{
    $id = getPostValue('getArticle-id');

    $client = getClient();
    $request = (new ArticleGetRequest)
        ->setId($id);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getArticleExtraInfoForApp(): string
{
    $id = getPostValue('getArticleExtraInfoForApp-id');

    $client = getClient();
    $request = (new ArticleExtraInfoForAppGetRequest)
        ->setId($id);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getSalesOrder(): string
{
    $client = getClient();
    $request = new SalesOrderGetRequest;

    return getResponseOutput($client, $client->sendRequest($request));
}

function getSalesOrderId(): string
{
    $id = getPostValue('getSalesOrderId-id');

    $client = getClient();
    $request = (new SalesOrderGetRequest)
        ->setId($id);

    return getResponseOutput($client, $client->sendRequest($request));
}

function putSalesOrder(): string
{
    $id = getPostValue('putSalesOrder-id');
    $status = getPostValue('putSalesOrder-status');

    $client = getClient();
    $request = (new SalesOrderIdPutRequest)
        ->setId($id)
        ->setData('status', $status);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getSalesInvoice(): string
{
    $client = getClient();
    $request = new SalesInvoiceGetRequest;

    return getResponseOutput($client, $client->sendRequest($request));
}

function getSalesInvoiceId(): string
{
    $id = getPostValue('getSalesInvoiceId-id');

    $client = getClient();
    $request = (new SalesInvoiceIdGetRequest)
        ->setId($id);
    
    return getResponseOutput($client, $client->sendRequest($request));
}

function putSalesInvoice(): string
{
    $id = getPostValue('putSalesInvoice-id');
    $status = getPostValue('putSalesInvoice-status');

    $client = getClient();
    $request = (new SalesInvoiceIdPutRequest)
        ->setId($id)
        ->setData('status', $status);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getMoneyTransaction(): string
{
    $client = getClient();
    $request = new MoneyTransactionGetRequest;

    return getResponseOutput($client, $client->sendRequest($request));
}

function getOpenItem(): string
{
    $client = getClient();
    $request = new OpenItemGetRequest;

    return getResponseOutput($client, $client->sendRequest($request));
}

function openItemMarkAsPaid(): string
{
    $id = getPostValue('openItemMarkAsPaid-id');
    $date = time() . '000';

    $client = getClient();
    $request = (new OpenItemMarkAsPaidPostRequest)
        ->setId($id)
        ->setData('datePaid', $date);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getActiveSalesChannels(): string
{
    $client = getClient();
    $request = new SalesChannelActiveSalesChannelsGetRequest;

    return getResponseOutput($client, $client->sendRequest($request));
}

function getWarehouseStocks(): string
{
    $client = getClient();
    $request = new WarehouseStocksGetRequest;

    return getResponseOutput($client, $client->sendRequest($request));
}

function handleRequest(string $request): ?string
{
    if (getPostValue($request, true) && function_exists($request)) {
        if ( ! getPostValue('tenant')) {
            return 'Tenant is missing!';
        }
        if ( ! getPostValue('token')) {
            return 'Token is missing!';
        }
        return call_user_func($request);
    }
    return null;
}

function getShipments(): string
{
    $page = getPostValue('getShipments-page');
    $pageSize = getPostValue('getShipments-pageSize');

    $client = getClient();
    $request = (new ShipmentsGetRequest)
        ->setPage($page ?? 1)
        ->setPageSize($pageSize ?? 100);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getShipment(): string
{
    $id = getPostValue('getShipment-id');

    $client = getClient();
    $request = (new ShipmentGetRequest)
        ->setId($id);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getCustomers(): string
{
    $page = getPostValue('getCustomers-page');
    $pageSize = getPostValue('getCustomers-pageSize');

    $client = getClient();
    $request = (new CustomersGetRequest)
        ->setPage($page ?? 1)
        ->setPageSize($pageSize ?? 100);

    $i = 0;
    do {
        if (null === getPostValue('getCustomers-filter-' . $i . '-property')
            || null === getPostValue('getCustomers-filter-' . $i . '-operator')
        ) {
            break;
        }

        $request->filter(
            getPostValue('getCustomers-filter-' . $i . '-property'),
            getPostValue('getCustomers-filter-' . $i . '-operator'),
            getPostValue('getCustomers-filter-' . $i . '-query')
        );

        $i++;
    } while (1);

    return getResponseOutput($client, $client->sendRequest($request));
}

function getCustomer(): string
{
    $id = getPostValue('getCustomer-id');

    $client = getClient();
    $request = (new CustomerGetRequest())
        ->setId($id);

    return getResponseOutput($client, $client->sendRequest($request));
}


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>weclapp PHP SDK</title>
    <style>
        h3 a {
            color: #575756;
            text-decoration: none;
        }
        h3 a span {
            color: #49B382;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px 0;
        }
        fieldset {
            margin-bottom: 20px;
            display: grid;
        }
        fieldset pre {
            box-sizing: border-box;
            width: 100%;
            max-height: 350px;
            overflow: auto;
            background-color: #f0f0f0;
            padding: 15px;
        }
        h4 {
            margin-top: 20px;
            margin-bottom: 0;
        }
        pre {
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .result {
            display: contents;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>weclapp SDK - sandbox</h1>
    <h3>Presented by <a href="https://shopmodule.biz/" target="_blank">ShopModule<span>.biz</span></a></h3>
    <form id="sdk" name="sdk" method="post">
        <fieldset>
            <legend>Base configuration</legend>
            <table>
                <tr>
                    <td style="width:150px"><label for="tenant">Tenant</label></td>
                    <td><input type="text" id="tenant" name="tenant" value="<?php echo getPostValue('tenant'); ?>" size="25"></td>
                </tr>
                <tr>
                    <td><label for="token">Token</label></td>
                    <td><input type="text" id="token" name="token" value="<?php echo getPostValue('token'); ?>" size="25"></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>GetArticles</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getArticles-page">Page</label></td>
                        <td><input type="text" id="getArticles-page" name="getArticles-page" value="<?php echo getPostValue('getArticles-page'); ?>" size="25"></td>
                    </tr>
                    <tr>
                        <td><label for="getArticles-pageSize">PageSize</label></td>
                        <td><input type="text" id="getArticles-pageSize" name="getArticles-pageSize" value="<?php echo getPostValue('getArticles-pageSize'); ?>" size="25"></td>
                    </tr>
                    <tr>
                        <td><label for="filter">Filter</label></td>
                        <td>
                            <table>
                                <?php for ($i = 0; $i < 5; $i++):?>
                                    <tr>
                                        <td>
                                            <select name="getArticles-filter-<?php echo $i; ?>-property">
                                                <?php
                                                    foreach ([
                                                        '' => '',
                                                        'articleNumber' => 'articleNumber',
                                                        'articleType' => 'articleType',
                                                        'createdDate' => 'createdDate',
                                                        'lastModifiedDate' => 'lastModifiedDate',
                                                        'name' => 'name',
                                                    ] as $key => $value) {
                                                        echo '<option value="' . $key . '"' . ($key == getPostValue('getArticles-filter-' . $i . '-property') ? ' selected' : '') . '>' . $value . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="getArticles-filter-<?php echo $i; ?>-operator">
                                                <?php
                                                    foreach ([
                                                        '' => '',
                                                        'eq' => 'equal',
                                                        'ne' => 'not equal',
                                                        'lt' => 'less than',
                                                        'gt' => 'greater than',
                                                        'le' => 'less equal',
                                                        'ge' => 'greater equal',
                                                        'null' => 'is null',
                                                        'notnull' => 'is not null',
                                                        'like' => 'like',
                                                        'notlike' => 'not like',
                                                        'ilike' => 'like, ignoring case',
                                                        'notilike' => 'not like, ignoring case',
                                                        'in' => 'is in the specified list',
                                                        'notin' => 'not in the specified list',
                                                    ] as $key => $value) {
                                                        echo '<option value="' . $key . '"' . ($key == getPostValue('getArticles-filter-' . $i . '-operator') ? ' selected' : '') . '>' . $value . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td><input type="text" name="getArticles-filter-<?php echo $i; ?>-query" value="<?php echo getPostValue('getArticles-filter-' . $i . '-query'); ?>"></td>
                                    </tr>
                                <?php endfor; ?>
                            </table>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="getArticles" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getArticles')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetArticle</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getArticle-id">Article-ID</label></td>
                        <td><input type="text" id="getArticle-id" name="getArticle-id" value="<?php echo getPostValue('getArticle-id'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="getArticle" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getArticle')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetArticleExtraInfoForApp</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getArticleExtraInfoForApp-id">Article-ID</label></td>
                        <td><input type="text" id="getArticleExtraInfoForApp-id" name="getArticleExtraInfoForApp-id" value="<?php echo getPostValue('getArticleExtraInfoForApp-id'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="getArticleExtraInfoForApp" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getArticleExtraInfoForApp')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetSalesOrder</legend>
            <div>
                <input type="submit" name="getSalesOrder" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getSalesOrder')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetSalesOrderId</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getSalesOrderId-id">SalesOrder-ID</label></td>
                        <td><input type="text" id="getSalesOrderId-id" name="getSalesOrderId-id" value="<?php echo getPostValue('getSalesOrderId-id'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="getSalesOrderId" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getSalesOrderId')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>PutSalesOrder</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="putSalesOrder-id">SalesOrder-ID</label></td>
                        <td><input type="text" id="putSalesOrder-id" name="putSalesOrder-id" value="<?php echo getPostValue('putSalesOrder-id'); ?>" size="25"></td>
                    </tr>
                    <tr>
                        <td style="width: 150px"><label for="putSalesOrder-status">Status</label></td>
                        <td>
                            <select id="putSalesOrder-status" name="putSalesOrder-status">
                                <option value="CANCELLED">CANCELLED</option>
                                <option value="CLOSED">CLOSED</option>
                                <option value="MANUALLY_CLOSED">MANUALLY_CLOSED</option>
                                <option value="ORDER_CONFIRMATION_PRINTED">ORDER_CONFIRMATION_PRINTED</option>
                                <option value="ORDER_ENTRY_IN_PROGRESS">ORDER_ENTRY_IN_PROGRESS</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="putSalesOrder" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('putSalesOrder')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetSalesInvoice</legend>
            <div>
                <input type="submit" name="getSalesInvoice" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getSalesInvoice')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetSalesInvoiceId</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getSalesInvoiceId-id">SalesInvoice-ID</label></td>
                        <td><input type="text" id="getSalesInvoiceId-id" name="getSalesInvoiceId-id" value="<?php echo getPostValue('getSalesInvoiceId-id'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="getSalesInvoiceId" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getSalesInvoiceId')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>PutSalesInvoice</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="putSalesInvoice-id">SalesInvoice-ID</label></td>
                        <td><input type="text" id="putSalesInvoice-id" name="putSalesInvoice-id" value="<?php echo getPostValue('putSalesInvoice-id'); ?>" size="25"></td>
                    </tr>
                    <tr>
                        <td style="width: 150px"><label for="putSalesInvoice-status">Status</label></td>
                        <td>
                            <select id="putSalesInvoice-status" name="putSalesInvoice-status">
                                <option value="APPROVED">APPROVED</option>
                                <option value="BOOKED">BOOKED</option>
                                <option value="BOOKING_APPROVED">BOOKING_APPROVED</option>
                                <option value="BOOKING_ERROR">BOOKING_ERROR</option>
                                <option value="DOCUMENT_CREATED">DOCUMENT_CREATED</option>
                                <option value="ENTRY_COMPLETED">ENTRY_COMPLETED</option>
                                <option value="INVOICE_CHECKED">INVOICE_CHECKED</option>
                                <option value="INVOICE_VERIFICATION">INVOICE_VERIFICATION</option>
                                <option value="NEW">NEW</option>
                                <option value="QUERY_INVOICE">QUERY_INVOICE</option>
                                <option value="SENT">SENT</option>
                                <option value="VOID">VOID</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="putSalesInvoice" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('putSalesInvoice')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetMoneyTransaction</legend>
            <div>
                <input type="submit" name="getMoneyTransaction" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getMoneyTransaction')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetOpenItem</legend>
            <div>
                <input type="submit" name="getOpenItem" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getOpenItem')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>OpenItemMarkAsPaid</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="openItemMarkAsPaid-id">OpenItem-ID</label></td>
                        <td><input type="text" id="openItemMarkAsPaid-id" name="openItemMarkAsPaid-id" value="<?php echo getPostValue('openItemMarkAsPaid-id'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="openItemMarkAsPaid" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('openItemMarkAsPaid')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetActiveSalesChannels</legend>
            <div>
                <input type="submit" name="getActiveSalesChannels" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getActiveSalesChannels')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetWarehouseStocks</legend>
            <div>
                <input type="submit" name="getWarehouseStocks" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getWarehouseStocks')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetShipments</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getShipments-page">Page</label></td>
                        <td><input type="text" id="getShipments-page" name="getShipments-page" value="<?php echo getPostValue('getShipments-page'); ?>" size="25"></td>
                    </tr>
                    <tr>
                        <td><label for="getShipments-pageSize">PageSize</label></td>
                        <td><input type="text" id="getShipments-pageSize" name="getShipments-pageSize" value="<?php echo getPostValue('getShipments-pageSize'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="getShipments" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getShipments')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetShipment</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getShipment-id">Shipment-ID</label></td>
                        <td><input type="text" id="getShipment-id" name="getShipment-id" value="<?php echo getPostValue('getShipment-id'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="getShipment" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getShipment')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetCustomers</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getCustomers-page">Page</label></td>
                        <td><input type="text" id="getCustomers-page" name="getCustomers-page" value="<?php echo getPostValue('getCustomers-page'); ?>" size="25"></td>
                    </tr>
                    <tr>
                        <td><label for="getCustomers-pageSize">PageSize</label></td>
                        <td><input type="text" id="getCustomers-pageSize" name="getCustomers-pageSize" value="<?php echo getPostValue('getCustomers-pageSize'); ?>" size="25"></td>
                    </tr>
                    <tr>
                        <td><label for="filter">Filter</label></td>
                        <td>
                            <table>
                                <?php for ($i = 0; $i < 5; $i++):?>
                                    <tr>
                                        <td>
                                            <select name="getCustomers-filter-<?php echo $i; ?>-property">
                                                <?php
                                                foreach ([
                                                    '' => '',
                                                    'customerNumber' => 'customerNumber',
                                                    'email' => 'email',
                                                    'firstName' => 'firstName',
                                                    'lastName' => 'lastName',
                                                    'personCompany' => 'personCompany',
                                                ] as $key => $value) {
                                                    echo '<option value="' . $key . '"' . ($key == getPostValue('getCustomers-filter-' . $i . '-property') ? ' selected' : '') . '>' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="getCustomers-filter-<?php echo $i; ?>-operator">
                                                <?php
                                                foreach ([
                                                    '' => '',
                                                    'eq' => 'equal',
                                                    'ne' => 'not equal',
                                                    'lt' => 'less than',
                                                    'gt' => 'greater than',
                                                    'le' => 'less equal',
                                                    'ge' => 'greater equal',
                                                    'null' => 'is null',
                                                    'notnull' => 'is not null',
                                                    'like' => 'like',
                                                    'notlike' => 'not like',
                                                    'ilike' => 'like, ignoring case',
                                                    'notilike' => 'not like, ignoring case',
                                                    'in' => 'is in the specified list',
                                                    'notin' => 'not in the specified list',
                                                ] as $key => $value) {
                                                    echo '<option value="' . $key . '"' . ($key == getPostValue('getCustomers-filter-' . $i . '-operator') ? ' selected' : '') . '>' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td><input type="text" name="getCustomers-filter-<?php echo $i; ?>-query" value="<?php echo getPostValue('getCustomers-filter-' . $i . '-query'); ?>"></td>
                                    </tr>
                                <?php endfor; ?>
                            </table>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="getCustomers" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getCustomers')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset>
            <legend>GetCustomer</legend>
            <div>
                <table>
                    <tr>
                        <td style="width: 150px"><label for="getCustomer-id">Customer-ID</label></td>
                        <td><input type="text" id="getCustomer-id" name="getCustomer-id" value="<?php echo getPostValue('getCustomer-id'); ?>" size="25"></td>
                    </tr>
                </table>
                <input type="submit" name="getCustomer" value="Send request"><br />
            </div>
            <?php if ($result = handleRequest('getCustomer')) { ?>
                <div class="result">
                    <h4>Result:</h4>
                    <pre><?php echo $result; ?></pre>
                </div>
            <?php } ?>
        </fieldset>
    </form>
</div>
</body>
</html>