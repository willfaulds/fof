<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class CheckboxDataprovider
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
                'attribs' => array(),
                'options' => array(),
                'value'   => ''
            ),
            'check' => array(
                'case'   => 'No field options or additional options',
                'result' => '<span class=""><input type="checkbox" name="" class=" " value="1" /></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(),
                'options' => array(
                    'id' => 'foo',
                    'class' => 'foo-class'
                ),
                'value'   => ''
            ),
            'check' => array(
                'case'   => 'No field options, with additional options',
                'result' => '<span id="foo" class=" foo-class"><input type="checkbox" name="" id="foo" class="  foo-class" value="1" /></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'disabled'  => true,
                    'required'  => true,
                    'autofocus' => true,
                    'checked'   => true,
                    'onchange'  => '__ON_CHANGE__',
                    'onclick'   => '__ON_CLICK__'
                ),
                'options' => array(),
                'value'   => '2'
            ),
            'check' => array(
                'case'   => 'With field options, no additional options',
                'result' => '<span class=""><input type="checkbox" name="" class=" " value="2" checked disabled="disabled" onclick="__ON_CLICK__" onchange="__ON_CHANGE__" required="required" aria-required="true" autofocus /></span>'
            )
        );

        return $data;
    }
}
