<?php

/**
 * Filename:    BasicClientTest.php
 * Created:     03/27/18, at 10:50 AM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use NAB\Tests\ClientTestHelpers\BasicClientTestHelper;
use NAB\WebApiBaseSdk\ClientInterface;
use Psr\Http\Message\RequestInterface;

class BasicClientTest extends AbstractClientTestBase
{
    protected function getGuzzleConfig($expectedHttpMethod = '')
    {
        $testQueryParams = $this->GET_REQUEST_QUERY_PARAMS;
        $config = ['spy' => function (RequestInterface $request) use ($testQueryParams, $expectedHttpMethod) {
            $expectedHeaders = [
                'Authorization',
                'Content-Type',
                'x-api-key',
            ];
            $username = 'ima-user';
            $password = 'ima-password';
            $authHeaderDigest = base64_encode("{$username}:{$password}");

            $actualHeaders = array_keys($request->getHeaders());

            sort($actualHeaders);
            sort($expectedHeaders);
            $this->assertSame($expectedHeaders, $actualHeaders);

            $this->assertSame($expectedHttpMethod, $request->getMethod());

            $this->assertSame('abc', $request->getHeader('x-api-key')[0]);
            $this->assertSame('Basic ' . $authHeaderDigest, $request->getHeader('Authorization')[0]);

            if ($request->getMethod() === ClientInterface::HTTP_METHOD_GET) {
                $queryParams = $request->getUri()->getQuery();
                $this->assertSame(http_build_query($testQueryParams), $queryParams);
            }
        }];
        return $config;
    }

    public function clientCanHandleRequestDataProvider()
    {
        return [
            [
                'system_under_test' => new BasicClientTestHelper(
                    'example.com',
                    'ima-user',
                    'ima-password',
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_GET)
                ),
                'system_under_test_method' => 'httpGet',
                'query_data' => $this->GET_REQUEST_QUERY_PARAMS,
            ],
            [
                'system_under_test' => new BasicClientTestHelper(
                    'example.com',
                    'ima-user',
                    'ima-password',
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_POST)
                ),
                'system_under_test_method' => 'httpPost',
                'query_data' => [],
            ],
            [
                'system_under_test' => new BasicClientTestHelper(
                    'example.com',
                    'ima-user',
                    'ima-password',
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_PUT)
                ),
                'system_under_test_method' => 'httpPut',
                'query_data' => []
            ],
            [
                'system_under_test' => new BasicClientTestHelper(
                    'example.com',
                    'ima-user',
                    'ima-password',
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_DELETE)
                ),
                'system_under_test_method' => 'httpDelete',
                'query_data' => []
            ],
        ];
    }

    /**
     * Get the expected header keys that your client should have set on request.
     *
     * @return array
     */
    protected function getExpectedHeaderKeys()
    {
        $expectedHeaders = [
            'Authorization',
            'Content-Type',
            'x-api-key',
        ];

        return $expectedHeaders;
    }
}
