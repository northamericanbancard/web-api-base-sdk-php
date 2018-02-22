<?php

/**
 * Filename:    ClientTest.php
 * Created:     1/22/18, at 8:05 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use NAB\WebApiBaseSdk\ClientInterface;
use NAB\WebApiBaseSdk\SimpleClient;
use NAB\Tests\AbstractFrameworkTestCase;
use Psr\Http\Message\RequestInterface;

class SimpleClientTest extends AbstractFrameworkTestCase
{
    // Send these on any get request. Ye hath been warned.
    const GET_REQUEST_QUERY_PARAMS = ['a' => 'b'];

    /**
     * @var SimpleClient
     */
    private $systemUnderTest;

    protected function setUp()
    {
        $this->systemUnderTest = new SimpleClientTestHelper(
            'example.com',
            'abc',
            /**
             * Fun little spy we're sneaking in to ensure we 'could' call Guzzle's send function with expected data.
             * {@see ClientTestHelper::send}
             */
            $config = ['spy' => function (RequestInterface $request) {
                $expectedHeaders = [
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

    public function testClientCanDoGetRequest()
    {
        $systemUnderTest = $this->systemUnderTest;
        $systemUnderTest->httpGet('localhost', self::GET_REQUEST_QUERY_PARAMS);
    }

    public function testClientCanDoPostRequest()
    {
        $systemUnderTest = $this->systemUnderTest;
        $systemUnderTest->httpPost('localhost');
    }
}