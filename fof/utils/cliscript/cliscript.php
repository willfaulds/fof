<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  utils
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Define ourselves as a parent file
if(!defined('_JEXEC'))
{
    define('_JEXEC', 1);
}

if(!defined('F0F_CLI_MINPHP'))
{
    define('F0F_CLI_MINPHP' , '5.3.1');
}

if (version_compare(PHP_VERSION, F0F_CLI_MINPHP, 'lt'))
{
    $curversion = PHP_VERSION;
    $bindir     = PHP_BINDIR;
    $f0f_minphp = F0F_CLI_MINPHP;

    echo <<< ENDWARNING
================================================================================
WARNING! Incompatible PHP version $curversion
================================================================================

This CRON script must be run using PHP version $f0f_minphp or later. Your server is
currently using a much older version which would cause this script to crash. As
a result we have aborted execution of the script. Please contact your host and
ask them for the correct path to the PHP CLI binary for PHP $f0f_minphp or later, then
edit your CRON job and replace your current path to PHP with the one your host
gave you.

For your information, the current PHP version information is as follows.

PATH:    $bindir
VERSION: $curversion

Further clarifications:

1. There is absolutely no possible way that you are receiving this warning in
   error. We are using the PHP_VERSION constant to detect the PHP version you
   are currently using. This is what PHP itself reports as its own version. It
   simply cannot lie.

2. Even though your *site* may be running in a higher PHP version that the one
   reported above, your CRON scripts will most likely not be running under it.
   This has to do with the fact that your site DOES NOT run under the command
   line and there are different executable files (binaries) for the web and
   command line versions of PHP.

3. Please note that you MUST NOT ask us for support about this error. We cannot
   possibly know the correct path to the PHP CLI binary as we have not set up
   your server. Your host must know and give that information.

4. The latest published versions of PHP can be found at http://www.php.net/
   Any older version is considered insecure and must NOT be used on a live
   server. If your server uses a much older version of PHP than that please
   notify them that their servers are insecure and in need of an update.

This script will now terminate. Goodbye.

ENDWARNING;
    die();
}

// Required by the CMS
define('DS', DIRECTORY_SEPARATOR);

// Timezone fix; avoids errors printed out by PHP 5.3.3+ (thanks Yannick!)
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
    if (function_exists('error_reporting'))
    {
        $oldLevel = error_reporting(0);
    }

    $serverTimezone	 = @date_default_timezone_get();

    if (empty($serverTimezone) || !is_string($serverTimezone))
    {
        $serverTimezone	 = 'UTC';
    }

    if (function_exists('error_reporting'))
    {
        error_reporting($oldLevel);
    }

    @date_default_timezone_set($serverTimezone);
}

// Load system defines
if (file_exists(__DIR__ . '/../../../../defines.php'))
{
    include_once __DIR__ . '/../../../../defines.php';
}

if (!defined('JPATH_BASE'))
{
    $path = rtrim(realpath(__DIR__.'/../../../..'), DIRECTORY_SEPARATOR);
    define('JPATH_BASE', $path);

    require_once JPATH_BASE . '/includes/defines.php';
}

// Load the rest of the framework include files
if (file_exists(JPATH_LIBRARIES . '/import.legacy.php'))
{
    require_once JPATH_LIBRARIES . '/import.legacy.php';
}
else
{
    require_once JPATH_LIBRARIES . '/import.php';
}
require_once JPATH_LIBRARIES . '/cms.php';

// Load the JApplicationCli class
JLoader::import('joomla.application.cli');
JLoader::import('joomla.application.component.helper');
JLoader::import('cms.component.helper');
JLoader::import('f0f.include');

abstract class F0FUtilsCliscript extends JApplicationCli
{
    protected $extension;

    /**
     * JApplicationCli didn't want to run on PHP CGI. I have my way of becoming
     * VERY convincing. Now obey your true master, you petty class!
     *
     * @param JInputCli $input
     * @param JRegistry $config
     * @param JDispatcher $dispatcher
     */
    public function __construct(JInputCli $input = null, JRegistry $config = null, JDispatcher $dispatcher = null)
    {
        // Close the application if we are not executed from the command line, Akeeba style (allow for PHP CGI)
        if (array_key_exists('REQUEST_METHOD', $_SERVER))
        {
            die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
        }

        $cgiMode = false;

        if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv']))
        {
            $cgiMode = true;
        }

        // If a input object is given use it.
        if ($input instanceof JInput)
        {
            $this->input = $input;
        }
        // Create the input based on the application logic.
        else
        {
            if (class_exists('JInput'))
            {
                if ($cgiMode)
                {
                    $query = "";
                    if (!empty($_GET))
                    {
                        foreach ($_GET as $k => $v)
                        {
                            $query .= " $k";
                            if ($v != "")
                            {
                                $query .= "=$v";
                            }
                        }
                    }
                    $query	 = ltrim($query);
                    $argv	 = explode(' ', $query);
                    $argc	 = count($argv);

                    $_SERVER['argv'] = $argv;
                }

                $this->input = new JInputCLI();
            }
        }

        // If a config object is given use it.
        if ($config instanceof JRegistry)
        {
            $this->config = $config;
        }
        // Instantiate a new configuration object.
        else
        {
            $this->config = new JRegistry;
        }

        // If a dispatcher object is given use it.
        if ($dispatcher instanceof JDispatcher)
        {
            $this->dispatcher = $dispatcher;
        }
        // Create the dispatcher based on the application logic.
        else
        {
            $this->loadDispatcher();
        }

        // Load the configuration object.
        $this->loadConfiguration($this->fetchConfigurationData());

        // Set the execution datetime and timestamp;
        $this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
        $this->set('execution.timestamp', time());

        // Set the current directory.
        $this->set('cwd', getcwd());
    }

    public function flushAssets()
    {
        // This is an empty function since JInstall will try to flush the assets even if we're in CLI (!!!)
        return true;
    }

    public function printBanner()
    {
        $year           = gmdate('Y');
        $phpversion     = PHP_VERSION;
        $phpenvironment = PHP_SAPI;

        $this->out("FrameworkOnFramework CLI ");
        $this->out("Copyright (C) 2010-$year Akeeba Ltd.");
        $this->out(str_repeat('-', 79));
        $this->out("FrameworkOnFramework is Free Software, distributed under the terms of the GNU General");
        $this->out("Public License version 2 or, at your option, any later version.");
        $this->out("This program comes with ABSOLUTELY NO WARRANTY as per sections 11 & 12 of the");
        $this->out("license. See http://www.gnu.org/licenses/gpl-2.0.html for details.");
        $this->out(str_repeat('-', 79));
        $this->out("You are using PHP $phpversion ($phpenvironment)");
        $this->out(str_repeat('-', 79));
    }

    public function execute()
    {
        if(!$this->extension)
        {
            $this->out("Extension not configured inside this script. I can't continue");
            $this->close();
        }

        // Set all errors to output the messages to the console, in order to avoid infinite loops in JError ;)
        restore_error_handler();
        JError::setErrorHandling(E_ERROR, 'die');
        JError::setErrorHandling(E_WARNING, 'echo');
        JError::setErrorHandling(E_NOTICE, 'echo');

        // Required by Joomla!
        JLoader::import('joomla.environment.request');

        // Load the language files
        $jlang = JFactory::getLanguage();
        $jlang->load($this->extension, JPATH_ADMINISTRATOR);
        $jlang->load($this->extension.'.override', JPATH_ADMINISTRATOR);

        $this->printBanner();

        $safe_mode = true;

        if (function_exists('ini_get'))
        {
            $safe_mode = ini_get('safe_mode');
        }

        if (!$safe_mode && function_exists('set_time_limit'))
        {
            $this->out("Unsetting time limit restrictions");
            @set_time_limit(0);
        }

        parent::execute();
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        return $this->extension;
    }
}