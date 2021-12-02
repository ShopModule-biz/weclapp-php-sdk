<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ShopModule\WeclappApi\Client;

class ClientTest extends TestCase
{

    protected function setUp(): void
    {
        //
    }

    public function testCreateClientAndConnect()
    {
        $client = new Client(null, null);

        $this->assertInstanceOf(Client::class, $client);
    }
}