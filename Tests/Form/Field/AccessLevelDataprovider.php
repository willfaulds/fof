<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class AccessLevelDataprovider
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
                'mock' => array(
                    'options' => array()
                ),
                'value'   => '2',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'No default options or field options',
                'result' => '<span class="">Registered</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'options' => array()
                ),
                'value'   => '2',
                'options' => array(
                    'id' => 'foobar',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'With field options',
                'result' => '<span id="foobar" class=" foo-class">Registered</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'options' => true
                ),
                'value'   => '',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'With "All" option',
                'result' => '<span class="">JOPTION_ACCESS_SHOW_ALL_LEVELS</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'options' => false
                ),
                'value'   => '2',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'With "All" option',
                'result' => '<span class="">Registered</span>'
            )
        );

        return $data;
    }
}
