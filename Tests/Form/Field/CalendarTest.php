<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Calendar;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestJoomlaPlatform;

require_once __DIR__.'/CalendarDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Calendar::<private>
 * @covers  FOF30\Form\Field\Calendar::<protected>
 */
class CalendarTest extends FOFTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        \JFactory::$application = $this->getMockCmsApp();
    }

    protected function tearDown()
    {
        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @group           Calendar
     * @group           Calendar__get
     * @covers          FOF30\Form\Field\Calendar::__get
     * @dataProvider    CalendarDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Calendar', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Calendar
     * @group           CalendarGetStatic
     * @covers          FOF30\Form\Field\Calendar::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMock('FOF30\Form\Field\Calendar', array('getCalendar'));
        $field->expects($this->once())->method('getCalendar')->with($this->equalTo('static'));

        $field->getStatic();
    }

    /**
     * @group           Calendar
     * @group           CalendarGetRepeatable
     * @covers          FOF30\Form\Field\Calendar::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMock('FOF30\Form\Field\Calendar', array('getCalendar'));
        $field->expects($this->once())->method('getCalendar')->with($this->equalTo('repeatable'));

        $field->getRepeatable();
    }

    /**
     * @group           Calendar
     * @group           CalendarGetCalendar
     * @covers          FOF30\Form\Field\Calendar::getCalendar
     * @dataProvider    CalendarDataprovider::getTestGetCalendar
     */
    public function testGetCalendar($test, $check)
    {
        $msg = 'Calendar::getCalendar %s - Case: '.$check['case'];

        /** @var TestJoomlaPlatform $platform */
        $platform = static::$container->platform;

        // Let's mock the config object, so I can have a fixed configuration
        $config = \JFactory::getConfig();
        $config->set('offset', 'Europe/Rome');

        // Let's mock the user timezone, too
        $platform::$user = new ClosureHelper(array(
            'getParam' => function($self, $key, $default = null) use ($test){
                if($key == 'timezone' && $test['userTimezone'])
                {
                    return $test['userTimezone'];
                }

                return $default;
            }
        ));

        $field = new Calendar();

        $xml = simplexml_load_string('<field type="Calendar" default="'.$test['field']['default'].'"/>');
        ReflectionHelper::setValue($field, 'element', $xml);

        $form = new Form(static::$container, 'Foobar');
        ReflectionHelper::setValue($field, 'form', $form);

        // HTML field attributes
        $field->maxlength = $test['attribs']['maxlength'];
        $field->readonly  = $test['attribs']['readonly'];
        $field->disabled  = $test['attribs']['disabled'];
        $field->required  = $test['attribs']['required'];
        $field->onchange  = $test['attribs']['onchange'];
        $field->class     = $test['attribs']['class'];
        $field->size      = $test['attribs']['size'];

        // Field class settings
        $field->value  = $test['field']['value'];
        $field->filter = $test['field']['filter'];
        $field->format = $test['field']['format'];
        $field->name = 'foobar-name';
        $field->id = 'foobar-id';

        $html = ReflectionHelper::invoke($field, 'getCalendar', $test['display']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}