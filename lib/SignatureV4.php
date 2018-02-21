<?php

/**
 * Filename:    SignatureV4.php
 * Created:     1/22/18, at 8:39 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\WebApiBaseSdk;

use Aws\Signature\SignatureV4 as AwsSignatureV4;

/**
 * An extension of Amazon's SignatureV4 class, that allows us to have sensible defaults
 * we are likely to use most of the time in-house.
 */
class SignatureV4 extends AwsSignatureV4
{
    /**
     * Sets up a new SignatureV4 with sensible defaults.
     *
     * @param string $service Service name to use when signing
     * @param string $region  Region name to use when signing
     * @param array $options  Array of configuration options used when signing
     *                          - unsigned-body: Flag to make request have unsigned payload.
     *                            Unsigned body is used primarily for streaming requests.
     */
    public function __construct($service = 'execute-api', $region = 'us-east-1', array $options = [])
    {
        parent::__construct($service, $region, $options);
    }
}
