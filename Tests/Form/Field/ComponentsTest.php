<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Components;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestJoomlaPlatform;

require_once __DIR__.'/ComponentsDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Components::<private>
 * @covers  FOF30\Form\Field\Components::<protected>
 */
class ComponentsTest extends FOFTestCase
{
    /**
     * @group           Components
     * @group           Components__get
     * @covers          FOF30\Form\Field\Components::__get
     * @dataProvider    ComponentsDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Components', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Components
     * @group           ComponentsGetStatic
     * @covers          FOF30\Form\Field\Components::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMock('FOF30\Form\Field\Components', array('getFieldContents'));
        $field->expects($this->once())->method('getFieldContents')->with($this->equalTo(array('id' => 'foo')));

        $field->id = 'foo';

        $field->getStatic();
    }

    /**
     * @group           Components
     * @group           ComponentsGetRepeatable
     * @covers          FOF30\Form\Field\Components::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMock('FOF30\Form\Field\Components', array('getFieldContents'));
        $field->expects($this->once())->method('getFieldContents')->with($this->equalTo(array('class' => 'foo')));

        $field->id = 'foo';

        $field->getRepeatable();
    }

    /**
     * @group           Components
     * @group           ComponentsGetFieldContents
     * @covers          FOF30\Form\Field\Components::getFieldContents
     * @dataProvider    ComponentsDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Components::getFieldContents %s - Case: '.$check['case'];

        $components = array(
            array('value' => 'foo', 'text' => 'Foobar'),
            array('value' => 'dummy', 'text' => 'Dummy'),
            array('value' => 'test', 'text' => 'Test component'),
        );

        $field = $this->getMock('FOF30\Form\Field\Components', array('getOptions'));
        $field->method('getOptions')->willReturn($components);

        $field->class = 'foo-class';
        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Components
     * @group           ComponentsTranslate
     * @covers          FOF30\Form\Field\Components::translate
     * @dataProvider    ComponentsDataprovider::getTestTranslate
     */
    public function testTranslate($test, $check)
    {
        $msg = 'Components::translate %s - Case: '.$check['case'];
        $callstack = array();

        /** @var TestJoomlaPlatform $platform */
        $platform = static::$container->platform;
        $platform::$language = function() use (&$callstack){
            return new ClosureHelper(array(
                'getDefault' => function(){
                    return 'en-GB';
                },
                'load' => function($self) use (&$callstack){
                    $arguments = func_get_args();
                    array_shift($arguments);

                    $new_args = array();

                    // Let's replace any path with a fixed prefix
                    foreach($arguments as $arg)
                    {
                        if(is_string($arg))
                        {
                            $arg = str_replace(JPATH_ROOT, '__ROOT__', $arg);
                        }

                        $new_args[] = $arg;
                    }

                    $callstack[] = $new_args;

                    // Let's always return false, so all the calls to the "load" function are performed
                    return false;
                }
            ));
        };

        $item = (object) array(
            'name'           => 'FOFTEST',
            'element'        => 'com_foftest',
            'manifest_cache' => json_encode($test['manifest']),
        );

        $form  = new Form(static::$container, 'Foobar');
        $field = new Components();
        $field->setForm($form);

        $result = $field->translate($item, $test['type']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
        $this->assertEquals($check['language'], $callstack, sprintf($msg, 'Failed to correctly load the language'));
    }
}