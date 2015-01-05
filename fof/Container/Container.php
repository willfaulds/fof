<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Container;

use FOF30\Autoloader\Autoloader;
use \JDatabaseDriver;
use FOF30\Pimple\Pimple;
use \JSession;

/**
 * Dependency injection container for FOF-powered components
 *
 * @property  string                                   $componentName      The name of the component (com_something)
 * @property  string                                   $componentNamespace The namespace of the component's classes (\Foobar)
 * @property  string                                   $frontEndPath       The absolute path to the front-end files
 * @property  string                                   $backEndPath        The absolute path to the front-end files
 *
 * @property-read  \FOF30\Platform\PlatformInterface   $platform           The platform abstraction layer object
 * @property-read  \FOF30\Platform\FilesystemInterface $filesystem         The filesystem abstraction layer object
 * @property-read  \FOF30\Configuration\Configuration  $appConfig          The application configuration registry
 * @property-read  \JDatabaseDriver                    $db                 The global database connection object
 * @property-read  \FOF30\Dispatcher\Dispatcher        $dispatcher         The application dispatcher
 * @property-read  \FOF30\Input\Input                  $input              The input object
 * @property-read  \JSession                           $session            The session manager
 */
class Container extends Pimple
{
	public function __construct(array $values = array())
	{
		// Initialise
		$this->componentName = '';
		$this->componentNamespace = '';
		$this->frontEndPath = '';
		$this->backEndPath = '';

		// Try to construct this container object
		parent::__construct($values);

		// Make sure we have a component name
		if (empty($this->componentName))
		{
			throw new Exception\NoComponent;
		}

		// Try to guess the component's namespace
		if (empty($this->componentNamespace))
		{
			$bareComponent = substr($this->componentName, 3);
			$this->componentNamespace = ucfirst($bareComponent);
		}
		else
		{
			$this->componentNamespace = trim($this->componentNamespace, '\\');
		}

		// Make sure we have front-end and back-end paths
		if (empty($this->frontEndPath))
		{
			$this->frontEndPath = JPATH_SITE . '/components/' . $this->componentName;
		}

		if (empty($this->backEndPath))
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
				return new \FOF30\Platform\Joomla\Filesystem($c);
			};
		}

		// Platform abstraction service
		if (!isset($this['platform']))
		{
			$this['platform'] = function (Container $c)
			{
				return new \FOF30\Platform\Joomla\Platform($c);
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
				return $this->platform->getDbo();
			};
		}

		// Component Dispatcher service
		if (!isset($this['dispatcher']))
		{
			$this['dispatcher'] = function (Container $c)
			{
				$className = $c->getNamespacePrefix() . '\Dispatcher';

				if (!class_exists($className))
				{
					$className = '\FOF30\Dispatcher\Dispatcher';
				}

				return new $className($c);
			};
		}

		// Input Access service
		if (!isset($this['input']))
		{
			$this['input'] = function (Container $c)
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