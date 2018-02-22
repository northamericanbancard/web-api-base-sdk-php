<?php

/**
 * Filename:    GuzzleSendOverrideTrait.php
 * Created:     1/22/18, at 8:08 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\ClientInterface;

/**
 * Helper for overriding guzzle logic in test helpers.
 */
trait GuzzleSendOverrideTrait
{

    /**
     * Handle the 'sending' of the request.
     *
     * @param RequestInterface $request
     * @param array $options
     *
     * @return null
     */
    public function send(RequestInterface $request, array $options = [])
    {
        //We're going to hide a spy in options
        $spyCallback = $this->getConfig('spy');
        $spyCallback($request);

        return;
    }
}
