<?php
/**
 * Filename:    BaseClientTest.php
 * Created:     03/05/18, at 1:36 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use NAB\WebApiBaseSdk\ClientInterface;
use Psr\Http\Message\RequestInterface;

abstract class AbstractClientTestBase extends AbstractFrameworkTestCase
{
    // Send these on any get request. Ye hath been warned.
    const TOKEN = 'my.tok.en';
    protected $GET_REQUEST_QUERY_PARAMS = ['a' => 'b'];

    /**
     * @param string $expectedHttpMethod The expected method using {@see ClientInterface}
     *
     * @return array
     */
    protected function getGuzzleConfig($expectedHttpMethod = '')
    {
        $testQueryParams = $this->GET_REQUEST_QUERY_PARAMS;
        $expectedHeaders = $this->getExpectedHeaderKeys();

        /**
         * Fun little spy we're sneaking in to ensure we 'could' call Guzzle's send function with expected data.
         * {@see ClientTestHelper::send}
         */
        $config = ['spy' =>
            function (RequestInterface $request) use (
                $testQueryParams,
                $expectedHeaders,
                $expectedHttpMethod
            ) {
                $actualHeaders = array_keys($request->getHeaders());

                sort($actualHeaders);
                sort($expectedHeaders);
                $this->assertSame($expectedHeaders, $actualHeaders);

                $this->assertSame($expectedHttpMethod, $request->getMethod());

                $this->assertSame('abc', $request->getHeader('x-api-key')[0]);
                if (isset($expectedHeaders['Authorization'])) {
                    $this->assertSame('Bearer ' . self::TOKEN, $request->getHeader('Authorization')[0]);
                }

                if ($request->getMethod() === ClientInterface::HTTP_METHOD_GET) {
                    $queryParams = $request->getUri()->getQuery();
                    $this->assertSame(http_build_query($testQueryParams), $queryParams);
                }
            }
        ];

        return $config;
    }

    /**
     * Get the expected header keys that your client should have set on request.
     *
     * @return array
     */
    abstract protected function getExpectedHeaderKeys();

    /**
     * Data provider that runs thru creating system under tests with the right config per
     * {@see self::testClientCanHandleRequest}
     *
     * @return array
     */
    abstract protected function clientCanHandleRequestDataProvider();

    /**
     * @param $systemUnderTest
     * @param $systemUnderTestMethodToCall
     * @param $queryData
     *
     * @dataProvider clientCanHandleRequestDataProvider
     */
    public function testClientCanHandleRequest(
        $systemUnderTest,
        $systemUnderTestMethodToCall,
        $queryData = []
    ) {
        $systemUnderTest->$systemUnderTestMethodToCall('localhost', $queryData);
    }
}
