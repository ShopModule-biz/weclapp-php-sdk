<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use ShopModule\WeclappApi\Client;
use ShopModule\WeclappApi\Requests\ArticlesGetRequest;
use ShopModule\WeclappApi\Responses\Response;

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
    $request = (new ArticlesGetRequest())
        ->setPage($page ?? 1)
        ->setPageSize($pageSize ?? 100);

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
                        <td style="width: 150px"><label for="getArticles-pageSize">PageSize</label></td>
                        <td><input type="text" id="getArticles-pageSize" name="getArticles-pageSize" value="<?php echo getPostValue('getArticles-pageSize'); ?>" size="25"></td>
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
    </form>
</div>
</body>
</html>