<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class JToolbarHelper
{
    protected static $methodStack = array();

    public static function __callStatic($name, $arguments)
    {
        // I have to save every call, because some methods (ie divider) would be called more than once
        self::$methodStack[$name][] = $arguments;
    }

    public static function getStack()
    {
        return self::$methodStack;
    }

    public static function resetStack()
    {
        self::$methodStack = array();
    }
}