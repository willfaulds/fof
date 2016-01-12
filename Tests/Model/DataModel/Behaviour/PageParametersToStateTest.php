<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\DataModel;

use FOF30\Input\Input;
use FOF30\Model\DataModel\Behaviour\PageParametersToState;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'PageParametersToStateDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\PageParametersToState::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\PageParametersToState::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\PageParametersToState
 */
class PageParametersToStateTest extends DatabaseTest
{
    protected $savedApplication;

    public function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();
    }

    protected function tearDown()
    {
        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @group           Behaviour
     * @group           PageParametersToStateOnAfterConstruct
     * @covers          FOF30\Model\DataModel\Behaviour\PageParametersToState::onAfterConstruct
     * @dataProvider    PageParametersToStateDataprovider::getTestOnAfterConstruct
     */
    public function testOnAfterConstruct($test, $check)
    {
        $msg = 'PageParametersToState::onAfterConstruct %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $container = new TestContainer(array(
            'componentName'	=> 'com_fakeapp',
            'input'         => new Input($test['input'])
        ));

        $platform = $container->platform;
        $platform::$isAdmin = $test['mock']['admin'];
        $platform::$user = (object)array('id' => 99);

        $model = new DataModelStub($container, $config);

        foreach($test['state'] as $key => $value)
        {
            $model->setState($key, $value);
        }

        $dispatcher = $model->getBehavioursDispatcher();

        $pageparams = new PageParametersToState($dispatcher);

        $fakeApp = new ClosureHelper(array(
            'getPageParameters' => function() use($test){
                return new \JRegistry($test['params']);
            }
        ));

        \JFactory::$application = $fakeApp;

        $pageparams->onAfterConstruct($model);

        foreach($check['state'] as $key => $value)
        {
            $this->assertEquals($value, $model->getState($key), sprintf($msg, 'Failed to set the correct value for the key: '.$key));
        }
    }
}
