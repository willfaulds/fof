<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class AssetsDataprovider
{
    public static function getTestOnAfterSave()
    {
        $data[] = array(
            array(
                'tableid' => 'foftest_bare_id',
                'table'   => '#__foftest_bares',
                'load'    => 0,
                'track'   => false,
                'rules'   => null
            ),
            array(
                'case'   => 'Without asset support',
                'count'  => 0,
                'rules'  => null
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars',
                'load'    => 0,
                'track'   => false,
                'rules'   => null
            ),
            array(
                'case'  => 'With asset support but no asset tracking',
                'count' => 0,
                'rules' => null
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars',
                'load'    => 2,
                'track'   => true,
                'rules'   => null
            ),
            array(
                'case'  => 'Asset support but no rules passed',
                'count' => 1,
                'rules' => ''
            )
        );

        $data[] = array(
            array(
                'tableid' => 'foftest_foobar_id',
                'table'   => '#__foftest_foobars',
                'load'    => 2,
                'track'   => true,
                'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
            ),
            array(
                'case'  => 'Asset support with rules',
                'count' => 1,
                'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
            )
        );

        // Asset support with rules - table with aliases
        /*$data[] = array(
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
        );*/

        return $data;
    }

    public static function getTestOnAfterBind()
    {
        $data[] = array(
            array(
                'table'   => '#__foftest_foobars',
                'tableid' => 'foftest_foobar_id',
                'bind'    => '',
                'load'    => 0,
                'track'   => false
            ),
            array(
                'case'  => 'Without asset tracking',
                'rules' => ''
            )
        );

        $data[] = array(
            array(
                'table'   => '#__foftest_foobars',
                'tableid' => 'foftest_foobar_id',
                'bind'    => '',
                'load'    => 0,
                'track'   => true
            ),
            array(
                'case'  => 'With asset tracking, no rules',
                'rules' => ''
            )
        );

        $data[] = array(
            array(
                'table'   => '#__foftest_foobars',
                'tableid' => 'foftest_foobar_id',
                'bind' => array(
                    'rules' => array(
                        'core.delete'     => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 => '', 5 => '', 8 => ''),
                        'core.edit'       => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 =>  1, 5 => '', 8 => ''),
                        'core.edit.state' => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 => '', 5 =>  1, 8 => ''),
                        'core.edit.own'   => array(1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 =>  1, 4 => '', 5 => '', 8 => '')
                    )
                ),
                'load'    => 0,
                'track'   => true
            ),
            array(
                'case'  => 'With asset tracking, rules with empty values',
                'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
            )
        );

        $data[] = array(
            array(
                'table'   => '#__foftest_foobars',
                'tableid' => 'foftest_foobar_id',
                'bind' => array(
                    'rules' => array(
                        'core.delete'     => array(6 => 1),
                        'core.edit'       => array(6 => 1, 4 =>  1),
                        'core.edit.state' => array(6 => 1, 5 =>  1),
                        'core.edit.own'   => array(6 => 1, 3 =>  1)
                    )
                ),
                'load'    => 0,
                'track'   => true
            ),
            array(
                'case'  => 'With asset tracking, rules without empty values',
                'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
            )
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