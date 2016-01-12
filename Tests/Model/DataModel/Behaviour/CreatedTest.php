<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */
namespace FOF30\Tests\DataModel;

use FOF30\Model\DataModel\Behaviour\Created;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'CreatedDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\Created::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\Created::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\Created
 */
class CreatedTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           CreatedOnBeforeCheck
     * @covers          FOF30\Model\DataModel\Behaviour\Created::onBeforeCheck
     */
    public function testOnBeforeCheck()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $platform = static::$container->platform;
        $platform::$user = (object)array('id' => 99);

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('addSkipCheckField'), array(static::$container, $config));
        $model->expects($this->exactly(2))->method('addSkipCheckField')
            ->withConsecutive(
                array($this->equalTo('created_on')),
                array($this->equalTo('created_by'))
            );

        $dispatcher = $model->getBehavioursDispatcher();
        $behavior   = new Created($dispatcher);

        $behavior->onBeforeCheck($model);
    }

    /**
     * @group           Behaviour
     * @group           CreatedOnBeforeCreate
     * @covers          FOF30\Model\DataModel\Behaviour\Created::onBeforeCreate
     * @dataProvider    CreatedDataprovider::getTestOnBeforeCreate
     */
    public function testOnBeforeCreate($test, $check)
    {
        $msg = 'Created::onAfterBuildQuery %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table'],
            'aliasFields' => $test['aliases']
        );

        $platform = static::$container->platform;
        $platform::$user = (object)array('id' => 99);

        $model      = new DataModelStub(static::$container, $config);
        $dataObject = new \stdClass();

        // Let's set some values inside the model
        if($test['created_on'])
        {
            $model->setFieldValue('created_on', $test['created_on']);
        }

        if($test['created_by'])
        {
            $model->setFieldValue('created_by', $test['created_by']);
        }

        $dispatcher = $model->getBehavioursDispatcher();
        $behavior   = new Created($dispatcher);

        $before_on = $model->getFieldValue('created_on');
        $before_by = $model->getFieldValue('created_by');

        $behavior->onBeforeCreate($model, $dataObject);

        $created_on = $model->getFieldAlias('created_on');
        $created_by = $model->getFieldAlias('created_by');

        if($check['created_on'])
        {
            // First of all let's check that the model and the dataobject have the same data
            $this->assertEquals($model->getFieldValue('created_on'), $dataObject->$created_on, sprintf($msg, 'Model and dataobject values are different for created_on'));

            // Of course I can't check the exact value of the timestamp, so I'll just check if they are not empty
            $this->assertNotEmpty($model->getFieldValue('created_on'), sprintf($msg, 'Saved the wrong value of created_on inside the model'));
            $this->assertNotEmpty($dataObject->$created_on, sprintf($msg, 'Saved the wrong value of created_on inside the dataobject'));

            $this->assertNotEquals('0000-00-00 00:00:00', $model->getFieldValue('created_on'), sprintf($msg, 'Saved the wrong value of created_on inside the model'));
            $this->assertNotEquals('0000-00-00 00:00:00', $dataObject->$created_on, sprintf($msg, 'Saved the wrong value of created_on inside the dataobject'));
        }
        else
        {
            $this->assertEquals($before_on, $model->getFieldValue('created_on'), sprintf($msg, 'The model field created_on should be unchanged'));
            $this->assertObjectNotHasAttribute($created_on, $dataObject, sprintf($msg, 'The dataobject should not have the created_on field'));
        }

        if($check['created_by'])
        {
            // First of all let's check that the model and the dataobject have the same data
            $this->assertEquals($model->getFieldValue('created_by'), $dataObject->$created_by, sprintf($msg, 'Model and dataobject values are different for created_by'));

            $this->assertEquals($check['created_by'], $model->getFieldValue('created_by'), sprintf($msg, 'Saved the wrong value of created_by inside the model'));
            $this->assertEquals($check['created_by'], $dataObject->$created_by, sprintf($msg, 'Saved the wrong value of created_by inside the dataobject'));
        }
        else
        {
            $this->assertEquals($before_by, $model->getFieldValue('created_by'), sprintf($msg, 'The model field created_by should be unchanged'));
            $this->assertObjectNotHasAttribute($created_by, $dataObject, sprintf($msg, 'The dataobject should not have the created_by field'));
        }
    }
}
