<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Checkboxes;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/CheckboxesDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Checkboxes::<private>
 * @covers  FOF30\Form\Field\Checkboxes::<protected>
 */
class CheckboxesTest extends FOFTestCase
{
    /**
     * @group           Checkboxes
     * @group           Checkboxes__get
     * @covers          FOF30\Form\Field\Checkboxes::__get
     * @dataProvider    CheckboxesDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Checkboxes', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Checkboxes
     * @group           CheckboxesGetStatic
     * @covers          FOF30\Form\Field\Checkboxes::getStatic
     * @dataProvider    CheckboxesDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Checkboxes', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Checkboxes" name="foobar" ';

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
     * @group           Checkboxes
     * @group           CheckboxesGetRepeatable
     * @covers          FOF30\Form\Field\Checkboxes::getRepeatable
     * @dataProvider    CheckboxesDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Checkboxes', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Checkboxes" name="foobar" ';

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
     * @group           Checkboxes
     * @group           CheckboxesGetFieldContents
     * @covers          FOF30\Form\Field\Checkboxes::getFieldContents
     * @dataProvider    CheckboxesDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Checkboxes::getFieldContents %s - Case: '.$check['case'];

        $field = new Checkboxes();

        $data = '<field type="Checkboxes" ';

        if($test['translate'])
        {
            $data .= 'translate="true" ';
        }

        $data .= '/>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->setValue($test['value']);

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}