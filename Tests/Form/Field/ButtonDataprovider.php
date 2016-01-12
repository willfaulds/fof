<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class ButtonDataprovider
{
    public static function getTestGetInput()
    {
        $data[] = array(
            'input' => array(
                'xml'     => '<field type="button" text="xml_text" />',
                'id'      => 'foobar',
                'class'   => '',
                'onclick' => '',
                'value'   => 'value'
            ),
            'check' => array(
                'case'   => 'Do not use field value',
                'result' => '<button id="foobar" class="btn " >xml_text</button>'
            )
        );

        $data[] = array(
            'input' => array(
                'xml'     => '<field type="button" text="xml_text" icon="test-icon" url="test-url" title="test-title" use_value="true" />',
                'id'      => 'foobar',
                'class'   => 'foobar-class',
                'onclick' => '__ONCLICK__',
                'value'   => 'value'
            ),
            'check' => array(
                'case'   => 'Passing all attributes',
                'result' => '<button id="foobar" class="btn foobar-class" onclick="__ONCLICK__" href="__FAKE_URL__" title="test-title" ><span class="icon test-icon"></span> value</button>'
            )
        );

        $data[] = array(
            'input' => array(
                'xml'     => '<field type="button" htmlelement="button" text="xml_text" />',
                'id'      => 'foobar',
                'class'   => '',
                'onclick' => '',
                'value'   => 'value',
                'use_value' => true
            ),
            'check' => array(
                'case'   => 'Passing HTML element - button',
                'result' => '<button id="foobar" class="btn " >xml_text</button>'
            )
        );

        $data[] = array(
            'input' => array(
                'xml'     => '<field type="button" htmlelement="a" text="xml_text" />',
                'id'      => 'foobar',
                'class'   => '',
                'onclick' => '',
                'value'   => 'value',
                'use_value' => true
            ),
            'check' => array(
                'case'   => 'Passing HTML element - a',
                'result' => '<a id="foobar" class="btn " >xml_text</a>'
            )
        );

        $data[] = array(
            'input' => array(
                'xml'     => '<field type="button" htmlelement="textarea" text="xml_text" />',
                'id'      => 'foobar',
                'class'   => '',
                'onclick' => '',
                'value'   => 'value',
                'use_value' => true
            ),
            'check' => array(
                'case'   => 'Passing HTML element - not allowed tag',
                'result' => '<button id="foobar" class="btn " >xml_text</button>'
            )
        );

        return $data;
    }
}
