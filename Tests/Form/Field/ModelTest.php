<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Model;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once __DIR__.'/ModelDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Model::<private>
 * @covers  FOF30\Form\Field\Model::<protected>
 */
class ModelTest extends DatabaseTest
{
    /**
     * @group           ModelField
     * @group           Model__get
     * @covers          FOF30\Form\Field\Model::__get
     * @dataProvider    FOF30\Tests\Form\Field\ModelDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Model', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           ModelField
     * @group           ModelGetStatic
     * @covers          FOF30\Form\Field\Model::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMock('FOF30\Form\Field\Model', array('getOptions'));
        $field->method('getOptions')->willReturn(array(
            array('value' => 'foo', 'text' => 'Foobar'),
            array('value' => 'dummy', 'text' => 'Dummy'),
        ));

        $field->id    = 'foo';
        $field->class = 'foo-class';
        $field->value = 'dummy';

        $result = $field->getStatic();

        $this->assertEquals('<span id="foo" class="foo-class">Dummy</span>', $result);
    }

    /**
     * @group           ModelField
     * @group           ModelGetRepeatable
     * @covers          FOF30\Form\Field\Model::getRepeatable
     * @dataProvider    FOF30\Tests\Form\Field\ModelDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'Model::getRepeatable %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\Model', array('parseFieldTags', 'getOptions'));
        $field->method('parseFieldTags')->willReturn('__PARSED__');
        $field->method('getOptions')->willReturn(array(
            array('value' => 'foo', 'text' => 'Foobar'),
            array('value' => 'dummy', 'text' => 'Dummy'),
        ));

        $data = '<field type="Model" ';

        foreach($test['attribs'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        foreach ($test['properties'] as $key => $value)
        {
            ReflectionHelper::setValue($field, $key, $value);
        }

        if($test['item'])
        {
            $config = array(
                'tableName' => '#__foftest_foobars',
                'idFieldName' => 'foftest_foobar_id'
            );

            $item = new DataModelStub(static::$container, $config);
            ReflectionHelper::setValue($field, 'item', $item);
        }

        $result = $field->getRepeatable();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           ModelField
     * @group           ModelGetOptions
     * @covers          FOF30\Form\Field\Model::getOptions
     * @dataProvider    FOF30\Tests\Form\Field\ModelDataprovider::getTestGetOptions
     */
    public function testGetOptions($test, $check)
    {
        $msg = 'Model::getOptions %s - Case: '.$check['case'];

        $callstack = array();

        $config = array(
            'tableName' => '#__foftest_foobars',
            'idFieldName' => 'foftest_foobar_id'
        );

        $methods = array('applyAccessFiltering', 'with', 'setState', 'get');
        $model   = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', $methods, array(static::$container, $config));
        $model->expects($this->exactly($check['applyAccess']))->method('applyAccessFiltering');
        $model->expects($this->exactly($check['with']))->method('with');
        $model->method('get')->willReturn($test['mock']['get']);
        $model->method('setState')->willReturnCallback(function($key, $value) use(&$callstack){
            $callstack[$key] = $value;
        });

        // Let's create our mocked factory that will return our mocked model
        $container = new TestContainer(array(
            'factory' => function() use ($model){
                return new ClosureHelper(array(
                    'model' => function() use($model){ return $model; }
                ));
            }
        ));

        $field = $this->getMock('FOF30\Form\Field\Model', array('parseFieldTags'));
        $field->method('parseFieldTags')->willReturn('__PARSED__');

        $form = new Form($container, 'Foobar');
        $field->setForm($form);

        ReflectionHelper::setValue($field, 'loadedOptions', $test['cache']);

        $data = '<field type="Model" ';

        // Let's add field attributes
        foreach($test['attribs'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '>';

        // Let's add state children elements
        foreach($test['children']['state'] as $key => $value)
        {
            $data .= '<state key="'.$key.'">'.$value.'</state>';
        }

        // Let's add state children elements
        foreach($test['children']['options'] as $value => $text)
        {
            $data .= '<option value="'.$value.'">'.$text.'</option>';
        }

        $data .= '</field>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $result = ReflectionHelper::invoke($field, 'getOptions', $test['force']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
        $this->assertEquals($check['setState'], $callstack, sprintf($msg, 'Failed to correctly set the state of the model'));
    }

    /**
     * @group           ModelField
     * @group           ModelParseFieldTags
     * @covers          FOF30\Form\Field\Model::parseFieldTags
     * @dataProvider    FOF30\Tests\Form\Field\ModelDataprovider::getTestParseFieldTags
     */
    public function testParseFieldTags($test, $check)
    {
        $msg = 'Model::parseFieldTags %s - Case: '.$check['case'];

        $item   = null;
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $fakeSession = new ClosureHelper(array(
            'getFormToken' => function(){
                return '_FAKE_SESSION_';
            }
        ));

        \JFactory::$session = $fakeSession;

        $input = static::$container->input;
        $input->set('Itemid', 100);

        $model = new DataModelStub(static::$container, $config);
        $form  = new Form(static::$container, 'Foobar');
        $field = new Model();

        if($test['load'])
        {
            $model->find($test['load']);
        }

        if($test['assign'])
        {
            $item = $model;
        }

        $form->setModel($model);
        $field->setForm($form);

        $result = ReflectionHelper::invoke($field, 'parseFieldTags', $test['text'], $item);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}