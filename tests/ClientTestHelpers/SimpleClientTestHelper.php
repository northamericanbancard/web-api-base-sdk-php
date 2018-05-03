<?php

/**
 * Filename:    SimpleClientTestHelper.php
 * Created:     1/22/18, at 8:08 PM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests\ClientTestHelpers;

use NAB\WebApiBaseSdk\SimpleClient as SimpleClient;
use NAB\Tests\GuzzleSendOverrideTrait;

/**
 * Test helper that opens up the send functionality in a way that we can spy on it, rather than
 * lose it in the client helper, or having to resort to mocking.
 */
class SimpleClientTestHelper extends SimpleClient
{
    use GuzzleSendOverrideTrait;
}
