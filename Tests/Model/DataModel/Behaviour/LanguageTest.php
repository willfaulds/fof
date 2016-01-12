<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\DataModel;

use FOF30\Input\Input;
use FOF30\Model\DataModel\Behaviour\Language;
use FOF30\Tests\Helpers\Application\AppWithLanguageFilter;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once 'LanguageDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\Language::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\Language::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\Language
 */
class LanguageTest extends DatabaseTest
{
    public function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->restoreFactoryState();
    }

    /**
     * @group           Behaviour
     * @group           LanguageOnBeforeBuildQuery
     * @covers          FOF30\Model\DataModel\Behaviour\Language::onBeforeBuildQuery
     * @dataProvider    LanguageDataprovider::getTestOnBeforeBuildQuery
     */
    public function testOnBeforeBuildQuery($test, $check)
    {
        $msg = 'Language::onAfterBuildQuery %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $platform = static::$container->platform;
        $platform::$isAdmin  = $test['mock']['admin'];
        $platform::$language = function(){
            return new ClosureHelper(array(
                'getTag' => function(){ return 'en-GB'; }
            ));
        };

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('blacklistFilters'), array(static::$container, $config));
        $model->expects($this->exactly($check['blacklist']))->method('blacklistFilters');

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $behavior   = new Language($dispatcher);

        $fakeParams = (object) array(
            'params' => array(
                'remove_default_prefix' => $test['mock']['removePrefix']
            )
        );

        ReflectionHelper::setValue($behavior, 'lang_filter_plugin', $fakeParams);

        if($test['langField'])
        {
            $model->setFieldAlias('language', $test['langField']);
            $model->addKnownField($test['langField']);
        }

        // Null for the app without the method, any value to set the app with the method
        if(is_null($test['mock']['langFilter']))
        {
            // Let's mock our application
            $fakeApp = new ClosureHelper();
        }
        else
        {
            $fakeApp = new AppWithLanguageFilter($test['mock']['langFilter']);
        }

        $fakeApp->input = new Input($test['input']);

        \JFactory::$application = $fakeApp;

        $behavior->onBeforeBuildQuery($model, $query);

        $where = ReflectionHelper::getValue($model, 'whereClauses');

        $this->assertEquals($check['where'], $where, sprintf($msg, 'Failed to set the correct where'));
    }

    /**
     * @group           Behaviour
     * @group           LanguageOnAfterLoad
     * @covers          FOF30\Model\DataModel\Behaviour\Language::onAfterLoad
     * @dataProvider    LanguageDataprovider::getTestOnAfterLoad
     */
    public function testOnAfterLoad($test, $check)
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $platform = static::$container->platform;
        $platform::$language = function(){
            return new ClosureHelper(array(
                'getTag' => function(){ return 'en-GB'; }
            ));
        };

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('reset'), array(static::$container, $config));
        $model->expects($this->exactly($check['reset']))->method('reset');

        $dispatcher = $model->getBehavioursDispatcher();
        $behavior   = new Language($dispatcher);
        $keys       = array();

        $fakeParams = (object) array(
            'params' => array(
                'remove_default_prefix' => $test['mock']['removePrefix']
            )
        );

        ReflectionHelper::setValue($behavior, 'lang_filter_plugin', $fakeParams);

        if($test['langField'])
        {
            $model->addKnownField('language');
            $model->setFieldValue('language', $test['langField']);
        }

        // Null for the app without the method, any value to set the app with the method
        if(is_null($test['mock']['langFilter']))
        {
            // Let's mock our application
            $fakeApp = new ClosureHelper();
        }
        else
        {
            $fakeApp = new AppWithLanguageFilter($test['mock']['langFilter']);
        }

        $fakeApp->input = new Input($test['input']);

        \JFactory::$application = $fakeApp;

        $behavior->onAfterLoad($model, $keys);
    }
}
