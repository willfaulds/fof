<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class LanguageFieldDataprovider
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
                'value'   => 'en-GB',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'No default options or field options',
                'result' => '<span class="">English</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'value'   => 'en-GB',
                'options' => array(
                    'id' => 'foobar',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'With field options',
                'result' => '<span id="foobar" class=" foo-class">English</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'value'   => '',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Empty value',
                'result' => '<span class="">Italian</span>'
            )
        );

        return $data;
    }

    public static function getTestGetOptions()
    {
        $data[] = array(
            'input' => array(
                'mock' => array(
                    'cache' => array()
                ),
                'attribs' => array()
            ),
            'check' => array(
                'case' => 'Empty cache, no XML attribs',
                'result' => array (
                    array ('text' => 'English (en-GB)', 'value' => 'en-GB'),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'cache' => array(
                        'site' => array(
                            array ( 'text' => 'Cached','value' => 'cached')
                        )
                    )
                ),
                'attribs' => array()
            ),
            'check' => array(
                'case' => 'Cached request, no XML attribs',
                'result' => array (
                    array ('text' => 'Cached', 'value' => 'cached')
                )
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'cache' => array()
                ),
                'attribs' => array(
                    'client' => 'administrator',
                    'none'   => 'All languages'
                )
            ),
            'check' => array(
                'case' => 'Empty cache, asking for the admin area, with "All" options',
                'result' => array (
                    (object) array ('text' => 'All languages', 'value' => '*', 'disable' => false),
                    array ('text' => 'English (en-GB)', 'value' => 'en-GB'),
                )
            )
        );

        return $data;
    }
}
