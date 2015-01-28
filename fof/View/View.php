<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\View;

use FOF30\Container\Container;
use FOF30\Model\Model;
use FOF30\View\Exception\AccessForbidden;
use FOF30\View\Exception\CannotGetName;
use FOF30\View\Exception\ModelNotFound;

defined('_JEXEC') or die;

/**
 * Class View
 *
 * A generic MVC view implementation
 *
 * @property-read  \FOF30\Input\Input  $input  The input object (magic __get returns the Input from the Container)
 */
class View
{
	/**
	 * The name of the view
	 *
	 * @var    array
	 */
	protected $name = null;

	/**
	 * Registered models
	 *
	 * @var    array
	 */
	protected $modelInstances = array();

	/**
	 * The default model
	 *
	 * @var    string
	 */
	protected $defaultModel = null;

	/**
	 * Layout name
	 *
	 * @var    string
	 */
	protected $layout = 'default';

	/**
	 * Layout template
	 *
	 * @var    string
	 */
	protected $layoutTemplate = '_';

	/**
	 * The set of search directories for view templates
	 *
	 * @var   array
	 */
	protected $templatePaths = array();

	/**
	 * The name of the default template source file.
	 *
	 * @var   string
	 */
	protected $template = null;

	/**
	 * The output of the template script.
	 *
	 * @var   string
	 */
	protected $output = null;

	/**
	 * A cached copy of the configuration
	 *
	 * @var   array
	 */
	protected $config = array();

	/**
	 * The container attached to this view
	 *
	 * @var   Container
	 */
	protected $container;

	/**
	 * The object used to locate view templates in the filesystem
	 *
	 * @var   ViewTemplateFinder
	 */
	private $viewFinder = null;

	/**
	 * Used when loading template files to avoid variable scope issues
	 *
	 * @var   null
	 */
	private $_tempFilePath = null;

	/**
	 * Current or most recently performed task.
	 * Currently public, it should be reduced to protected in the future
	 *
	 * @var  string
	 */
	public $task;

	/**
	 * The mapped task that was performed.
	 * Currently public, it should be reduced to protected in the future
	 *
	 * @var  string
	 */
	public $doTask;

	/**
	 * Should I run the pre-render step?
	 *
	 * @var    boolean
	 */
	protected $doPreRender = true;

	/**
	 * Should I run the post-render step?
	 *
	 * @var    boolean
	 */
	protected $doPostRender = true;

	/**
	 * Constructor.
	 *
	 * The $config array can contain the following overrides:
	 * name           string  The name of the view (defaults to the view class name)
	 * template_path  string  The path of the layout directory
	 * layout         string  The layout for displaying the view
	 * viewFinder     ViewTemplateFinder  The object used to locate view templates in the filesystem
	 *
	 * @param   Container $container  The container we belong to
	 * @param   array     $config     The configuration overrides for the view
	 *
	 * @return  View
	 */
	public function __construct(Container $container, array $config = array())
	{
		$this->container = $container;

		$this->config = $config;

		// Get the view name
		if (isset($this->config['name']))
		{
			$this->name = $this->config['name'];
		}

		$this->name = $this->getName();

		// Set the default template search path
		if (array_key_exists('template_path', $this->config))
		{
			// User-defined dirs
			$this->setTemplatePath($this->config['template_path']);
		}
		else
		{
			$this->setTemplatePath($this->container->thisPath . '/View/' . ucfirst($this->name) . '/tmpl');
		}

		// Set the layout
		if (array_key_exists('layout', $this->config))
		{
			$this->setLayout($this->config['layout']);
		}

		$templatePath = JPATH_THEMES;
		$fallback = $templatePath . '/' . $this->container->platform->getTemplate() . '/html/' . $this->container->componentName . '/' . $this->name;
		$this->addTemplatePath($fallback);

		// Get extra directories through event dispatchers
		$extraPathsResults = $this->container->platform->runPlugins('onGetViewTemplatePaths', array(
			$this->container->componentName,
			$this->getName()
		));

		if (is_array($extraPathsResults) && !empty($extraPathsResults))
		{
			foreach ($extraPathsResults as $somePaths)
			{
				if (!empty($somePaths))
				{
					foreach ($somePaths as $aPath)
					{
						$this->addTemplatePath($aPath);
					}
				}
			}
		}

		// Set the ViewFinder
		$this->viewFinder = $this->container->factory->viewFinder($this);

		if (!empty($config['viewFinder']) && is_object($config['viewFinder']) && ($config['viewFinder'] instanceof ViewTemplateFinder))
		{
			$this->viewFinder = $config['viewFinder'];
		}

		$this->baseurl = $this->container->platform->URIbase();
	}

	/**
	 * Magic get method. Handles magic properties:
	 * $this->input  mapped to $this->container->input
	 *
	 * @param   string  $name  The property to fetch
	 *
	 * @return  mixed|null
	 */
	function __get($name)
	{
		// Handle $this->input
		if ($name == 'input')
		{
			return $this->container->input;
		}

		// Property not found; raise error
		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE);

		return null;
	}

	/**
	 * Sets an entire array of search paths for templates or resources.
	 *
	 * @param   mixed $path The new search path, or an array of search paths.  If null or false, resets to the current
	 *                      directory only.
	 *
	 * @return  void
	 */
	protected function setTemplatePath($path)
	{
		// Clear out the prior search dirs
		$this->templatePaths = array();

		// Actually add the user-specified directories
		$this->addTemplatePath($path);

		// Set the alternative template search dir
		$templatePath = JPATH_THEMES;
		$fallback = $templatePath . '/' . $this->container->platform->getTemplate() . '/html/' . $this->container->componentName . '/' . $this->name;
		$this->addTemplatePath($fallback);

		// Get extra directories through event dispatchers
		$extraPathsResults = $this->container->platform->runPlugins('onGetViewTemplatePaths', array(
			$this->container->componentName,
			$this->getName()
		));

		if (is_array($extraPathsResults) && !empty($extraPathsResults))
		{
			foreach ($extraPathsResults as $somePaths)
			{
				if (!empty($somePaths))
				{
					foreach ($somePaths as $aPath)
					{
						$this->addTemplatePath($aPath);
					}
				}
			}
		}
	}

	/**
	 * Adds to the search path for templates and resources.
	 *
	 * @param   mixed $path The directory or stream, or an array of either, to search.
	 *
	 * @return  void
	 */
	protected function addTemplatePath($path)
	{
		// Just force to array
		settype($path, 'array');

		// Loop through the path directories
		foreach ($path as $dir)
		{
			// No surrounding spaces allowed!
			$dir = trim($dir);

			// Add trailing separators as needed
			if (substr($dir, -1) != DIRECTORY_SEPARATOR)
			{
				// Directory
				$dir .= DIRECTORY_SEPARATOR;
			}

			// Add to the top of the search dirs
			array_unshift($this->templatePaths, $dir);
		}
	}

	/**
	 * Method to get the view name
	 *
	 * The model name by default parsed using the classname, or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @return  string  The name of the model
	 *
	 * @throws  \Exception
	 */
	public function getName()
	{
		if (empty($this->name))
		{
			$r = null;

			if (!preg_match('/(.*)\\\\View\\\\(.*)\\\\(.*)/i', get_class($this), $r))
			{
				throw new CannotGetName;
			}

			$this->name = strtolower($r[2]);
		}

		return $this->name;
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed $var The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var)
	{
		return htmlspecialchars($var, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Method to get data from a registered model or a property of the view
	 *
	 * @param   string $property  The name of the method to call on the Model or the property to get
	 * @param   string $default   The default value [optional]
	 * @param   string $modelName The name of the Model to reference [optional]
	 *
	 * @return  mixed  The return value of the method
	 */
	public function get($property, $default = null, $modelName = null)
	{
		// If $model is null we use the default model
		if (is_null($modelName))
		{
			$model = $this->defaultModel;
		}
		else
		{
			$model = strtolower($modelName);
		}

		// First check to make sure the model requested exists
		if (isset($this->modelInstances[$model]))
		{
			// Model exists, let's build the method name
			$method = 'get' . ucfirst($property);

			// Does the method exist?
			if (method_exists($this->modelInstances[$model], $method))
			{
				// The method exists, let's call it and return what we get
				$result = $this->modelInstances[$model]->$method();

				return $result;
			}
			else
			{
				$result = $this->modelInstances[$model]->$property();

				if (is_null($result))
				{
					return $default;
				}

				return $result;
			}
		}
		// If the model doesn't exist, try to fetch a View property
		else
		{
			if (@isset($this->$property))
			{
				return $this->$property;
			}
			else
			{
				return $default;
			}
		}
	}

	/**
	 * Returns a named Model object
	 *
	 * @param   string $name     The Model name. If null we'll use the modelName
	 *                           variable or, if it's empty, the same name as
	 *                           the Controller
	 *
	 * @return  Model  The instance of the Model known to this Controller
	 */
	public function getModel($name = null)
	{
		if (!empty($name))
		{
			$modelName = strtolower($name);
		}
		elseif (!empty($this->defaultModel))
		{
			$modelName = strtolower($this->defaultModel);
		}
		else
		{
			$modelName = strtolower($this->name);
		}

		if (!array_key_exists($modelName, $this->modelInstances))
		{
			throw new ModelNotFound($modelName, $this->name);
		}

		return $this->modelInstances[$modelName];
	}

	/**
	 * Pushes the default Model to the View
	 *
	 * @param   Model $model The model to push
	 */
	public function setDefaultModel(Model &$model)
	{
		$name = $model->getName();

		$this->setDefaultModelName($name);
		$this->setModel($this->defaultModel, $model);
	}

	/**
	 * Set the name of the Model to be used by this View
	 *
	 * @param   string $modelName The name of the Model
	 *
	 * @return  void
	 */
	public function setDefaultModelName($modelName)
	{
		$this->defaultModel = $modelName;
	}

	/**
	 * Pushes a named model to the View
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
	 * Overrides the default method to execute and display a template script.
	 * Instead of loadTemplate is uses loadAnyTemplate.
	 *
	 * @param   string $tpl The name of the template file to parse
	 *
	 * @return  boolean  True on success
	 *
	 * @throws  \Exception  When the layout file is not found
	 */
	public function display($tpl = null)
	{
		$eventName = 'onBefore' . ucfirst($this->doTask);
		$result = $this->triggerEvent($eventName);

		if (!$result)
		{
			throw new AccessForbidden;
		}

		$templateResult = $this->loadTemplate($tpl);

		$eventName = 'onAfter' . ucfirst($this->doTask);
		$result = $this->triggerEvent($eventName);

		if (!$result)
		{
			throw new AccessForbidden;
		}

		if (is_object($templateResult) && ($templateResult instanceof \Exception))
		{
			throw $templateResult;
		}
		else
		{
			if ($this->doPreRender)
			{
				$this->preRender();
			}

			echo $templateResult;

			if ($this->doPostRender)
			{
				$this->postRender();
			}

			return true;
		}
	}

	/**
	 * Get the layout.
	 *
	 * @return  string  The layout name
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Sets the layout name to use
	 *
	 * @param   string $layout The layout name or a string in format <template>:<layout file>
	 *
	 * @return  string  Previous value.
	 */
	public function setLayout($layout)
	{
		$previous = $this->layout;
		if (strpos($layout, ':') === false)
		{
			$this->layout = $layout;
		}
		else
		{
			// Convert parameter to array based on :
			$temp = explode(':', $layout);
			$this->layout = $temp[1];

			// Set layout template
			$this->layoutTemplate = $temp[0];
		}

		return $previous;
	}

	/**
	 * Our function uses loadAnyTemplate to provide smarter view template loading.
	 *
	 * @param   string  $tpl    The name of the template file to parse
	 * @param   boolean $strict Should we use strict naming, i.e. force a non-empty $tpl?
	 *
	 * @return  mixed  A string if successful, otherwise an Exception
	 */
	public function loadTemplate($tpl = null, $strict = false)
	{
		$result = '';

		$uris = $this->viewFinder->getViewTemplateUris(array(
			'component' => $this->container->componentName,
			'view'      => $this->getName(),
			'layout'    => $this->getLayout(),
			'tpl'       => $tpl,
			'strictTpl' => $strict
		));

		foreach ($uris as $uri)
		{
			$result = $this->loadAnyTemplate($uri);

			if (!($result instanceof \Exception))
			{
				break;
			}
		}

		if ($result instanceof \Exception)
		{
			$this->container->platform->raiseError($result->getCode(), $result->getMessage());
		}

		return $result;
	}

	/**
	 * Loads a template given any path. The path is in the format componentPart://componentName/viewName/layoutName,
	 * for example
	 * site:com_example/items/default
	 * admin:com_example/items/default_subtemplate
	 * auto:com_example/things/chair
	 * any:com_example/invoices/printpreview
	 *
	 * @param   string $uri        The template path
	 * @param   array  $forceParams A hash array of variables to be extracted in the local scope of the template file
	 *
	 * @return  string  The output of the template
	 *
	 * @throws  \Exception  When the layout file is not found
	 */
	public function loadAnyTemplate($uri = '', $forceParams = array())
	{
		$layoutTemplate = $this->getLayoutTemplate();

		$extraPaths = array();

		if (isset($this->_path) || property_exists($this, '_path'))
		{
			$extraPaths = $this->_path['template'];
		}
		elseif (isset($this->path) || property_exists($this, 'path'))
		{
			$extraPaths = $this->path['template'];
		}

		$this->_tempFilePath = $this->viewFinder->resolveUriToPath($uri, $layoutTemplate, $extraPaths);

		unset($layoutTemplate);
		unset($extraPaths);
		unset($uri);

		// Never allow a 'this' property

		if (isset($this->this))
		{
			unset($this->this);
		}

		// TODO – BEGIN – Use engines (depend on extension of $this->_tempFilePath)
		// Force parameters into scope

		if (!empty($forceParams))
		{
			extract($forceParams);
		}

		// Start capturing output into a buffer
		ob_start();

		// Include the requested template filename in the local scope (this will execute the view logic).
		include $this->_tempFilePath;

		// Done with the requested template; get the buffer and clear it.
		$output = ob_get_contents();
		ob_end_clean();

		// TODO – END – Use engines
		return $output;
	}

	/**
	 * Get the layout template.
	 *
	 * @return  string  The layout template name
	 */
	public function getLayoutTemplate()
	{
		return $this->layoutTemplate;
	}

	/**
	 * Load a helper file
	 *
	 * @param   string $helperClass    The last part of the name of the helper
	 *                                 class.
	 *
	 * @return  void
	 *
	 * @deprecated  3.0  Just use the class in your code. That's what the autoloader is for.
	 */
	public function loadHelper($helperClass = null)
	{
		// Get the helper class name
		$className = '\\' . $this->container->getNamespacePrefix() . '\\Helper\\' . ucfirst($helperClass);

		// This trick autoloads the helper class. We can't instantiate it as
		// helpers are (supposed to be) abstract classes with static method
		// interfaces.
		class_exists($className);
	}

	/**
	 * Returns a reference to the container attached to this View
	 *
	 * @return Container
	 */
	public function &getContainer()
	{
		return $this->container;
	}

	public function getTask()
	{
		return $this->task;
	}

	/**
	 * @param   string  $task
	 *
	 * @return  $this   This for chaining
	 */
	public function setTask($task)
	{
		$this->task = $task;

		return $this;
	}

	public function getDoTask()
	{
		return $this->doTask;
	}

	/**
	 * @param   string  $task
	 *
	 * @return  $this   This for chaining
	 */
	public function setDoTask($task)
	{
		$this->doTask = $task;

		return $this;
	}

	/**
	 * Triggers an object-specific event. The event runs both locally –if a suitable method exists– and through the
	 * Joomla! plugin system. A true/false return value is expected. The first false return cancels the event.
	 *
	 * EXAMPLE
	 * Component: com_foobar, Object name: item, Event: onBeforeSomething, Arguments: array(123, 456)
	 * The event calls:
	 * 1. $this->onBeforeSomething(123, 456)
	 * 2. Joomla! plugin event onComFoobarViewItemBeforeSomething($this, 123, 456)
	 *
	 * @param   string  $event      The name of the event, typically named onPredicateVerb e.g. onBeforeKick
	 * @param   array   $arguments  The arguments to pass to the event handlers
	 *
	 * @return  bool
	 */
	protected function triggerEvent($event, array $arguments = array())
	{
		$result = true;

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
		array_unshift($arguments, $this);

		// If we have an "on" prefix for the event (e.g. onFooBar) remove it and stash it for later.
		$prefix = '';

		if (substr($event, 0, 2) == 'on')
		{
			$prefix = 'on';
			$event = substr($event, 2);
		}

		// Get the component/model prefix for the event
		$prefix .= 'Com' . ucfirst($this->container->bareComponentName) . 'View';
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

	/**
	 * Sets the pre-render flag
	 *
	 * @param   boolean  $value  True to enable the pre-render step
	 *
	 * @return  void
	 */
	public function setPreRender($value)
	{
		$this->doPreRender = $value;
	}

	/**
	 * Sets the post-render flag
	 *
	 * @param   boolean  $value  True to enable the post-render step
	 *
	 * @return  void
	 */
	public function setPostRender($value)
	{
		$this->doPostRender = $value;
	}

	/**
	 * Runs before rendering the view template, echoing HTML to put before the
	 * view template's generated HTML
	 *
	 * @return void
	 */
	protected function preRender()
	{
		// You need to implement this in children classes
	}

	/**
	 * Runs after rendering the view template, echoing HTML to put after the
	 * view template's generated HTML
	 *
	 * @return  void
	 */
	protected function postRender()
	{
		// You need to implement this in children classes
	}
}