<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Media;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestJoomlaPlatform;
use org\bovigo\vfs\vfsStream;

require_once __DIR__.'/MediaDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Media::<private>
 * @covers  FOF30\Form\Field\Media::<protected>
 */
class MediaTest extends FOFTestCase
{
    /**
     * @group           Media
     * @group           Media__get
     * @covers          FOF30\Form\Field\Media::__get
     * @dataProvider    MediaDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Media', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Media
     * @group           MediaGetStatic
     * @covers          FOF30\Form\Field\Media::getStatic
     * @dataProvider    MediaDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Media', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Media" name="foobar" ';

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
     * @group           Media
     * @group           MediaGetRepeatable
     * @covers          FOF30\Form\Field\Media::getRepeatable
     * @dataProvider    MediaDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Media', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Media" name="foobar" ';

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
     * @group           Media
     * @group           MediaGetFieldContents
     * @covers          FOF30\Form\Field\Media::getFieldContents
     * @dataProvider    MediaDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Media::getFieldContents %s - Case: '.$check['case'];

        // Let's mock the filesystem, so I can create and remove files at will
        vfsStream::setup('root', null, $test['filesystem']);

        /** @var TestJoomlaPlatform $platform */
        $platform = static::$container->platform;
        $platform::$uriRoot = 'http://www.example.com';
        $platform::$baseDirs = array(
            'root' => vfsStream::url('root')
        );

        $form  = new Form(static::$container, 'Foobar');
        $field = new Media();
        $field->setForm($form);

        $data = '<field type="Media" name="foobar" ';

        foreach($test['attributes'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';
        $xml   = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->value = $test['value'];

        $result = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}