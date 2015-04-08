<?php
/**
 * @package    FrameworkOnFramework.UnitTest
 * @subpackage Core
 *
 * @copyright  Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Sometimes we have to perform some tasks BEFORE calling the constructor. In production we can easily do that,
 * since we just need to extend the base class; if you need to do something like this in test, you must use this
 * class and pass the correct parameter in the $config argument
 */
class FtestTable extends F0FTable
{
	public function __construct($table, $key, &$db, $config = array())
	{
		if(isset($config['join']))
		{
			$this->setQueryJoin($config['join']);
		}

		parent::__construct($table, $key, $db);
	}
}