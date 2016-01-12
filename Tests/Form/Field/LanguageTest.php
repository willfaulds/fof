<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Language;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/LanguageDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Language::<private>
 * @covers  FOF30\Form\Field\Language::<protected>
 */
class LanguageTest extends FOFTestCase
{
    /**
     * @group           Language
     * @group           Language__get
     * @covers          FOF30\Form\Field\Language::__get
     * @dataProvider    LanguageFieldDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Language', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Language
     * @group           LanguageGetStatic
     * @covers          FOF30\Form\Field\Language::getStatic
     * @dataProvider    LanguageFieldDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Language', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Language" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getStatic();
    }

    /**
     * @group           Language
     * @group           LanguageGetRepeatable
     * @covers          FOF30\Form\Field\Language::getRepeatable
     * @dataProvider    LanguageFieldDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Language', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Language" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getRepeatable();
    }

    /**
     * @group           Language
     * @group           LanguageGetFieldContents
     * @covers          FOF30\Form\Field\Language::getFieldContents
     * @dataProvider    LanguageFieldDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Language::getFieldContents %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\Language', array('getOptions'));
        $field->method('getOptions')->willReturn(array(
            array('value' => 'it-IT', 'text' => 'Italian'),
            array('value' => 'en-GB', 'text' => 'English'),
        ));

        // Registered access level
        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Language
     * @group           LanguageGetOptions
     * @covers          FOF30\Form\Field\Language::getOptions
     * @dataProvider    LanguageFieldDataprovider::getTestGetOptions
     */
    public function testGetOptions($test, $check)
    {
        $msg = 'Language::getOptions %s - Case: '.$check['case'];

        $data = '<field type="Language" ';

        foreach($test['attribs'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';

        $xml = simplexml_load_string($data);

        $field = new Language();

        ReflectionHelper::setValue($field, 'element', $xml);
        ReflectionHelper::setValue($field, 'cachedOptions', $test['mock']['cache']);

        $result = ReflectionHelper::invoke($field, 'getOptions');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}