<?php
/**
 *  @package     FrameworkOnFramework
 *  @subpackage  include
 *  @copyright   Copyright (C) 2010-2015 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 *
 *  Initializes F0F
 */

defined('_JEXEC') or die();

if (!defined('FOF30_INCLUDED'))
{
    define('FOF30_INCLUDED', '##VERSION##');

	// Register the F0F autoloader
    require_once __DIR__ . '/Autoloader/Autoloader.php';

	// TODO Register a debug log
	if (defined('JDEBUG') && JDEBUG)
	{
		// F0FPlatform::getInstance()->logAddLogger('fof.log.php');
	}
}