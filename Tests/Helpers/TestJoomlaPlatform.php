<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers;

use FOF30\Platform\Joomla\Platform as PlatformJoomla;

/**
 * A specialised Joomla! platform abstraction class which can lie about running under CLI, frontend or backend.
 */
class TestJoomlaPlatform extends PlatformJoomla
{
	/**
	 * @var		boolean   Should this platform instance report running under CLI mode?
	 */
	public static $isCli = false;

	/**
	 * @var		boolean   Should this platform instance report running under Joomla! backend?
	 */
	public static $isAdmin = false;

	/**
	 * Main function to detect if we're running in a CLI environment and we're admin. This method is designed to lie.
	 *
	 * @return  array  isCLI and isAdmin. It's not an associative array, so we can use list.
	 */
	protected function isCliAdmin()
	{
		return array(self::$isCli, self::$isAdmin);
	}
}