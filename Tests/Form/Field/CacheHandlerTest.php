<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/CacheHandlerDataprovider.php';

/**
 * @covers  FOF30\Form\Field\CacheHandler::<private>
 * @covers  FOF30\Form\Field\CacheHandler::<protected>
 */
class CacheHandlerLevelTest extends FOFTestCase
{
    /**
     * @group           CacheHandler
     * @group           CacheHandler__get
     * @covers          FOF30\Form\Field\CacheHandler::__get
     * @dataProvider    CacheHandlerDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\CacheHandler', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           CacheHandler
     * @group           CacheHandlerGetStatic
     * @covers          FOF30\Form\Field\CacheHandler::getStatic
     * @dataProvider    CacheHandlerDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\CacheHandler', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="CacheHandler" name="foobar" ';

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
     * @group           CacheHandler
     * @group           CacheHandlerGetRepeatable
     * @covers          FOF30\Form\Field\CacheHandler::getRepeatable
     * @dataProvider    CacheHandlerDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\CacheHandler', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="CacheHandler" name="foobar" ';

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
     * @group           CacheHandler
     * @group           CacheHandlerGetFieldContents
     * @covers          FOF30\Form\Field\CacheHandler::getFieldContents
     * @dataProvider    CacheHandlerDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'CacheHandler::getFieldContents %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\CacheHandler', array('getOptions'));
        $field->method('getOptions')->willReturn(array(
            array('value' => 'apc', 'text' => 'APC'),
            array('value' => 'file', 'text' => 'File'),
        ));

        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}
