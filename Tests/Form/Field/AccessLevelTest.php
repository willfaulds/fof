<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Form;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/AccessLevelDataprovider.php';

/**
 * @covers  FOF30\Form\Field\AccessLevel::<private>
 * @covers  FOF30\Form\Field\AccessLevel::<protected>
 */
class AccessLevelTest extends FOFTestCase
{
    /**
     * @group           AccessLevel
     * @group           AccessLevel__get
     * @covers          FOF30\Form\Field\AccessLevel::__get
     * @dataProvider    AccessLevelDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\AccessLevel', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           AccessLevel
     * @group           AccessLevelGetStatic
     * @covers          FOF30\Form\Field\AccessLevel::getStatic
     * @dataProvider    AccessLevelDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\AccessLevel', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="AccessLevel" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getStatic();
    }

    /**
     * @group           AccessLevel
     * @group           AccessLevelGetRepeatable
     * @covers          FOF30\Form\Field\AccessLevel::getRepeatable
     * @dataProvider    AccessLevelDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\AccessLevel', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="AccessLevel" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getRepeatable();
    }

    /**
     * @group           AccessLevel
     * @group           AccessLevelGetFieldContents
     * @covers          FOF30\Form\Field\AccessLevel::getFieldContents
     * @dataProvider    AccessLevelDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'AccessLevel::getFieldContents %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\AccessLevel', array('getOptions'));
        $field->method('getOptions')->willReturn($test['mock']['options']);

        $form = new Form(static::$container, 'Foobar');
        $field->setForm($form);

        // Registered access level
        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}