<?php

/**
 * Filename:    Client.php
 * Created:     1/22/18, at 7:55 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\WebApiBaseSdk;

use Aws\Credentials\Credentials;
use NAB\WebApiBaseSdk\SignatureV4;
use NAB\WebApiBaseSdk\AbstractClient;
use GuzzleHttp\Psr7\Request;

/**
 * Client which will allow us to sign requests we make using sigv4.
 */
class AwsApiGatewayClient extends AbstractClient
{
    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * @var SignatureV4
     */
    private $signature;

    /**
     * Sets up a new Client.
     *
     * @param string      $baseUrl     The base-url for the request, no trailing '/'
     * @param SignatureV4 $signature   An instance of AWS' SigV4 signer
     * @param Credentials $credentials The access key and secret key for an IAM user
     * @param string      $xApiKey     An API key to use during requests, if needed
     * @param array $config            Guzzle configuration
     */
    public function __construct(
        $baseUrl,
        SignatureV4 $signature,
        Credentials $credentials,
        $xApiKey = null,
        array $config = []
    ) {
        $this->signature = $signature;
        $this->credentials = $credentials;

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
        $request = new Request($method, $endpoint, $headers, $body);
        $signedRequest = $this->signature->signRequest($request, $this->credentials);

        return $signedRequest;
    }
}
