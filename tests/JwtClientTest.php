<?php

/**
 * Filename:    JwtClientTest.php
 * Created:     1/22/18, at 8:05 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use NAB\Tests\ClientTestHelpers\JwtClientTestHelper;
use NAB\WebApiBaseSdk\ClientInterface;

class JwtClientTest extends AbstractClientTestBase
{
    public function clientCanHandleRequestDataProvider()
    {
        return [
            [
                'system_under_test' => new JwtClientTestHelper(
                    'example.com',
                    self::TOKEN,
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_GET)
                ),
                'system_under_test_method' => 'httpGet',
                'query_data' => $this->GET_REQUEST_QUERY_PARAMS,
            ],
            [
                'system_under_test' => new JwtClientTestHelper(
                    'example.com',
                    self::TOKEN,
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_POST)
                ),
                'system_under_test_method' => 'httpPost',
                'query_data' => [],
            ],
            [
                'system_under_test' => new JwtClientTestHelper(
                    'example.com',
                    self::TOKEN,
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_PUT)
                ),
                'system_under_test_method' => 'httpPut',
                'query_data' => [],
            ],
            [
                'system_under_test' => new JwtClientTestHelper(
                    'example.com',
                    self::TOKEN,
                    'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_DELETE)
                ),
                'system_under_test_method' => 'httpDelete',
                'query_data' => [],
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
