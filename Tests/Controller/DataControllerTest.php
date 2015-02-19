<?php

namespace FOF30\Tests\DataController;

use FOF30\Input\Input;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Controller\DataControllerStub;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'DataControllerDataprovider.php';

/**
 * @covers      FOF30\Controller\DataController::<protected>
 * @covers      FOF30\Controller\DataController::<private>
 * @package     FOF30\Tests\DataController
 */
class DataControllertest extends FOFTestCase
{
    /**
     * @covers          FOF30\Controller\DataController::__construct
     * @dataProvider    DataControllerDataprovider::getTest__construct
     */
    public function test__construct($test, $check)
    {
        $msg = 'DataController::__construct %s - Case: '.$check['case'];

        $config = array();

        if($test['model'])
        {
            $config['modelName'] = $test['model'];
        }

        if($test['view'])
        {
            $config['viewName'] = $test['view'];
        }

        if($test['cache'])
        {
            $config['cacheableTasks'] = $test['cache'];
        }

        if($test['privileges'])
        {
            $config['taskPrivileges'] = $test['privileges'];
        }

        $controller = new DataControllerStub(static::$container, $config);

        $modelName = ReflectionHelper::getValue($controller, 'modelName');
        $viewName  = ReflectionHelper::getValue($controller, 'viewName');
        $cache     = ReflectionHelper::getValue($controller, 'cacheableTasks');
        $privileges = ReflectionHelper::getValue($controller, 'taskPrivileges');

        $this->assertEquals($check['model'], $modelName, sprintf($msg, 'Failed to set the correct modelName'));
        $this->assertEquals($check['view'], $viewName, sprintf($msg, 'Failed to set the correct viewName'));
        $this->assertEquals($check['cache'], $cache, sprintf($msg, 'Failed to set the correct task cache'));
        $this->assertEquals($check['privileges'], $privileges, sprintf($msg, 'Failed to set the correct task privileges'));
    }

    /**
     * @group           DataController
     * @group           DataControllerBrowse
     * @covers          FOF30\Controller\DataController::browse
     * @dataProvider    DataControllerDataprovider::getTestBrowse
     */
    public function testBrowse($test, $check)
    {
        $msg = 'DataController::browse %s - Case: '.$check['case'];

        $checker = array();
        $input = new Input($test['mock']['input']);

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => $input
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('display', 'getModel'), array($container));
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['display']));

        $controller->expects($this->any())->method('getModel')->willReturn(new ClosureHelper(array(
            'savestate' => function($self, $state) use(&$checker){
                $checker['savestate'] = $state;
            },
            'setFormName' => function($self, $formName) use(&$checker){
                $checker['setFormName'] = $formName;
            },
            'getForm' => function() use($test){
                return $test['mock']['getForm'];
            }
        )));

        ReflectionHelper::setValue($controller, 'cacheableTasks', $test['mock']['cache']);
        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);

        $controller->browse();

        $hasForm = ReflectionHelper::getValue($controller, 'hasForm');

        $this->assertEquals($check['savestate'], $checker['savestate'], sprintf($msg, 'Failed to correctly set the savestate'));
        $this->assertEquals($check['formName'], $checker['setFormName'], sprintf($msg, 'Failed to correctly set the form name'));
        $this->assertEquals($check['hasForm'], $hasForm, sprintf($msg, 'Failed to set hasForm'));
    }

    /**
     * @covers          FOF30\Controller\DataController::read
     * @dataProvider    DataControllerDataprovider::getTestRead
     */
    public function testRead($test, $check)
    {
        $msg     = 'DataController::read %s - Case: '.$check['case'];

        $modelMethods = array('getId', 'getForm', 'setFormName');
        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub', $modelMethods, array(), '', false);

        $model->expects($this->any())->method('getId')->willReturnCallback(function() use (&$test){
            return array_shift($test['mock']['getId']);
        });

        $model->expects($this->any())->method('getForm')->willReturn($test['mock']['getForm']);
        $model->expects($this->any())->method('setFormName')->with($check['setForm']);


        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getModel', 'getIDsFromRequest', 'display'), array(static::$container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')
            ->willReturn($test['mock']['ids']);

        $controller->expects($this->any())->method('display')->with($this->equalTo($check['display']));

        if($check['exception'])
        {
            $this->setExpectedException('FOF30\Controller\Exception\ItemNotFound', 'COM_FAKEAPP_ERR_NESTEDSET_NOTFOUND');
        }

        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);
        ReflectionHelper::setValue($controller, 'cacheableTasks', $test['mock']['cache']);

        $controller->read();

        $layout  = ReflectionHelper::getValue($controller, 'layout');
        $hasForm = ReflectionHelper::getValue($controller, 'hasForm');

        $this->assertEquals($check['layout'], $layout, sprintf($msg, 'Failed to set the layout'));
        $this->assertEquals($check['hasForm'], $hasForm, sprintf($msg, 'Failed to set the hasForm flag'));
    }

    /**
     * @group           DataController
     * @group           DataControllerAdd
     * @covers          FOF30\Controller\DataController::add
     * @dataProvider    DataControllerDataprovider::getTestAdd
     */
    public function tXestAdd($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $container->segment->setFlash('fakeapp_dummycontrollers', $test['mock']['flash']);

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('reset', 'bind'), array($container));
        $model->expects($this->any())->method('reset')->willReturn(null);
        $model->expects($check['bind'] ? $this->once() : $this->never())->method('bind')
            ->with($check['bind'])->willReturn(null);

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getModel', 'display'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('display')->willReturn(null);

        $controller->add();
    }

    /**
     * @group           DataController
     * @group           DataControllerEdit
     * @covers          FOF30\Controller\DataController::edit
     * @dataProvider    DataControllerDataprovider::getTestEdit
     */
    public function tXestEdit($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $container->segment->setFlash('fakeapp_dummycontrollers', $test['mock']['flash']);

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('getId', 'lock', 'bind'), array($container));
        $model->expects($this->any())->method('getId')->willReturn($test['mock']['getId']);

        $method = $model->expects($this->any())->method('lock');

        if($test['mock']['lock'] === 'throw')
        {
            $method->willThrowException(new \Exception('Exception thrown while locking'));
        }
        else
        {
            $method->willReturn(null);
        }

        $model->expects($check['bind'] ? $this->once() : $this->never())->method('bind')
                ->with($check['bind'])->willReturn(null);

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub',
            array('getModel', 'getIDsFromRequest', 'setRedirect', 'display'), array($container));

        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest')->willReturn(null);
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')
            ->willReturn(null)->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo('error'));
        $controller->expects($check['display'] ? $this->once() : $this->never())->method('display')->willReturn(null);

        ReflectionHelper::setValue($controller, 'layout', $test['mock']['layout']);

        $controller->edit();

        $layout = ReflectionHelper::getValue($controller, 'layout');
        $this->assertEquals($check['layout'], $layout, 'DataController::edit failed to set the layout');
    }

    /**
     * @group           DataController
     * @group           DataControllerApply
     * @covers          FOF30\Controller\DataController::apply
     * @dataProvider    DataControllerDataprovider::getTestApply
     */
    public function tXestApply($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'id' => $test['mock']['id'],
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'applySave', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('applySave')->willReturn($test['mock']['apply']);
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')
            ->willReturn(null)->with($this->equalTo($check['url']), $this->equalTo($check['msg']));

        $controller->apply();
    }

    /**
     * @group           DataController
     * @group           DataControllerCopy
     * @covers          FOF30\Controller\DataController::copy
     * @dataProvider    DataControllerDataprovider::getTestCopy
     */
    public function tXestCopy($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('find', 'copy'), array($container));
        $model->expects($this->any())->method('find')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['find']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in find');
                }

                return $ret;
            }
        );

        $model->expects($this->any())->method('copy')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['copy']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in copy');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->copy();
    }

    /**
     * @group           DataController
     * @group           DataControllerSave
     * @covers          FOF30\Controller\DataController::save
     * @dataProvider    DataControllerDataprovider::getTestSave
     */
    public function tXestSave($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'applySave', 'setRedirect'), array($container));
        $controller->expects($this->once())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->once())->method('applySave')->willReturn($test['mock']['apply']);
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')->willReturn(null)
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']))
        ;

        $controller->save();
    }

    /**
     * @group           DataController
     * @group           DataControllerSavenew
     * @covers          FOF30\Controller\DataController::savenew
     * @dataProvider    DataControllerDataprovider::getTestSavenew
     */
    public function tXestSavenew($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'applySave', 'setRedirect'), array($container));
        $controller->expects($this->once())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->once())->method('applySave')->willReturn($test['mock']['apply']);
        $controller->expects($check['redirect'] ? $this->once() : $this->never())->method('setRedirect')->willReturn(null)
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']))
        ;

        $controller->savenew();
    }

    /**
     * @group           DataController
     * @group           DataControllerCancel
     * @covers          FOF30\Controller\DataController::cancel
     * @dataProvider    DataControllerDataprovider::getTestCancel
     */
    public function tXestCancel($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('unlock', 'getId'), array($container));
        $model->expects($this->once())->method('getId')->willReturn($test['mock']['getId']);
        $model->expects($this->once())->method('unlock')->willReturn(null);

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null)->with($this->equalTo($check['url']));

        // In this test we can't check if data has been removed from the Session, since I'll have to mock the entire framework
        // my pc, myself and probably the entire universe
        $controller->cancel();
    }

    /**
     * @group           DataController
     * @group           DataControllerPublish
     * @covers          FOF30\Controller\DataController::publish
     * @dataProvider    DataControllerDataprovider::getTestPublish
     */
    public function tXestPublish($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'idFieldName' => 'id',
                'tableName'   => '#__dbtest'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('publish'), array($container));
        $model->expects($this->any())->method('publish')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['publish']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in publish');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->publish();
    }

    /**
     * @group           DataController
     * @group           DataControllerUnpublish
     * @covers          FOF30\Controller\DataController::unpublish
     * @dataProvider    DataControllerDataprovider::getTestUnpublish
     */
    public function tXestUnpublish($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'idFieldName' => 'id',
                'tableName'   => '#__dbtest'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('unpublish'), array($container));
        $model->expects($this->any())->method('unpublish')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['unpublish']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in unpublish');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->unpublish();
    }

    /**
     * @group           DataController
     * @group           DataControllerArchive
     * @covers          FOF30\Controller\DataController::archive
     * @dataProvider    DataControllerDataprovider::getTestArchive
     */
    public function tXestArchive($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'idFieldName' => 'id',
                'tableName'   => '#__dbtest'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('archive'), array($container));
        $model->expects($this->any())->method('archive')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['archive']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in archive');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->archive();
    }

    /**
     * @group           DataController
     * @group           DataControllerTrash
     * @covers          FOF30\Controller\DataController::trash
     * @dataProvider    DataControllerDataprovider::getTestTrash
     */
    public function tXestTrash($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'idFieldName' => 'id',
                'tableName'   => '#__dbtest'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('trash'), array($container));
        $model->expects($this->any())->method('trash')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['trash']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in trash');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->trash();
    }

    /**
     * The best way to test with method is to run it and check vs the database
     *
     * @group           DataController
     * @group           DataControllerSaveorder
     * @covers          FOF30\Controller\DataController::saveorder
     * @dataProvider    DataControllerDataprovider::getTestsaveorder
     */
    public function tXestSaveorder($test, $check)
    {
        $msg = 'DataController::saveorder %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'order'     => $test['ordering'],
                'returnurl' => $test['returnurl'] ? base64_encode($test['returnurl']) : '',
            )),
            'mvc_config' => array(
                'idFieldName' => 'id',
                'tableName'   => $test['table']
            )
        ));

        $model      = new DataModelStub($container);
        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null)
            ->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->saveorder();

        $db = self::$driver;

        $query = $db->getQuery(true)
                    ->select('id')
                    ->from($db->qn('#__dbtest_extended'))
                    ->order($db->qn('ordering').' ASC');
        $rows = $db->setQuery($query)->loadColumn();

        $this->assertEquals($check['rows'], $rows, sprintf($msg, 'Failed to save the order of the rows'));
    }

    /**
     * @group           DataController
     * @group           DataControllerOrderdown
     * @covers          FOF30\Controller\DataController::orderdown
     * @dataProvider    DataControllerDataprovider::getTestOrderdown
     */
    public function tXestOrderdown($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('move', 'getId'), array($container));
        $model->expects($this->once())->method('getId')->willReturn($test['mock']['getId']);
        $model->expects($this->any())->method('move')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['move']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in move');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->orderdown();
    }

    /**
     * @group           DataController
     * @group           DataControllerOrderup
     * @covers          FOF30\Controller\DataController::orderup
     * @dataProvider    DataControllerDataprovider::getTestOrderup
     */
    public function tXestOrderup($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('move', 'getId'), array($container));
        $model->expects($this->once())->method('getId')->willReturn($test['mock']['getId']);
        $model->expects($this->any())->method('move')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['move']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in move');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($check['getFromReq'] ? $this->once() : $this->never())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->orderup();
    }

    /**
     * @group           DataController
     * @group           DataControllerRemove
     * @covers          FOF30\Controller\DataController::remove
     * @dataProvider    DataControllerDataprovider::getTestRemove
     */
    public function tXestRemove($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'returnurl' => $test['mock']['returnurl'] ? base64_encode($test['mock']['returnurl']) : '',
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('find', 'delete'), array($container));
        $model->expects($this->any())->method('find')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['find']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in find');
                }

                return $ret;
            }
        );

        $model->expects($this->any())->method('delete')->willReturnCallback(
            function() use (&$test)
            {
                // Should I return a value or throw an exception?
                $ret = array_shift($test['mock']['delete']);

                if($ret === 'throw')
                {
                    throw new \Exception('Exception in delete');
                }

                return $ret;
            }
        );

        $controller = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataControllerStub', array('csrfProtection', 'getModel', 'getIDsFromRequest', 'setRedirect'), array($container));
        $controller->expects($this->any())->method('csrfProtection')->willReturn(null);
        $controller->expects($this->any())->method('getModel')->willReturn($model);
        $controller->expects($this->any())->method('getIDsFromRequest')->willReturn($test['mock']['ids']);
        $controller->expects($this->once())->method('setRedirect')->willReturn(null);

        $controller->expects($this->once())->method('setRedirect')->with($this->equalTo($check['url']), $this->equalTo($check['msg']), $this->equalTo($check['type']));

        $controller->remove();
    }

    /**
     * @group           DataController
     * @group           DataControllerGetModel
     * @covers          FOF30\Controller\DataController::getModel
     * @dataProvider    DataControllerDataprovider::getTestGetModel
     */
    public function tXestGetModel($test, $check)
    {
        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'id',
                'tableName'   => '#__dbtest'
            )
        ));

        $controller = new DataControllerStub($container);

        if($check['exception'])
        {
            $this->setExpectedException('Exception');
        }

        $model = $controller->getModel($test['model']);

        $this->assertInstanceOf('\\FOF30\\Controller\\DataModel', $model, 'DataController::getModel should return a DataModel');
    }

    /**
     * @group           DataController
     * @group           DataControllerGetIDsFromRequest
     * @covers          FOF30\Controller\DataController::getIDsFromRequest
     * @dataProvider    DataControllerDataprovider::getTestGetIDsFromRequest
     */
    public function tXestGetIDsFromRequest($test, $check)
    {
        $msg = 'DataController::getIDsFromRequest %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => 'com_fakeapp',
            'input' => new Input(array(
                'cid' => $test['mock']['cid'],
                'id'  => $test['mock']['id'],
                'dbtest_nestedset_id' => $test['mock']['kid']
            )),
            'mvc_config' => array(
                'autoChecks'  => false,
                'idFieldName' => 'dbtest_nestedset_id',
                'tableName'   => '#__dbtest_nestedsets'
            )
        ));

        $model = $this->getMock('\\FOF30\\Tests\\Stubs\\Controller\\DataModelStub', array('find'), array($container));
        $model->expects($check['load'] ? $this->once() : $this->never())->method('find')->with($check['loadid']);

        $controller = new DataControllerStub($container);

        $result = $controller->getIDsFromRequest($model, $test['load']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
    }
}