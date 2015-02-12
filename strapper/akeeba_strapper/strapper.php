<?php
/**
 * Akeeba Strapper
 *
 * A handy distribution of namespaced jQuery and Bootstrap 2.3.4
 *
 * THIS IS LEGACY CODE. SUPPORT FOR AKEEBA STRAPPER WILL BE DROPPED IN DECEMBER 31ST, 2015.
 *
 * @copyright Copyright (C) 2010 - 2015 Nicholas K. Dionysopoulos / Akeeba Ltd. All rights reserved.
 * @license   GNU General Public License version 2 or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

if (!defined('FOF30_INCLUDED'))
{
	JLoader::import('fof3.fof30.include');
}

if (!@include_once (__DIR__ . '/version.php') && !defined('AKEEBASTRAPPER_VERSION'))
{
	define('AKEEBASTRAPPER_VERSION', 'dev');
	define('AKEEBASTRAPPER_DATE', gmdate('Y-m-d'));
	define('AKEEBASTRAPPER_MEDIATAG', md5(AKEEBASTRAPPER_VERSION . AKEEBASTRAPPER_DATE));
}

class AkeebaStrapper
{
	/** @var bool True when jQuery is already included */
	public static $_includedJQuery = false;

	/** @var bool True when Bootstrap is already included */
	public static $_includedBootstrap = false;

	/** @var array List of URLs to Javascript files */
	public static $scriptURLs = array();

	/** @var array List of script definitions to include in the head */
	public static $scriptDefs = array();

	/** @var array List of URLs to CSS files */
	public static $cssURLs = array();

	/** @var array List of URLs to LESS files */
	public static $lessURLs = array();

	/** @var array List of CSS definitions to include in the head */
	public static $cssDefs = array();

	/** @var string A query tag to append to CSS and JS files for versioning purposes */
	public static $tag = null;

	public static function getFakeContainer()
	{
		static $fakeContainer = null;

		if (is_null($fakeContainer))
		{
			$fakeContainer = new FOF30\Container\Container(array(
				'componentName' => 'com_FOOBAR'
			));
		}

		return $fakeContainer;
	}

	/**
	 * Gets the query tag.
	 *
	 * Uses AkeebaStrapper::$tag as the default tag for the extension's mediatag. If
	 * $overrideTag is set then that tag is used in stead.
	 *
	 * @param    string $overrideTag      If defined this tag is used in stead of
	 *                                    AkeebaStrapper::$tag
	 *
	 * @return    string    The extension's query tag (e.g. ?23f742d04111881faa36ea8bc6d31a59)
	 *                    or an empty string if it's not set
	 */
	public static function getTag($overrideTag = null)
	{
		if ($overrideTag !== null)
		{
			$tag = $overrideTag;
		}
		else
		{
			$tag = self::$tag;
		}

		if (empty($tag))
		{
			$tag = '';
		}
		else
		{
			$tag = '?' . ltrim($tag, '?');
		}

		return $tag;
	}

	/**
	 * Is this something running under the CLI mode?
	 *
	 * @staticvar bool|null $isCli
	 * @return null
	 */
	public static function isCli()
	{
		static $isCli = null;

		if (is_null($isCli))
		{
			try
			{
				if (is_null(JFactory::$application))
				{
					$isCli = true;
				}
				else
				{
					$isCli = JFactory::getApplication() instanceof \Exception;
				}
			}
			catch (Exception $e)
			{
				$isCli = true;
			}
		}

		return $isCli;
	}

	public static function getPreference($key, $default = null)
	{
		static $config = null;

		if (is_null($config))
		{
			// Load a configuration INI file which controls which files should be skipped
			$iniFile = self::getFakeContainer()->template->parsePath('media://akeeba_strapper/strapper.ini', true);

			$config = parse_ini_file($iniFile);
		}

		if (!array_key_exists($key, $config))
		{
			$config[$key] = $default;
		}

		return $config[$key];
	}

	/**
	 * Loads our namespaced jQuery, accessible through akeeba.jQuery
	 */
	public static function jQuery()
	{
		if (self::isCli())
		{
			return;
		}

		// Load jQuery only once
		if (self::$_includedJQuery)
		{
			return;
		}

		$jQueryLoad = self::getPreference('jquery_load', 'auto');

		if (!in_array($jQueryLoad, array('auto', 'full', 'namespace', 'none')))
		{
			$jQueryLoad = 'auto';
		}

		self::$_includedJQuery = true;

		if ($jQueryLoad == 'none')
		{
			return;
		}

		if ($jQueryLoad == 'auto')
		{
			$jQueryLoad = 'namespace';
			JHtml::_('jquery.framework');
		}

		if ($jQueryLoad == 'full')
		{
			self::addJSfile('media://akeeba_strapper/js/akeebajq.js', AKEEBASTRAPPER_MEDIATAG);
			self::addJSfile('media://akeeba_strapper/js/akjqmigrate.js', AKEEBASTRAPPER_MEDIATAG);

			return;
		}

		self::addJSfile('media://akeeba_strapper/js/namespace.js', AKEEBASTRAPPER_MEDIATAG);
	}

	/**
	 * Loads our namespaced Twitter Bootstrap. You have to wrap the output you want style
	 * with an element having the class akeeba-bootstrap added to it.
	 */
	public static function bootstrap()
	{
		if (self::isCli())
		{
			return;
		}

		// Load Bootstrap only once
		if (self::$_includedBootstrap)
		{
			return;
		}

		$default = 'lite';

		$loadBootstrap = self::getPreference('bootstrap', $default);

		if ($loadBootstrap == 'front')
		{
			$isFrontend = self::getFakeContainer()->platform->isFrontend();

			$loadBootstrap = $isFrontend ? 'full' : 'lite';
		}

		if (!in_array($loadBootstrap, array('full', 'lite', 'none', 'front')))
		{
			$loadBootstrap = 'lite';
		}

		if ($loadBootstrap == 'lite')
		{
			// Use Joomla!'s Javascript
			JHtml::_('bootstrap.framework');
		}

		if (!self::$_includedJQuery)
		{
			self::jQuery();
		}

		self::$_includedBootstrap = true;

		$altCss = array('media://akeeba_strapper/css/strapper.j3.min.css');

		if ($loadBootstrap == 'full')
		{
			array_unshift($altCss, 'media://akeeba_strapper/css/bootstrap.min.css');

			$filename = self::getFakeContainer()->template->parsePath('media://akeeba_strapper/js/bootstrap.min.js', true);

			if (@filesize($filename) > 5)
			{
				self::addJSfile('media://akeeba_strapper/js/bootstrap.min.js', AKEEBASTRAPPER_MEDIATAG);
			}
		}
		elseif ($loadBootstrap != 'none')
		{
			array_unshift($altCss, 'media://akeeba_strapper/css/bootstrap.j32.min.css');
		}

		foreach ($altCss as $css)
		{
			self::addCSSfile($css, AKEEBASTRAPPER_MEDIATAG);
		}
	}

	/**
	 * Adds an arbitrary Javascript file.
	 *
	 * @param $path           string    The path to the file, in the format media://path/to/file
	 * @param $overrideTag    string    If defined this version tag overrides AkeebaStrapper::$tag
	 */
	public static function addJSfile($path, $overrideTag = null)
	{
		if (self::isCli())
		{
			return;
		}

		$tag = self::getTag($overrideTag);

		self::$scriptURLs[] = array(self::getFakeContainer()->template->parsePath($path), $tag);
	}

	/**
	 * Add inline Javascript
	 *
	 * @param $script string Raw inline Javascript
	 */
	public static function addJSdef($script)
	{
		if (self::isCli())
		{
			return;
		}

		self::$scriptDefs[] = $script;
	}

	/**
	 * Adds an arbitrary CSS file.
	 *
	 * @param $path           string    The path to the file, in the format media://path/to/file
	 * @param $overrideTag    string    If defined this version tag overrides AkeebaStrapper::$tag
	 */
	public static function addCSSfile($path, $overrideTag = null)
	{
		if (self::isCli())
		{
			return;
		}

		$tag = self::getTag($overrideTag);

		self::$cssURLs[] = array(self::getFakeContainer()->template->parsePath($path), $tag);
	}

	/**
	 * Adds an arbitraty LESS file.
	 *
	 * @param $path           string The path to the file, in the format media://path/to/file
	 * @param $altPaths       string|array The path to the alternate CSS files, in the format media://path/to/file
	 * @param $overrideTag    string    If defined this version tag overrides AkeebaStrapper::$tag
	 */
	public static function addLESSfile($path, $altPaths = null, $overrideTag = null)
	{
		if (self::isCli())
		{
			return;
		}

		$tag = self::getTag($overrideTag);

		self::$lessURLs[] = array($path, $altPaths, $tag);
	}

	/**
	 * Add inline CSS
	 *
	 * @param $style string Raw inline CSS
	 */
	public static function addCSSdef($style)
	{
		if (self::isCli())
		{
			return;
		}

		self::$cssDefs[] = $style;
	}

	/**
	 * Do we need to preload?
	 *
	 * @return bool True if we need to preload
	 */
	public static function needPreload()
	{
		$needPreload = (bool)self::getPreference('preload', 0);

		// Do not allow Joomla! 3+ preloading if jQueryLoad is "auto" or "namespace" (which are both
		// namespace in Joomla! 3+). Else only the namespacing for the jQuery library will be loaded,
		// without a jQuery library being loaded on forehand, which results in jQuery error(s).
		$jQueryLoad = self::getPreference('jquery_load', 'auto');

		if (in_array($jQueryLoad, array('auto', 'namespace')))
		{
			$needPreload = false;
		}

		return $needPreload;
	}
}

/**
 * This is a workaround which ensures that Akeeba's namespaced JavaScript and CSS will be loaded
 * without being tampered with by any system plugin. Moreover, since we are loading first, we can
 * be pretty sure that namespacing *will* work and we won't cause any incompatibilities with third
 * party extensions loading different versions of these GUI libraries.
 *
 * This code works by registering a system plugin hook :) It will grab the HTML and drop its own
 * JS and CSS definitions in the head of the script, before anything else has the chance to run.
 *
 * Peace.
 */
function AkeebaStrapperLoader()
{
	// If there are no script defs, just go to sleep
	if (
		empty(AkeebaStrapper::$scriptURLs) &&
		empty(AkeebaStrapper::$scriptDefs) &&
		empty(AkeebaStrapper::$cssDefs) &&
		empty(AkeebaStrapper::$cssURLs) &&
		empty(AkeebaStrapper::$lessURLs)
	)
	{
		return;
	}

	$myscripts = '';

	$preload = AkeebaStrapper::needPreload();

	$buffer = JFactory::getApplication()->getBody();

	// Include Javascript files
	if (!empty(AkeebaStrapper::$scriptURLs))
	{
		foreach (AkeebaStrapper::$scriptURLs as $entry)
		{
			list($url, $tag) = $entry;

			if ($preload)
			{
				$myscripts .= '<script type="text/javascript" src="' . $url . $tag . '"></script>' . "\n";
			}
			else
			{
				JFactory::getDocument()->addScript($url . $tag);
			}
		}
	}

	// Include Javscript snippets
	if (!empty(AkeebaStrapper::$scriptDefs))
	{
		if ($preload)
		{
			$myscripts .= '<script type="text/javascript" language="javascript">' . "\n";
		}
		else
		{
			$myscripts = '';
		}
		foreach (AkeebaStrapper::$scriptDefs as $def)
		{
			$myscripts .= $def . "\n";
		}
		if ($preload)
		{
			$myscripts .= '</script>' . "\n";
		}
		else
		{
			JFactory::getDocument()->addScriptDeclaration($myscripts);
		}
	}

	// Include LESS files
	if (!empty(AkeebaStrapper::$lessURLs))
	{
		foreach (AkeebaStrapper::$lessURLs as $entry)
		{
			list($lessFile, $altFiles, $tag) = $entry;

			$url = AkeebaStrapper::getFakeContainer()->template->addLESS($lessFile, $altFiles, true);

			if ($preload)
			{
				if (empty($url))
				{
					if (!is_array($altFiles) && empty($altFiles))
					{
						$altFiles = array($altFiles);
					}
					if (!empty($altFiles))
					{
						foreach ($altFiles as $altFile)
						{
							$url = AkeebaStrapper::getFakeContainer()->template->parsePath($altFile);
							$myscripts .= '<link type="text/css" rel="stylesheet" href="' . $url . $tag . '" />' . "\n";
						}
					}
				}
				else
				{
					$myscripts .= '<link type="text/css" rel="stylesheet" href="' . $url . $tag . '" />' . "\n";
				}
			}
			else
			{
				if (empty($url))
				{
					if (!is_array($altFiles) && empty($altFiles))
					{
						$altFiles = array($altFiles);
					}
					if (!empty($altFiles))
					{
						foreach ($altFiles as $altFile)
						{
							$url = AkeebaStrapper::getFakeContainer()->template->parsePath($altFile);
							JFactory::getDocument()->addStyleSheet($url . $tag);
						}
					}
				}
				else
				{
					JFactory::getDocument()->addStyleSheet($url . $tag);
				}
			}
		}
	}

	// Include CSS files
	if (!empty(AkeebaStrapper::$cssURLs))
	{
		foreach (AkeebaStrapper::$cssURLs as $entry)
		{
			list($url, $tag) = $entry;

			if ($preload)
			{
				$myscripts .= '<link type="text/css" rel="stylesheet" href="' . $url . $tag . '" />' . "\n";
			}
			else
			{
				JFactory::getDocument()->addStyleSheet($url . $tag);
			}
		}
	}

	// Include style definitions
	if (!empty(AkeebaStrapper::$cssDefs))
	{
		$myscripts .= '<style type="text/css">' . "\n";
		foreach (AkeebaStrapper::$cssDefs as $def)
		{
			if ($preload)
			{
				$myscripts .= $def . "\n";
			}
			else
			{
				JFactory::getDocument()->addScriptDeclaration($def . "\n");
			}
		}
		$myscripts .= '</style>' . "\n";
	}

	if ($preload)
	{
		$pos = strpos($buffer, "<head>");
		if ($pos > 0)
		{
			$buffer = substr($buffer, 0, $pos + 6) . $myscripts . substr($buffer, $pos + 6);

			JFactory::getApplication()->setBody($buffer);
		}
	}
}

/**
 * Akeeba Strapper onAfterRender entry point.
 *
 * Makes sure Akeeba Strapper's bootstrap[.min].js is only loaded when
 * bootstrap[.min].js has not yet been loaded.
 */
function AkeebaStrapperOnAfterRender()
{
	if (AkeebaStrapper::$_includedBootstrap)
	{
		$buffer = JFactory::getApplication()->getBody();

		// Get all bootstrap[.min].js to remove
		$count = 0;
		$scriptsToRemove = array();
		$scriptRegex = "/<script [^>]+(\/>|><\/script>)/i";
		preg_match_all($scriptRegex, $buffer, $matches);
		$scripts = $matches[0];

		foreach ($scripts as $script)
		{
			$jsRegex = "/([^\"\'=]+\.js)(\?[^\"\']*){0,1}[\"\']/i";
			preg_match_all($jsRegex, $script, $matches);

			foreach ($matches[1] as $scriptUrl)
			{
				$scriptName = basename($scriptUrl);

				if (in_array($scriptName, array('bootstrap.min.js', 'bootstrap.js')))
				{
					$count++;

					if (strpos($script, 'media/akeeba_strapper/js/bootstrap.min.js') !== false)
					{
						$scriptsToRemove[] = $script;
					}
				}
			}
		}

		// Remove duplicated bootstrap scripts from the output
		if ($count > 1 && !empty($scriptsToRemove))
		{
			$buffer = str_replace($scriptsToRemove, '', $buffer);

			JFactory::getApplication()->setBody($buffer);
		}
	}
}

// Add our pseudo-plugins to the application event queue
if (!AkeebaStrapper::isCli())
{
	$app = JFactory::getApplication();

	if (AkeebaStrapper::needPreload())
	{
		$app->registerEvent('onAfterRender', 'AkeebaStrapperLoader');
	}
	else
	{
		$app->registerEvent('onBeforeRender', 'AkeebaStrapperLoader');
	}

	$app->registerEvent('onAfterRender', 'AkeebaStrapperOnAfterRender');
}