<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\View;

use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * Locates the appropriate template file for a view
 */
class ViewTemplateFinder
{
	/** @var  View  The view we are attached to */
	protected $view;

	protected $container;

	/**
	 * @param   View  $view  The view we are attached to
	 */
	function __construct(View $view)
	{
		$this->view = $view;
		$this->container = $view->getContainer();
	}


}