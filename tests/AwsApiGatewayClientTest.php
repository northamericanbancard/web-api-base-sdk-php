<?php

/**
 * Filename:    AwsApiGatewayClientTest.php
 * Created:     1/22/18, at 8:05 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use Aws\Credentials\Credentials;
use NAB\Tests\ClientTestHelpers\AwsApiGatewayClientTestHelper;
use NAB\WebApiBaseSdk\ClientInterface;
use NAB\WebApiBaseSdk\SignatureV4;

class AwsApiGatewayClientTest extends AbstractClientTestBase
{
    public function clientCanHandleRequestDataProvider()
    {
        return [
            [
                'system_under_test' => new AwsApiGatewayClientTestHelper(
                    'example.com',
                    new SignatureV4(),
                    new Credentials(null, null),
                    $apiKey = 'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_GET)
                ),
                'system_under_test_method' => 'httpGet',
                'query_data' => $this->GET_REQUEST_QUERY_PARAMS,
            ],
            [
                'system_under_test' => new AwsApiGatewayClientTestHelper(
                    'example.com',
                    new SignatureV4(),
                    new Credentials(null, null),
                    $apiKey = 'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_POST)
                ),
                'system_under_test_method' => 'httpPost',
                'query_data' => [],
            ],
            [
                'system_under_test' => new AwsApiGatewayClientTestHelper(
                    'example.com',
                    new SignatureV4(),
                    new Credentials(null, null),
                    $apiKey = 'abc',
                    $this->getGuzzleConfig(ClientInterface::HTTP_METHOD_PUT)
                ),
                'system_under_test_method' => 'httpPut',
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
            'X-Amz-Date',
            'Content-Type',
            'x-api-key',
        ];

        return $expectedHeaders;
    }
}
