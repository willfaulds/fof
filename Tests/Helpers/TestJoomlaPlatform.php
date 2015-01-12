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
	/** @var bool Should this platform instance report running under CLI mode? */
	public static $isCli = false;

	/** @var bool Should this platform instance report running under Joomla! backend? */
	public static $isAdmin = false;

	/** @var string|null The template name reported by this class */
	public static $template = null;

	/** @var array|null The template suffixes to return e.g. ['.j32', '.j3'] and so on */
	public static $templateSuffixes = null;

	/** @var array|null The platform base directories to return */
	public static $baseDirs = null;

	/**
	 * Main function to detect if we're running in a CLI environment and we're admin. This method is designed to lie.
	 *
	 * @return  array  isCLI and isAdmin. It's not an associative array, so we can use list.
	 */
	protected function isCliAdmin()
	{
		return array(self::$isCli, self::$isAdmin);
	}

	/**
	 * Returns the application's template name
	 *
	 * @param   boolean|array  $params  An optional associative array of configuration settings
	 *
	 * @return  string  The template name. System is the fallback.
	 */
	public function getTemplate($params = false)
	{
		if (is_null(self::$template))
		{
			return parent::getTemplate($params);
		}

		return self::$template;
	}

	public function getTemplateSuffixes()
	{
		if (is_null(self::$templateSuffixes))
		{
			return parent::getTemplateSuffixes();
		}

		return self::$templateSuffixes;
	}

	public function getPlatformBaseDirs()
	{
		if (is_null(self::$baseDirs))
		{
			return parent::getPlatformBaseDirs();
		}

		return self::$baseDirs;
	}

	/**
	 * Sync the isCli / isAdmin with the real values
	 */
	public function resetIsCliAdmin()
	{
		list(self::$isCli, self::$isAdmin) = parent::isCliAdmin();
	}
}