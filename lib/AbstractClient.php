<?php
/**
 * Filename:    AbstractClient.php
 * Created:     21/02/18, at 2:55 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\WebApiBaseSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use NAB\WebApiBaseSdk\ClientInterface;

/**
 * Parent of any client this library supports regarding authentication.
 */
abstract class AbstractClient extends Client implements ClientInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var array
     */
    private $headers;

    /**
     * Sets up a new Client implementation. We are being restrictive here for aid in debugging and control
     * over guzzle changes as they change implementations.
     *
     * @param string $baseUrl The base URL, without trailing / or query params.
     * @param string $xApiKey An API key header (usually associated with AWS APIG)
     * @param array  $config  Any Guzzle configuration
     */
    public function __construct($baseUrl, $xApiKey = null, array $config = [])
    {
        $this->baseUrl = $baseUrl;

        // By default, let's not throw exceptions on non-200 responses.
        if (!isset($config['http_errors'])) {
            $config['http_errors'] = false;
        }

        $headers = ['Content-Type' => 'application/json'];
        if (isset($config['headers'])) {
            $headers += $config['headers'];
            unset($config['headers']);
        }

        if ($xApiKey) {
            $headers['x-api-key'] = $xApiKey;
        }

        $this->headers = $headers;
        parent::__construct($config);
    }

    /**
     * A special little gift for this base SDK. If you ever come across the need where your `one` SDK is actually
     * the same `path` spread across more than one host - you can swap hosts on run-time to allow you to aggregate
     * results from the same SDK rather than creating new services over and over.
     *
     * NOTE: Because this class will most-likely be wrapped up by your actual SDK - you are going to have to
     * have a function, such as `getClient`, that returns the current instance.
     *
     * @param string $newBaseUrl The new base-url - no trailing `/`.
     */
    public function swapBaseUrl($newBaseUrl)
    {
        $this->baseUrl = $newBaseUrl;
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
    abstract protected function createRequest($method, $endpoint, array $headers, $body);

    /**
     * Wrapper around Guzzle's sending process of a post request.
     *
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the post request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException IFF HTTP_ERRORS are true on construct.
     */
    public function httpPost($path, array $queryParams = [], array $headers = [], $body = null)
    {
        return $this->doSendRequest(self::HTTP_METHOD_POST, $path, $queryParams, $headers, $body);
    }

    /**
     * Wrapper around Guzzle's sending process of a get request.
     *
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the get request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException IFF HTTP_ERRORS are true on construct.
     */
    public function httpGet($path, array $queryParams = [], array $headers = [], $body = null)
    {
        return $this->doSendRequest(self::HTTP_METHOD_GET, $path, $queryParams, $headers, $body);
    }

    /**
     * Wrapper around Guzzle's sending process of a put request.
     *
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the put request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException IFF HTTP_ERRORS are true on construct.
     */
    public function httpPut($path, array $queryParams = [], array $headers = [], $body = null)
    {
        return $this->doSendRequest(self::HTTP_METHOD_PUT, $path, $queryParams, $headers, $body);
    }

    /**
     * Wrapper around Guzzle's sending process of a delete request.
     *
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the delete request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException IFF HTTP_ERRORS are true on construct.
     */
    public function httpDelete($path, array $queryParams = [], array $headers = [], $body = null)
    {
        return $this->doSendRequest(self::HTTP_METHOD_DELETE, $path, $queryParams, $headers, $body);
    }

    /**
     * @return string The base url (no trailing /).
     */
    protected function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return array Any set headers.
     */
    protected function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Wrapper around Guzzle's sending process of a request.
     *
     * @param string $method     The HTTP method of the request
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the post request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException IFF HTTP_ERRORS are true on construct.
     */
    private function doSendRequest($method, $path, array $queryParams, array $headers, $body)
    {
        if ($queryParams) {
            $path = $path . '?' . http_build_query($queryParams);
        }

        $endpoint = $this->getBaseUrl() . $path;
        $requestHeaders = array_merge($this->getHeaders(), $headers);
        $request = $this->createRequest($method, $endpoint, $requestHeaders, $body);

        $response = $this->send($request);
        return $response;
    }
}
