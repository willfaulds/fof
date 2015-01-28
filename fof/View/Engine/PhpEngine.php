<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\View\Engine;

defined('_JEXEC') or die;

/**
 * View engine for plain PHP template files (no translation).
 */
class PhpEngine implements EngineInterface
{
	/**
	 * Get the evaluated contents of the view template.
	 *
	 * @param   string  $path   The path to the view template
	 *
	 * @return  string  The evaluated content
	 */
	public function get($path)
	{
		return $path;
	}
}