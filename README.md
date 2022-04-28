# weclapp-php-sdk
A software development kit (SDK) to access the weclapp API quickly and easily.

API documentation: https://www.weclapp.com/api/

# How to use
You can download the Package over composer

```
composer require shopmodule-biz/weclapp-php-sdk
```

or with following line in composer File:

```
"require": {
    "php": ">=7.2.0",
    "ext-curl": "*",
    "ext-json": "*",
    "shopmodule-biz/weclapp-php-sdk": ">=v1"
}
```

# Simple example to use
Request for the first 100 articles.
```
use ShopModule\WeclappApi\Client;
use ShopModule\WeclappApi\Requests\ArticlesGetRequest;

$tenant = 'YOUR WECLAPP TENANT';
$token = 'YOUR WECLAPP TOKEN';

$cient = new Client($tenant, $token);
$request = (new ArticlesGetRequest)
    ->setPage(1)
    ->setPageSize(100);

$response = $client->sendRequest($request);
$data = $response->getData();
```

The response is a standalone object. The data from weclapp can be fetched using the `getData()` method.
