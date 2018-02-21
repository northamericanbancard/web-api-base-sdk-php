<?php
/**
 * Filename:    JwtClient.php
 * Created:     21/02/18, at 2:34 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\WebApiBaseSdk;

use GuzzleHttp\Psr7\Request;
use NAB\WebApiBaseSdk\ClientInterface;
use NAB\WebApiBaseSdk\AbstractClient;

class JwtClient extends AbstractClient implements ClientInterface
{
    /**
     * @var string
     */
    private $jwt;

    /**
     * Sets up a new JWT Client.
     *
     * @param string      $baseUrl
     * @param string      $jwt
     * @param null|string $xApiKey
     * @param array       $config
     */
    public function __construct($baseUrl, $jwt, $xApiKey = null, array $config = [])
    {
        $this->jwt = $jwt;
        parent::__construct($baseUrl, $xApiKey, $config);
    }

    /**
     * Helper to create the final request object.
     *
     * @param string $method   The HTTP Method to use in the Request
     * @param string $endpoint The final endpoint (including the query params)
     * @param array  $headers  An array of headers to send
     * @param string $body     The body of the Request
     *
     * @return Request
     */
    protected function createRequest($method, $endpoint, array $headers, $body)
    {
        $headers['Authorization'] = 'Bearer ' . $this->jwt;
        $request = new Request($method, $endpoint, $headers, $body);

        return $request;
    }
}
