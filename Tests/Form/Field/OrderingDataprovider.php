<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

class OrderingDataprovider
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

    public static function getTestGetInput()
    {
        $data[] = array(
            'input' => array(
                'properties' => array(
                    'class'     => 'foo-class',
                    'disabled'  => true,
                    'name'      => 'ordering',
                    'size'      => 5,
                    'onchange'  => '__ONCHANGE__',
                    'value'     => 5
                ),
                'attributes' => array()
            ),
            'check' => array(
                'case'   => 'Normal ordering field',
                'result' => '<select id="ordering" name="ordering" class="foo-class" disabled size="5" onchange="__ONCHANGE__">
	<option value="0">0 JOPTION_ORDER_FIRST</option>
	<option value="1">1. Guinea Pig row</option>
	<option value="2">2. Second row</option>
	<option value="3">3. Third row</option>
	<option value="4">4. Fourth row</option>
	<option value="5" selected="selected">5. Locked record</option>
	<option value="6">6 JOPTION_ORDER_LAST</option>
</select>
'
            )
        );

        $data[] = array(
            'input' => array(
                'properties' => array(
                    'class'     => 'foo-class',
                    'disabled'  => true,
                    'name'      => 'ordering',
                    'size'      => 5,
                    'onchange'  => '__ONCHANGE__',
                    'value'     => 5,
                    'readonly'  => true
                ),
                'attributes' => array()
            ),
            'check' => array(
                'case'   => 'Field is read only',
                'result' => '<select name="" class="foo-class" disabled size="5" onchange="__ONCHANGE__">
	<option value="0">0 JOPTION_ORDER_FIRST</option>
	<option value="1">1. Guinea Pig row</option>
	<option value="2">2. Second row</option>
	<option value="3">3. Third row</option>
	<option value="4">4. Fourth row</option>
	<option value="5" selected="selected">5. Locked record</option>
	<option value="6">6 JOPTION_ORDER_LAST</option>
</select>
<input type="hidden" name="ordering" value="5"/>'
            )
        );

        return $data;
    }

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(
                'properties' => array(
                    'rowid' => 2,
                    'value' => 10
                ),
                'attribs'    => array(),
                'mock' => array(
                    'order' => 'id',
                    'ajax'  => false,
                    'perms' => false
                )
            ),
            'check' => array(
                'case' => 'No ajax support, list not ordered by the ordering field',
                'result' => '<span>__ORDER_UP__</span><span>__ORDER_DOWN__</span><input type="text" name="order[]" size="5" value="10" disabled="disabled"class="text-area-order" style="text-align: center" />'
            )
        );

        $data[] = array(
            'input' => array(
                'properties' => array(
                    'rowid' => 2,
                    'value' => 10
                ),
                'attribs'    => array(),
                'mock' => array(
                    'order' => 'ordering',
                    'ajax'  => false,
                    'perms' => false
                )
            ),
            'check' => array(
                'case' => 'No ajax support, list ordered by the ordering field',
                'result' => '<span>__ORDER_UP__</span><span>__ORDER_DOWN__</span><input type="text" name="order[]" size="5" value="10" class="text-area-order" style="text-align: center" />'
            )
        );

        $data[] = array(
            'input' => array(
                'properties' => array(
                    'rowid' => 2,
                    'value' => 10
                ),
                'attribs'    => array(),
                'mock' => array(
                    'order' => 'id',
                    'ajax'  => array('saveOrder' => true),
                    'perms' => false
                )
            ),
            'check' => array(
                'case' => 'Ajax support, list not ordered by the ordering field, no edit perms',
                'result' => '<span class="sortable-handler inactive" ><i class="icon-menu"></i></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'properties' => array(
                    'rowid' => 2,
                    'value' => 10
                ),
                'attribs'    => array(),
                'mock' => array(
                    'order' => 'id',
                    'ajax'  => array('saveOrder' => true),
                    'perms' => true
                )
            ),
            'check' => array(
                'case' => 'Ajax support, list not ordered by the ordering field, with edit perms',
                'result' => '<div class="order-disabled"><span class="sortable-handler " title="" rel="tooltip"><i class="icon-menu"></i></span></div>'
            )
        );

        $data[] = array(
            'input' => array(
                'properties' => array(
                    'rowid' => 2,
                    'value' => 10
                ),
                'attribs'    => array(),
                'mock' => array(
                    'order' => 'ordering',
                    'ajax'  => array('saveOrder' => true),
                    'perms' => true
                )
            ),
            'check' => array(
                'case' => 'Ajax support, list ordered by the ordering field, with edit perms',
                'result' => '<div class="order-enabled"><span class="sortable-handler " title="" rel="tooltip"><i class="icon-menu"></i></span><input type="text" name="order[]" size="5" class="input-mini text-area-order" value="10" /></div>'
            )
        );

        $data[] = array(
            'input' => array(
                'properties' => array(
                    'rowid' => 2,
                    'value' => 10
                ),
                'attribs'    => array(),
                'mock' => array(
                    'order' => 'ordering',
                    'ajax'  => array('saveOrder' => false),
                    'perms' => true
                )
            ),
            'check' => array(
                'case' => 'Ajax support but no saveOrder, list ordered by the ordering field, with edit perms',
                'result' => '<div class="order-enabled"><span class="sortable-handler inactive tip-top" title="JORDERINGDISABLED" rel="tooltip"><i class="icon-menu"></i></span><input type="text" name="order[]" size="5" class="input-mini text-area-order" value="10" /></div>'
            )
        );

        return $data;
    }
}