<?php

/**
 * Filename:    AbstractFrameworkTestCase.php
 * Created:     16/01/18, at 10:45 AM
 * @author      James Hollenbeck <jhollenbeck@nabancard.com>
 * @copyright   1992-2018 North American Bancard
 */

namespace NAB\Tests;

use NAB\WebApiBaseSdk\AbstractClient;
use NAB\WebApiBaseSdk\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Central manager for the PHPUnit_Framework_TestCase. This allows for a single point-of-change for PHPUnit's interface
 * changes.
 */
abstract class AbstractFrameworkTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractClient
     */
    protected $systemUnderTest;

    /**
     * Centralizes the shenanigans of creating a class mock.
     *
     * @param string $className
     * @param array  $methods
     * @param bool   $useOriginalConstructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockForClass($className, array $methods, $useOriginalConstructor = false)
    {
        $mockBuilder = $this
            ->getMockBuilder($className)
            ->setMethods($methods);

        if (!$useOriginalConstructor) {
            $mockBuilder->disableOriginalConstructor();
        }

        return $mockBuilder->getMock();
    }
}
