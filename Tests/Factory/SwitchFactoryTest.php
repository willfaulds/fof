<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Factory;

use FOF30\Factory\SwitchFactory;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;

require_once 'SwitchFactoryDataprovider.php';

/**
 * @covers      FOF30\Factory\SwitchFactory::<protected>
 * @covers      FOF30\Factory\SwitchFactory::<private>
 * @package     FOF30\Tests\Factory
 */
class SwitchFactoryTest extends FOFTestCase
{
    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::__construct
     */
    public function test__construct()
    {
        $factory = new SwitchFactory(static::$container);

        $this->assertTrue(ReflectionHelper::getValue($factory, 'formLookupInOtherSide'));
    }

    /**
     * @group           SwitchFactory
     * @covers          FOF30\Factory\SwitchFactory::controller
     * @dataProvider    SwitchFactoryDataprovider::getTestController
     */
    public function testController($test, $check)
    {
        $msg   = 'SwitchFactory::controller %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$isAdmin = $test['backend'];

        $factory = new SwitchFactory(static::$container);

        $result = $factory->controller($test['view']);

        $this->assertInstanceOf($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}