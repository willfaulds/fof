<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class GenericListDataprovider
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
                'options'  => 0,
                'result'   => ''
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'options'  => 1,
                'result'   => '<span id="foo" >Foobar</span>'
            )
        );

        return $data;
    }

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(
                'item'    => false,
                'attribs' => array(
                    'legacy' => true,
                    'url'    => false
                ),
                'properties' => array()
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'result'   => '',
                'input'    => 1,
                'options'  => 0
            )
        );

        $data[] = array(
            'input' => array(
                'item'    => false,
                'attribs' => array(
                    'legacy' => false,
                    'url'    => false
                ),
                'properties' => array(
                    'id'    => 'foo',
                    'value' => 'foo',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'result'   => '<span class="foo foo-class">Foobar</span>',
                'input'    => 0,
                'options'  => 1
            )
        );

        $data[] = array(
            'input' => array(
                'item'    => false,
                'attribs' => array(
                    'legacy' => false,
                    'url'    => true
                ),
                'properties' => array(
                    'id'    => 'foo',
                    'value' => 'foo',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'     => 'Using the link (no item loaded)',
                'result'   => '<span class="foo foo-class">Foobar</span>',
                'input'    => 0,
                'options'  => 1
            )
        );

        $data[] = array(
            'input' => array(
                'item'    => true,
                'attribs' => array(
                    'legacy' => false,
                    'url'    => true
                ),
                'properties' => array(
                    'id'    => 'foo',
                    'value' => 'foo',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'     => 'Using the link (item loaded)',
                'result'   => '<span class="foo foo-class"><a href="__PARSED__">Foobar</a></span>',
                'input'    => 0,
                'options'  => 1
            )
        );

        return $data;
    }

    public static function getTestGetOptionName()
    {
        $data[] = array(
            'input' => array(
                'data'     => array(
                    array('value' => 'first', 'text' => 'First item'),
                    array('value' => 'second', 'text' => 'Second item'),
                ),
                'selected' => '',
                'optkey'   => 'value',
                'opttext'  => 'text'
            ),
            'check' => array(
                'case'   => 'Array of arrays, no value selected',
                'result' => 'First item'
            )
        );

        $data[] = array(
            'input' => array(
                'data'     => array(
                    array('value' => 'first', 'text' => 'First item'),
                    array('value' => 'second', 'text' => 'Second item'),
                ),
                'selected' => 'second',
                'optkey'   => 'value',
                'opttext'  => 'text'
            ),
            'check' => array(
                'case'   => 'Array of arrays, value selected',
                'result' => 'Second item'
            )
        );

        $data[] = array(
            'input' => array(
                'data'     => array(
                    (object) array('value' => 'first', 'text' => 'First item'),
                    (object) array('value' => 'second', 'text' => 'Second item'),
                ),
                'selected' => '',
                'optkey'   => 'value',
                'opttext'  => 'text'
            ),
            'check' => array(
                'case'   => 'Array of objects, no value selected',
                'result' => 'First item'
            )
        );

        $data[] = array(
            'input' => array(
                'data'     => array(
                    (object) array('value' => 'first', 'text' => 'First item'),
                    (object) array('value' => 'second', 'text' => 'Second item'),
                ),
                'selected' => 'second',
                'optkey'   => 'value',
                'opttext'  => 'text'
            ),
            'check' => array(
                'case'   => 'Array of objects, value selected',
                'result' => 'Second item'
            )
        );

        $data[] = array(
            'input' => array(
                'data'     => array(
                    'first'  => 'First item',
                    'second' => 'Second item',
                ),
                'selected' => '',
                'optkey'   => 'value',
                'opttext'  => 'text'
            ),
            'check' => array(
                'case'   => 'Simple associative array, no value selected',
                'result' => 'First item'
            )
        );

        $data[] = array(
            'input' => array(
                'data'     => array(
                    'first'  => 'First item',
                    'second' => 'Second item',
                ),
                'selected' => 'second',
                'optkey'   => 'value',
                'opttext'  => 'text'
            ),
            'check' => array(
                'case'   => 'Simple associative array, value selected',
                'result' => 'Second item'
            )
        );

        return $data;
    }

    public static function getTestGetOptions()
    {
        $data[] = array(
            'input' => array(
                'attribs' => array(),
                'options' => array(
                    array('value' => 'foo', 'text' => 'Foobar', 'extra' => ''),
                    array('value' => 'dummy', 'text' => 'Dummy', 'extra' => ''),
                )
            ),
            'check' => array(
                'case'   => 'Simple list with options set in the XML code',
                'result' => array (
                    (object)array(
                        'value' => 'foo',
                        'text' => 'Foobar',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                    (object)array(
                        'value' => 'dummy',
                        'text' => 'Dummy',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(),
                'options' => array(
                    array('value' => 'foo', 'text' => 'Foobar', 'extra' => 'class="test" disabled="true"'),
                    array('value' => 'dummy', 'text' => 'Dummy', 'extra' => 'onclick="__ONCLICK__"'),
                )
            ),
            'check' => array(
                'case'   => 'Simple list with options set in the XML code (extra attribs)',
                'result' => array (
                    (object)array(
                        'value' => 'foo',
                        'text' => 'Foobar',
                        'disable' => true,
                        'class' => 'test',
                        'onclick' => '',
                    ),
                    (object)array(
                        'value' => 'dummy',
                        'text' => 'Dummy',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '__ONCLICK__',
                    ),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'order' => 'name'
                ),
                'options' => array(
                    array('value' => 'foo', 'text' => 'Foobar', 'extra' => ''),
                    array('value' => 'dummy', 'text' => 'Dummy', 'extra' => ''),
                )
            ),
            'check' => array(
                'case'   => 'Simple list with options set in the XML code - with ordering',
                'result' => array (
                    (object)array(
                        'value' => 'dummy',
                        'text' => 'Dummy',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                    (object)array(
                        'value' => 'foo',
                        'text' => 'Foobar',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'order' => 'name',
                    'order_dir' => 'desc'
                ),
                'options' => array(
                    array('value' => 'foo', 'text' => 'Foobar', 'extra' => ''),
                    array('value' => 'dummy', 'text' => 'Dummy', 'extra' => ''),
                )
            ),
            'check' => array(
                'case'   => 'Simple list with options set in the XML code - with reverse ordering',
                'result' => array (
                    (object)array(
                        'value' => 'foo',
                        'text' => 'Foobar',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                    (object)array(
                        'value' => 'dummy',
                        'text' => 'Dummy',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'source_class' => 'NonExistingClass',
                    'source_method' => 'notHere'
                ),
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Using a non existing class and method',
                'result' => array ()
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'source_class' => 'Fakeapp\Site\Model\Foobar',
                    'source_method' => 'notHere'
                ),
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Correct class but non existing method',
                'result' => array ()
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'source_file'   => 'admin://Standalone.php',
                    'source_class'  => 'Standalone',
                    'source_method' => 'getOptions'
                ),
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Correct class and method (direct include)',
                'result' => array (
                    (object)array(
                        'value' => 'first',
                        'text' => 'First item',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 'second',
                        'text' => 'Second item',
                        'disable' => false,
                    )
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'source_class' => 'Fakeapp\Site\Model\Foobar',
                    'source_method' => 'getOptions'
                ),
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Correct class and method (autoloader)',
                'result' => array (
                    (object)array(
                        'value' => 'first',
                        'text' => 'First item',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 'second',
                        'text' => 'Second item',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 1,
                        'text' => 'Yes',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 0,
                        'text' => 'No',
                        'disable' => false,
                    ),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'source_class' => 'Fakeapp\Site\Model\Foobar',
                    'source_method' => 'getOptions',
                    'source_translate' => false
                ),
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Correct class and method, no translate (autoloader)',
                'result' => array (
                    (object)array(
                        'value' => 'first',
                        'text' => 'First item',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 'second',
                        'text' => 'Second item',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 1,
                        'text' => 'JYES',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 0,
                        'text' => 'JNO',
                        'disable' => false,
                    ),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'source_class' => 'Fakeapp\Site\Model\Foobar',
                    'source_method' => 'getOptionsWithKeys',
                    'source_key' => 'value',
                    'source_value' => 'text'
                ),
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Correct class and method, options with keys (autoloader)',
                'result' => array (
                    (object)array(
                        'value' => 'first',
                        'text' => 'First item',
                        'disable' => false,
                    ),
                    (object)array(
                        'value' => 'second',
                        'text' => 'Second item',
                        'disable' => false,
                    )
                )
            )
        );

        $data[] = array(
            'input' => array(
                'attribs' => array(
                    'source_class' => 'Fakeapp\Site\Model\Foobar',
                    'source_method' => 'getOptionsWithKeys',
                    'source_format' => 'optionsobject'
                ),
                'options' => array(
                    array('value' => 'foo', 'text' => 'Foobar', 'extra' => ''),
                    array('value' => 'dummy', 'text' => 'Dummy', 'extra' => ''),
                )
            ),
            'check' => array(
                'case'   => 'Merging class options with XML ones',
                'result' => array (
                    (object) array(
                        'value' => 'foo',
                        'text' => 'Foobar',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                    (object) array(
                        'value' => 'dummy',
                        'text' => 'Dummy',
                        'disable' => false,
                        'class' => '',
                        'onclick' => '',
                    ),
                    array (
                        'value' => 'first',
                        'text' => 'First item',
                    ),
                    array (
                        'value' => 'second',
                        'text' => 'Second item',
                    ),
                )
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