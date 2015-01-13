<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Container;

use FOF30\Autoloader\Autoloader;
use FOF30\Pimple\Pimple;
use FOF30\Platform\Joomla\Filesystem as JoomlaFilesystem;
use FOF30\Platform\Joomla\Platform as JoomlaPlatform;
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
 * @property  array                                    $mvc_config         Configuration overrides for MVC, Dispatcher, Toolbar
 *
 * @property-read  \FOF30\Configuration\Configuration  $appConfig          The application configuration registry
 * @property-read  \JDatabaseDriver                    $db                 The database connection object
 * @property-read  \FOF30\Dispatcher\Dispatcher        $dispatcher         The component's dispatcher
 * @property-read  \FOF30\Event\Dispatcher             $eventDispatcher    The component's event dispatcher
 * @property-read  \FOF30\Platform\FilesystemInterface $filesystem         The filesystem abstraction layer object
 * @property-read  \FOF30\Input\Input                  $input              The input object
 * @property-read  \FOF30\Platform\PlatformInterface   $platform           The platform abstraction layer object
 * @property-read  \JSession                           $session            Joomla! session storage
 * @property-read  \FOF30\Template\Template            $template           The template helper
 * @property-read  \FOF30\Toolbar\Toolbar              $toolbar            The component's toolbar
 */
class Container extends Pimple
{
	public function __construct(array $values = array())
	{
		// Initialise
		$this->bareComponentName = '';
		$this->componentName = '';
		$this->componentNamespace = '';
		$this->frontEndPath = '';
		$this->backEndPath = '';
		$this->thisPath = '';

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
			$this['db'] = function ()
			{
				return $this->platform->getDbo();
			};
		}

		// Request Dispatcher service
		if (!isset($this['dispatcher']))
		{
			$this['dispatcher'] = function (Container $c)
			{
				$className = $c->getNamespacePrefix() . '\\Dispatcher';

				if (!class_exists($className))
				{
					$className = '\\FOF30\\Dispatcher\\Dispatcher';
				}

				return new $className($c);
			};
		}

		// Event Dispatcher service
		if (!isset($this['eventDispatcher']))
		{
			$this['eventDispatcher'] = function (Container $c)
			{
				$className = $c->getNamespacePrefix() . '\\Event\\Dispatcher';

				if (!class_exists($className))
				{
					$className = '\\FOF30\\Event\\Dispatcher';
				}

				return new $className($c);
			};
		}

		// Component toolbar provider
		if (!isset($this['toolbar']))
		{
			$this['toolbar'] = function (Container $c)
			{
				$className = $c->componentNamespace . '\\Toolbar';

				if (!class_exists($className, true))
				{
					$className = '\\FOF30\\Toolbar\\Toolbar';
				}

				return $className($c);
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

			case 'site':
				return $frontEndNamespace;
				break;

			case 'admin':
				return $backEndNamespace;
				break;
		}
	}
}