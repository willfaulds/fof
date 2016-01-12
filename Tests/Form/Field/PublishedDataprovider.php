<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class PublishedDataprovider
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

    public static function getTestGetStatic()
    {
        $data[] = array(
            'input' => array(
                'value' => ''
            ),
            'check' => array(
                'case'   => 'No value set',
                'result' => '<span id="foo_id"  class="foo-class">Unpublished</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'value' => 1
            ),
            'check' => array(
                'case'   => 'Value set',
                'result' => '<span id="foo_id"  class="foo-class">Published</span>'
            )
        );

        return $data;
    }

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(
                'attribs' => array()
            ),
            'check' => array(
                'case'   => '',
                'result' => <<<HTML
<a class="btn btn-micro hasTooltip" href="javascript:void(0);" onclick="return listItemTask('cb2','publish')" title="JLIB_HTML_PUBLISH_ITEM"><i class="icon-unpublish"></i></a>
HTML

            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'prefix' => 'foo',
                    'checkbox' => 'dd',
                    'publish_up' => '2015-08-05',
                    'publish_down' => '2015-08-10'
                )
            ),
            'check' => array(
                'case'   => '',
                'result' => <<<HTML
<a class="btn btn-micro hasTooltip" href="javascript:void(0);" onclick="return listItemTask('dd2','foopublish')" title="JLIB_HTML_PUBLISH_ITEM&lt;br /&gt;JLIB_HTML_PUBLISHED_START&lt;br /&gt;JLIB_HTML_PUBLISHED_FINISHED"><i class="icon-unpublish"></i></a>
HTML

            )
        );

        return $data;
    }
}
