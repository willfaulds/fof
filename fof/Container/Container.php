<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Container;

use FOF30\Autoloader\Autoloader;
use FOF30\Inflector\Inflector;
use FOF30\Pimple\Pimple;
use FOF30\Platform\Joomla\Filesystem as JoomlaFilesystem;
use FOF30\Platform\Joomla\Platform as JoomlaPlatform;
use FOF30\Render\RenderInterface;
use FOF30\Template\Template;
use JDatabaseDriver;
use JSession;

defined('_JEXEC') or die;

/**
 * Dependency injection container for FOF-powered components
 *
 * @property  string                                   $componentName      The name of the component (com_something)
 * @property  string                                   $bareComponentName  The name of the component without com_ (something)
 * @property  string                                   $componentNamespace The namespace of the component's classes (\Foobar)
 * @property  string                                   $frontEndPath       The absolute path to the front-end files
 * @property  string                                   $backEndPath        The absolute path to the front-end files
 * @property  string                                   $thisPath           The preferred path. Backend for Admin application, frontend otherwise
 * @property  string                                   $rendererClass      The fully qualified class name of the view renderer we'll be using. Must implement FOF30\Render\RenderInterface.
 * @property  string                                   $factoryClass       The fully qualified class name or slug (basic, switch) of the MVC Factory object, default is FOF30\Factory\BasicFactory.
 * @property  array                                    $mvc_config         Configuration overrides for MVC, Dispatcher, Toolbar
 *
 * @property-read  \FOF30\Configuration\Configuration  $appConfig          The application configuration registry
 * @property-read  \JDatabaseDriver                    $db                 The database connection object
 * @property-read  \FOF30\Dispatcher\Dispatcher        $dispatcher         The component's dispatcher
 * @property-read  \FOF30\Factory\FactoryInterface     $factory            The MVC object factory
 * @property-read  \FOF30\Platform\FilesystemInterface $filesystem         The filesystem abstraction layer object
 * @property-read  \FOF30\Input\Input                  $input              The input object
 * @property-read  \FOF30\Platform\PlatformInterface   $platform           The platform abstraction layer object
 * @property-read  \FOF30\Render\RenderInterface       $renderer           The view renderer
 * @property-read  \JSession                           $session            Joomla! session storage
 * @property-read  \FOF30\Template\Template            $template           The template helper
 * @property-read  \FOF30\Toolbar\Toolbar              $toolbar            The component's toolbar
 */
class Container extends Pimple
{
	/**
	 * Returns a container instance for a specific component
	 *
	 * @param   string  $component  The component you want to get a container for, e.g. com_foobar.
	 * @param   null    $namespace  The namespace of the component, if different that the bare name of the component.
	 * @param   string  $section    The application section (site, admin) you want to fetch. Any other value results in auto-detection.
	 * @param   array   $values     Any container configuration overrides you want to apply.
	 *
	 * @return \FOF30\Container\Container
	 */
	public static function &getInstance($component, $namespace = null, $section = 'auto', array $values = array())
	{
		// $values always overrides $namespace
		if (isset($values['componentNamespace']))
		{
			$namespace = $values['componentNamespace'];
		}

		// If there is no namespace set, try to guess it.
		if (empty($namespace))
		{
			$bareComponent = $component;

			if (substr($component, 0, 4) == 'com_')
			{
				$bareComponent = substr($component, 4);
			}

			$namespace = ucfirst($bareComponent);
		}

		// Get the default front-end/back-end paths
		$frontEndPath = JPATH_SITE . '/components/' . $component;
		$backEndPath = JPATH_ADMINISTRATOR . '/components/' . $component;

		// Apply path overrides
		if (isset($values['frontEndPath']))
		{
			$frontEndPath = $values['frontEndPath'];
		}

		if (isset($values['backEndPath']))
		{
			$backEndPath = $values['backEndPath'];
		}

		// Get the namespaces for the front-end and back-end parts of the component
		$frontEndNamespace = '\\' . $namespace . '\\Site\\';
		$backEndNamespace = '\\' . $namespace . '\\Admin\\';

		// Special case: if the frontend and backend paths are identical, we don't use the Site and Admin namespace
		// suffixes after $this->componentNamespace (so you may use FOF with JApplicationWeb apps)
		if ($frontEndPath == $backEndPath)
		{
			$frontEndNamespace = '\\' . $namespace . '\\';
			$backEndNamespace = '\\' . $namespace . '\\';
		}

		// Do we have to register the component's namespaces with the autoloader?
		$autoloader = Autoloader::getInstance();

		if (!$autoloader->hasMap($frontEndNamespace))
		{
			$autoloader->addMap($frontEndNamespace, $frontEndPath);
		}

		if (!$autoloader->hasMap($backEndNamespace))
		{
			$autoloader->addMap($backEndNamespace, $backEndPath);
		}

		// Which component section (site, admin) do we want to get?
		if (!in_array($section, array('site', 'admin')))
		{
			$tmpContainer = new Container(array('componentName' => $component));
			$section = $tmpContainer->platform->isBackend() ? 'admin' : 'site';
			unset($tmpContainer);
		}

		// Get the Container class name
		$classNamespace = ($section == 'admin') ? $backEndNamespace : $frontEndNamespace;
		$class = $classNamespace . 'Container';

		$values = array_merge($values, array(
			'componentName' => $component,
			'componentNamespace' => $namespace,
		));

		if (class_exists($class, true))
		{
			return new $class($values);
		}
		else
		{
			return new Container($values);
		}
	}

	public function __construct(array $values = array())
	{
		// Initialise
		$this->bareComponentName = '';
		$this->componentName = '';
		$this->componentNamespace = '';
		$this->frontEndPath = '';
		$this->backEndPath = '';
		$this->thisPath = '';
		$this->factoryClass = 'FOF30\\Factory\\BasicFactory';

		// Try to construct this container object
		parent::__construct($values);

		// Make sure we have a component name
		if (empty($this['componentName']))
		{
			throw new Exception\NoComponent;
		}

		$bareComponent = substr($this->componentName, 4);

		$this['bareComponentName'] = $bareComponent;

		// Try to guess the component's namespace
		if (empty($this['componentNamespace']))
		{
			$this->componentNamespace = ucfirst($bareComponent);
		}
		else
		{
			$this->componentNamespace = trim($this->componentNamespace, '\\');
		}

		// Make sure we have front-end and back-end paths
		if (empty($this['frontEndPath']))
		{
			$this->frontEndPath = JPATH_SITE . '/components/' . $this->componentName;
		}

		if (empty($this['backEndPath']))
		{
			$this->backEndPath = JPATH_ADMINISTRATOR . '/components/' . $this->componentName;
		}

		// Get the namespaces for the front-end and back-end parts of the component
		$frontEndNamespace = '\\' . $this->componentNamespace . '\\Site\\';
		$backEndNamespace = '\\' . $this->componentNamespace . '\\Admin\\';

		// Special case: if the frontend and backend paths are identical, we don't use the Site and Admin namespace
		// suffixes after $this->componentNamespace (so you may use FOF with JApplicationWeb apps)
		if ($this->frontEndPath == $this->backEndPath)
		{
			$frontEndNamespace = '\\' . $this->componentNamespace . '\\';
			$backEndNamespace = '\\' . $this->componentNamespace . '\\';
		}

		// Do we have to register the component's namespaces with the autoloader?
		$autoloader = Autoloader::getInstance();

		if (!$autoloader->hasMap($frontEndNamespace))
		{
			$autoloader->addMap($frontEndNamespace, $this->frontEndPath);
		}

		if (!$autoloader->hasMap($backEndNamespace))
		{
			$autoloader->addMap($backEndNamespace, $this->backEndPath);
		}

		// Filesystem abstraction service
		if (!isset($this['filesystem']))
		{
			$this['filesystem'] = function (Container $c)
			{
				return new JoomlaFilesystem($c);
			};
		}

		// Platform abstraction service
		if (!isset($this['platform']))
		{
			$this['platform'] = function (Container $c)
			{
				return new JoomlaPlatform($c);
			};
		}

		if (empty($this['thisPath']))
		{
			$this['thisPath'] = $this['frontEndPath'];

			if ($this->platform->isBackend())
			{
				$this['thisPath'] = $this['backEndPath'];
			}
		}

		// MVC Factory service
		if (!isset($this['factory']))
		{
			$this['factory'] = function (Container $c)
			{
				if (empty($c['factoryClass']))
				{
					$c['factoryClass'] = 'FOF30\\Factory\\BasicFactory';
				}

				if (strpos($c['factoryClass'], '\\') === false)
				{
					$class = $c->getNamespacePrefix() . 'Factory\\' . $c['factoryClass'];

					if (class_exists($class))
					{
						$c['factoryClass'] = $class;
					}
					else
					{
						$c['factoryClass'] = '\\FOF30\\Factory\\' . ucfirst($c['factoryClass']) . 'Factory';
 					}
				}

				if (!class_exists($c['factoryClass'], true))
				{
					$c['factoryClass'] = 'FOF30\\Factory\\BasicFactory';
				}

				$factoryClass = $c['factoryClass'];

				return new $factoryClass($c);
			};
		}

		// Component Configuration service
		if (!isset($this['appConfig']))
		{
			$this['appConfig'] = function (Container $c)
			{
				$class = $c->getNamespacePrefix() . 'Configuration\\Configuration';

				if (!class_exists($class, true))
				{
					$class = '\\FOF30\\Configuration\\Configuration';
				}

				return new $class($c);
			};
		}

		// Database Driver service
		if (!isset($this['db']))
		{
			$this['db'] = function (Container $c)
			{
				return $c->platform->getDbo();
			};
		}

		// Request Dispatcher service
		if (!isset($this['dispatcher']))
		{
			$this['dispatcher'] = function (Container $c)
			{
				return $c->factory->dispatcher();
			};
		}

		// Component toolbar provider
		if (!isset($this['toolbar']))
		{
			$this['toolbar'] = function (Container $c)
			{
				return $c->factory->toolbar();
			};
		}

		// View renderer
		if (!isset($this['renderer']))
		{
			$this['renderer'] = function (Container $c)
			{
				if (isset($c['rendererClass']) && class_exists($c['rendererClass']))
				{
					$class = $c['rendererClass'];
					$renderer = new $class($c);

					if ($renderer instanceof RenderInterface)
					{
						return $renderer;
					}
				}

				$filesystem     = $c->filesystem;

				// Try loading the stock renderers shipped with F0F
				$path = dirname(__FILE__) . '/../Render/';
				$renderFiles = $filesystem->folderFiles($path, '.php');
				$renderer = null;
				$priority = 0;

				if (!empty($renderFiles))
				{
					foreach ($renderFiles as $filename)
					{
						if ($filename == 'Base.php')
						{
							continue;
						}

						if ($filename == 'RenderInterface.php')
						{
							continue;
						}

						$camel = Inflector::camelize($filename);
						$className = 'FOF30\\Render\\' . ucfirst(Inflector::getPart($camel, 0));

						if (!class_exists($className, true))
						{
							continue;
						}

						/** @var RenderInterface $renderer */
						$renderer = new $className($c);

						$info = $renderer->getInformation();

						if (!$info->enabled)
						{
							continue;
						}

						if ($info->priority > $priority)
						{
							$priority = $info->priority;
						}
					}
				}

				return $renderer;
			};
		}

		// Input Access service
		if (!isset($this['input']))
		{
			$this['input'] = function ()
			{
				return new \FOF30\Input\Input();
			};
		}

		// Session service
		if (!isset($this['session']))
		{
			$this['session'] = function ()
			{
				return \JFactory::getSession();
			};
		}

		// Template service
		if (!isset($this['template']))
		{
			$this['template'] = function (Container $c)
			{
				return new Template($c);
			};
		}
	}

	/**
	 * Get the applicable namespace prefix for a component section. Possible sections:
	 * auto			Auto-detect which is the current component section
	 * inverse      The inverse area than auto
	 * site			Frontend
	 * admin		Backend
	 *
	 * @param   string  $section  The section you want to get information for
	 *
	 * @return  string  The namespace prefix for the component's classes, e.g. \Foobar\Example\Site\
	 */
	public function getNamespacePrefix($section = 'auto')
	{
		// Get the namespaces for the front-end and back-end parts of the component
		$frontEndNamespace = '\\' . $this->componentNamespace . '\\Site\\';
		$backEndNamespace = '\\' . $this->componentNamespace . '\\Admin\\';

		// Special case: if the frontend and backend paths are identical, we don't use the Site and Admin namespace
		// suffixes after $this->componentNamespace (so you may use FOF with JApplicationWeb apps)
		if ($this->frontEndPath == $this->backEndPath)
		{
			$frontEndNamespace = '\\' . $this->componentNamespace . '\\';
			$backEndNamespace = '\\' . $this->componentNamespace . '\\';
		}

		switch ($section)
		{
			default:
			case 'auto':
				if ($this->platform->isBackend())
				{
					return $backEndNamespace;
				}
				else
				{
					return $frontEndNamespace;
				}
				break;

			case 'inverse':
				if ($this->platform->isBackend())
				{
					return $frontEndNamespace;
				}
				else
				{
					return $backEndNamespace;
				}
				break;

			case 'site':
				return $frontEndNamespace;
				break;

			case 'admin':
				return $backEndNamespace;
				break;
		}
	}
}