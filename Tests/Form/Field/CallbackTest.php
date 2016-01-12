<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Callback;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once __DIR__.'/CallbackDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Callback::<private>
 * @covers  FOF30\Form\Field\Callback::<protected>
 */
class CallbackTest extends FOFTestCase
{
    /**
     * @group           Callback
     * @group           Callback__get
     * @covers          FOF30\Form\Field\Callback::__get
     * @dataProvider    CallbackDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Callback', array('getStatic', 'getRepeatable', 'getInput'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');
        $field->expects($this->exactly($check['input']))->method('getInput');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);
        ReflectionHelper::setValue($field, 'input', $test['input']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Callback
     * @group           CallbackGetStatic
     * @covers          FOF30\Form\Field\Callback::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMock('FOF30\Form\Field\Callback', array('getCallbackResults'));
        $field->expects($this->once())->method('getCallbackResults');

        $field->getStatic();
    }

    /**
     * @group           Callback
     * @group           CallbackGetRepeatable
     * @covers          FOF30\Form\Field\Callback::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMock('FOF30\Form\Field\Callback', array('getCallbackResults'));
        $field->expects($this->once())->method('getCallbackResults');

        $field->getRepeatable();
    }

    /**
     * @group           Callback
     * @group           CallbackGetCallbackResults
     * @covers          FOF30\Form\Field\Callback::getCallbackResults
     * @dataProvider    CallbackDataprovider::getTestGetCallbackResults
     */
    public function testGetCallbackResults($test, $check)
    {
        $msg = 'Callback::getCallbackResults %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $container = new TestContainer(array(
            'componentName'	=> 'com_fakeapp',
            // I have to mocke the parsePath method, otherwise he will use the paths to our Guinea Pig site
            'template' => new ClosureHelper(array(
                'parsePath' => function($self, $path){
                    return preg_replace('#^.*?://(.*)#', JPATH_TESTS.'/Stubs/Fakeapp/$1', $path);
                }
            ))
        ));

        $model = new DataModelStub($container, $config);
        $form  = new Form($container, 'Foobar');
        $field = new Callback();

        $data = '<field type="Callback" ';

        foreach($test['element'] as $attr => $value)
        {
            $data .= $attr.'="'.$value.'" ';
        }

        $data .= '/>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $form->loadFile(JPATH_TESTS.'/_data/form/form.default.xml');
        $form->setModel($model);

        $field->value = 'value';
        $field->setForm($form);

        $result = ReflectionHelper::invoke($field, 'getCallbackResults');

        if(!$check['result'])
        {
            $this->assertEmpty($result, sprintf($msg, 'Returned the wrong result'));
        }
        else
        {
            // If I have an actual result, let's check that the returned stuff is the same of the passed one
            // to the class method
            $this->assertSame($model, $result['model']);
            $this->assertSame($form, $result['form']);
            $this->assertSame('browse', $result['formType']);
            $this->assertSame('value', $result['fieldValue']);
            $this->assertSame($xml, $result['fieldElement']);
        }
    }
}
