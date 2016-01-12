<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Images;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestJoomlaPlatform;
use org\bovigo\vfs\vfsStream;

require_once __DIR__.'/ImagesDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Images::<private>
 * @covers  FOF30\Form\Field\Images::<protected>
 */
class ImagesTest extends FOFTestCase
{
    /**
     * @group           Images
     * @group           ImagesGetFieldContents
     * @covers          FOF30\Form\Field\Images::getFieldContents
     * @dataProvider    ImagesDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Images::getFieldContents %s - Case: '.$check['case'];

        // Let's mock the filesystem, so I can create and remove files at will
        vfsStream::setup('root', null, $test['filesystem']);

        /** @var TestJoomlaPlatform $platform */
        $platform = static::$container->platform;
        $platform::$uriRoot = 'http://www.example.com';
        $platform::$baseDirs = array(
            'root' => vfsStream::url('root')
        );

        $form  = new Form(static::$container, 'Foobar');
        $field = new Images();
        $field->setForm($form);

        $data = '<field type="Images" name="foobar" ';

        foreach($test['attributes'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';
        $xml   = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->setValue($test['value']);

        $result = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}