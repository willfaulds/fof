<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class RelationsDataprovider
{
    public static function getTest__construct()
    {
        // Child table with parents
        $data[] = array(
            array('table' => 'child'),
            array(
                'hasParent' => true,
                'relation'  => array(
                    'key'     => 'parent',
                    'content' => array(
                        'tableClass'  => 'FoftestTableParent',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                ),
                'default'   => array(
                    'parent'  => 'parent'
                )
            )
        );

        // Table with no parent link
        $data[] = array(
            array('table' => 'bare'),
            array(
                'hasParent' => false,
                'relation'  => array(
                    'key'     => 'parent'
                )
            )
        );

        return $data;
    }

    public static function getTestAddChildRelation()
    {
        // Default usage of child relation, everything is "magically" set
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'child',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => true
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'child',
                    'key'       => 'child',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableChild',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Relation is not the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'child',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'child',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableChild',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Force relation parameters
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'child',
                    'tableClass' => 'FoftestTableTestchild',
                    'localKey'   => 'foftest_local_id',
                    'remoteKey'  => 'foftest_remote_id',
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'child',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableTestchild',
                        'localKey'    => 'foftest_local_id',
                        'remoteKey'   => 'foftest_remote_id',
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestAddParentRelation()
    {
        // Default usage of child relation, everything is "magically" set
        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'itemName'   => 'parent',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => true
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'parent',
                    'key'       => 'parent',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableParent',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Relation is not the default one
        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'itemName'   => 'parent',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'parent',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableParent',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Force relation parameters
        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'itemName'   => 'parent',
                    'tableClass' => 'FoftestTableTestparent',
                    'localKey'   => 'foftest_local_id',
                    'remoteKey'  => 'foftest_remote_id',
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'parent',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableTestparent',
                        'localKey'    => 'foftest_local_id',
                        'remoteKey'   => 'foftest_remote_id',
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestAddChildrenRelation()
    {
        // Default usage of children relation, everything is "magically" set
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'children',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => true
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'children',
                    'key'       => 'children',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableChild',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Relation is not the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'children',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'children',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableChild',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Force relation parameters
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'children',
                    'tableClass' => 'FoftestTableTestchildren',
                    'localKey'   => 'foftest_local_id',
                    'remoteKey'  => 'foftest_remote_id',
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'children',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableTestchildren',
                        'localKey'    => 'foftest_local_id',
                        'remoteKey'   => 'foftest_remote_id',
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestAddMultipleRelation()
    {
        // Default usage of multiple relation, everything is "magically" set - relation name is singular
        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'group',
                    'tableClass'    => null,
                    'localKey'      => null,
                    'ourPivot'      => null,
                    'theirPivot'    => null,
                    'remoteKey'     => null,
                    'glueTable'     => null,
                    'default'       => true,
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'groups',
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'remoteKey'     => 'foftest_group_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                        'theirPivotKey' => 'foftest_group_id',
                        'ourPivotKey'   => 'foftest_part_id'
                    )
                )

            )
        );

        // Default usage of multiple relation, everything is "magically" set - relation name is plural
        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => null,
                    'localKey'      => null,
                    'ourPivot'      => null,
                    'theirPivot'    => null,
                    'remoteKey'     => null,
                    'glueTable'     => null,
                    'default'       => true,
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'groups',
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'remoteKey'     => 'foftest_group_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                        'theirPivotKey' => 'foftest_group_id',
                        'ourPivotKey'   => 'foftest_part_id'
                    )
                )

            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'group',
                    'tableClass'    => null,
                    'localKey'      => null,
                    'ourPivot'      => null,
                    'theirPivot'    => null,
                    'remoteKey'     => null,
                    'glueTable'     => null,
                    'default'       => false,
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'remoteKey'     => 'foftest_group_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                        'theirPivotKey' => 'foftest_group_id',
                        'ourPivotKey'   => 'foftest_part_id'
                    )
                )

            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'group',
                    'tableClass'    => 'FoftestTableTestgroup',
                    'localKey'      => 'foftest_local_id',
                    'ourPivot'      => 'foftest_ourpivot_id',
                    'theirPivot'    => 'foftest_theirpivot_id',
                    'remoteKey'     => 'foftest_remote_id',
                    'glueTable'     => '#__foftest_gluetable',
                    'default'       => false,
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableTestgroup',
                        'localKey'      => 'foftest_local_id',
                        'ourPivotKey'   => 'foftest_ourpivot_id',
                        'theirPivotKey' => 'foftest_theirpivot_id',
                        'remoteKey'     => 'foftest_remote_id',
                        'pivotTable'    => '#__foftest_gluetable',
                    )
                )

            )
        );

        return $data;
    }

    public static function getTestRemoveRelation()
    {
        // Try to remove only one relation, without supplying the type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove only one relation, supplying the type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove only one relation from the whole array, supplying the type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array(),        // I can simply ignore the contents, since they're not used
                        'foftest_parent_second' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent_second' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove only one relation, supplying the (wrong) type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => 'child',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove only one relation, without supplying the type, but it's marked as the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );

        // Try to remove only one relation, without supplying the type, but it's NOT marked as the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'another_relation',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'another_relation',
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );


        return $data;
    }

    public static function getTestClearRelations()
    {
        // Try to remove relations only from the parent group
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove relations only from the "wrong" type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'children',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Remove a relations that is the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );

        // Remove a relations that is NOT the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'children',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );

        return $data;
    }

    public static function getTestHasRelation()
    {
        // Check if this relation exists at all
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => true
            )
        );

        // Check if this relation exists at all (NO!)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'im_not_here',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => false
            )
        );

        // Check if this relation exists in the child namespace
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => 'child',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => true
            )
        );

        // Check if this relation exists in the wrong namespace
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetRelation()
    {
        // Search for a relation in all the namespaces
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => true
            )
        );

        // Search for a relation in a specific namespace (and it exists)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => 'child',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => true
            )
        );

        // Search for a relation in a specific namespace (and it DOESN'T exists)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => false
            )
        );

        // Search for a relation in all the namespaces and it DOESN'T exists
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'not_here',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetRelatedItem()
    {
        // Get existing related item (child)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => true
            )
        );

        // Get existing related item (parent)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => true
            )
        );

        // Get existing related item - wrong type (plural instead of singular)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_children',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => false
            )
        );

        // Get existing related item - wrong type (plural instead of singular)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_children',
                'type'      => 'children',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(),
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetRelatedItems()
    {
        // Get existing related item (children)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_children',
                'type'      => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                )
            ),
            array(
                'result' => true
            )
        );

        // Get existing related item (children)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_children',
                'type'      => 'children',
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                )
            ),
            array(
                'result' => true
            )
        );

        // Get existing related item (multiple)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_multiple',
                'type'      => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                )
            ),
            array(
                'result' => true
            )
        );

        // Get existing related item (multiple)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                )
            ),
            array(
                'result' => false
            )
        );

        // Get existing related item (multiple)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(
                        'foftest_parent' => array()
                    ),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                )
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(
                        'foftest_parent' => array()
                    ),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                )
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => 'child',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(
                        'foftest_parent' => array()
                    ),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetParent()
    {
        // Existing parent relation
        $data[] = array(
            array('table' => 'child'),
            array(
                'itemName'  => 'foftest_parent',
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => true
            )
        );

        // Existing default parent relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => true
            )
        );

        // Non-Existing parent relation
        $data[] = array(
            array('table' => 'child'),
            array(
                'itemName'  => 'wrong_parent',
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        // Non-Existing default parent relation
        $data[] = array(
            array('table' => 'child'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetChild()
    {
        // Existing relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_child',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => true
            )
        );

        // Existing default relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => 'foftest_child',
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => true
            )
        );

        // Non-Existing relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'wrong_child',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        // Non-Existing default parent relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetChildren()
    {
        // Existing relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_children',
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => true
            )
        );

        // Existing default relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => 'foftest_children',
                    'multiple'  => null,
                )
            ),
            array(
                'result' => true
            )
        );

        // Non-Existing relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'wrong_children',
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        // Non-Existing default parent relation
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(
                        'foftest_children' => array()
                    ),
                    'multiple'  => array(),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetSiblings()
    {
        $data[] = array(
            array('table' => 'child'),
            array(
                'itemName' => null
            ),
            array(
                'result' => true,
                'iterator' => array(
                    'tableClass' => 'FoftestTableChild',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id',
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'itemName' => 'parent'
            ),
            array(
                'result' => true,
                'iterator' => array(
                    'tableClass' => 'FoftestTableChild',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id',
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'itemName' => 'wrong_parent'
            ),
            array(
                'result'   => false,
                'iterator' => array()
            )
        );

        $data[] = array(
            array('table' => 'bare'),
            array(
                'itemName' => null
            ),
            array(
                'result'   => false,
                'iterator' => array()
            )
        );

        return $data;
    }

    public static function getTestGetMultiple()
    {
        // Existing relation
        $data[] = array(
            array('table' => 'part'),
            array(
                'itemName'  => 'foftest_multiple',
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => true
            )
        );

        // Existing default relation
        $data[] = array(
            array('table' => 'part'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => 'foftest_multiple',
                )
            ),
            array(
                'result' => true
            )
        );

        // Non-Existing relation
        $data[] = array(
            array('table' => 'part'),
            array(
                'itemName'  => 'wrong_multiple',
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        // Non-Existing default relation
        $data[] = array(
            array('table' => 'part'),
            array(
                'itemName'  => null,
                'relations' => array(
                    'child'     => array(),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(
                        'foftest_multiple' => array()
                    ),
                ),
                'default' => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestGetTableFromRelation()
    {
        // Try to load the parent
        $data[] = array(
            array('table' => 'child'),
            array(
                'loadid' => 2,
                'relation' => array(
                    'tableClass' => 'FoftestTableParent',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id'
                )
            ),
            array(
                'id' => 1
            )
        );

        // Try to load the first child (parent with only 1 child)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'loadid' => 2,
                'relation' => array(
                    'tableClass' => 'FoftestTableChild',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id'
                )
            ),
            array(
                'id' => 3
            )
        );

        // Try to load the first child (parent with several children)
        $data[] = array(
            array('table' => 'parent'),
            array(
                'loadid' => 1,
                'relation' => array(
                    'tableClass' => 'FoftestTableChild',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id'
                )
            ),
            array(
                'id' => 1
            )
        );

        return $data;
    }

    public static function getTestGetTableFromRelationInvalidArgs()
    {
        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array()
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'tableClass' => ''
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'tableClass' => '',
                    'localKey'   => ''
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'tableClass' => '',
                    'localKey'   => '',
                    'remoteKey'  => ''
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'tableClass' => 'SimplyWrong',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id'
                )
            )
        );

        return $data;
    }

    public static function getTestGetIteratorFromRelation()
    {
        // Try to load the children
        $data[] = array(
            array('table' => 'parent'),
            array(
                'loadid' => 1,
                'relation' => array(
                    'tableClass' => 'FoftestTableChild',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id'
                )
            ),
            array(
                'count' => 2
            )
        );

        // Parent with no children
        $data[] = array(
            array('table' => 'parent'),
            array(
                'loadid' => 3,
                'relation' => array(
                    'tableClass' => 'FoftestTableChild',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id'
                )
            ),
            array(
                'count' => 0
            )
        );

        // Pivot table - Parts and groups
        $data[] = array(
            array('table' => 'part'),
            array(
                'loadid' => 1,
                'relation' => array(
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_part_id',
                    'remoteKey'     => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                    'theirPivotKey' => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_part_id'
                )
            ),
            array(
                'count' => 2
            )
        );

        return $data;
    }

    public static function getTestGetIteratorFromRelationInvalidArgs()
    {
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array()
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'tableClass' => ''
                )
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'tableClass' => '',
                    'localKey'   => ''
                )
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'tableClass' => '',
                    'localKey'   => '',
                    'remoteKey'  => ''
                )
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'tableClass' => 'SimplyWrong',
                    'localKey'   => 'foftest_parent_id',
                    'remoteKey'  => 'foftest_parent_id'
                )
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'tableClass'  => 'SimplyWrong',
                    'localKey'    => 'foftest_parent_id',
                    'remoteKey'   => 'foftest_parent_id',
                    'pivotTable'  => '',
                )
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'tableClass'  => 'SimplyWrong',
                    'localKey'    => 'foftest_parent_id',
                    'remoteKey'   => 'foftest_parent_id',
                    'pivotTable'  => '',
                    'theirPivotKey'  => '',
                )
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'tableClass'  => 'SimplyWrong',
                    'localKey'    => 'foftest_parent_id',
                    'remoteKey'   => 'foftest_parent_id',
                    'pivotTable'  => '',
                    'theirPivotKey'  => '',
                    'ourPivotKey'    => '',
                )
            )
        );

        return $data;
    }

    public static function getTestAddBespokeSimpleRelation()
    {
        // Data remains the same
        $data[] = array(
            array('table' => 'parent'),
            array(
                'invoke' => array(
                   'relationType' => 'child',
                   'itemName'     => 'child',
                   'tableClass'   => 'FoftestTableChild',
                   'localKey'     => 'foftest_parent_id',
                   'remoteKey'    => 'foftest_parent_id',
                   'default'      => false,
                ),
                'process' => array(
                    'tableClass'   => 'FoftestTableChild',
                    'localKey'     => 'foftest_parent_id',
                    'remoteKey'    => 'foftest_parent_id'
                )
            ),
            array(
                'relations' => array(
                    'child' => array(
                        'tableClass'   => 'FoftestTableChild',
                        'localKey'     => 'foftest_parent_id',
                        'remoteKey'    => 'foftest_parent_id'
                    )
                ),
                'default' => null
            )
        );

        // Data is the same and it's the default
        $data[] = array(
            array('table' => 'parent'),
            array(
                'invoke' => array(
                    'relationType' => 'child',
                    'itemName'     => 'child',
                    'tableClass'   => 'FoftestTableChild',
                    'localKey'     => 'foftest_parent_id',
                    'remoteKey'    => 'foftest_parent_id',
                    'default'      => true,
                ),
                'process' => array(
                    'tableClass'   => 'FoftestTableChild',
                    'localKey'     => 'foftest_parent_id',
                    'remoteKey'    => 'foftest_parent_id'
                )
            ),
            array(
                'relations' => array(
                    'child' => array(
                        'tableClass'   => 'FoftestTableChild',
                        'localKey'     => 'foftest_parent_id',
                        'remoteKey'    => 'foftest_parent_id'
                    )
                ),
                'default' => 'child'
            )
        );

        // Data gets changed
        $data[] = array(
            array('table' => 'parent'),
            array(
                'invoke' => array(
                    'relationType' => 'child',
                    'itemName'     => 'child',
                    'tableClass'   => 'wrong_table',
                    'localKey'     => 'wrong_local',
                    'remoteKey'    => 'wrong_remote',
                    'default'      => false,
                ),
                'process' => array(
                    'tableClass'   => 'FoftestTableChild',
                    'localKey'     => 'foftest_parent_id',
                    'remoteKey'    => 'foftest_parent_id'
                )
            ),
            array(
                'relations' => array(
                    'child' => array(
                        'tableClass'   => 'FoftestTableChild',
                        'localKey'     => 'foftest_parent_id',
                        'remoteKey'    => 'foftest_parent_id'
                    )
                ),
                'default' => null
            )
        );

        return $data;
    }

    public static function getTestAddBespokePivotRelation()
    {
        // Data remains the same
        $data[] = array(
            array('table' => 'part'),
            array(
                'invoke' => array(
                    'relationType'  => 'multiple',
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableChild',
                    'localKey'      => 'foftest_parent_id',
                    'remoteKey'     => 'foftest_parent_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                    'theirPivotKey' => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_part_id',
                    'default'       => false,
                ),
                'process' => array(
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_part_id',
                    'remoteKey'     => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                    'theirPivotKey' => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_part_id'
                )
            ),
            array(
                'relations' => array(
                    'groups' => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'remoteKey'     => 'foftest_group_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                        'theirPivotKey' => 'foftest_group_id',
                        'ourPivotKey'   => 'foftest_part_id'
                    )
                ),
                'default' => null
            )
        );

        // Data is the same and it's the default
        $data[] = array(
            array('table' => 'part'),
            array(
                'invoke' => array(
                    'relationType'  => 'multiple',
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableChild',
                    'localKey'      => 'foftest_parent_id',
                    'remoteKey'     => 'foftest_parent_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                    'theirPivotKey' => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_part_id',
                    'default'       => true,
                ),
                'process' => array(
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_part_id',
                    'remoteKey'     => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                    'theirPivotKey' => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_part_id'
                )
            ),
            array(
                'relations' => array(
                    'groups' => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'remoteKey'     => 'foftest_group_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                        'theirPivotKey' => 'foftest_group_id',
                        'ourPivotKey'   => 'foftest_part_id'
                    )
                ),
                'default' => 'groups'
            )
        );

        // Data gets changed
        $data[] = array(
            array('table' => 'part'),
            array(
                'invoke' => array(
                    'relationType' => 'multiple',
                    'itemName'     => 'groups',
                    'tableClass'   => 'wrong_table',
                    'localKey'     => 'wrong',
                    'remoteKey'    => 'wrong',
                    'ourPivotKey'  => 'wrong',
                    'theirPivotKey'=> 'wrong',
                    'pivotTable'   => 'wrong',
                    'default'      => false,
                ),
                'process' => array(
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_part_id',
                    'remoteKey'     => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                    'theirPivotKey' => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_part_id'
                )
            ),
            array(
                'relations' => array(
                    'groups' => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'remoteKey'     => 'foftest_group_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                        'theirPivotKey' => 'foftest_group_id',
                        'ourPivotKey'   => 'foftest_part_id'
                    )
                ),
                'default' => null
            )
        );

        return $data;
    }

    public static function getTestNormaliseParameters()
    {
        // Supply nothin, everything is "magically" set
        $data[] = array(
            array('table' => 'child'),
            array(
                'pivot'         => false,
                'itemName'      => 'parent',
                'tableClass'    => null,
                'localKey'      => null,
                'remoteKey'     => null,
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'parent',
                    'tableClass'    => 'FoftestTableParent',
                    'localKey'      => 'foftest_parent_id',
                    'remoteKey'     => 'foftest_parent_id',
                    'ourPivotKey'   => null,
                    'theirPivotKey' => null,
                    'pivotTable'    => null,
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'pivot'         => false,
                'itemName'      => 'parent',
                'tableClass'    => 'FoftestTableCustom',
                'localKey'      => null,
                'remoteKey'     => null,
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'parent',
                    'tableClass'    => 'FoftestTableCustom',
                    'localKey'      => 'foftest_custom_id',
                    'remoteKey'     => 'foftest_custom_id',
                    'ourPivotKey'   => null,
                    'theirPivotKey' => null,
                    'pivotTable'    => null,
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'pivot'         => false,
                'itemName'      => 'parent',
                'tableClass'    => 'FoftestTableCustom',
                'localKey'      => 'foftest_customkey_id',
                'remoteKey'     => null,
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'parent',
                    'tableClass'    => 'FoftestTableCustom',
                    'localKey'      => 'foftest_customkey_id',
                    'remoteKey'     => 'foftest_customkey_id',
                    'ourPivotKey'   => null,
                    'theirPivotKey' => null,
                    'pivotTable'    => null,
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'pivot'         => false,
                'itemName'      => 'parent',
                'tableClass'    => 'FoftestTableCustom',
                'localKey'      => null,
                'remoteKey'     => 'foftest_customremote_id',
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'parent',
                    'tableClass'    => 'FoftestTableCustom',
                    'localKey'      => 'foftest_customremote_id',
                    'remoteKey'     => 'foftest_customremote_id',
                    'ourPivotKey'   => null,
                    'theirPivotKey' => null,
                    'pivotTable'    => null,
                )
            )
        );

        $data[] = array(
            array('table' => 'child'),
            array(
                'pivot'         => false,
                'itemName'      => 'parent',
                'tableClass'    => 'FoftestTableCustom',
                'localKey'      => 'foftest_customlocal_id',
                'remoteKey'     => 'foftest_customremote_id',
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'parent',
                    'tableClass'    => 'FoftestTableCustom',
                    'localKey'      => 'foftest_customlocal_id',
                    'remoteKey'     => 'foftest_customremote_id',
                    'ourPivotKey'   => null,
                    'theirPivotKey' => null,
                    'pivotTable'    => null,
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => null,
                'localKey'      => null,
                'remoteKey'     => null,
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_group_id',
                    'remoteKey'     => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_group_id',
                    'theirPivotKey' => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => 'FoftestTableGroup',
                'localKey'      => null,
                'remoteKey'     => null,
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_group_id',
                    'remoteKey'     => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_group_id',
                    'theirPivotKey' => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => 'FoftestTableGroup',
                'localKey'      => 'foftest_part_id',
                'remoteKey'     => null,
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_part_id',
                    'remoteKey'     => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_part_id',
                    'theirPivotKey' => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => 'FoftestTableGroup',
                'localKey'      => null,
                'remoteKey'     => 'foftest_group_id',
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_group_id',
                    'remoteKey'     => 'foftest_group_id',
                    'ourPivotKey'   => 'foftest_group_id',
                    'theirPivotKey' => 'foftest_group_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => 'FoftestTableGroup',
                'localKey'      => 'foftest_local_id',
                'remoteKey'     => 'foftest_remote_id',
                'ourPivotKey'   => null,
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_local_id',
                    'remoteKey'     => 'foftest_remote_id',
                    'ourPivotKey'   => 'foftest_local_id',
                    'theirPivotKey' => 'foftest_remote_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => 'FoftestTableGroup',
                'localKey'      => 'foftest_local_id',
                'remoteKey'     => 'foftest_remote_id',
                'ourPivotKey'   => 'foftest_ourkey_id',
                'theirPivotKey' => null,
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_local_id',
                    'remoteKey'     => 'foftest_remote_id',
                    'ourPivotKey'   => 'foftest_ourkey_id',
                    'theirPivotKey' => 'foftest_remote_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => 'FoftestTableGroup',
                'localKey'      => 'foftest_local_id',
                'remoteKey'     => 'foftest_remote_id',
                'ourPivotKey'   => 'foftest_ourkey_id',
                'theirPivotKey' => 'foftest_theirkey_id',
                'pivotTable'    => null,
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_local_id',
                    'remoteKey'     => 'foftest_remote_id',
                    'ourPivotKey'   => 'foftest_ourkey_id',
                    'theirPivotKey' => 'foftest_theirkey_id',
                    'pivotTable'    => '#__foftest_parts_groups',
                )
            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'pivot'         => true,
                'itemName'      => 'groups',
                'tableClass'    => 'FoftestTableGroup',
                'localKey'      => 'foftest_local_id',
                'remoteKey'     => 'foftest_remote_id',
                'ourPivotKey'   => 'foftest_ourkey_id',
                'theirPivotKey' => 'foftest_theirkey_id',
                'pivotTable'    => '#__pivot_table',
            ),
            array(
                'parameters' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => 'FoftestTableGroup',
                    'localKey'      => 'foftest_local_id',
                    'remoteKey'     => 'foftest_remote_id',
                    'ourPivotKey'   => 'foftest_ourkey_id',
                    'theirPivotKey' => 'foftest_theirkey_id',
                    'pivotTable'    => '#__pivot_table',
                )
            )
        );

        return $data;
    }

    public static function getTestNormaliseItemName()
    {
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'parent',
                'plural'   => false,
            ),
            array(
                'itemname' => 'parent'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'parents',
                'plural'   => false,
            ),
            array(
                'itemname' => 'parent'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'child',
                'plural'   => true,
            ),
            array(
                'itemname' => 'children'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'children',
                'plural'   => true,
            ),
            array(
                'itemname' => 'children'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'foftest_child',
                'plural'   => true,
            ),
            array(
                'itemname' => 'foftest_children'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'foftest_children',
                'plural'   => false,
            ),
            array(
                'itemname' => 'foftest_child'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'foftest_children',
                'plural'   => true,
            ),
            array(
                'itemname' => 'foftest_children'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'foftest_part_group',
                'plural'   => true,
            ),
            array(
                'itemname' => 'foftest_PartGroups'
            )
        );

        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName' => 'foftest_part_groups',
                'plural'   => false,
            ),
            array(
                'itemname' => 'foftest_PartGroup'
            )
        );

        return $data;
    }
}
