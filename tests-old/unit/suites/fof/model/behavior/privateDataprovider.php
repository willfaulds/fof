<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  TableBehaviors
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class privateDataprovider
{
    public static function getTestOnAfterBuildQuery()
    {
        $db = JFactory::getDbo();

        // On frontend no filtering
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend' => false,
                'user'     => 42,
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Apply filtering
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend' => true,
                'user'     => 42,
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Apply filtering with table alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')
                     ->where($db->qn('dummy').'.'.$db->qn('created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend'    => true,
                'user'        => 42,
                'table_alias' => 'dummy',
                'query'       => $test
            ),
            array('query' => $check)
        );

        // Table with column alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('fo_created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'frontend'  => true,
                'user'      => 42,
                'query'     => $test,
                'aliases'   => array(
                    'created_by' => 'fo_created_by'
                )
            ),
            array('query' => $check)
        );

        // Table with column alias and table alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')
                     ->where($db->qn('dummy').'.'.$db->qn('fo_created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'frontend'    => true,
                'user'        => 42,
                'table_alias' => 'dummy',
                'query'       => $test,
                'aliases'     => array(
                    'created_by' => 'fo_created_by'
                )
            ),
            array('query' => $check)
        );

        // No table support
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $data[] = array(
            array('name' => 'bares'),
            array(
                'frontend' => true,
                'user'     => 42,
                'query'    => $test
            ),
            array('query' => $check)
        );

        return $data;
    }

    public static function getTestOnAfterGetItem()
    {
        // Record not loaded
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'user' => 42
            ),
            array('nullify' => false)
        );

        // Record of the creator
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1,
                'user'   => 42
            ),
            array('nullify' => false)
        );

        // Not record of the creator
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1,
                'user'   => 43
            ),
            array('nullify' => true)
        );

        // Record not loaded - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'user'   => 42,
                'aliases' => array(
                    'tbl_key'     => 'id_foobar_aliases',
                    'created_by'  => 'fo_created_by'
                )
            ),
            array('nullify' => false)
        );

        // Record of the creater - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'user'   => 42,
                'aliases' => array(
                    'tbl_key'     => 'id_foobar_aliases',
                    'created_by'  => 'fo_created_by'
                )
            ),
            array('nullify' => false)
        );

        // Not record of the creator - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'user'   => 43,
                'aliases' => array(
                    'tbl_key'     => 'id_foobar_aliases',
                    'created_by'  => 'fo_created_by'
                )
            ),
            array('nullify' => true)
        );

        // Table with no created_by support
        $data[] = array(
            array('name' => 'bares'),
            array(
                'loadid' => 1,
                'user'   => 42,
            ),
            array('nullify' => false)
        );

        return $data;
    }
}