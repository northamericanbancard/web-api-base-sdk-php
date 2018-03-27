<?php

/**
 * Filename:    BasicClientTestHelper.php
 * Created:     03/27/18, at 10:45 AM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use NAB\WebApiBaseSdk\BasicClient;
use NAB\Tests\GuzzleSendOverrideTrait;

/**
 * Test helper that opens up the send functionality in a way that we can spy on it, rather than
 * lose it in the client helper, or having to resort to mocking.
 */
class BasicClientTestHelper extends BasicClient
{
    use GuzzleSendOverrideTrait;
}
