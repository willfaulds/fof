<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class ModifiedDataprovider
{
    public static function getTestOnBeforeUpdate()
    {
        $data[] = array(
            'input' => array(
                'tableid'     => 'foftest_foobar_id',
                'table'       => '#__foftest_foobars',
                'locked'      => false,
                'modified_on' => null,
                'modified_by' => null,
                'aliases'     => array()
            ),
            'check' => array(
                'case' => 'Datetime and user fields empty',
                'modified_on' => true,
                'modified_by' => 99
            )
        );

        $data[] = array(
            'input' => array(
                'tableid'     => 'foftest_foobar_id',
                'table'       => '#__foftest_foobars',
                'locked'      => false,
                'modified_on' => '2015-07-13 15:09:00',
                'modified_by' => 88,
                'aliases'     => array()
            ),
            'check' => array(
                'case' => 'Datetime and user are not empty',
                'modified_on' => true,
                'modified_by' => 99
            )
        );

        $data[] = array(
            'input' => array(
                'tableid'     => 'id_foobar_aliases',
                'table'       => '#__foftest_foobaraliases',
                'locked'      => false,
                'modified_on' => null,
                'modified_by' => null,
                'aliases'     => array(
                    'modified_on' => 'fo_modified_on',
                    'modified_by' => 'fo_modified_by'
                )
            ),
            'check' => array(
                'case' => 'No datetime and user on table with aliases',
                'modified_on' => true,
                'modified_by' => 99
            )
        );

        $data[] = array(
            'input' => array(
                'tableid'     => 'foftest_bare_id',
                'table'       => '#__foftest_bares',
                'locked'      => false,
                'modified_on' => null,
                'modified_by' => null,
                'aliases'     => array()
            ),
            'check' => array(
                'case' => 'Table with no modified_on/by support',
                'modified_on' => false,
                'modified_by' => false
            )
        );

        $data[] = array(
            'input' => array(
                'tableid'     => 'foftest_foobar_id',
                'table'       => '#__foftest_foobars',
                'locked'      => true,
                'modified_on' => '2015-07-13 15:09:00',
                'modified_by' => 88,
                'aliases'     => array()
            ),
            'check' => array(
                'case' => 'Record is locked',
                'modified_on' => false,
                'modified_by' => false
            )
        );

        return $data;
    }
}