<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\View\Engine;

defined('_JEXEC') or die;

interface EngineInterface
{
	/**
	 * Get the include path for a parsed view template
	 *
	 * @param   string  $path   The path to the view template
	 *
	 * @return  string  The evaluated content
	 */
	public function get($path);
}