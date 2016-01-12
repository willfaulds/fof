<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class EmailDataprovider
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
                'legacy' => true
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'input'    => 1,
                'contents' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'contents' => 1
            )
        );

        return $data;
    }

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(
                'legacy' => true
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'input'    => 1,
                'contents' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'contents' => 1
            )
        );

        return $data;
    }

    public static function getTestGetFieldContents()
    {
        $data[] = array(
            'input' => array(
                'options'    => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class'
                ),
                'attribs'    => array(),
                'properties' => array(
                    'class' => 'field-class',
                    'value' => 'test@example.com'
                )
            ),
            'check' => array(
                'case'   => 'Passing additional field options',
                'result' => '<span id="foo-id" class="field-class foo-class">test@example.com</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attribs'    => array(
                    'empty_replacement' => 'replace empty'
                ),
                'properties' => array(
                    'value' => ''
                )
            ),
            'check' => array(
                'case'   => 'Empty replacement',
                'result' => '<span class="">replace empty</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attribs'    => array(
                    'show_link' => true
                ),
                'properties' => array(
                    'value' => 'test@example.com'
                )
            ),
            'check' => array(
                'case'   => 'Display the link',
                'result' => '<span class=""><a href="mailto:test@example.com">test@example.com</a></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attribs'    => array(
                    'show_link' => true,
                    'url' => 'fake url'
                ),
                'properties' => array(
                    'value' => 'test@example.com'
                )
            ),
            'check' => array(
                'case'   => 'Display the link, url passed in the XML field',
                'result' => '<span class=""><a href="mailto:__PARSED__">test@example.com</a></span>'
            )
        );

        return $data;
    }

    public static function getTestParseFieldTags()
    {
        $data[] = array(
            'input' => array(
                'load'   => false,
                'assign' => false,
                'text'   => '__ID:[ITEM:ID]__ __ITEMID:[ITEMID]__ __TOKEN:[TOKEN]__ __TITLE:[ITEM:TITLE]__'
            ),
            'check' => array(
                'case' => 'Record no assigned and not loaded',
                'result' => '__ID:__ __ITEMID:100__ __TOKEN:_FAKE_SESSION___ __TITLE:__'
            )
        );

        $data[] = array(
            'input' => array(
                'load'   => 1,
                'assign' => false,
                'text'   => '__ID:[ITEM:ID]__ __ITEMID:[ITEMID]__ __TOKEN:[TOKEN]__ __TITLE:[ITEM:TITLE]__'
            ),
            'check' => array(
                'case' => 'Record no assigned and loaded',
                'result' => '__ID:1__ __ITEMID:100__ __TOKEN:_FAKE_SESSION___ __TITLE:Guinea Pig row__'
            )
        );

        $data[] = array(
            'input' => array(
                'load'   => 1,
                'assign' => true,
                'text'   => '__WRONG:[ITEM:WRONG]__'
            ),
            'check' => array(
                'case' => 'Record assigned and loaded, field not existing',
                'result' => '__WRONG:[ITEM:WRONG]__'
            )
        );

        return $data;
    }
}