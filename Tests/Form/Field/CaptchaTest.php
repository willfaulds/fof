<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/CaptchaDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Captcha::<private>
 * @covers  FOF30\Form\Field\Captcha::<protected>
 */
class CaptchaTest extends FOFTestCase
{
    /**
     * @group           Captcha
     * @group           Captcha__get
     * @covers          FOF30\Form\Field\Captcha::__get
     * @dataProvider    CaptchaDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Captcha', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Captcha
     * @group           CaptchaGetStatic
     * @covers          FOF30\Form\Field\Captcha::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMock('FOF30\Form\Field\Captcha', array('getInput'));
        $field->expects($this->once())->method('getInput');

        $field->getStatic();
    }

    /**
     * @group           Captcha
     * @group           CaptchaGetRepeatable
     * @covers          FOF30\Form\Field\Captcha::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMock('FOF30\Form\Field\Captcha', array('getInput'));
        $field->expects($this->once())->method('getInput');

        $field->getRepeatable();
    }
}