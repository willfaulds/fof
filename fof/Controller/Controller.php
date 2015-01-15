<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Controller;

use FOF30\Container\Container;
use FOF30\Model\Model;
use FOF30\View\View;

defined('_JEXEC') or die;

/**
 * Class Controller
 *
 * A generic MVC controller implementation
 *
 * @package Awf\Mvc
 */
class Controller
{
	/**
	 * The name of the controller
	 *
	 * @var    array
	 */
	protected $name = null;

	/**
	 * The mapped task that was performed.
	 *
	 * @var    string
	 */
	protected $doTask;

	/**
	 * Redirect message.
	 *
	 * @var    string
	 */
	protected $message;

	/**
	 * Redirect message type.
	 *
	 * @var    string
	 */
	protected $messageType;

	/**
	 * Array of class methods
	 *
	 * @var    array
	 */
	protected $methods;

	/**
	 * The set of search directories for resources (views).
	 *
	 * @var    array
	 */
	protected $paths;

	/**
	 * URL for redirection.
	 *
	 * @var    string
	 */
	protected $redirect;

	/**
	 * Current or most recently performed task.
	 *
	 * @var    string
	 */
	protected $task;

	/**
	 * Array of class methods to call for a given task.
	 *
	 * @var    array
	 */
	protected $taskMap;

	/**
	 * Instance container.
	 *
	 * @var    Controller
	 */
	protected static $instance;

	/**
	 * The current view name; you can override it in the configuration
	 *
	 * @var string
	 */
	protected $view = '';

	/**
	 * The current layout; you can override it in the configuration
	 *
	 * @var string
	 */
	protected $layout = null;

	/**
	 * A cached copy of the class configuration parameter passed during initialisation
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * Overrides the name of the view's default model
	 *
	 * @var string
	 */
	protected $modelName = null;

	/**
	 * Overrides the name of the view's default view
	 *
	 * @var string
	 */
	protected $viewName = null;

	/**
	 * An array of Model instances known to this Controller
	 *
	 * @var   array[Model]
	 */
	protected $modelInstances = array();

	/**
	 * An array of View instances known to this Controller
	 *
	 * @var   array[View]
	 */
	protected $viewInstances = array();

	/**
	 * The container attached to this Controller
	 *
	 * @var Container
	 */
	protected $container = null;

	/**
	 * Set to true to enable CSRF protection on selected tasks. The possible
	 * values are:
	 * 0	Disabled; no token checks are performed
	 * 1	Enabled; token checks are always performed
	 * 2	Only on HTML requests and backend; token checks are always performed in the back-end and in the front-end only when format is 'html'
	 * 3	Only on back-end; token checks are performer only in the back-end
	 *
	 * @var    integer
	 */
	protected $csrfProtection = 2;

	/**
	 * Public constructor of the Controller class
	 *
	 * @param   Container $container The application container
	 *
	 * @return  Controller
	 */
	public function __construct(Container $container)
	{
		// Initialise
		$this->methods = array();
		$this->message = null;
		$this->messageType = 'message';
		$this->paths = array();
		$this->redirect = null;
		$this->taskMap = array();

		if (isset($container['mvc_config']))
		{
			$config = $container['mvc_config'];
		}
		else
		{
			$config = array();
		}

		// Get local copies of things included in the container
		$this->input = $container->input;

		$this->container = $container;

		// Determine the methods to exclude from the base class.
		$xMethods = get_class_methods('\\Awf\\Mvc\\Controller');

		// Get the public methods in this class using reflection.
		$r = new \ReflectionClass($this);
		$rMethods = $r->getMethods(\ReflectionMethod::IS_PUBLIC);

		foreach ($rMethods as $rMethod)
		{
			$mName = $rMethod->getName();

			// Add default display method if not explicitly declared.
			if (!in_array($mName, $xMethods) || $mName == 'display' || $mName == 'main')
			{
				$this->methods[] = strtolower($mName);

				// Auto register the methods as tasks.
				$this->taskMap[strtolower($mName)] = $mName;
			}
		}

		// Get the default values for the component and view names
		$this->view = $this->getName();
		$this->layout = $this->input->getCmd('layout', null);

		// If the default task is set, register it as such
		if (array_key_exists('default_task', $config))
		{
			$this->registerDefaultTask($config['default_task']);
		}
		else
		{
			$this->registerDefaultTask('main');
		}

		// Set the default view.
		if (array_key_exists('default_view', $config))
		{
			$this->default_view = $config['default_view'];
		}
		elseif (empty($this->default_view))
		{
			$this->default_view = $this->view;
		}

		// Cache the config
		$this->config = $config;

		// Set any model/view name overrides
		if (array_key_exists('viewName', $config))
		{
			$this->setViewName($config['viewName']);
		}

		if (array_key_exists('modelName', $config))
		{
			$this->setModelName($config['modelName']);
		}
	}

	/**
	 * Executes a given controller task. The onBefore<task> and onAfter<task>
	 * methods are called automatically if they exist.
	 *
	 * @param   string $task The task to execute, e.g. "browse"
	 *
	 * @return  null|bool  False on execution failure
	 *
	 * @throws  \Exception  When the task is not found
	 */
	public function execute($task)
	{
		$this->task = $task;

		$task = strtolower($task);

		if (!isset($this->taskMap[$task]) && !isset($this->taskMap['__default']))
		{
			throw new \Exception(\JText::sprintf('JLIB_APPLICATION_ERROR_TASK_NOT_FOUND', $task), 404);
		}

		$eventName = 'onBefore' . ucfirst($task);
		$result = $this->triggerEvent($eventName);

		if (!$result)
		{
			return false;
		}

		// Do not allow the display task to be directly called
		$task = strtolower($task);

		if (isset($this->taskMap[$task]))
		{
			$doTask = $this->taskMap[$task];
		}
		elseif (isset($this->taskMap['__default']))
		{
			$doTask = $this->taskMap['__default'];
		}
		else
		{
			$doTask = null;
		}

		// Record the actual task being fired
		$this->doTask = $doTask;

		$ret = $this->$doTask();

		$eventName = 'onAfter' . ucfirst($task);
		$result = $this->triggerEvent($eventName);

		if (!$result)
		{
			return false;
		}

		return $ret;
	}

	/**
	 * Default task. Assigns a model to the view and asks the view to render
	 * itself.
	 *
	 * @return  void
	 */
	public function display()
	{
		$view = $this->getView();
		$view->setTask($this->task);
		$view->setDoTask($this->doTask);

		// Get/Create the model
		if ($model = $this->getModel())
		{
			// Push the model into the view (as default)
			$view->setDefaultModel($model);
		}

		// Set the layout
		if (!is_null($this->layout))
		{
			$view->setLayout($this->layout);
		}

		// Display the view
		$view->display();
	}

	/**
	 * Alias to the display() task
	 *
	 * @codeCoverageIgnore
	 */
	public function main()
	{
		$this->display();
	}

	/**
	 * Returns a named Model object
	 *
	 * @param   string $name     The Model name. If null we'll use the modelName
	 *                           variable or, if it's empty, the same name as
	 *                           the Controller
	 * @param   array  $config   Configuration parameters to the Model. If skipped
	 *                           we will use $this->config
	 *
	 * @return  Model  The instance of the Model known to this Controller
	 */
	public function getModel($name = null, $config = array())
	{
		if (!empty($name))
		{
			$modelName = strtolower($name);
		}
		elseif (!empty($this->modelName))
		{
			$modelName = strtolower($this->modelName);
		}
		else
		{
			$modelName = strtolower($this->view);
		}

		if (!array_key_exists($modelName, $this->modelInstances))
		{
			if (empty($config))
			{
				$config = $this->config;
			}

			if (empty($name))
			{
				$config['modelTemporaryInstance'] = true;
			}
			else
			{
				// Other classes are loaded with persistent state disabled and their state/input blanked out
				$config['modelTemporaryInstance'] = false;
				$config['modelClearState'] = true;
				$config['modelClearInput'] = true;
			}

			$this->container['mvc_config'] = $config;

			// Get the model's class name
			$modelNameForClass = empty($name) ? $this->modelName : ucfirst($name);
			$className = $this->container->getNamespacePrefix() . 'Model\\' . $modelNameForClass;
			$this->modelInstances[$modelName] = new $className($this->container, $config);
		}

		return $this->modelInstances[$modelName];
	}

	/**
	 * Returns a named View object
	 *
	 * @param   string $name     The Model name. If null we'll use the modelName
	 *                           variable or, if it's empty, the same name as
	 *                           the Controller
	 * @param   array  $config   Configuration parameters to the Model. If skipped
	 *                           we will use $this->config
	 *
	 * @return  View  The instance of the Model known to this Controller
	 */
	public function getView($name = null, $config = array())
	{
		if (!empty($name))
		{
			$viewName = strtolower($name);
		}
		elseif (!empty($this->viewName))
		{
			$viewName = strtolower($this->viewName);
		}
		else
		{
			$viewName = strtolower($this->view);
		}

		if (!array_key_exists($viewName, $this->viewInstances))
		{
			if (empty($config))
			{
				$config = $this->config;
			}

			$viewType = $this->input->getCmd('format', 'html');

			$this->container['mvc_config'] = $config;

			// Get the model's class name
			$viewNameForClass = empty($name) ? $this->viewName : ucfirst($name);
			$className = $this->container->getNamespacePrefix() . 'View\\' . $viewNameForClass . '\\' . ucfirst($viewType);

			$this->viewInstances[$viewName] = new $className($this->container);
		}

		return $this->viewInstances[$viewName];
	}

	/**
	 * Set the name of the view to be used by this Controller
	 *
	 * @param   string $viewName The name of the view
	 *
	 * @return  void
	 */
	public function setViewName($viewName)
	{
		$this->viewName = $viewName;
	}

	/**
	 * Set the name of the model to be used by this Controller
	 *
	 * @param   string $modelName The name of the model
	 *
	 * @return  void
	 */
	public function setModelName($modelName)
	{
		$this->modelName = $modelName;
	}

	/**
	 * Pushes a named model to the Controller
	 *
	 * @param   string $modelName The name of the Model
	 * @param   Model  $model     The actual Model object to push
	 *
	 * @return  void
	 */
	public function setModel($modelName, Model &$model)
	{
		$this->modelInstances[$modelName] = $model;
	}

	/**
	 * Pushes a named view to the Controller
	 *
	 * @param   string $viewName The name of the View
	 * @param   View   $view     The actual View object to push
	 *
	 * @return  void
	 */
	public function setView($viewName, View &$view)
	{
		$this->viewInstances[$viewName] = $view;
	}

	/**
	 * Method to get the controller name
	 *
	 * The controller name is set by default parsed using the classname, or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @return  string  The name of the controller
	 *
	 * @throws  \Exception  If it's impossible to determine the name and it's not set
	 */
	public function getName()
	{
		if (empty($this->name))
		{
			$r = null;

			if (!preg_match('/(.*)\\\\Controller\\\\(.*)/i', get_class($this), $r))
			{
				throw new \Exception(\JText::_('LIB_FOF_CONTROLLER_ERR_GET_NAME'), 500);
			}

			$this->name = strtolower($r[2]);
		}

		return $this->name;
	}

	/**
	 * Get the last task that is being performed or was most recently performed.
	 *
	 * @return  string  The task that is being performed or was most recently performed.
	 */
	public function getTask()
	{
		return $this->task;
	}

	/**
	 * Gets the available tasks in the controller.
	 *
	 * @return  array  Array[i] of task names.
	 */
	public function getTasks()
	{
		return $this->methods;
	}

	/**
	 * Redirects the browser or returns false if no redirect is set.
	 *
	 * @return  boolean  False if no redirect exists.
	 */
	public function redirect()
	{
		if ($this->redirect)
		{
			$app = \JFactory::getApplication();
			$app->enqueueMessage($this->message, $this->messageType);
			$app->redirect($this->redirect);

			return true;
		}

		return false;
	}

	/**
	 * Register the default task to perform if a mapping is not found.
	 *
	 * @param   string $method The name of the method in the derived class to perform if a named task is not found.
	 *
	 * @return  Controller  This object to support chaining.
	 */
	public function registerDefaultTask($method)
	{
		$this->registerTask('__default', $method);

		return $this;
	}

	/**
	 * Register (map) a task to a method in the class.
	 *
	 * @param   string $task   The task.
	 * @param   string $method The name of the method in the derived class to perform for this task.
	 *
	 * @return  Controller  This object to support chaining.
	 */
	public function registerTask($task, $method)
	{
		if (in_array(strtolower($method), $this->methods))
		{
			$this->taskMap[strtolower($task)] = $method;
		}

		return $this;
	}

	/**
	 * Unregister (unmap) a task in the class.
	 *
	 * @param   string $task The task.
	 *
	 * @return  Controller  This object to support chaining.
	 */
	public function unregisterTask($task)
	{
		unset($this->taskMap[strtolower($task)]);

		return $this;
	}

	/**
	 * Sets the internal message that is passed with a redirect
	 *
	 * @param   string $text Message to display on redirect.
	 * @param   string $type Message type. Optional, defaults to 'message'.
	 *
	 * @return  string  Previous message
	 */
	public function setMessage($text, $type = 'message')
	{
		$previous = $this->message;
		$this->message = $text;
		$this->messageType = $type;

		return $previous;
	}

	/**
	 * Set a URL for browser redirection.
	 *
	 * @param   string $url  URL to redirect to.
	 * @param   string $msg  Message to display on redirect. Optional, defaults to value set internally by controller, if any.
	 * @param   string $type Message type. Optional, defaults to 'message' or the type set by a previous call to setMessage.
	 *
	 * @return  Controller   This object to support chaining.
	 */
	public function setRedirect($url, $msg = null, $type = null)
	{
		$this->redirect = $url;
		if ($msg !== null)
		{
			// Controller may have set this directly
			$this->message = $msg;
		}

		// Ensure the type is not overwritten by a previous call to setMessage.
		if (empty($type))
		{
			if (empty($this->messageType))
			{
				$this->messageType = 'info';
			}
		}
		// If the type is explicitly set, set it.
		else
		{
			$this->messageType = $type;
		}

		return $this;
	}

	/**
	 * Provides CSRF protection through the forced use of a secure token. If the token doesn't match the one in the
	 * session we return false.
	 *
	 * @return  bool
	 *
	 * @throws  \Exception
	 */
	protected function csrfProtection()
	{
		static $isCli = null, $isAdmin = null;

		$platform = $this->container->platform;

		if (is_null($isCli))
		{
			$isCli   = $platform->isCli();
			$isAdmin = $platform->isBackend();
		}

		switch ($this->csrfProtection)
		{
			// Never
			case 0:
				return true;
				break;

			// Always
			case 1:
				break;

			// Only back-end and HTML format
			case 2:
				if ($isCli)
				{
					return true;
				}
				elseif (!$isAdmin && ($this->input->get('format', 'html', 'cmd') != 'html'))
				{
					return true;
				}
				break;

			// Only back-end
			case 3:
				if (!$isAdmin)
				{
					return true;
				}
				break;
		}

		$hasToken = false;
		$session  = $this->container->session;

		// Joomla! 2.5+ (Platform 12.1+) method
		if (method_exists($session, 'getToken'))
		{
			$token    = $session->getToken();
			$hasToken = $this->input->get($token, false, 'none') == 1;

			if (!$hasToken)
			{
				$hasToken = $this->input->get('_token', null, 'none') == $token;
			}
		}

		// Joomla! 2.5+ formToken method
		if (!$hasToken)
		{
			if (method_exists($session, 'getFormToken'))
			{
				$token    = $session->getFormToken();
				$hasToken = $this->input->get($token, false, 'none') == 1;

				if (!$hasToken)
				{
					$hasToken = $this->input->get('_token', null, 'none') == $token;
				}
			}
		}

		if (!$hasToken)
		{
			$platform->raiseError(403, \JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));

			return false;
		}

		return true;
	}

	/**
	 * Triggers an object-specific event. The event runs both locally –if a suitable method exists– and through the
	 * Joomla! plugin system. A true/false return value is expected. The first false return cancels the event.
	 *
	 * EXAMPLE
	 * Component: com_foobar, Object name: item, Event: onBeforeSomething, Arguments: array(123, 456)
	 * The event calls:
	 * 1. $this->onBeforeSomething(123, 456)
	 * 2. Joomla! plugin event onComFoobarControllerItemBeforeSomething($this, 123, 456)
	 *
	 * @param   string  $event      The name of the event, typically named onPredicateVerb e.g. onBeforeKick
	 * @param   array   $arguments  The arguments to pass to the event handlers
	 *
	 * @return  bool
	 */
	protected function triggerEvent($event, array $arguments = array())
	{
		$result = false;

		// If there is an object method for this event, call it
		if (method_exists($this, $event))
		{
			switch (count($arguments))
			{
				case 0:
					$result = $this->{$event}();
					break;
				case 1:
					$result = $this->{$event}($arguments[0]);
					break;
				case 2:
					$result = $this->{$event}($arguments[0], $arguments[1]);
					break;
				case 3:
					$result = $this->{$event}($arguments[0], $arguments[1], $arguments[2]);
					break;
				case 4:
					$result = $this->{$event}($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
					break;
				case 5:
					$result = $this->{$event}($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
					break;
				default:
					$result = call_user_func_array(array($this, $event), $arguments);
					break;
			}
		}

		if ($result === false)
		{
			return false;
		}

		// All other event handlers live outside this object, therefore they need to be passed a reference to this
		// objects as the first argument.
		array_unshift($arguments, &$this);

		// If we have an "on" prefix for the event (e.g. onFooBar) remove it and stash it for later.
		$prefix = '';

		if (substr($event, 0, 2) == 'on')
		{
			$prefix = 'on';
			$event = substr($event, 2);
		}

		// Get the component/model prefix for the event
		$prefix .= 'Com' . ucfirst($this->container->bareComponentName) . 'Controller';
		$prefix .= ucfirst($this->getName());

		// The event name will be something like onComFoobarItemsBeforeSomething
		$event = $prefix . $event;

		// Call the Joomla! plugins
		$results = $this->container->platform->runPlugins($event, $arguments);

		if (!empty($results))
		{
			foreach ($results as $result)
			{
				if ($result === false)
				{
					return false;
				}
			}
		}

		return true;
	}
}