<?php
/**
 * @package    FrameworkOnFramework.UnitTest
 * @subpackage Core
 *
 * @copyright  Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class FtestTableRelations extends F0FTableRelations
{
    public function normaliseParameters($pivot = false, &$itemName, &$tableClass, &$localKey, &$remoteKey, &$ourPivotKey, &$theirPivotKey, &$pivotTable)
    {
        parent::normaliseParameters($pivot, $itemName, $tableClass, $localKey, $remoteKey, $ourPivotKey, $theirPivotKey, $pivotTable);
    }
}