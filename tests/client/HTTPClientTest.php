<?php

declare(strict_types=1);

namespace Amadeus\Tests\Client;

use Amadeus\Client\AccessToken;
use Amadeus\Client\BasicHTTPClient;
use Amadeus\Client\Request;
use Amadeus\Client\Response;
use Amadeus\Configuration;
use Amadeus\Exceptions\AuthenticationException;
use Amadeus\Exceptions\ClientException;
use Amadeus\Exceptions\NetworkException;
use Amadeus\Exceptions\NotFoundException;
use Amadeus\Exceptions\ResponseException;
use Amadeus\Exceptions\ServerException;
use Amadeus\Tests\PHPUnitUtil;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use ReflectionException;

#[
    CoversClass(BasicHTTPClient::class),
    CoversClass(AccessToken::class),
    CoversClass(Configuration::class),
    CoversClass(Request::class),
    CoversClass(Response::class),
    CoversClass(ResponseException::class),
    CoversClass(ServerException::class),
    CoversClass(NotFoundException::class),
    CoversClass(AuthenticationException::class),
    CoversClass(ClientException::class),
    CoversClass(NetworkException::class)
]
final class HTTPClientTest extends TestCase
{
    private BasicHTTPClient $client;
    private Configuration $configuration;
    private string $path;
    private array $params;
    private string $body;
    private array $info;
    private string $result;
    private AccessToken $accessToken;

    #[Before]
    protected function setUp(): void
    {
        $this->path = "/foo";
        $this->params = array("foo" => "bar");
        $this->body = "{foo: bar}";

        $this->info = array(
            "url" => '/v1/security/oauth2/token',
            "http_code" => 200,
            "header_size" => 39
        );
        $this->result =
            "HTTP/1.1 200 OK"
            . "HeadersKey: HeadersValue"
            . " "
            . "{"
            . "\"data\" : [ {"
            . " \"access_token\" : \"my_token\""
            . " } ]"
            . "}";

        $this->configuration = new Configuration("client_id", "client_secret");

        $this->client = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->getMock();

        $this->accessToken = new AccessToken($this->client, __DIR__ . "/cached_token_test.json");

        $this->client->expects($this->any())
            ->method("getAccessToken")
            ->willReturn($this->accessToken);
    }

    /**
     * @throws ResponseException
     */
    public function testGetWithOnlyPath(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->onlyMethods(array('execute', 'getAccessToken'))
            ->getMock();
        $obj->expects($this->any())
            ->method("getAccessToken")
            ->willReturn($this->accessToken);

        $request = new Request(
            "GET",
            $this->path,
            null,
            null,
            $obj->getAccessToken()->getBearerToken(),
            $obj
        );

        $obj->expects($this->once())->method('execute')->with($request);

        $obj->getWithOnlyPath("/foo");
    }

    /**
     * @throws ResponseException
     */
    public function testGetWithParams(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->onlyMethods(array('execute', 'getAccessToken'))
            ->getMock();
        $obj->expects($this->any())
            ->method("getAccessToken")
            ->willReturn($this->accessToken);

        $request = new Request(
            "GET",
            $this->path,
            $this->params,
            null,
            $obj->getAccessToken()->getBearerToken(),
            $obj
        );

        $obj->expects($this->once())->method('execute')->with($request);

        $obj->getWithArrayParams("/foo", $this->params);
    }

    /**
     * @throws ResponseException
     */
    public function testPostWithBody(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->onlyMethods(array('execute', 'getAccessToken'))
            ->getMock();
        $obj->expects($this->any())
            ->method("getAccessToken")
            ->willReturn($this->accessToken);

        $request = new Request(
            "POST",
            $this->path,
            null,
            $this->body,
            $obj->getAccessToken()->getBearerToken(),
            $obj
        );

        $obj->expects($this->once())
            ->method('execute')
            ->with($request);

        $obj->postWithStringBody("/foo", $this->body);
    }

    public function testPost4FetchAccessToken(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->onlyMethods(array('execute'))
            ->getMock();

        $params4FetchAccessToken = array(
            'client_id' => $this->configuration->getClientId(),
            'client_secret' => $this->configuration->getClientSecret(),
            'grant_type' => 'client_credentials'
        );

        $request = new Request(
            "POST",
            '/v1/security/oauth2/token',
            $params4FetchAccessToken,
            null,
            null,
            $obj
        );

        $obj->expects($this->once())
            ->method('execute')
            ->with($request)
            ->willReturn(new Response($request, $this->info, $this->result));

        $obj->getAccessToken()->fetchAccessToken();
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectResponseException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(-1);

        $this->expectException(ResponseException::class);
        PHPUnitUtil::callMethod($this->client, 'detectError', array($response));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectServerException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(500);

        $this->expectException(ServerException::class);
        PHPUnitUtil::callMethod($this->client, 'detectError', array($response));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectNotFoundException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(404);

        $this->expectException(NotFoundException::class);
        PHPUnitUtil::callMethod($this->client, 'detectError', array($response));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectAuthenticationException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(401);

        $this->expectException(AuthenticationException::class);
        PHPUnitUtil::callMethod($this->client, 'detectError', array($response));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectClientException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(400);

        $this->expectException(ClientException::class);
        PHPUnitUtil::callMethod($this->client, 'detectError', array($response));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectNetworkException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(0);

        $this->expectException(NetworkException::class);
        PHPUnitUtil::callMethod($this->client, 'detectError', array($response));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectCode204NoException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(204);

        $this->assertNull(PHPUnitUtil::callMethod($this->client, 'detectError', array($response)));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testDetectCode201NoException(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects($this->any())->method("getStatusCode")->willReturn(201);

        $this->assertNull(PHPUnitUtil::callMethod($this->client, 'detectError', array($response)));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testSetCurlOptionsWithDefault(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->getMock();

        $request = $this->createMock(Request::class);

        $request->expects($this->once())->method('getUri');
        $request->expects($this->once())->method('getHeaders');

        PHPUnitUtil::callMethod($obj, "setCurlOptions", array(curl_init(), $request));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testSetCurlOptionsWithSsl(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->getMock();

        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('getSslCertificate')->willReturn($this->path);

        $request->expects($this->exactly(2))->method('getSslCertificate');

        PHPUnitUtil::callMethod($obj, "setCurlOptions", array(curl_init(), $request));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testSetCurlOptionsWithPostWithBody(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->getMock();

        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('getVerb')->willReturn("POST");
        $request->expects($this->any())->method('getParams')->willReturn($this->params);
        $request->expects($this->any())->method('getBody')->willReturn($this->body);

        $request->expects($this->once())->method('getVerb');
        $request->expects($this->exactly(2))->method('getBody');
        $request->expects($this->exactly(0))->method('getParams');

        PHPUnitUtil::callMethod($obj, "setCurlOptions", array(curl_init(), $request));
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function testSetCurlOptionsWithPostWithParams(): void
    {
        $obj = $this->getMockBuilder(BasicHTTPClient::class)
            ->setConstructorArgs(array($this->configuration))
            ->getMock();

        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('getVerb')->willReturn("POST");
        $request->expects($this->any())->method('getParams')->willReturn($this->params);
        $request->expects($this->any())->method('getBody')->willReturn(null);

        $request->expects($this->once())->method('getVerb');
        $request->expects($this->exactly(1))->method('getBody');
        $request->expects($this->exactly(2))->method('getParams');

        PHPUnitUtil::callMethod($obj, "setCurlOptions", array(curl_init(), $request));
    }
}
