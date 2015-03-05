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
            $result = array_shift($test['mock']['create']);

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

        $this->assertEquals($check['names'], $names, sprintf($msg, 'Failed to correctly search for the classname'));
    }

    /**
     * @group           BasicFactory
     * @covers          BasicFactory::model
     * @dataProvider    BasicFactoryDataprovider::getTestModel
     */
    public function testModel($test, $check)
    {
        $msg   = 'BasicFactory::model %s - Case: '.$check['case'];
        $names = array();

        $factory = $this->getMock('FOF30\Factory\BasicFactory', array('createModel'), array(static::$container));
        $factory->expects($this->any())->method('createModel')->willReturnCallback(function($class) use(&$test, &$names){
            $names[] = $class;
            $result = array_shift($test['mock']['create']);

            if($result !== true){
                throw new $result($class);
            }

            return $result;
        });

        if($check['exception'])
        {
            $this->setExpectedException($check['exception']);
        }

        $factory->model($test['view']);

        $this->assertEquals($check['names'], $names, sprintf($msg, 'Failed to correctly search for the classname'));
    }

    /**
     * @group           BasicFactory
     * @covers          BasicFactory::view
     * @dataProvider    BasicFactoryDataprovider::getTestView
     */
    public function testView($test, $check)
    {
        $msg   = 'BasicFactory::view %s - Case: '.$check['case'];
        $names = array();

        $factory = $this->getMock('FOF30\Factory\BasicFactory', array('createView'), array(static::$container));
        $factory->expects($this->any())->method('createView')->willReturnCallback(function($class) use(&$test, &$names){
            $names[] = $class;
            $result = array_shift($test['mock']['create']);

            if($result !== true){
                throw new $result($class);
            }

            return $result;
        });

        if($check['exception'])
        {
            $this->setExpectedException($check['exception']);
        }

        $factory->view($test['view'], $test['type']);

        $this->assertEquals($check['names'], $names, sprintf($msg, 'Failed to correctly search for the classname'));
    }

    /**
     * @group           BasicFactory
     * @covers          BasicFactory::dispatcher
     * @dataProvider    BasicFactoryDataprovider::getTestDispatcher
     */
    public function testDispatcher($test, $check)
    {
        $msg  = 'BasicFactory::dispatcher %s - Case: '.$check['case'];
        $name = '';

        $factory = $this->getMock('FOF30\Factory\BasicFactory', array('createDispatcher'), array(static::$container));
        $factory->expects($this->any())->method('createDispatcher')->willReturnCallback(function($class) use($test, &$name){
                $name   = $class;
                $result = $test['mock']['create'];

                if($result !== true){
                    throw new $result($class);
                }

                return $result;
            });

        $result = $factory->dispatcher();

        $this->assertEquals($check['name'], $name, sprintf($msg, 'Failed to search for the correct class'));

        if(is_object($result))
        {
            $this->assertInstanceOf('FOF30\Dispatcher\Dispatcher', $result, sprintf($msg, 'Failed to return the correct result'));
        }
        else
        {
            $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct result'));
        }
    }

    /**
     * @group           BasicFactory
     * @covers          BasicFactory::toolbar
     * @dataProvider    BasicFactoryDataprovider::getTestToolbar
     */
    public function testToolbar($test, $check)
    {
        $msg  = 'BasicFactory::toolbar %s - Case: '.$check['case'];
        $name = '';

        $factory = $this->getMock('FOF30\Factory\BasicFactory', array('createToolbar'), array(static::$container));
        $factory->expects($this->any())->method('createToolbar')->willReturnCallback(function($class) use($test, &$name){
            $name   = $class;
            $result = $test['mock']['create'];

            if($result !== true){
                throw new $result($class);
            }

            return $result;
        });

        $result = $factory->toolbar();

        $this->assertEquals($check['name'], $name, sprintf($msg, 'Failed to search for the correct class'));

        if(is_object($result))
        {
            $this->assertInstanceOf('FOF30\Toolbar\Toolbar', $result, sprintf($msg, 'Failed to return the correct result'));
        }
        else
        {
            $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct result'));
        }
    }

    /**
     * @group           BasicFactory
     * @covers          BasicFactory::transparentAuthentication
     * @dataProvider    BasicFactoryDataprovider::getTestTransparentAuthentication
     */
    public function testTransparentAuthentication($test, $check)
    {
        $msg  = 'BasicFactory::transparentAuthentication %s - Case: '.$check['case'];
        $name = '';

        $factory = $this->getMock('FOF30\Factory\BasicFactory', array('createTransparentAuthentication'), array(static::$container));
        $factory->expects($this->any())->method('createTransparentAuthentication')->willReturnCallback(function($class) use($test, &$name){
            $name   = $class;
            $result = $test['mock']['create'];

            if($result !== true){
                throw new $result($class);
            }

            return $result;
        });

        $result = $factory->transparentAuthentication();

        $this->assertEquals($check['name'], $name, sprintf($msg, 'Failed to search for the correct class'));

        if(is_object($result))
        {
            $this->assertInstanceOf('FOF30\TransparentAuthentication\TransparentAuthentication', $result, sprintf($msg, 'Failed to return the correct result'));
        }
        else
        {
            $this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct result'));
        }
    }
}