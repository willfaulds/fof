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

require_once __DIR__.'/IntegerDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Integer::<private>
 * @covers  FOF30\Form\Field\Integer::<protected>
 */
class IntegerTest extends FOFTestCase
{
    /**
     * @group           Integer
     * @group           Integer__get
     * @covers          FOF30\Form\Field\Integer::__get
     * @dataProvider    IntegerDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Integer', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Integer
     * @group           IntegerGetStatic
     * @covers          FOF30\Form\Field\Integer::getStatic
     * @dataProvider    IntegerDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $msg = 'Integer::getStatic %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\Integer', array('getInput'));
        $field->expects($this->exactly($check['input']))->method('getInput');

        $field->id = 'foo';
        $field->value = '12';

        $data  = '<field type="Integer" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $result = $field->getStatic();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Integer
     * @group           IntegerGetRepeatable
     * @covers          FOF30\Form\Field\Integer::getRepeatable
     * @dataProvider    IntegerDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'Integer::getRepeatable %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\Integer', array('getInput', 'getOptions'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->method('getOptions')->willReturn(array(
            array('value' => 1, 'text' => 1 ),
            array('value' => 5, 'text' => 5 ),
            array('value' => 10, 'text' => 10 ),
            array('value' => 12, 'text' => 12 ),
        ));

        $field->id = 'foo';
        $field->value = 12;

        $data  = '<field type="Integer" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $result = $field->getRepeatable();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}