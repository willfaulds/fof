<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

// Required to load FOF and Joomla!
define('_JEXEC', 1);

// Include the FOF autoloader.
if (false == include __DIR__ . '/../fof/Autoloader/Autoloader.php')
{
	echo 'ERROR: FOF Autoloader not found' . PHP_EOL;

	exit(1);
}

// Tell the FOF autoloader where to load test classes from (very useful for stubs!)
\FOF30\Autoloader\Autoloader::getInstance()->addMap('FOF\\Tests\\', __DIR__);
// \FOF30\Autoloader\Autoloader::getInstance()->addMap('Fakeapp\\', __DIR__ . '/Stubs/Fakeapp');

// Include the Composer autoloader.
if (false == include __DIR__ . '/../vendor/autoload.php')
{
	echo 'ERROR: You need to install Composer and run `composer install` on FOF before running the tests.' . PHP_EOL;

	exit(1);
}

// Don't report strict errors. This is needed because sometimes a test complains about arguments passed as reference
ini_set('zend.ze1_compatibility_mode', '0');
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 1);

// Fix magic quotes on PHP 5.3
if (version_compare(PHP_VERSION, '5.4.0', 'lt'))
{
	ini_set('magic_quotes_runtime', 0);
}

// Timezone fix; avoids errors printed out by PHP 5.3.3+
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
	if (function_exists('error_reporting'))
	{
		$oldLevel = error_reporting(0);
	}

	$serverTimezone = @date_default_timezone_get();

	if (empty($serverTimezone) || !is_string($serverTimezone))
	{
		$serverTimezone = 'UTC';
	}

	if (function_exists('error_reporting'))
	{
		error_reporting($oldLevel);
	}

	@date_default_timezone_set($serverTimezone);
}

//Am I in Travis CI?
if (getenv('TRAVIS'))
{
	require_once __DIR__ . '/config_travis.php';

	$siteroot = $fofTestConfig[getenv('JVERSION_TEST')];
}
else
{
	require_once __DIR__ . '/config.php';

	$siteroot = $fofTestConfig['site_root'];
}

// Set up the Joomla! environment
if (file_exists($siteroot . '/defines.php'))
{
	include_once $siteroot . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', $siteroot);

	require_once JPATH_BASE . '/includes/defines.php';
}

if (!defined('JPATH_TESTS'))
{
	define('JPATH_TESTS', __DIR__);
}

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/import.legacy.php';
require_once JPATH_LIBRARIES . '/cms.php';

/**
// Apply the SQL
require_once __DIR__ . '/stubs/dbimport.php';
$importer = new FteststubsDbimport;
$importer->importdb();

// Register the FOF test classes.
JLoader::registerPrefix('Ftest', JPATH_TESTS . '/unit/core');
JLoader::import('joomla.filesystem.path');

// Load FOF's autoloader
jimport('joomla.application.input');
require_once JPATH_TESTS . '/unit/core/reflection/reflection.php';
require_once JPATH_TESTS . '/unit/core/closure/closure.php';
require_once __DIR__ . '/../../fof/include.php';
/**/