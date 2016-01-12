<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\GenericList;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once __DIR__.'/GenericListDataprovider.php';

/**
 * @covers  FOF30\Form\Field\GenericList::<private>
 * @covers  FOF30\Form\Field\GenericList::<protected>
 */
class GenericListTest extends DatabaseTest
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
     * @group           GenericList
     * @group           GenericList__get
     * @covers          FOF30\Form\Field\GenericList::__get
     * @dataProvider    GenericListDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\GenericList', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           GenericList
     * @group           GenericListGetStatic
     * @covers          FOF30\Form\Field\GenericList::getStatic
     * @dataProvider    GenericListDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $msg = 'GenericList::getStatic %s - Case: '.$check['case'];

        $options = array(
            array('value' => '', 'text' => ''),
            array('value' => 'foo', 'text' => 'Foobar')
        );

        $field = $this->getMock('FOF30\Form\Field\GenericList', array('getInput', 'getOptions'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['options']))->method('getOptions')->willReturn($options);

        $field->id = 'foo';
        $field->value = 'foo';

        $data  = '<field type="GenericList" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $result = $field->getStatic();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           GenericList
     * @group           GenericListGetRepeatable
     * @covers          FOF30\Form\Field\GenericList::getRepeatable
     * @dataProvider    GenericListDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'GenericList::getRepeatable %s - Case: '.$check['case'];

        $options = array(
            array('value' => '', 'text' => ''),
            array('value' => 'foo', 'text' => 'Foobar')
        );

        $field = $this->getMock('FOF30\Form\Field\GenericList', array('getInput', 'getOptions', 'parseFieldTags'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['options']))->method('getOptions')->willReturn($options);
        $field->method('parseFieldTags')->willReturn('__PARSED__');

        $data  = '<field type="GenericList" name="foobar" ';

        if($test['attribs']['legacy'])
        {
            $data .= 'legacy="true"';
        }

        if($test['attribs']['url'])
        {
            $data .= 'url="'.$test['attribs']['url'].'"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        foreach($test['properties'] as $key => $value)
        {
            $field->$key = $value;
        }

        if($test['item'])
        {
            $config = array(
                'idFieldName' => 'foftest_foobar_id',
                'tableName'   => '#__foftest_foobars'
            );

            $model = new DataModelStub(static::$container, $config);
            $field->item = $model;
        }

        $result = $field->getRepeatable();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           GenericList
     * @group           GenericListGetOptionName
     * @covers          FOF30\Form\Field\GenericList::getOptionName
     * @dataProvider    GenericListDataprovider::getTestGetOptionName
     */
    public function testGetOptionName($test, $check)
    {
        $msg = 'GenericList::getOptionName %s - Case: '.$check['case'];

        $result = GenericList::getOptionName($test['data'], $test['selected'], $test['optkey'], $test['opttext']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           GenericList
     * @group           GenericListGetOptions
     * @covers          FOF30\Form\Field\GenericList::getOptions
     * @dataProvider    GenericListDataprovider::getTestGetOptions
     */
    public function testGetOptions($test, $check)
    {
        $msg = 'GenericList::getOptions %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName'	=> 'com_fakeapp',
            // I have to mock the parsePath method, otherwise he will use the paths to our Guinea Pig site
            'template' => new ClosureHelper(array(
                'parsePath' => function($self, $path){
                    return preg_replace('#^.*?://(.*)#', JPATH_TESTS.'/Stubs/Fakeapp/$1', $path);
                }
            ))
        ));

        $field = new GenericList();
        ReflectionHelper::setValue($field, 'fieldname', 'foftest');

        $data = '<field type="GenericList" ';

        foreach($test['attribs'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '>';

        foreach ($test['options'] as $option)
        {
            $data .= '<option value="'.$option['value'].'" '.$option['extra'].' >'.$option['text'].'</option>';
        }

        $data .= '</field>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $form = new Form($container, 'Foobar');
        $field->setForm($form);

        $result = ReflectionHelper::invoke($field, 'getOptions');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           GenericList
     * @group           GenericListParseFieldTags
     * @covers          FOF30\Form\Field\GenericList::parseFieldTags
     * @dataProvider    GenericListDataprovider::getTestParseFieldTags
     */
    public function testParseFieldTags($test, $check)
    {
        $msg = 'GenericList::parseFieldTags %s - Case: '.$check['case'];

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
        $field = new GenericList();

        if($test['load'])
        {
            $model->find($test['load']);
        }

        if($test['assign'])
        {
            $field->item = $model;
        }

        $form->setModel($model);
        $field->setForm($form);

        $result = ReflectionHelper::invoke($field, 'parseFieldTags', $test['text']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}