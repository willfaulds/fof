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
use FOF30\Factory\Exception\ControllerNotFound;
use FOF30\Factory\Exception\DispatcherNotFound;
use FOF30\Factory\Exception\FormLoadData;
use FOF30\Factory\Exception\FormLoadFile;
use FOF30\Factory\Exception\ModelNotFound;
use FOF30\Factory\Exception\ToolbarNotFound;
use FOF30\Factory\Exception\TransparentAuthenticationNotFound;
use FOF30\Factory\Exception\ViewNotFound;
use FOF30\Form\Form;
use FOF30\Inflector\Inflector;
use FOF30\Model\Model;
use FOF30\Toolbar\Toolbar;
use FOF30\TransparentAuthentication\TransparentAuthentication;
use FOF30\View\View;
use FOF30\View\ViewTemplateFinder;

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

	/** @var  bool  Should I look for form files on the other side of the component? */
	protected $formLookupInOtherSide = false;

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
		catch (DispatcherNotFound $e)
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
			return $this->createtoolbar($toolbarClass, $config);
		}
		catch (ToolbarNotFound $e)
		{
			// Not found. Return the default Toolbar
			return new Toolbar($this->container, $config);
		}
	}

	/**
	 * Creates a new TransparentAuthentication handler
	 *
	 * @param   array $config The configuration values for the TransparentAuthentication object
	 *
	 * @return  TransparentAuthentication
	 */
	function transparentAuthentication(array $config = array())
	{
		$authClass = $this->container->getNamespacePrefix() . 'TransparentAuthentication\\TransparentAuthentication';

		try
		{
			return $this->createTransparentAuthentication($authClass, $config);
		}
		catch (TransparentAuthenticationNotFound $e)
		{
			// Not found. Return the default TA
			return new TransparentAuthentication($this->container, $config);
		}
	}

	/**
	 * Creates a new Form object
	 *
	 * @param   string  $name      The name of the form.
	 * @param   string  $source    The form source filename without path and .xml extension e.g. "form.default" OR raw XML data
	 * @param   string  $viewName  The name of the view you're getting the form for.
	 * @param   array   $options   Options to the Form object
	 * @param   bool    $replace   Should form fields be replaced if a field already exists with the same group/name?
	 * @param   bool    $xpath     An optional xpath to search for the fields.
	 *
	 * @return  Form|null  The loaded form or null if the form filename doesn't exist
	 *
	 * @throws  \RuntimeException If the form exists but cannot be loaded
	 */
	function form($name, $source, $viewName = null, array $options = array(), $replace = true, $xpath = false)
	{
		// Get a new form instance
		$form = new Form($this->container, $name, $options);

		// If $source looks like raw XML data, parse it directly
		if (strpos($source, '<form ') !== false)
		{
			if ($form->load($source, $replace, $xpath) === false)
			{
				throw new FormLoadData;
			}

			return $form;
		}

		$formFileName = $this->getFormFilename($source, $viewName);

		if (empty($formFileName))
		{
			return null;
		}

		if ($form->loadFile($formFileName, $replace, $xpath) === false)
		{
			throw new FormLoadFile($source);
		}

		return $form;
	}

	/**
	 * Creates a view template finder object for a specific View
	 *
	 * The default configuration is:
	 * Look for .php, .blade.php files; default layout "default"; no default subtemplate;
	 * look only for the specified view; do NOT fall back to the default layout or subtemplate;
	 * look for templates ONLY in site or admin, depending on where we're running from
	 *
	 * @param   View  $view   The view this view template finder will be attached to
	 * @param   array $config Configuration variables for the object
	 *
	 * @return  mixed
	 */
	function viewFinder(View $view, array $config = array())
	{
		// Initialise the configuration with the default values
		$defaultConfig = array(
			'extensions'    => array('.php', '.blade.php'),
			'defaultLayout' => 'default',
			'defaultTpl'    => '',
			'strictView'    => true,
			'strictTpl'     => true,
			'strictLayout'  => true,
			'sidePrefix'    => 'auto'
		);

		$config = array_merge($defaultConfig, $config);

		// Apply fof.xml overrides
		$appConfig = $this->container->appConfig;
		$key = "models." . $view->getName() . ".config";

		$fofXmlConfig = array(
			'extensions'    => $appConfig->get("$key.templateExtensions", $config['extensions']),
			'strictView'    => $appConfig->get("$key.templateStrictView", $config['strictView']),
			'strictTpl'     => $appConfig->get("$key.templateStrictTpl", $config['strictTpl']),
			'strictLayout'  => $appConfig->get("$key.templateStrictLayout", $config['strictLayout']),
			'sidePrefix'    => $appConfig->get("$key.templateLocation", $config['sidePrefix'])
		);

		$config = array_merge($config, $fofXmlConfig);

		// Create the new view template finder object
		return new ViewTemplateFinder($view, $config);
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
			throw new ControllerNotFound($controllerClass);
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
			throw new ModelNotFound($modelClass);
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
			throw new ViewNotFound($viewClass);
		}

		return new $viewClass($this->container, $config);
	}

	/**
	 * Creates a Toolbar object
	 *
	 * @param   string  $toolbarClass  The fully qualified class name for the Toolbar
	 * @param   array   $config        The configuration values for the Toolbar object
	 *
	 * @return  Toolbar
	 *
	 * @throws  \RuntimeException  If the $toolbarClass does not exist
	 */
	protected function createToolbar($toolbarClass, array $config = array())
	{
		if (!class_exists($toolbarClass))
		{
			throw new ToolbarNotFound($toolbarClass);
		}

		return new $toolbarClass($this->container, $config);
	}

	/**
	 * Creates a Dispatcher object
	 *
	 * @param   string  $dispatcherClass  The fully qualified class name for the Dispatcher
	 * @param   array   $config            The configuration values for the Dispatcher object
	 *
	 * @return  Dispatcher
	 *
	 * @throws  \RuntimeException  If the $dispatcherClass does not exist
	 */
	protected function createDispatcher($dispatcherClass, array $config = array())
	{
		if (!class_exists($dispatcherClass))
		{
			throw new DispatcherNotFound($dispatcherClass);
		}

		return new $dispatcherClass($this->container, $config);
	}

	/**
	 * Creates a TransparentAuthentication object
	 *
	 * @param   string  $authClass  The fully qualified class name for the TransparentAuthentication
	 * @param   array   $config     The configuration values for the TransparentAuthentication object
	 *
	 * @return  TransparentAuthentication
	 *
	 * @throws  \RuntimeException  If the $authClass does not exist
	 */
	protected function createTransparentAuthentication($authClass, $config)
	{
		if (!class_exists($authClass))
		{
			throw new TransparentAuthenticationNotFound($authClass);
		}

		return new $authClass($this->container, $config);
	}

	/**
	 * Tries to find the absolute file path for an abstract form filename. For example, it may convert form.default to
	 * /home/myuser/mysite/components/com_foobar/View/tmpl/form.default.xml.
	 *
	 * @param   string  $source    The abstract form filename
	 * @param   string  $viewName  The name of the view we're getting the path for
	 *
	 * @return  string|bool  The fill path to the form XML file or boolean false if it's not found
	 */
	protected function getFormFilename($source, $viewName = null)
	{
		if (empty($source))
		{
			return false;
		}

		$componentName = $this->container->componentName;

		if (empty($viewName))
		{
			$viewName = $this->container->dispatcher->getController()->getView()->getName();
		}

		$viewNameAlt = Inflector::singularize($viewName);

		if ($viewNameAlt == $viewName)
		{
			$viewNameAlt = Inflector::pluralize($viewName);
		}

		$componentPaths = $this->container->platform->getComponentBaseDirs($componentName);

		$file_root      = $componentPaths['main'];
		$alt_file_root  = $componentPaths['alt'];
		$template_root  = $this->container->platform->getTemplateOverridePath($componentName);

		// Basic paths we need to always search
		$paths = array(
			// Template override
			$template_root . '/' . $viewName,
			$template_root . '/' . $viewNameAlt,
			// This side of the component
			$file_root . '/View/' . $viewName . '/tmpl',
			$file_root . '/View/' . $viewNameAlt . '/tmpl',
		);

		// The other side of the component
		if ($this->formLookupInOtherSide)
		{
			$paths[] = $alt_file_root . '/View/' . $viewName . '/tmpl';
			$paths[] = $alt_file_root . '/View/' . $viewNameAlt . '/tmpl';
		}

		// Legacy paths, this side of the component
		$paths[] = $file_root . '/views/' . $viewName . '/tmpl';
		$paths[] = $file_root . '/views/' . $viewNameAlt . '/tmpl';
		$paths[] = $file_root . '/Model/forms';
		$paths[] = $file_root . '/models/forms';

		// Legacy paths, the other side of the component
		if ($this->formLookupInOtherSide)
		{
			$paths[] = $file_root . '/views/' . $viewName . '/tmpl';
			$paths[] = $file_root . '/views/' . $viewNameAlt . '/tmpl';
			$paths[] = $file_root . '/Model/forms';
			$paths[] = $file_root . '/models/forms';
		}

		$paths = array_unique($paths);

		// Set up the suffixes to look into
		$suffixes = array();
		$temp_suffixes = $this->container->platform->getTemplateSuffixes();

		if (!empty($temp_suffixes))
		{
			foreach ($temp_suffixes as $suffix)
			{
				$suffixes[] = $suffix . '.xml';
			}
		}

		$suffixes[] = '.xml';

		// Look for all suffixes in all paths
		$result     = false;
		$filesystem = $this->container->filesystem;

		foreach ($paths as $path)
		{
			foreach ($suffixes as $suffix)
			{
				$filename = $path . '/' . $source . $suffix;

				if ($filesystem->fileExists($filename))
				{
					$result = $filename;
					break;
				}
			}

			if ($result)
			{
				break;
			}
		}

		return $result;
	}
}