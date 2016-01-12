<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Editor;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/EditorDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Editor::<private>
 * @covers  FOF30\Form\Field\Editor::<protected>
 */
class EditorTest extends FOFTestCase
{
    /**
     * @group           Editor
     * @group           Editor__get
     * @covers          FOF30\Form\Field\Editor::__get
     * @dataProvider    EditorDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Editor', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Editor
     * @group           EditorGetStatic
     * @covers          FOF30\Form\Field\Editor::getStatic
     * @dataProvider    EditorDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Editor', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Editor" name="foobar" ';

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
     * @group           Editor
     * @group           EditorGetRepeatable
     * @covers          FOF30\Form\Field\Editor::getRepeatable
     * @dataProvider    EditorDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Editor', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Editor" name="foobar" ';

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
     * @group           Editor
     * @group           EditorGetFieldContents
     * @covers          FOF30\Form\Field\Editor::getFieldContents
     * @dataProvider    EditorDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Editor::getFieldContents %s - Case: '.$check['case'];

        $field = new Editor();

        $field->class = $test['class'];
        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}