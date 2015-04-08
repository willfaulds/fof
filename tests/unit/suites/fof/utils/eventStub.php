<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Utils
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class F0FUtilsObservableEventStub extends F0FUtilsObservableEvent
{
	/**
	 * @var array Record of calls made to myEvent
	 */
	public $calls = array();

	/**
	 * Records calls in $calls
	 *
	 * Used to verify the firing of events
	 *
	 * @return true
	 */
	public function myEvent()
	{
		$this->calls[] = array('method' => 'myEvent', 'args' => func_get_args());

		return true;
	}
}
