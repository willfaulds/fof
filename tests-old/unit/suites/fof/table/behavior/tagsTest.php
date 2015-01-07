<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  TableBehaviors
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'tagsDataprovider.php';

class F0FTableBehaviorTagsTest extends FtestCaseDatabase
{
	protected function setUp()
	{
		$loadDataset = true;
		$annotations = $this->getAnnotations();

		// Do I need a dataset for this set or not?
		if(isset($annotations['method']) && isset($annotations['method']['preventDataLoading']))
		{
			$loadDataset = false;
		}

		parent::setUp($loadDataset);

		F0FPlatform::forceInstance(null);
		F0FTable::forceInstance(null);
	}

	/**
	 * @group               tagsOnAfterBind
	 * @group               F0FTableBehavior
	 * @covers              F0FTableBehaviorTags::onAfterBind
	 * @dataProvider        getTestOnAfterBind
	 */
	public function testOnAfterBind()
	{

	}

	public function getTestOnAfterBind()
	{
		return tagsDataprovider::getTestOnAfterBind();
	}
}