<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class CheckboxesDataprovider
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
                'options'   => array(),
                'translate' => false,
                'value'     => array()
            ),
            'check' => array(
                'case'   => 'Empty values, no translate, no field options',
                'result' => '<span class=""></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'options'   => array(
                    'id' => 'foo',
                    'class' => 'foo-class'
                ),
                'translate' => false,
                'value'     => array('JYES', 5)
            ),
            'check' => array(
                'case'   => 'With values, no translate, with field options',
                'result' => '<span id="foo" class=" foo-class"><span>JYES</span><span>5</span></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'options'   => array(
                    'id' => 'foo',
                    'class' => 'foo-class'
                ),
                'translate' => true,
                'value'     => array('JYES', 5)
            ),
            'check' => array(
                'case'   => 'With values, with translate, with field options',
                'result' => '<span id="foo" class=" foo-class"><span>Yes</span><span>5</span></span>'
            )
        );

        return $data;
    }
}
