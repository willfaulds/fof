<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  TableBehaviors
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'assetsDataprovider.php';

class F0FTableBehaviorAssetsTest extends FtestCaseDatabase
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
     * @group               assetsOnAfterStore
     * @group               F0FTableBehavior
     * @covers              F0FTableBehaviorAssets::onAfterStore
     * @dataProvider        getTestOnAfterStore
     */
    public function testOnAfterStore($tableinfo, $test, $check)
    {
        $db = JFactory::getDbo();

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['name']));

        if(isset($test['tbl_key']))
        {
            $config['tbl_key'] = $test['tbl_key'];
        }

        $table = F0FTable::getAnInstance($tableinfo['name'], 'FoftestTable', $config);

        $reflection = new ReflectionProperty($table, 'tableDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($table);

        $behavior = new F0FTableBehaviorAssets($dispatcher);

        if(isset($test['alias']))
        {
            foreach($test['alias'] as $column => $alias)
            {
                $table->setColumnAlias($column, $alias);
            }

            $table->setAssetsTracked(true);
        }

        if(isset($test['id']))
        {
            $table->load($test['id']);
        }

        if(isset($test['rules']))
        {
            $table->setRules($test['rules']);
        }

        $query       = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
        $beforeTotal = $db->setQuery($query)->loadResult();

        $return = $behavior->onAfterStore($table);

        $this->assertEquals($check['return'], $return, 'F0FTableBehaviorAssets::onAfterStore returned a wrong value');

        $asset = null;
        if($check['count'] == 0)
        {
            $query      = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
            $afterTotal = $db->setQuery($query)->loadResult();

            $this->assertEquals(0, $beforeTotal - $afterTotal, 'F0FTableBehaviorAssets::onAfterStore wrong number of assets saved');
        }
        else
        {
            // Let's check what has been saved
            $query = $db->getQuery(true)
                        ->select('id, rules')
                        ->from('#__assets')
                        ->where('name = '.$db->q($table->getAssetName()));
            $asset = $db->setQuery($query)->loadObject();

            $this->assertEquals($check['count'], (int) (!is_null($asset)), 'F0FTableBehaviorAssets::onAfterStore wrong number of assets saved');
        }


        if(isset($check['rules']))
        {
            $this->assertEquals($check['rules'], $asset->rules, 'F0FTableBehaviorAssets::onAfterStore wrong rule stored');
        }

        if($asset)
        {
            $asset_field = $table->getColumnAlias('asset_id');
            $table->load($test['id']);

            $this->assertEquals($asset->id, $table->$asset_field, 'F0FTableBehaviorAssets::onAfterStore asset id not store inside the table');
        }
    }

    /**
     * @group               assetsOnAfterBind
     * @group               F0FTableBehavior
     * @covers              F0FTableBehaviorAssets::onAfterBind
     * @dataProvider        getTestOnAfterBind
     */
    public function testOnAfterBind($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['name']));

        if(isset($test['tbl_key']))
        {
            $config['tbl_key'] = $test['tbl_key'];
        }

        $table = F0FTable::getAnInstance($tableinfo['name'], 'FoftestTable', $config);

        $reflection = new ReflectionProperty($table, 'tableDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($table);

        $behavior = new F0FTableBehaviorAssets($dispatcher);

        if(isset($test['alias']))
        {
            foreach($test['alias'] as $column => $alias)
            {
                $table->setColumnAlias($column, $alias);
            }

            $table->setAssetsTracked(true);
        }

        if(isset($test['id']))
        {
            $table->load($test['id']);
        }

        $return = $behavior->onAfterBind($table, $test['bind']);
        $rules  = $table->getRules();

        $this->assertEquals($check['return'], $return, 'F0FTableBehaviorAssets::onAfterStore returned a wrong value');
        $this->assertJsonStringEqualsJsonString($check['rules'], (string) $rules, 'F0FTableBehaviorAssets::onAfterStore set rules wrong');
    }

    /**
     * @group               assetsOnBeforeDelete
     * @group               F0FTableBehavior
     * @covers              F0FTableBehaviorAssets::onBeforeDelete
     * @dataProvider        getTestOnBeforeDelete
     */
    public function testOnBeforeDelete($tableinfo, $test, $check)
    {
        $db              = JFactory::getDbo();
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['name']));

        if(isset($test['tbl_key']))
        {
            $config['tbl_key'] = $test['tbl_key'];
        }

        $table = F0FTable::getAnInstance($tableinfo['name'], 'FoftestTable', $config);

        $reflection = new ReflectionProperty($table, 'tableDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($table);

        $behavior = new F0FTableBehaviorAssets($dispatcher);

        if(isset($test['alias']))
        {
            foreach($test['alias'] as $column => $alias)
            {
                $table->setColumnAlias($column, $alias);
            }

            $table->setAssetsTracked(true);
        }

        if(isset($test['loadid']))
        {
            $table->load($test['loadid']);
        }

        $query       = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
        $beforeTotal = $db->setQuery($query)->loadResult();

        $return = $behavior->onBeforeDelete($table, isset($test['id']) ? $test['id'] : null);

        $this->assertEquals($check['return'], $return, 'F0FTableBehaviorAssets::onBeforeDelete returned a wrong value');

        $query      = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
        $afterTotal = $db->setQuery($query)->loadResult();

        $this->assertEquals($check['count'], $beforeTotal - $afterTotal, 'F0FTableBehaviorAssets::onBeforeDelete deleted a wrong number of assets');
    }

    public function getTestOnAfterStore()
    {
        return assetsDataprovider::getTestOnAfterStore();
    }

    public function getTestOnAfterBind()
    {
        return assetsDataprovider::getTestOnAfterBind();
    }

    public function getTestOnBeforeDelete()
    {
        return assetsDataprovider::getTestOnBeforeDelete();
    }
}
