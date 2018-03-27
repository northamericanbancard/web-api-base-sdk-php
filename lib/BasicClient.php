<?php
/**
 * Filename:    BasicClient.php
 * Created:     27/03/18, at 10:41 AM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\WebApiBaseSdk;

use GuzzleHttp\Psr7\Request;
use NAB\WebApiBaseSdk\ClientInterface;
use NAB\WebApiBaseSdk\AbstractClient;

class BasicClient extends AbstractClient implements ClientInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Sets up a new Basic Client.
     *
     * @param string      $baseUrl
     * @param string      $username
     * @param string      $password
     * @param null|string $xApiKey
     * @param array       $config
     */
    public function __construct($baseUrl, $username, $password, $xApiKey = null, array $config = [])
    {
        $this->username = $username;
        $this->password = $password;
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
        $headers['Authorization'] = 'Basic ' . base64_encode("{$this->username}:{$this->password}");
        $request = new Request($method, $endpoint, $headers, $body);

        return $request;
    }
}
