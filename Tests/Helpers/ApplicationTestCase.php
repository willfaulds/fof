<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers;

use FOF30\Container\Container;

/**
 * Base class for tests requiring a container and/or an application to be set up
 *
 * @package Awf\Tests\Helpers
 */
abstract class ApplicationTestCase extends \PHPUnit_Framework_TestCase
{
	/** @var Container A container suitable for unit testing */
	public static $container = null;

	public function __construct($name = null, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		static::$container = new Container(array(
			'componentName'	=> 'com_fakeapp',
		));
	}
}