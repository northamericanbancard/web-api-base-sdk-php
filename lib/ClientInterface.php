<?php
/**
 * Filename:    ClientInterface.php
 * Created:     21/02/18, at 2:43 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\WebApiBaseSdk;

/**
 * While it's certainly not the normal case you see interfaces acting as an enum - in this case, since HTTP
 * verbs are unlikely to grow to unmanageable bounds, due to them being relatively static for so many years,
 * we can get away with this here. Otherwise - if you need many different states/statuses, look into strategy or
 * perhaps flyweight patterns to maintain value-object states.
 */
interface ClientInterface
{
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_PUT = 'PUT';

    /**
     * Wrapper around Guzzle's sending process of a get request.
     *
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the get request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function httpGet($path, array $queryParams = [], array $headers = [], $body = null);

    /**
     * Wrapper around Guzzle's sending process of a post request.
     *
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the post request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function httpPost($path, array $queryParams = [], array $headers = [], $body = null);

    /**
     * Wrapper around Guzzle's sending process of a put request.
     *
     * @param string $path       The path to send the request to (no query params)
     * @param array $queryParams Any query params to attach to the url
     * @param array $headers     Extra headers to add to Guzzle's default
     * @param mixed $body        The body of the put request
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function httpPut($path, array $queryParams = [], array $headers = [], $body = null);
}
