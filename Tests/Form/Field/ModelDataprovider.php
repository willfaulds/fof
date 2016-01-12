<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

class ModelDataprovider
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
                'item'       => false,
                'attribs'    => array(),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'No value selected, no empty replacement',
                'result' => '<span class="foo-class"></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'item'       => false,
                'attribs'    => array(
                    'empty_replacement' => 'Placeholder'
                ),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'No value selected, with empty replacement',
                'result' => '<span class="foo-class">Placeholder</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'item'       => false,
                'attribs'    => array(
                    'empty_replacement' => 'JYES'
                ),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'No value selected, with empty replacement (translated)',
                'result' => '<span class="foo-class">Yes</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'item'       => false,
                'attribs'    => array(
                    'empty_replacement' => '2',
                    'format' => '%F'
                ),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'No value selected, with empty replacement (formatted)',
                'result' => '<span class="foo-class">'.sprintf('%F', 2).'</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'item'       => false,
                'attribs'    => array(
                    'empty_replacement' => '2',
                ),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class',
                    'value' => 'dummy'
                )
            ),
            'check' => array(
                'case'   => 'With value selected',
                'result' => '<span class="foo-class">Dummy</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'item'       => false,
                'attribs'    => array(
                    'url' => 'somelink'
                ),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class',
                    'value' => 'dummy'
                )
            ),
            'check' => array(
                'case'   => 'With link but no datamodel',
                'result' => '<span class="foo-class">Dummy</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'item'       => true,
                'attribs'    => array(),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class',
                    'value' => 'dummy'
                )
            ),
            'check' => array(
                'case'   => 'With datamodel but no link',
                'result' => '<span class="foo-class">Dummy</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'item'       => true,
                'attribs'    => array(
                    'url' => 'somelink'
                ),
                'properties' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class',
                    'value' => 'dummy'
                )
            ),
            'check' => array(
                'case'   => 'With datamodel and link',
                'result' => '<span class="foo-class"><a href="__PARSED__">Dummy</a></span>'
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

    public static function getTestGetOptions()
    {
        $data[] = array(
            'input' => array(
                'force'    => false,
                'cache'    => array(),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title'
                ),
                'children' => array(
                    'state'   => array(),
                    'options' => array()
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('value' => '1', 'title' => 'First'),
                        (object) array('value' => '2', 'title' => 'Second'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Simple fetching from the model',
                'applyAccess' => 0,
                'with'        => 0,
                'setState'    => array(),
                'result'      => array (
                    (object) array('value' => '1', 'text' => 'First', 'disable' => false),
                    (object) array('value' => '2', 'text' => 'Second', 'disable' => false),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'force'    => false,
                'cache'    => array(
                    'Foobar#$#foobars' => array(
                        (object) array('value' => '100', 'title' => 'Cached'),
                    )
                ),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title'
                ),
                'children' => array(
                    'state'   => array(),
                    'options' => array()
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('value' => '1', 'title' => 'First'),
                        (object) array('value' => '2', 'title' => 'Second'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Cache hit',
                'applyAccess' => 0,
                'with'        => 0,
                'setState'    => array(),
                'result'      => array (
                    (object) array('value' => '100', 'title' => 'Cached'),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'force'    => true,
                'cache'    => array(
                    'Foobar#$#foobars' => array(
                        (object) array('value' => '100', 'title' => 'Cached'),
                    )
                ),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title'
                ),
                'children' => array(
                    'state'   => array(),
                    'options' => array()
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('value' => '1', 'title' => 'First'),
                        (object) array('value' => '2', 'title' => 'Second'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Cache loaded, but we force refresh',
                'applyAccess' => 0,
                'with'        => 0,
                'setState'    => array(),
                'result'      => array (
                    (object) array('value' => '1', 'text' => 'First', 'disable' => false),
                    (object) array('value' => '2', 'text' => 'Second', 'disable' => false),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'force'    => false,
                'cache'    => array(),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title',
                    'apply_access' => 1,
                    'with' => 'foo,bar'
                ),
                'children' => array(
                    'state'   => array(),
                    'options' => array()
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('value' => '1', 'title' => 'First'),
                        (object) array('value' => '2', 'title' => 'Second'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Using "with" and apply access filtering',
                'applyAccess' => 1,
                'with'        => 1,
                'setState'    => array(),
                'result'      => array (
                    (object) array('value' => '1', 'text' => 'First', 'disable' => false),
                    (object) array('value' => '2', 'text' => 'Second', 'disable' => false),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'force'    => false,
                'cache'    => array(),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title'
                ),
                'children' => array(
                    'state'   => array(
                        'foo'   => 'bar',
                        'dummy' => 'dummy'
                    ),
                    'options' => array()
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('value' => '1', 'title' => 'First'),
                        (object) array('value' => '2', 'title' => 'Second'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Setting state variables to the model',
                'applyAccess' => 0,
                'with'        => 0,
                'setState'    => array(
                    'foo'   => 'bar',
                    'dummy' => 'dummy'
                ),
                'result'      => array (
                    (object) array('value' => '1', 'text' => 'First', 'disable' => false),
                    (object) array('value' => '2', 'text' => 'Second', 'disable' => false),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'force'    => false,
                'cache'    => array(),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title',
                    'key_field' => 'key',
                    'value_field' => 'value',
                    'none' => 'None placeholder',
                    'translate' => true
                ),
                'children' => array(
                    'state'   => array(),
                    'options' => array()
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('key' => '1', 'value' => 'JYES'),
                        (object) array('key' => '2', 'value' => 'JNO'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Using field XML attributes',
                'applyAccess' => 0,
                'with'        => 0,
                'setState'    => array(),
                'result'      => array (
                    (object) array('value' => '', 'text' => 'None placeholder', 'disable' => false),
                    (object) array('value' => '1', 'text' => 'Yes', 'disable' => false),
                    (object) array('value' => '2', 'text' => 'No', 'disable' => false),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'force'    => false,
                'cache'    => array(),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title'
                ),
                'children' => array(
                    'state'   => array(),
                    'options' => array(
                        'foo' => 'Bar',
                        'dummy' => 'Dummy'
                    )
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('value' => '1', 'title' => 'First'),
                        (object) array('value' => '2', 'title' => 'Second'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Merging with XML options',
                'applyAccess' => 0,
                'with'        => 0,
                'setState'    => array(),
                'result'      => array (
                    (object) array('value' => 'foo', 'text' => 'Bar', 'disable' => false, 'class' => false, 'onclick' => false),
                    (object) array('value' => 'dummy', 'text' => 'Dummy', 'disable' => false, 'class' => false, 'onclick' => false),
                    (object) array('value' => '1', 'text' => 'First', 'disable' => false),
                    (object) array('value' => '2', 'text' => 'Second', 'disable' => false),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'force'    => false,
                'cache'    => array(),
                'attribs'  => array(
                    'model' => 'foobars',
                    'name'  => 'title',
                    'parse_value' => 1
                ),
                'children' => array(
                    'state'   => array(),
                    'options' => array()
                ),
                'mock'     => array(
                    'get'     => array(
                        (object) array('value' => '1', 'title' => 'First'),
                        (object) array('value' => '2', 'title' => 'Second'),
                    )
                )
            ),
            'check' => array(
                'case'        => 'Parse values',
                'applyAccess' => 0,
                'with'        => 0,
                'setState'    => array(),
                'result'      => array (
                    (object) array('value' => '1', 'text' => '__PARSED__', 'disable' => false),
                    (object) array('value' => '2', 'text' => '__PARSED__', 'disable' => false),
                )
            )
        );

        return $data;
    }
}
