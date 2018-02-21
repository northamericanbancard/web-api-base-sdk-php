<?php
/**
 * Filename:    Client.php
 * Created:     21/02/18, at 6:34 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\WebApiBaseSdk;

use GuzzleHttp\Psr7\Request;
use NAB\WebApiBaseSdk\AbstractClient;

/**
 * Simple client sans authentication standards (with exception of possible x-api-key, if you so wish).
 */
class SimpleClient extends AbstractClient
{
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
    public function createRequest($method, $endpoint, array $headers, $body)
    {
        return new Request($method, $endpoint, $headers, $body);
    }
}
