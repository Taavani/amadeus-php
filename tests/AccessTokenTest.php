<?php

declare(strict_types=1);

namespace Amadeus\Tests;

use Amadeus\Amadeus;
use Amadeus\Client\AccessToken;
use Amadeus\Configuration;
use Amadeus\Exceptions\ResponseException;
use PHPUnit\Framework\TestCase;

final class AccessTokenTest extends TestCase
{
    private Amadeus $client;

    /**
     * @Before
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = $this->createMock(Amadeus::class);

        $configuration = new Configuration("client_id", "client_secret");
        $this->client->expects($this->any())
            ->method("getConfiguration")
            ->willReturn($configuration);

        $result = (object) [
          "access_token" => "my_token",
          "expires_at" => time() + 600
        ];
        $this->client->expects($this->any())
            ->method("getAuthorizedToken")
            ->willReturn(new AccessToken($result));
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function testNewToken(): void
    {
        $this->assertEquals('my_token', $this->client->getAuthorizedToken()->getAccessToken());
    }
}
