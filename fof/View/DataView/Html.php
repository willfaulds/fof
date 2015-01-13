<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\View\DataView;

defined('_JEXEC') or die;

class Html extends Raw
{
	/**
	 * Executes before rendering the page for the Add task.
	 *
	 * @return  boolean  Return true to allow rendering of the page
	 */
	protected function onBeforeAdd()
	{
		// Hide main menu
		\JFactory::getApplication()->input->set('hidemainmenu', true);

		return true;
	}

	/**
	 * Executes before rendering the page for the Edit task.
	 *
	 * @return  boolean  Return true to allow rendering of the page
	 */
	protected function onBeforeEdit()
	{
		// Hide main menu
		\JFactory::getApplication()->input->set('hidemainmenu', true);

		return true;
	}

	/**
	 * Executes before rendering the page for the Read task.
	 *
	 * @return  boolean  Return true to allow rendering of the page
	 */
	protected function onBeforeRead()
	{
		return true;
	}
} 