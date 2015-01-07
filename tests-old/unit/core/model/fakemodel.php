<?php
/**
 * @package    FrameworkOnFramework.UnitTest
 * @subpackage Core
 *
 * @copyright  Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class FtestFakeModel {
	
	public function getName()
	{
		return '';
	}
}

class FtestFakeModel2 extends FtestFakeModel {
	
	public function getTable()
	{
		return false;
	}
}