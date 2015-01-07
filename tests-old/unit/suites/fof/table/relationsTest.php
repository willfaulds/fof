<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'relationsDataprovider.php';
require_once JPATH_TESTS.'/unit/core/table/relations.php';

class F0FTableRelationsTest extends FtestCaseDatabase
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
     * Let's check if F0FTableReations constructor detects the parent table relation
     *
     * @group               relationsConstruct
     * @group               F0FTableRelations
     * @dataProvider        getTest__construct
     * @covers              F0FTableRelations::__construct
     */
    public function test__construct($tableinfo, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        // The table is supposed to have a parent, let's check if it was detected
        if($check['hasParent'])
        {
            $this->assertArrayHasKey($check['relation']['key'], $relations['parent'], 'F0FTableRelations failed to reconize and store the parent relation');
            $this->assertEquals($check['relation']['content'], $relations['parent'][$check['relation']['key']], 'F0FTableRelations stored the wrong info for this parent relation');
            $this->assertEquals($check['relation']['key'], $defaultRelation['parent'], 'F0FTableRelation failed to store the parent relation as the default one');
        }
        else
        {
            $this->assertArrayNotHasKey($check['relation']['key'], $relations['parent'], 'F0FTableRelations failed to reconize and store the parent relation');
            $this->assertEquals(array(), $relations['parent'], 'F0FTableRelations should contain no parent relation');
        }

    }

    /**
     * @group               relationsAddChildRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestAddChildRelation
     * @covers              F0FTableRelations::addChildRelation
     */
    public function testAddChildRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relation->addChildRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['remoteKey'],
            $test['relation']['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['child'][$check['relation']['key']], 'F0FTableRelations stored the wrong info for this child relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['child'], 'F0FTableRelation default relation not stored as expected');
    }

    /**
     * @group               relationsAddParentRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestAddParentRelation
     * @covers              F0FTableRelations::addParentRelation
     */
    public function testAddParentRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        // To test it properly, I have to clear the relation automatically created by the constructor
        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, array(
                'child'		=> array(),
                'parent'	=> array(),
                'children'	=> array(),
                'multiple'	=> array()
            )
        );

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, array(
                'child'		=> null,
                'parent'	=> null,
                'children'	=> null,
                'multiple'	=> null,
            )
        );

        $relation->addParentRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['remoteKey'],
            $test['relation']['default']
        );

        $relations       = $relations->getValue($relation);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['parent'][$check['relation']['key']], 'F0FTableRelations stored the wrong info for this parent relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['parent'], 'F0FTableRelation default relation not stored as expected');
    }

    /**
     * @group               relationsAddChildrenRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestAddChildrenRelation
     * @covers              F0FTableRelations::addChildrenRelation
     */
    public function testAddChildrenRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relation->addChildrenRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['remoteKey'],
            $test['relation']['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['children'][$check['relation']['key']], 'F0FTableRelations stored the wrong info for children relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['children'], 'F0FTableRelation default relation not stored as expected');
    }

    /**
     * @group               relationsAddMultipleRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestAddMultipleRelation
     * @covers              F0FTableRelations::addMultipleRelation
     */
    public function testAddMultipleRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relation->addMultipleRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['ourPivot'],
            $test['relation']['theirPivot'],
            $test['relation']['remoteKey'],
            $test['relation']['glueTable'],
            $test['relation']['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['multiple'][$check['relation']['key']], 'F0FTableRelations stored the wrong info for multiple relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['multiple'], 'F0FTableRelation default relation not stored as expected');
    }

    /**
     * @group               relationsRemoveRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestRemoveRelation
     * @covers              F0FTableRelations::removeRelation
     */
    public function testRemoveRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        // Do I have a default relation?
        if(isset($test['default']))
        {
            $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
            $defaultRelation->setAccessible(true);
            $defaultRelation->setValue($relation, $test['default']);
        }

        $relation->removeRelation($test['itemName'], $test['type']);

        $relations = $relations->getValue($relation);

        $this->assertEquals($check['relations'], $relations, 'F0FTableRelations::removeRelation failed to remove the relation(s)');

        if(isset($check['default']))
        {
            $defaultRelation = $defaultRelation->getValue($relation);

            $this->assertEquals($check['default'], $defaultRelation, 'F0FTableRelations::removeRelation failed to remove the default relation');
        }
    }

    /**
     * @group               relationsClearRelations
     * @group               F0FTableRelations
     * @dataProvider        getTestClearRelations
     * @covers              F0FTableRelations::clearRelations
     */
    public function testClearRelations($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        // Do I have a default relation?
        if(isset($test['default']))
        {
            $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
            $defaultRelation->setAccessible(true);
            $defaultRelation->setValue($relation, $test['default']);
        }

        $relation->clearRelations($test['type']);

        $relations = $relations->getValue($relation);

        $this->assertEquals($check['relations'], $relations, 'F0FTableRelations::removeRelation failed to remove the relation(s)');

        if(isset($check['default']))
        {
            $defaultRelation = $defaultRelation->getValue($relation);

            $this->assertEquals($check['default'], $defaultRelation, 'F0FTableRelations::removeRelation failed to remove the default relation');
        }
    }

    /**
     * @group               relationsHasRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestHasRelation
     * @covers              F0FTableRelations::hasRelation
     */
    public function testHasRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $result = $relation->hasRelation($test['itemName'], $test['type']);

        $this->assertEquals($check['result'], $result, 'F0FTableRelations::hasRelation failed to detect the relation');

    }

    /**
     * @group               relationsGetRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestGetRelation
     * @covers              F0FTableRelations::getRelation
     */
    public function testGetRelation($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $result = $relation->getRelation($test['itemName'], $test['type']);

        $this->assertArrayHasKey('type', $result, 'F0FTableRelations::getRelation should set the "type" key');
    }

    /**
     * @group               relationsGetRelatedItem
     * @group               F0FTableRelations
     * @dataProvider        getTestGetRelatedItem
     * @covers              F0FTableRelations::getRelatedItem
     */
    public function testGetRelatedItem($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('getParent', 'getChild'), array($table));
        $relation->expects($this->any())->method('getParent')->will($this->returnValue(true));
        $relation->expects($this->any())->method('getChild')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $relation->getRelatedItem($test['itemName'], $test['type']);
    }

    /**
     * @group               relationsGetRelatedItems
     * @group               F0FTableRelations
     * @dataProvider        getTestGetRelatedItems
     * @covers              F0FTableRelations::getRelatedItems
     */
    public function testGetRelatedItems($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('getChildren', 'getMultiple', 'getSiblings'), array($table));
        $relation->expects($this->any())->method('getChildren')->will($this->returnValue(true));
        $relation->expects($this->any())->method('getMultiple')->will($this->returnValue(true));
        $relation->expects($this->any())->method('getSiblings')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $relation->getRelatedItems($test['itemName'], $test['type']);
    }

    /**
     * @group               relationsGetParent
     * @group               F0FTableRelations
     * @dataProvider        getTestGetParent
     * @covers              F0FTableRelations::getParent
     */
    public function testGetParent($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('getTableFromRelation'), array($table));
        $relation->expects($this->any())->method('getTableFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getParent($test['itemName']);
    }

    /**
     * @group               relationsGetChild
     * @group               F0FTableRelations
     * @dataProvider        getTestGetChild
     * @covers              F0FTableRelations::getChild
     */
    public function testGetChild($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('getTableFromRelation'), array($table));
        $relation->expects($this->any())->method('getTableFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getChild($test['itemName']);
    }

    /**
     * @group               relationsGetChildren
     * @group               F0FTableRelations
     * @dataProvider        getTestGetChildren
     * @covers              F0FTableRelations::getChildren
     */
    public function testGetChildren($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('getIteratorFromRelation'), array($table));
        $relation->expects($this->any())->method('getIteratorFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getChildren($test['itemName']);
    }

    /**
     * @group               relationsGetSiblings
     * @group               F0FTableRelations
     * @dataProvider        getTestGetSiblings
     * @covers              F0FTableRelations::getSiblings
     */
    public function testGetSiblings($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('getIteratorFromRelation'), array($table));
        $relation->expects($this->any())->method('getIteratorFromRelation')->with($this->equalTo($check['iterator']));
        $relation->expects($this->any())->method('getIteratorFromRelation')->will($this->returnValue(true));

        $relation->getSiblings($test['itemName']);
    }

    /**
     * @group               relationsGetMultiple
     * @group               F0FTableRelations
     * @dataProvider        getTestGetMultiple
     * @covers              F0FTableRelations::getMultiple
     */
    public function testGetMultiple($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('getIteratorFromRelation'), array($table));
        $relation->expects($this->any())->method('getIteratorFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getMultiple($test['itemName']);
    }


    /**
     * @group               relationsGetTableFromRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestGetTableFromRelation
     * @covers              F0FTableRelations::getTableFromRelation
     */
    public function testGetTableFromRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $table->load($test['loadid']);

        $relation = $table->getRelations();

        $method = new ReflectionMethod($relation, 'getTableFromRelation');
        $method->setAccessible(true);

        $relatedTable = $method->invoke($relation, $test['relation']);

        $pk = $relatedTable->getKeyName();

        $this->assertInstanceOf('F0FTable', $relatedTable, 'F0FTableRelations::getTableFromRelation should return an instance of the F0FTable');
        $this->assertEquals($check['id'], $relatedTable->$pk, 'F0FTableRelations::getTableFromRelation loaded the wrong linked table');
    }

    /**
     * @group               relationsGetTableFromRelationNoLoad
     * @group               F0FTableRelations
     * @covers              F0FTableRelations::getTableFromRelation
     */
    public function testGetTableFromRelationNoLoad()
    {
        $this->setExpectedException('RuntimeException');

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => 'child'));
        $table 		     = F0FTable::getAnInstance('child', 'FoftestTable', $config);

        $relationArg = array(
            'tableClass' => 'FoftestTableParent',
            'localKey'   => 'foftest_parent_id',
            'remoteKey'  => 'foftest_parent_id'
        );

        $relation = $table->getRelations();

        $method = new ReflectionMethod($relation, 'getTableFromRelation');
        $method->setAccessible(true);

        $method->invoke($relation, $relationArg);
    }

    /**
     * @group               relationsGetTableFromRelationInvalidArgs
     * @group               F0FTableRelations
     * @dataProvider        getTestGetTableFromRelationInvalidArgs
     * @covers              F0FTableRelations::getTableFromRelation
     */
    public function testGetTableFromRelationInvalidArgs($tableinfo, $test)
    {
        $this->setExpectedException('InvalidArgumentException');

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $method = new ReflectionMethod($relation, 'getTableFromRelation');
        $method->setAccessible(true);

        $method->invoke($relation, $test['relation']);
    }

    /**
     * @group               relationsGetIteratorFromRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestGetIteratorFromRelation
     * @covers              F0FTableRelations::getIteratorFromRelation
     */
    public function testGetIteratorFromRelation($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $table->load($test['loadid']);

        $relation = $table->getRelations();

        $method = new ReflectionMethod($relation, 'getIteratorFromRelation');
        $method->setAccessible(true);

        $items = $method->invoke($relation, $test['relation']);

        $this->assertInstanceOf('F0FDatabaseIterator', $items, 'F0FTableRelations::getIteratorFromRelation should return an instance of the F0FDatabaseIterator');
        $this->assertEquals($check['count'], count($items), 'F0FTableRelations::getIteratorFromRelation returned the wrong number of items');
    }

    /**
     * @group               relationsGetIteratorFromRelationNoLoad
     * @group               F0FTableRelations
     * @covers              F0FTableRelations::getIteratorFromRelation
     */
    public function testGetIteratorFromRelationNoLoad()
    {
        $this->setExpectedException('RuntimeException');

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => 'parent'));
        $table 		     = F0FTable::getAnInstance('child', 'FoftestTable', $config);

        $relationArg = array(
            'tableClass' => 'FoftestTableChild',
            'localKey'   => 'foftest_parent_id',
            'remoteKey'  => 'foftest_parent_id'
        );

        $relation = $table->getRelations();

        $method = new ReflectionMethod($relation, 'getIteratorFromRelation');
        $method->setAccessible(true);

        $method->invoke($relation, $relationArg);
    }

    /**
     * @group               relationsGetIteratorFromRelationInvalidArgs
     * @group               F0FTableRelations
     * @dataProvider        getTestGetIteratorFromRelationInvalidArgs
     * @covers              F0FTableRelations::getIteratorFromRelation
     */
    public function testGetIteratorFromRelationInvalidArgs($tableinfo, $test)
    {
        $this->setExpectedException('InvalidArgumentException');

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $method = new ReflectionMethod($relation, 'getIteratorFromRelation');
        $method->setAccessible(true);

        $method->invoke($relation, $test['relation']);
    }

    /**
     * This is a simple test to check if the data is actually stored inside the correct array and if this
     * method considers the modifications made by normaliseParameters
     *
     * @group               relationsAddBespokeSimpleRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestAddBespokeSimpleRelation
     * @covers              F0FTableRelations::addBespokeSimpleRelation
     */
    public function testAddBespokeSimpleRelation($tableinfo, $test, $check)
    {
        $invoke  = $test['invoke'];
        $process = $test['process'];

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('normaliseParameters'), array($table));
        $relation->expects($this->any())->method('normaliseParameters')->will(
            $this->returnCallback(function($a1, &$a2, &$a3, &$a4, &$a5) use ($process){
                $a3 = $process['tableClass'];
                $a4 = $process['localKey'];
                $a5 = $process['remoteKey'];
            })
        );

        $method = new ReflectionMethod($relation, 'addBespokeSimpleRelation');
        $method->setAccessible(true);

        $method->invoke($relation,
            $invoke['relationType'],
            $invoke['itemName'],
            $invoke['tableClass'],
            $invoke['localKey'],
            $invoke['remoteKey'],
            $invoke['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $default = new ReflectionProperty($relation, 'defaultRelation');
        $default->setAccessible(true);
        $default = $default->getValue($relation);

        $this->assertEquals($check['relations'], $relations[$invoke['relationType']], 'F0FTableRelations::addBespokeSimpleRelation failed to store the relation');
        $this->assertEquals($check['default'], $default[$invoke['relationType']], 'F0FTableRelations::addBespokeSimpleRelation failed to store the default relation');
    }

    /**
     * This is a simple test to check if the data is actually stored inside the correct array and if this
     * method considers the modifications made by normaliseParameters
     *
     * @group               relationsAddBespokePivotRelation
     * @group               F0FTableRelations
     * @dataProvider        getTestAddBespokePivotRelation
     * @covers              F0FTableRelations::addBespokePivotRelation
     */
    public function testAddBespokePivotRelation($tableinfo, $test, $check)
    {
        $invoke  = $test['invoke'];
        $process = $test['process'];

        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('F0FTableRelations', array('normaliseParameters'), array($table));
        $relation->expects($this->any())->method('normaliseParameters')->will(
            $this->returnCallback(function($a1, &$a2, &$a3, &$a4, &$a5, &$a6, &$a7, &$a8) use ($process){
                $a3 = $process['tableClass'];
                $a4 = $process['localKey'];
                $a5 = $process['remoteKey'];
                $a6 = $process['ourPivotKey'];
                $a7 = $process['theirPivotKey'];
                $a8 = $process['pivotTable'];
            })
        );

        $method = new ReflectionMethod($relation, 'addBespokePivotRelation');
        $method->setAccessible(true);

        $method->invoke($relation,
            $invoke['relationType'],
            $invoke['itemName'],
            $invoke['tableClass'],
            $invoke['localKey'],
            $invoke['remoteKey'],
            $invoke['ourPivotKey'],
            $invoke['theirPivotKey'],
            $invoke['pivotTable'],
            $invoke['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $default = new ReflectionProperty($relation, 'defaultRelation');
        $default->setAccessible(true);
        $default = $default->getValue($relation);

        $this->assertEquals($check['relations'], $relations[$invoke['relationType']], 'F0FTableRelations::addBespokePivotRelation failed to store the relation');
        $this->assertEquals($check['default'], $default[$invoke['relationType']], 'F0FTableRelations::addBespokePivotRelation failed to store the default relation');
    }

    /**
     * @group               relationsNormaliseParameters
     * @group               F0FTableRelations
     * @dataProvider        getTestNormaliseParameters
     * @covers              F0FTableRelations::normaliseParameters
     */
    public function testNormaliseParameters($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = new FtestTableRelations($table);

        $relation->normaliseParameters(
            $test['pivot'],
            $test['itemName'],
            $test['tableClass'],
            $test['localKey'],
            $test['remoteKey'],
            $test['ourPivotKey'],
            $test['theirPivotKey'],
            $test['pivotTable']
        );

        // Let's copy the processed test data and unset the keys we're not interested in, so we
        // can directly check it the array is the correct one
        $processed = $test;
        unset($processed['pivot']);

        $this->assertEquals($check['parameters'], $processed, 'F0FTableRelations::normaliseParameters failed to set all the parameters');
    }

    /**
     * @group               relationsNormaliseItemName
     * @group               F0FTableRelations
     * @dataProvider        getTestNormaliseItemName
     * @covers              F0FTableRelations::normaliseItemName
     */
    public function testNormaliseItemName($tableinfo, $test, $check)
    {
        $config['input'] = new F0FInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = F0FTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $method = new ReflectionMethod($relation, 'normaliseItemName');
        $method->setAccessible(true);

        $itemname = $method->invoke($relation, $test['itemName'], $test['plural']);

        $this->assertEquals($check['itemname'], $itemname, 'F0FTableRelations::normaliseItemName created a wrong itemname string');
    }

    public static function getTest__construct()
    {
        return RelationsDataprovider::getTest__construct();
    }

    public static function getTestAddChildRelation()
    {
        return RelationsDataprovider::getTestAddChildRelation();
    }

    public static function getTestAddParentRelation()
    {
        return RelationsDataprovider::getTestAddParentRelation();
    }

    public static function getTestAddChildrenRelation()
    {
        return RelationsDataprovider::getTestAddChildrenRelation();
    }

    public static function getTestAddMultipleRelation()
    {
        return RelationsDataprovider::getTestAddMultipleRelation();
    }

    public static function getTestRemoveRelation()
    {
        return RelationsDataprovider::getTestRemoveRelation();
    }

    public static function getTestClearRelations()
    {
        return RelationsDataprovider::getTestClearRelations();
    }

    public static function getTestHasRelation()
    {
        return RelationsDataprovider::getTestHasRelation();
    }

    public static function getTestGetRelation()
    {
        return RelationsDataprovider::getTestGetRelation();
    }

    public static function getTestGetRelatedItem()
    {
        return RelationsDataprovider::getTestGetRelatedItem();
    }

    public static function getTestGetRelatedItems()
    {
        return RelationsDataprovider::getTestGetRelatedItems();
    }

    public static function getTestGetParent()
    {
        return RelationsDataprovider::getTestGetParent();
    }

    public static function getTestGetChild()
    {
        return RelationsDataprovider::getTestGetChild();
    }

    public static function getTestGetChildren()
    {
        return RelationsDataprovider::getTestGetChildren();
    }

    public static function getTestGetSiblings()
    {
        return RelationsDataprovider::getTestGetSiblings();
    }

    public static function getTestGetMultiple()
    {
        return RelationsDataprovider::getTestGetMultiple();
    }

    public static function getTestGetTableFromRelation()
    {
        return RelationsDataprovider::getTestGetTableFromRelation();
    }

    public static function getTestGetTableFromRelationInvalidArgs()
    {
        return RelationsDataprovider::getTestGetTableFromRelationInvalidArgs();
    }

    public static function getTestGetIteratorFromRelation()
    {
        return RelationsDataprovider::getTestGetIteratorFromRelation();
    }

    public static function getTestGetIteratorFromRelationInvalidArgs()
    {
        return RelationsDataprovider::getTestGetIteratorFromRelationInvalidArgs();
    }

    public static function getTestAddBespokeSimpleRelation()
    {
        return RelationsDataprovider::getTestAddBespokeSimpleRelation();
    }

    public function getTestAddBespokePivotRelation()
    {
        return RelationsDataprovider::getTestAddBespokePivotRelation();
    }

    public function getTestNormaliseParameters()
    {
        return RelationsDataprovider::getTestNormaliseParameters();
    }

    public static function getTestNormaliseItemName()
    {
        return RelationsDataprovider::getTestNormaliseItemName();
    }
}