<?php

namespace FOF30\Tests\Dispatcher;

use FOF30\Dispatcher\Dispatcher;
use FOF30\Input\Input;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;

require_once 'DispatcherDataprovider.php';

/**
 * @covers      FOF30\Dispatcher\Dispatcher::<protected>
 * @covers      FOF30\Dispatcher\Dispatcher::<private>
 * @package     FOF30\Tests\Dispatcher
 */
class DispatcherTest extends FOFTestCase
{
    /**
     * @covers          FOF30\Dispatcher\Dispatcher::__construct
     * @dataProvider    DispatcherDataprovider::getTest__construct
     */
    public function test__construct($test, $check)
    {
        $msg = 'Dispatcher::__construct %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'input' => new Input($test['mock']['input'])
        ));

        $config = array();

        if($test['mock']['defaultView'])
        {
            $config['defaultView'] = $test['mock']['defaultView'];
        }

        $dispatcher = new Dispatcher($container, $config);

        $defView = ReflectionHelper::getValue($dispatcher, 'defaultView');
        $view    = ReflectionHelper::getValue($dispatcher, 'view');
        $layout  = ReflectionHelper::getValue($dispatcher, 'layout');
        $containerView = $container->input->get('view', null);

        $this->assertEquals($check['defaultView'], $defView, sprintf($msg, 'Failed to set the default view'));
        $this->assertEquals($check['view'], $view, sprintf($msg, 'Failed to set the view'));
        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['containerView'], $containerView, sprintf($msg, 'Failed to correctly update the container input'));
    }
}