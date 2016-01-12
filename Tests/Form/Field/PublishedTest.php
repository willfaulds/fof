<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Published;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once __DIR__.'/PublishedDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Published::<private>
 * @covers  FOF30\Form\Field\Published::<protected>
 */
class PublishedTest extends FOFTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        \JFactory::$application = $this->getMockCmsApp();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->restoreFactoryState();
    }

    /**
     * @group           Published
     * @group           Published__get
     * @covers          FOF30\Form\Field\Published::__get
     * @dataProvider    PublishedDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Published', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Published
     * @group           PublishedGetStatic
     * @covers          FOF30\Form\Field\Published::getStatic
     * @dataProvider    PublishedDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $msg = 'Published::getStatic %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\Published', array('getOptions'));
        $field->method('getOptions')->willReturn(array(
            array('value' => 1, 'text' => 'Published'),
            array('value' => 0, 'text' => 'Unpublished'),
        ));

        $field->class = 'foo-class';
        $field->id    = 'foo-id';
        $field->value = $test['value'];

        $result = $field->getStatic();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Published
     * @group           PublishedGetRepeatable
     * @covers          FOF30\Form\Field\Published::getRepeatable
     * @dataProvider    PublishedDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'Published::getRepeatable %s - Case: '.$check['case'];

        $field = new Published();

        $data = '<field type="Published" ';

        foreach($test['attribs'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $config = array('tableName' => '#__foftest_foobars', 'idFieldName' => 'foftest_foobar_id');
        $model = new DataModelStub(static::$container, $config);

        $field->value = 5;
        $field->rowid = 2;
        $field->item  = $model;

        $result = $field->getRepeatable();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Published
     * @group           PublishedGetRepeatable
     * @covers          FOF30\Form\Field\Published::getRepeatable
     */
    public function testGetRepeatableException()
    {
        $this->setExpectedException('FOF30\Form\Exception\DataModelRequired');

        $field = new Published();

        $field->getRepeatable();
    }
}