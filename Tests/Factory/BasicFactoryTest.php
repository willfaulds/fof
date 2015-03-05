<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Factory;

use FOF30\Factory\BasicFactory;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once 'BasicFactoryDataprovider.php';

/**
 * @covers      FOF30\Factory\BasicFactory::<protected>
 * @covers      FOF30\Factory\BasicFactory::<private>
 * @package     FOF30\Tests\Factory
 */
class BasicFactoryTest extends FOFTestCase
{
    /**
     * @group           BasicFactory
     * @covers          BasicFactory::__construct
     */
    public function test__construct()
    {
        $factory   = new BasicFactory(static::$container);
        $container = ReflectionHelper::getValue($factory, 'container');

        $this->assertSame(static::$container, $container, 'BasicFactory::__construct Failed to pass the container');
    }

    /**
     * @group           BasicFactory
     * @covers          BasicFactory::controller
     * @dataProvider    BasicFactoryDataprovider::getTestController
     */
    public function testController($test, $check)
    {
        $msg   = 'BasicFactory::controller %s - Case: '.$check['case'];
        $names = array();

        $factory = $this->getMock('FOF30\Factory\BasicFactory', array('createController'), array(static::$container));
        $factory->expects($this->any())->method('createController')->willReturnCallback(function($class) use(&$test, &$names){
            $names[] = $class;
            $result = array_shift($test['mock']['controller']);

            if($result !== true){
                throw new $result($class);
            }

            return $result;
        });

        if($check['exception'])
        {
            $this->setExpectedException($check['exception']);
        }

        $factory->controller($test['view']);

        $this->assertEquals($check['names'], $names, sprintf($msg, 'Failed to correctly search for the controller classname'));
    }
}