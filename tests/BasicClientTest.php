<?php

/**
 * Filename:    BasicClientTest.php
 * Created:     03/27/18, at 10:50 AM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use NAB\WebApiBaseSdk\BasicClient;
use NAB\WebApiBaseSdk\ClientInterface;
use NAB\Tests\AbstractFrameworkTestCase;
use Psr\Http\Message\RequestInterface;

class BasicClientTest extends AbstractFrameworkTestCase
{
    // Send these on any get request. Ye hath been warned.
    private $GET_REQUEST_QUERY_PARAMS = ['a' => 'b'];

    /**
     * @var BasicClient
     */
    private $systemUnderTest;

    protected function setUp()
    {
        $username = 'ima-user';
        $password = 'ima-password';
        $authHeaderDigest = base64_encode("{$username}:{$password}");
        $testQueryParams = $this->GET_REQUEST_QUERY_PARAMS;
        $this->systemUnderTest = new BasicClientTestHelper(
            'example.com',
            'ima-user',
            'ima-password',
            'abc',
            /**
             * Fun little spy we're sneaking in to ensure we 'could' call Guzzle's send function with expected data.
             * {@see ClientTestHelper::send}
             */
            $config = ['spy' => function (RequestInterface $request) use ($testQueryParams, $authHeaderDigest) {
                $expectedHeaders = [
                    'Authorization',
                    'Content-Type',
                    'x-api-key',
                ];

                $actualHeaders = array_keys($request->getHeaders());

                sort($actualHeaders);
                sort($expectedHeaders);
                $this->assertSame($expectedHeaders, $actualHeaders);

                $this->assertSame('abc', $request->getHeader('x-api-key')[0]);
                $this->assertSame('Basic ' . $authHeaderDigest, $request->getHeader('Authorization')[0]);

                if ($request->getMethod() === ClientInterface::HTTP_METHOD_GET) {
                    $queryParams = $request->getUri()->getQuery();
                    $this->assertSame(http_build_query($testQueryParams), $queryParams);
                }
            }]
        );
    }

    protected function tearDown()
    {
        unset($this->systemUnderTest);
    }

    public function testClientCanDoGetRequest()
    {
        $systemUnderTest = $this->systemUnderTest;
        $systemUnderTest->httpGet('localhost', $this->GET_REQUEST_QUERY_PARAMS);
    }

    public function testClientCanDoPostRequest()
    {
        $systemUnderTest = $this->systemUnderTest;
        $systemUnderTest->httpPost('localhost');
    }
}
