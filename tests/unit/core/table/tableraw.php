<?php
/**
 * @package    FrameworkOnFramework.UnitTest
 * @subpackage Core
 *
 * @copyright  Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class TableFtest extends F0FTable {

	public function __construct($table, $key, &$db, $config = array())
	{
		parent::__construct($table, $key, $db, $config);

		$this->_tbl     = '#__foftest_foobars';
		$this->_tbl_key = 'foftest_foobar_id';
	}
}