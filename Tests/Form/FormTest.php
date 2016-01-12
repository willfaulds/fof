<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form;

use FOF30\Form\Form;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\Model\DataModelStub;
use FOF30\Tests\Stubs\View\DataView\RawStub;

require_once 'FormDataprovider.php';

/**
 * @covers      FOF30\Form\Form::<protected>
 * @covers      FOF30\Form\Form::<private>
 * @package     FOF30\Tests\Form
 */
class FormTest extends FOFTestCase
{
    protected function setUp()
    {
        parent::setUp();

        // Required by the Views
        $_SERVER['HTTP_HOST'] = 'www.example.com';
    }

    protected function tearDown()
    {
        parent::tearDown();

        // Required by the Views
        if(isset($_SERVER['HTTP_HOST']))
        {
            unset($_SERVER['HTTP_HOST']);
        }
    }

    /**
     * @group           Form
     * @group           Form__construct
     * @covers          FOF30\Form\Form::__construct
     */
    public function test__construct()
    {
        $form = new Form(static::$container, 'Foobar');

        $this->assertSame(static::$container, ReflectionHelper::getValue($form, 'container'), 'Failed to pass the container to the form');
    }

    /**
     * @group           Form
     * @group           FormGetAttribute
     * @covers          FOF30\Form\Form::getAttribute
     * @dataProvider    FormDataprovider::getTestGetAttribute
     */
    public function testGetAttribute($test, $check)
    {
        $msg = 'Form::getAttribute %s - Case: '.$check['case'];

        $form = new Form(static::$container, 'Foobar');
        $form->loadFile(JPATH_TESTS.'/_data/form/form.default.xml');

        $attribute = $form->getAttribute($test['attribute'], 'default');

        $this->assertEquals($check['result'], $attribute, sprintf($msg, 'Returned the wrong value'));
    }

    /**
     * @group           Form
     * @group           FormLoadCSSFiles
     * @covers          FOF30\Form\Form::loadCSSFiles
     * @dataProvider    FormDataprovider::getTestLoadCSSFiles
     */
    public function testLoadCSSFiles($test, $check)
    {
        $msg     = 'Form::loadCSSFiles %s - Case: '.$check['case'];
        $counter = array('css' => array(), 'less' => array());

        $fakeView = new ClosureHelper(array(
            'addCssFile' => function($self, $css) use(&$counter){
                $counter['css'][] = $css;
            },
            'addLess' => function($self, $less, $alt) use(&$counter){
                $counter['less'][] = array($less, $alt);
            }
        ));

        $form = $this->getMock('FOF30\Form\Form', array('getAttribute', 'getView'), array(static::$container, 'Foobar'));
        $form->method('getAttribute')->willReturnCallback(function($attribute) use($test){
            if(isset($test['mock']['attributes'][$attribute]))
            {
                return $test['mock']['attributes'][$attribute];
            }

            return null;
        });

        $form->method('getView')->willReturn($fakeView);

        $form->loadCSSFiles();

        $this->assertEquals($check['view'], $counter, sprintf($msg, 'Failed to correctly load the css or less files'));
    }

    /**
     * @group           Form
     * @group           FormLoadJSFiles
     * @covers          FOF30\Form\Form::loadJSFiles
     * @dataProvider    FormDataprovider::getTestLoadJSFiles
     */
    public function testLoadJSFiles($test, $check)
    {
        $msg     = 'Form::loadJSFiles %s - Case: '.$check['case'];
        $counter = array('js' => array());

        $fakeView = new ClosureHelper(array(
            'addJavascriptFile' => function($self, $js) use(&$counter){
                $counter['js'][] = $js;
            }
        ));

        $form = $this->getMock('FOF30\Form\Form', array('getAttribute', 'getView'), array(static::$container, 'Foobar'));
        $form->method('getAttribute')->willReturnCallback(function($attribute) use($test){
            if(isset($test['mock']['attributes'][$attribute]))
            {
                return $test['mock']['attributes'][$attribute];
            }

            return null;
        });

        $form->method('getView')->willReturn($fakeView);

        $form->loadJSFiles();

        $this->assertEquals($check['view'], $counter, sprintf($msg, 'Failed to correctly load the js files'));
    }

    /**
     * @group           Form
     * @group           FormGetData
     * @covers          FOF30\Form\Form::getData
     */
    public function testGetData()
    {
        $data = new \JRegistry();

        $form = new Form(static::$container, 'Foobar');

        ReflectionHelper::setValue($form, 'data', $data);

        $newData = $form->getData();

        $this->assertSame($data, $newData);
    }

    /**
     * @group           Form
     * @group           FormLoadFile
     * @covers          FOF30\Form\Form::loadFile
     * @dataProvider    FormDataprovider::getTestLoadFile
     */
    public function testLoadFile($test, $check)
    {
        $msg = 'Form::loadFile %s - Case: '.$check['case'];

        $form = new Form(static::$container, 'Foobar');

        $result = $form->loadFile($test['file']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Form
     * @group           FormSetModel
     * @covers          FOF30\Form\Form::setModel
     */
    public function testSetModel()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = new DataModelStub(static::$container, $config);
        $form  = new Form(static::$container, 'Foobar');

        $form->setModel($model);

        $this->assertSame($model, ReflectionHelper::getValue($form, 'model'));
    }

    /**
     * @group           Form
     * @group           FormGetModel
     * @covers          FOF30\Form\Form::getModel
     */
    public function testGetModel()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = new DataModelStub(static::$container, $config);
        $form  = new Form(static::$container, 'Foobar');

        ReflectionHelper::setValue($form, 'model', $model);

        $this->assertSame($model, $form->getModel());
    }

    /**
     * @group           Form
     * @group           FormSetView
     * @covers          FOF30\Form\Form::setView
     */
    public function testSetView()
    {
        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';

        $view = new RawStub(static::$container);
        $form = new Form(static::$container, 'Foobar');

        $form->setView($view);

        $this->assertSame($view, ReflectionHelper::getValue($form, 'view'));
    }

    /**
     * @group           Form
     * @group           FormGetView
     * @covers          FOF30\Form\Form::getView
     */
    public function testGetView()
    {
        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';

        $view = new RawStub(static::$container);
        $form = new Form(static::$container, 'Foobar');

        ReflectionHelper::setValue($form, 'view', $view);

        $this->assertSame($view, $form->getView());
    }

    /**
     * @group           Form
     * @group           FormGetHeaderset
     * @covers          FOF30\Form\Form::getHeaderset
     * @dataProvider    FormDataprovider::getTestGetHeaderset
     */
    public function testGetHeaderset($test, $check)
    {
        $msg     = 'Form::getHeaderset %s - Case: '.$check['case'];
        $checker = array();

        $groups = array();

        // phpUnit will try to serialize the data passed from the Dataprovider, sadly SimpleXML doesn't allow
        // to do that, so I have to load it directly here
        if($test['mock']['groups'])
        {
            $xml = simplexml_load_file(JPATH_TESTS.'/_data/form/form.default.xml');
            $groups = $xml->xpath('//header');
        }

        $form = $this->getMock('FOF30\Form\Form', array('findHeadersByGroup', 'loadHeader'), array(static::$container, 'Foobar'));
        $form->method('findHeadersByGroup')->willReturn($groups);
        $form->method('loadHeader')->willReturnCallback(function($element, $group) use (&$test, &$checker){
            $checker[] = $group;
            return array_shift($test['mock']['header']);
        });

        $fields = $form->getHeaderset();

        $this->assertEquals($check['header'], $checker, sprintf($msg, 'Failed to correctly build the header group'));
        $this->assertEquals($check['fields'], $fields, sprintf($msg, 'Returned the wrong value'));
    }

    /**
     * @group           Form
     * @group           FormGetHeader
     * @covers          FOF30\Form\Form::getHeader
     * @dataProvider    FormDataprovider::getTestGetHeader
     */
    public function testGetHeader($test, $check)
    {
        $msg = 'Form::getHeader %s - Case: '.$check['case'];

        $form = $this->getMock('FOF30\Form\Form', array('findHeader', 'loadHeader'), array(static::$container, 'Foobar'));
        $form->method('findHeader')->willReturn($test['mock']['find']);
        $form->method('loadHeader')->willReturn('mocked');

        if($test['load'])
        {
            $form->loadFile(JPATH_TESTS.'/_data/form/form.default.xml');
        }

        $result = $form->getHeader('foobar');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Form
     * @group           FormLoadClass
     * @covers          FOF30\Form\Form::loadClass
     * @dataProvider    FormDataprovider::getTestLoadClass
     */
    public function testLoadClass($test, $check)
    {
        $msg = 'Form::loadClass %s - Case: '.$check['case'];

        $form = new Form(static::$container, 'Foobar');

        $class = $form->loadClass($test['entity'], $test['type']);

        $class = trim($class, '\\');

        $this->assertEquals($check['result'], $class, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           Form
     * @group           FormSetContainer
     * @covers          FOF30\Form\Form::setContainer
     */
    public function testSetContainer()
    {
        $container = new TestContainer();
        $form = new Form(static::$container, 'Foobar');

        $form->setContainer($container);

        $this->assertSame($container, ReflectionHelper::getValue($form, 'container'));
    }

    /**
     * @group           Form
     * @group           FormGetContainer
     * @covers          FOF30\Form\Form::getContainer
     */
    public function testGetContainer()
    {
        $container = new TestContainer();
        $form = new Form(static::$container, 'Foobar');

        ReflectionHelper::setValue($form, 'container', $container);

        $this->assertSame($container, $form->getContainer());
    }
}