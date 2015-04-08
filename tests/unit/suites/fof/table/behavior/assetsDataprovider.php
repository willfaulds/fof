<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  TableBehaviors
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class assetsDataprovider
{
    public static function getTestOnAfterStore()
    {
        // Without asset support
        $data[] = array(
            array(
                'name' => 'bare',
            ),
            array(),
            array('return' => true, 'count' => 0)
        );

        // Asset support but no rules passed
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array('id' => 2),
            array('return' => true, 'count' => 1, 'rules' => '')
        );

        // Asset support with rules
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array(
                'id'    => 2,
                'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
            ),
            array('return' => true, 'count' => 1, 'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}')
        );

        // Asset support with rules - table with aliases
        $data[] = array(
            array(
                'name' => 'foobaraliases',
            ),
            array(
                'id'      => 2,
                'tbl_key' => 'id_foobar_aliases',
                'alias'   => array(
                    'asset_id' => 'fo_asset_id',
                ),
                'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
            ),
            array('return' => true, 'count' => 1, 'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}')
        );

        return $data;
    }

    public static function getTestOnAfterBind()
    {
        // Without asset support
        $data[] = array(
            array(
                'name' => 'bare',
            ),
            array('bind' => ''),
            array('return' => true, 'rules' => '')
        );

        // With asset support - no rules
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array('bind' => ''),
            array('return' => true, 'rules' => '')
        );

        // With asset support - with rules with empty values
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array(
                'bind' => array(
                    'rules' => array(
                        'core.delete'     => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 => '', 5 => '', 8 => ''),
                        'core.edit'       => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 =>  1, 5 => '', 8 => ''),
                        'core.edit.state' => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 => '', 5 =>  1, 8 => ''),
                        'core.edit.own'   => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 =>  1, 4 => '', 5 => '', 8 => '')
                    )
                )
            ),
            array('return' => true, 'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}')
        );

        // With asset support - with rules without empty values
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array(
                'bind' => array(
                    'rules' => array(
                        'core.delete'     => array(6 => 1),
                        'core.edit'       => array(6 => 1, 4 =>  1),
                        'core.edit.state' => array(6 => 1, 5 =>  1),
                        'core.edit.own'   => array(6 => 1, 3 =>  1)
                    )
                )
            ),
            array('return' => true, 'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}')
        );

        return $data;
    }

    public static function getTestOnBeforeDelete()
    {
        // Without asset support
        $data[] = array(
            array(
                'name' => 'bare',
            ),
            array(),
            array('return' => true, 'count' => 0)
        );

        // With asset support - not loaded
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array(),
            array('return' => false, 'count' => 0)
        );

        // With asset support - loaded no asset
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array('loadid' => 2),
            array('return' => true, 'count' => 0)
        );

        // With asset support - loaded with asset
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array('loadid' => 4),
            array('return' => true, 'count' => 1)
        );

        // With asset support - loaded (using ID) with asset
        $data[] = array(
            array(
                'name' => 'foobar',
            ),
            array('id' => 4),
            array('return' => true, 'count' => 1)
        );

        // With asset support - loaded (using ID) with asset
        $data[] = array(
            array(
                'name' => 'foobaraliases',
            ),
            array(
                'id'      => 2,
                'tbl_key' => 'id_foobar_aliases',
                'alias'   => array(
                    'asset_id' => 'fo_asset_id',
                )
            ),
            array('return' => true, 'count' => 0)
        );

        return $data;
    }
}