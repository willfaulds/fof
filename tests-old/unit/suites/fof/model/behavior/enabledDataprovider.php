<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  TableBehaviors
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class enabledDataprovider
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
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Apply filtering
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('enabled').' = '.$db->q(1));
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend' => true,
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Table with alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('fo_enabled').' = '.$db->q(1));
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'frontend' => true,
                'query'    => $test,
                'aliases'  => array(
                    'enabled' => 'fo_enabled'
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
                'query'    => $test
            ),
            array('query' => $check)
        );

        return $data;
    }

    public static function getTestOnAfterGetItem()
    {
        // Record enabled
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1
            ),
            array('nullify' => false)
        );

        // Record NOT enabled
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 2
            ),
            array('nullify' => true)
        );

        // Record enabled - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'aliases' => array(
                    'tbl_key'  => 'id_foobar_aliases',
                    'enabled'  => 'fo_enabled'
                )
            ),
            array('nullify' => false)
        );

        // Record NOT enabled - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 2,
                'aliases' => array(
                    'tbl_key'  => 'id_foobar_aliases',
                    'enabled'  => 'fo_enabled'
                )
            ),
            array('nullify' => true)
        );

        // Table with no enabled support
        $data[] = array(
            array('name' => 'bares'),
            array(
                'loadid' => 1,
            ),
            array('nullify' => false)
        );

        return $data;
    }
}