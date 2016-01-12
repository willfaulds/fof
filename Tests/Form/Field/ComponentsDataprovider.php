<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class ComponentsDataprovider
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

    public static function getTestGetFieldContents()
    {
        $data[] = array(
            'input' => array(
                'value'   => '',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Empty value, no additional field options',
                'result' => '<span class="foo-class">Foobar</span>',
            )
        );

        $data[] = array(
            'input' => array(
                'value'   => 'dummy',
                'options' => array(
                    'id' => 'foo-id',
                    'class' => 'extra-class'
                )
            ),
            'check' => array(
                'case'   => 'With value and additional field options',
                'result' => '<span id="foo-id" class="foo-class extra-class">Dummy</span>',
            )
        );

        return $data;
    }

    public static function getTestTranslate()
    {
        $data[] = array(
            'input' => array(
                'manifest' => array(),
                'type' => 'plugin'
            ),
            'check' => array(
                'case'     => 'Trying to load a "plugin" extension',
                'result'   => 'FOFTEST',
                'language' => array()
            )
        );

        $data[] = array(
            'input' => array(
                'manifest' => array(
                    'element' => 'manifest_element',
                    'name'    => 'MANIFEST_NAME'
                ),
                'type' => 'component'
            ),
            'check' => array(
                'case'     => 'Trying to load a "component" extension',
                'result'   => 'MANIFEST_NAME',
                'language' => array (
                    0 =>
                        array (
                            0 => 'manifest_element.sys',
                            1 => '__ROOT__/administrator',
                            2 => NULL,
                            3 => false,
                            4 => false,
                        ),
                    1 =>
                        array (
                            0 => 'manifest_element.sys',
                            1 => '__ROOT__/administrator/components/manifest_element',
                            2 => NULL,
                            3 => false,
                            4 => false,
                        ),
                    2 =>
                        array (
                            0 => 'manifest_element.sys',
                            1 => '__ROOT__/administrator',
                            2 => 'en-GB',
                            3 => false,
                            4 => false,
                        ),
                    3 =>
                        array (
                            0 => 'manifest_element.sys',
                            1 => '__ROOT__/administrator/components/manifest_element',
                            2 => 'en-GB',
                            3 => false,
                            4 => false,
                        ),
                )
            )
        );

        return $data;
    }
}
