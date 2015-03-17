<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Toolbar;

use FOF30\Input\Input;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Toolbar\ToolbarStub;
use FOF30\Toolbar\Toolbar;

require_once 'ToolbarDataprovider.php';
require_once JPATH_TESTS.'/Stubs/Joomla/JToolbarHelper.php';

/**
 * @covers  FOF30\Toolbar\Toolbar::<protected>
 * @covers  FOF30\Toolbar\Toolbar::<private>
 */
class ToolbarTest extends FOFTestCase
{
    /**
     * @covers          FOF30\Toolbar\Toolbar::__construct
     */
    public function test__construct()
    {
        $msg = 'Toolbar::__construct %s';
        $config = array(
            'renderFrontendButtons' => true,
            'renderFrontendSubmenu' => true
        );

        $platform = static::$container->platform;
        // Otherwise the toolbar will try to load the js framework
        $platform::$isCli = true;
        $platform::$authorise = function(){
            // To keep it simply, the user has access to everything
            return true;
        };

        $toolbar = new Toolbar(static::$container, $config);

        $perms   = ReflectionHelper::getValue($toolbar, 'perms');
        $buttons = ReflectionHelper::getValue($toolbar, 'renderFrontendButtons');
        $submenu = ReflectionHelper::getValue($toolbar, 'renderFrontendSubmenu');

        $checkPerms = (object) array(
            'manage'    => true,
            'create'    => true,
            'edit'      => true,
            'editstate' => true,
            'delete'    => true,
        );

        $this->assertTrue($buttons, sprintf($msg, 'Failed to set the frontend buttons flag'));
        $this->assertTrue($submenu, sprintf($msg, 'Failed to set the frontend submenu flag'));
        $this->assertEquals($checkPerms, $perms, sprintf($msg, 'Failed to set the permissions'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::renderToolbar
     * @dataProvider    ToolbarDataprovider::getTestRenderToolbar
     */
    public function testRenderToolbar($test, $check)
    {
        $msg = 'Toolbar::renderToolbar %s - Case: '.$check['case'];

        $controller = $this->getMock('\FOF30\Tests\Stubs\Controller\ControllerStub', array('getName', 'getTask'), array(static::$container));
        $controller->expects($this->any())->method('getName')->willReturn($test['mock']['getName']);
        $controller->expects($this->any())->method('getTask')->willReturn($test['mock']['getTask']);

        $dispacher = $this->getMock('FOF30\Dispatcher\Dispatcher', array('getController'), array(static::$container));
        $dispacher->expects($this->any())->method('getController')
            ->willReturn($test['mock']['getController'] ? $controller : null);

        $appConfig = $this->getMock('FOF30\Configuration\Configuration', array('get'), array(), '', false);
        $appConfig->expects($this->any())->method('get')->with($check['config'])->willReturn($test['mock']['config']);

        $container = new TestContainer(array(
            'input'      => new Input($test['input']),
            'dispatcher' => $dispacher,
            'appConfig'  => $appConfig
        ));

        $toolbar = new ToolbarStub($container);

        ReflectionHelper::setValue($toolbar, 'useConfigurationFile', $test['useConfig']);

        $toolbar->renderToolbar($test['view'], $test['task']);

        $methods = $toolbar->methodCounter;

        $this->assertEquals($check['counter'], $methods, sprintf($msg, 'Failed to correctly invoke "on" methods'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::getRenderFrontendButtons
     */
    public function testGetRenderFrontendButtons()
    {
        $toolbar = new ToolbarStub(static::$container);

        ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', true);

        $this->assertTrue($toolbar->getRenderFrontendButtons());
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::setRenderFrontendButtons
     */
    public function testSetRenderFrontendButtons()
    {
        $toolbar = new ToolbarStub(static::$container);

        $toolbar->setRenderFrontendButtons(true);

        $this->assertTrue(ReflectionHelper::getValue($toolbar, 'renderFrontendButtons'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::getRenderFrontendSubmenu
     */
    public function testGetRenderFrontendSubmenu()
    {
        $toolbar = new ToolbarStub(static::$container);

        ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', true);

        $this->assertTrue($toolbar->getRenderFrontendSubmenu());
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::setRenderFrontendSubmenu
     */
    public function testSetRenderFrontendSubmenu()
    {
        $toolbar = new ToolbarStub(static::$container);

        $toolbar->setRenderFrontendSubmenu(true);

        $this->assertTrue(ReflectionHelper::getValue($toolbar, 'renderFrontendSubmenu'));
    }

    /**
     * @covers          FOF30\Toolbar\Toolbar::isDataView
     * @dataProvider    ToolbarDataprovider::getTestIsDataView
     */
    public function testIsDataView($test, $check)
    {
        $msg = 'Toolbar::isDataView %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';
        $platform::$uriBase  = 'www.example.com';

        $TestContainer = static::$container;

        $controller = $this->getMock('\FOF30\Tests\Stubs\Controller\ControllerStub', array('getView'), array(static::$container));
        $controller->expects($this->any())->method('getView')->willReturnCallback(function() use ($test, $TestContainer){
            if(!is_null($test['mock']['getView'])){
                return new $test['mock']['getView']($TestContainer);
            }

            return null;
        });

        $dispacher = $this->getMock('FOF30\Dispatcher\Dispatcher', array('getController'), array(static::$container));
        $dispacher->expects($this->any())->method('getController')
            ->willReturn($test['mock']['getController'] ? $controller : null);

        $container = new TestContainer(array(
            'dispatcher' => $dispacher
        ));

        $toolbar = new ToolbarStub($container);

        ReflectionHelper::setValue($toolbar, 'isDataView', $test['mock']['cache']);

        $result = $toolbar->isDataView();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}
