<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory;

use FOF30\Container\Container;
use FOF30\Controller\Controller;
use FOF30\Dispatcher\Dispatcher;
use FOF30\Model\Model;
use FOF30\Toolbar\Toolbar;
use FOF30\View\View;

defined('_JEXEC') or die;

/**
 * MVC object factory. This implements the basic functionality, i.e. creating MVC objects only if the classes exist in
 * the same component section (front-end, back-end) you are currently running in. The Dispatcher and Toolbar will be
 * created from default objects if specialised classes are not found in your application.
 */
class BasicFactory implements FactoryInterface
{
	/** @var  Container  The container we belong to */
	protected $container = null;

	/**
	 * Public constructor for the factory object
	 *
	 * @param  \FOF30\Container\Container $container  The container we belong to
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

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

		return $this->createController($controllerClass, $config);
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

		return $this->createModel($modelClass, $config);
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

		return $this->createView($viewClass, $config);
	}

	/**
	 * Creates a new Dispatcher
	 *
	 * @return  Dispatcher
	 */
	function dispatcher()
	{
		$dispatcherClass = $this->container->getNamespacePrefix() . 'Dispatcher\\Dispatcher';

		try
		{
			return $this->createDispatcher($dispatcherClass);
		}
		catch (\RuntimeException $e)
		{
			// Not found. Return the default Dispatcher
			return new Dispatcher($this->container);
		}
	}

	/**
	 * Creates a new Toolbar
	 *
	 * @return  Toolbar
	 */
	function toolbar()
	{
		$toolbarClass = $this->container->getNamespacePrefix() . 'Toolbar\\Toolbar';

		try
		{
			return $this->createToolbar($toolbarClass);
		}
		catch (\RuntimeException $e)
		{
			// Not found. Return the default Dispatcher
			return new Toolbar($this->container);
		}
	}

	/**
	 * Creates a Controller object
	 *
	 * @param   string  $controllerClass  The fully qualified class name for the Controller
	 * @param   array   $config           Optional MVC configuration values for the Controller object.
	 *
	 * @return  Controller
	 *
	 * @throws  \RuntimeException  If the $controllerClass does not exist
	 */
	protected function createController($controllerClass, array $config = array())
	{
		if (!class_exists($controllerClass))
		{
			throw new \RuntimeException(\JText::_('LIB_FOF_CONTROLLER_ERR_NOT_FOUND'), 500);
		}

		return new $controllerClass($this->container, $config);
	}

	/**
	 * Creates a Model object
	 *
	 * @param   string  $modelClass  The fully qualified class name for the Model
	 * @param   array   $config      Optional MVC configuration values for the Model object.
	 *
	 * @return  Model
	 *
	 * @throws  \RuntimeException  If the $modelClass does not exist
	 */
	protected function createModel($modelClass, array $config = array())
	{
		if (!class_exists($modelClass))
		{
			throw new \RuntimeException(\JText::_('LIB_FOF_MODEL_ERR_NOT_FOUND'), 500);
		}

		return new $modelClass($this->container, $config);
	}

	/**
	 * Creates a View object
	 *
	 * @param   string  $viewClass  The fully qualified class name for the View
	 * @param   array   $config     Optional MVC configuration values for the View object.
	 *
	 * @return  Model
	 *
	 * @throws  \RuntimeException  If the $viewClass does not exist
	 */
	protected function createView($viewClass, array $config = array())
	{
		if (!class_exists($viewClass))
		{
			throw new \RuntimeException(\JText::_('LIB_FOF_VIEW_ERR_NOT_FOUND'), 500);
		}

		return new $viewClass($this->container, $config);
	}

	/**
	 * Creates a Toolbar object
	 *
	 * @param   string  $toolbarClass  The fully qualified class name for the Toolbar
	 *
	 * @return  Toolbar
	 *
	 * @throws  \RuntimeException  If the $toolbarClass does not exist
	 */
	protected function createToolbar($toolbarClass)
	{
		if (!class_exists($toolbarClass))
		{
			throw new \RuntimeException(\JText::_('LIB_FOF_TOOLBAR_ERR_NOT_FOUND'), 500);
		}

		return new $toolbarClass($this->container);
	}

	/**
	 * Creates a Dispatcher object
	 *
	 * @param   string  $dispatcherClass  The fully qualified class name for the Dispatcher
	 *
	 * @return  Dispatcher
	 *
	 * @throws  \RuntimeException  If the $dispatcherClass does not exist
	 */
	protected function createDispatcher($dispatcherClass)
	{
		if (!class_exists($dispatcherClass))
		{
			throw new \RuntimeException(\JText::_('LIB_FOF_DISPATCHER_ERR_NOT_FOUND'), 500);
		}

		return new $dispatcherClass($this->container);
	}
}