<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\View;

use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestContainer;
use FOF30\Tests\Stubs\View\DataView\RawStub;

require_once __DIR__.'/RawDataprovider.php';

/**
 * @covers      FOF30\View\DataView\Raw::<protected>
 * @covers      FOF30\View\DataView\Raw::<private>
 * @package     FOF30\Tests\DataView\Raw
 */
class RawTest extends FOFTestCase
{
    /**
     * @covers          FOF30\View\DataView\Raw::__construct
     * @dataProvider    RawDataprovider::getTest__construct
     */
    public function test__construct($test, $check)
    {
        $msg = 'DataView::__construct %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$uriBase = 'www.example.com';
        $platform::$template = 'fake_test_template';
        $platform::$isCli = $test['mock']['isCli'];
        $platform::$authorise = function(){
            return false;
        };

        $view = new RawStub(static::$container);

        $permissions = ReflectionHelper::getValue($view, 'permissions');

        $this->assertEquals($check['permissions'], $permissions, sprintf($msg, 'Failed to set the permissions'));
    }
}

