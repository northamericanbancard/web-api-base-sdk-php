<?php

/**
 * Filename:    ClientTest.php
 * Created:     1/22/18, at 8:05 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use Aws\Credentials\Credentials;
use NAB\WebApiBaseSdk\ClientInterface;
use NAB\WebApiBaseSdk\SignatureV4;
use NAB\WebApiBaseSdk\AwsApiGatewayClient;
use NAB\Tests\AbstractFrameworkTestCase;
use Psr\Http\Message\RequestInterface;

class AwsApiGatewayClientTest extends AbstractFrameworkTestCase
{
    // Send these on any get request. Ye hath been warned.
    const GET_REQUEST_QUERY_PARAMS = ['a' => 'b'];

    /**
     * @var AwsApiGatewayClient
     */
    private $systemUnderTest;

    protected function setUp()
    {
        $this->systemUnderTest = new AwsApiGatewayClientTestHelper(
            'example.com',
            new SignatureV4(),
            new Credentials(null, null),
            $apiKey = 'abc',
            /**
             * Fun little spy we're sneaking in to ensure we 'could' call Guzzle's send function with expected data.
             * {@see ClientTestHelper::send}
             */
            $config = ['spy' => function (RequestInterface $request) {
                $expectedHeaders = [
                    'Authorization',
                    'X-Amz-Date',
                    'Content-Type',
                    'x-api-key',
                ];

                $actualHeaders = array_keys($request->getHeaders());

                sort($actualHeaders);
                sort($expectedHeaders);
                $this->assertSame($expectedHeaders, $actualHeaders);

                $this->assertSame('abc', $request->getHeader('x-api-key')[0]);

                if ($request->getMethod() === ClientInterface::HTTP_METHOD_GET) {
                    $queryParams = $request->getUri()->getQuery();
                    $this->assertSame(http_build_query(self::GET_REQUEST_QUERY_PARAMS), $queryParams);
                }
            }]
        );
    }

    protected function tearDown()
    {
        unset($this->systemUnderTest);
    }

    public function testClientCanSignGetRequest()
    {
        $systemUnderTest = $this->systemUnderTest;
        $systemUnderTest->httpGet('localhost', self::GET_REQUEST_QUERY_PARAMS);
    }

    public function testClientCanSignPostRequest()
    {
        $systemUnderTest = $this->systemUnderTest;
        $systemUnderTest->httppost('localhost');
    }
}
