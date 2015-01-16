<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Dispatcher;

use FOF30\Container\Container;

class Dispatcher
{
	/** @var  Container  The container we belong to */
	protected $container = null;

	function __construct(Container $container)
	{
		$this->container = $container;
	}
}