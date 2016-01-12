<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class ColorDataprovider
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
                'legacy' => true,
                'value'  => '',
                'class'  => ''
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'input'    => 1,
                'result'   => ''
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false,
                'value'  => '#000000',
                'class'  => 'foo-class'
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'result'   => '<div class="foo foo-class" style="width:20px; height:20px; background-color:#000000;"></div>'
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false,
                'value'  => '000000',
                'class'  => ''
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute (2)',
                'input'    => 0,
                'result'   => '<div class="foo " style="width:20px; height:20px; background-color:#000000;"></div>'
            )
        );

        return $data;
    }
}
