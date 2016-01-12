<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class ActionsDataprovider
{
    public static function getTest__get()
    {
        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, not cached',
                'static' => 1,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => 'cached',
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, not cached',
                'static' => 0,
                'repeat' => 1
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => 'cached'
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        return $data;
    }

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(
                'id'          => 'foftest_foobar_id',
                'table'       => '#__foftest_foobars',
                'enabled'     => 2,
                'published'   => 1,
                'unpublished' => 1,
                'archived'    => 1,
                'trash'       => 1,
                'all'         => 1,
            ),
            'check' => array(
                'case'   => 'Table with publish support, record archived',
                'result' => '<div class="btn-group">__FAKE_PUBLISH__<button data-toggle="dropdown" class="dropdown-toggle btn btn-micro"><span class="caret"></span></button><ul class="dropdown-menu"><li><a href = "javascript://" onclick="listItemTask(\'cb\', \'unpublish\')"><span class="icon-unarchive"></span> JTOOLBAR_UNARCHIVE</a></li><li><a href = "javascript://" onclick="listItemTask(\'cb\', \'trash\')"><span class="icon-trash"></span> JTOOLBAR_TRASH</a></li></ul></div>',
                'publishField' => 1
            )
        );

        $data[] = array(
            'input' => array(
                'id'          => 'foftest_foobar_id',
                'table'       => '#__foftest_foobars',
                'enabled'     => -2,
                'published'   => 1,
                'unpublished' => 1,
                'archived'    => 1,
                'trash'       => 1,
                'all'         => 1,
            ),
            'check' => array(
                'case'   => 'Table with publish support, record trashed',
                'result' => '<div class="btn-group">__FAKE_PUBLISH__<button data-toggle="dropdown" class="dropdown-toggle btn btn-micro"><span class="caret"></span></button><ul class="dropdown-menu"><li><a href = "javascript://" onclick="listItemTask(\'cb\', \'archive\')"><span class="icon-archive"></span> JTOOLBAR_ARCHIVE</a></li><li><a href = "javascript://" onclick="listItemTask(\'cb\', \'publish\')"><span class="icon-publish"></span> JTOOLBAR_PUBLISH</a></li></ul></div>',
                'publishField' => 1
            )
        );

        $data[] = array(
            'input' => array(
                'id'          => 'foftest_foobar_id',
                'table'       => '#__foftest_foobars',
                'enabled'     => -2,
                'published'   => 0,
                'unpublished' => 0,
                'archived'    => 0,
                'trash'       => 0,
                'all'         => 0,
            ),
            'check' => array(
                'case'   => 'Table with publish support, do not display anything',
                'result' => '<div class="btn-group"></div>',
                'publishField' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'id'          => 'foftest_bare_id',
                'table'       => '#__foftest_bares',
                'enabled'     => 0,
                'published'   => 0,
                'unpublished' => 0,
                'archived'    => 0,
                'trash'       => 0,
                'all'         => 0,
            ),
            'check' => array(
                'case'   => 'Table with no publish support',
                'result' => '<div class="btn-group"></div>',
                'publishField' => 0
            )
        );

        return $data;
    }
}
