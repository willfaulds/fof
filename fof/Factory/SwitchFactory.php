<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory;

use FOF30\Controller\Controller;
use FOF30\Dispatcher\Dispatcher;
use FOF30\Model\Model;
use FOF30\Toolbar\Toolbar;
use FOF30\View\View;

defined('_JEXEC') or die;

/**
 * MVC object factory. This implements the advanced functionality, i.e. creating MVC objects only if the classes exist
 * in any component section (front-end, back-end). For example, if you're in the front-end and a Model class doesn't
 * exist there but does exist in the back-end then the back-end class will be returned.
 *
 * The Dispatcher and Toolbar will be created from default objects if specialised classes are not found in your application.
 */
class SwitchFactory extends BasicFactory implements FactoryInterface
{
	/**
	 * Create a new Controller object
	 *
	 * @param   string  $viewName  The name of the view we're getting a Controller for.
	 * @param   array   $config    Optional MVC configuration values for the Controller object.
	 *
	 * @return  Controller
	 */
	public function controller($viewName, array $config = array())
	{
		$controllerClass = $this->container->getNamespacePrefix() . 'Controller\\' . ucfirst($viewName);

		try
		{
			return $this->createController($controllerClass, $config);
		}
		catch (\RuntimeException $e)
		{
			$controllerClass = $this->container->getNamespacePrefix('inverse') . 'Controller\\' . ucfirst($viewName);

			return $this->createController($controllerClass, $config);
		}
	}

	/**
	 * Create a new Model object
	 *
	 * @param   string  $viewName  The name of the view we're getting a Model for.
	 * @param   array   $config    Optional MVC configuration values for the Model object.
	 *
	 * @return  Model
	 */
	public function model($viewName, array $config = array())
	{
		$modelClass = $this->container->getNamespacePrefix() . 'Model\\' . ucfirst($viewName);

		try
		{
			return $this->createModel($modelClass, $config);
		}
		catch (\RuntimeException $e)
		{
			$modelClass = $this->container->getNamespacePrefix('inverse') . 'Model\\' . ucfirst($viewName);

			return $this->createModel($modelClass, $config);
		}
	}

	/**
	 * Create a new View object
	 *
	 * @param   string  $viewName  The name of the view we're getting a View object for.
	 * @param   string  $viewType  The type of the View object. By default it's "html".
	 * @param   array   $config    Optional MVC configuration values for the View object.
	 *
	 * @return  View
	 */
	public function view($viewName, $viewType = 'html', array $config = array())
	{
		$viewClass = $this->container->getNamespacePrefix() . 'View\\' . ucfirst($viewName) . '\\' . ucfirst($viewType);

		try
		{
			return $this->createModel($viewClass, $config);
		}
		catch (\RuntimeException $e)
		{
			$viewClass = $this->container->getNamespacePrefix('inverse') . 'View\\' . ucfirst($viewName) . '\\' . ucfirst($viewType);

			return $this->createView($viewClass, $config);
		}
	}

	/**
	 * Creates a new Dispatcher
	 *
	 * @param   array  $config  The configuration values for the Dispatcher object
	 *
	 * @return  Dispatcher
	 */
	function dispatcher(array $config = array())
	{
		$dispatcherClass = $this->container->getNamespacePrefix() . 'Dispatcher\\Dispatcher';

		try
		{
			return $this->createDispatcher($dispatcherClass, $config);
		}
		catch (\RuntimeException $e)
		{
			// Not found. Let's go on.
		}

		$dispatcherClass = $this->container->getNamespacePrefix('inverse') . 'Dispatcher\\Dispatcher';

		try
		{
			return $this->createDispatcher($dispatcherClass, $config);
		}
		catch (\RuntimeException $e)
		{
			// Not found. Return the default Dispatcher
			return new Dispatcher($this->container, $config);
		}
	}

	/**
	 * Creates a new Toolbar
	 *
	 * @param   array  $config  The configuration values for the Toolbar object
	 *
	 * @return  Toolbar
	 */
	function toolbar(array $config = array())
	{
		$toolbarClass = $this->container->getNamespacePrefix() . 'Toolbar\\Toolbar';

		try
		{
			return $this->createToolbar($toolbarClass, $config);
		}
		catch (\RuntimeException $e)
		{
			// Not found. Let's go on.
		}

		$toolbarClass = $this->container->getNamespacePrefix('inverse') . 'Toolbar\\Toolbar';

		try
		{
			return $this->createToolbar($toolbarClass, $config);
		}
		catch (\RuntimeException $e)
		{
			// Not found. Return the default Dispatcher
			return new Toolbar($this->container, $config);
		}
	}
}