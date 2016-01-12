<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class RawDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isCli' => false
                )
            ),
            array(
                'case' => 'We are not in CLI',
                'permissions' => (object)array(
                    'create' => false,
                    'edit' => false,
                    'editown' => false,
                    'editstate' => false,
                    'delete' => false,
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isCli' => true
                )
            ),
            array(
                'case' => 'We are in CLI',
                'permissions' => (object)array(
                    'create' => true,
                    'edit' => true,
                    'editown' => true,
                    'editstate' => true,
                    'delete' => true,
                )
            )
        );

        return $data;
    }
}