<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Actions;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once __DIR__.'/ActionsDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Actions::<private>
 * @covers  FOF30\Form\Field\Actions::<protected>
 */
class ActionsTest extends FOFTestCase
{
    /**
     * @group           AccessLevel
     * @group           Actions__get
     * @covers          FOF30\Form\Field\Actions::__get
     * @dataProvider    ActionsDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Actions', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           AccessLevel
     * @group           ActionsGetStatic
     * @covers          FOF30\Form\Field\Actions::getStatic
     */
    public function testGetStatic()
    {
        $this->setExpectedException('FOF30\Form\Exception\GetStaticNotAllowed');

        $field = new Actions();

        $field->getStatic();
    }

    /**
     * @group           AccessLevel
     * @group           ActionsGetRepeatable
     * @covers          FOF30\Form\Field\Actions::getRepeatable
     * @dataProvider    ActionsDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'Actions::getRepeatable %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['id'],
            'tableName'   => $test['table']
        );

        $model = new DataModelStub(static::$container, $config);
        $model->setFieldValue('enabled', $test['enabled']);

        $fakeField = new ClosureHelper(array(
            'getRepeatable' => function(){
                return '__FAKE_PUBLISH__';
            }
        ));

        $field = $this->getMock('FOF30\Form\Field\Actions', array('getPublishedField'));
        $field->expects($this->exactly($check['publishField']))->method('getPublishedField')->willReturn($fakeField);

        $data  = '<field type="Actions"';
        $data .= ' show_published="'.($test['published'] ? 1 : 0).'"';
        $data .= ' show_unpublished="'.($test['unpublished'] ? 1 : 0).'"';
        $data .= ' show_archived="'.($test['archived'] ? 1 : 0).'"';
        $data .= ' show_trash="'.($test['trash'] ? 1 : 0).'"';
        $data .= ' show_all="'.($test['all'] ? 1 : 0).'"';

        $data .= ' />';

        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);

        ReflectionHelper::setValue($field, 'element', $xml);
        ReflectionHelper::setValue($field, 'item', $model);

        $html = $field->getRepeatable();

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           AccessLevel
     * @group           ActionsGetRepeatable
     * @covers          FOF30\Form\Field\Actions::getRepeatable
     */
    public function testGetRepeatableException()
    {
        $this->setExpectedException('FOF30\Form\Exception\DataModelRequired');

        $field = new Actions();

        $field->getRepeatable();
    }
}