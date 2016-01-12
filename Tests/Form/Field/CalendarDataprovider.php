<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class CalendarDataprovider
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
                'property' => 'input',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the input method, not cached',
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

    public function getTestGetCalendar()
    {
        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '',
                    'value'   => '',
                    'filter'  => '',
                    'format'  => ''
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, no attribs set',
                'result' => '<div class="input-append"><input type="text" title="" name="foobar-name" id="foobar_id" value="" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '',
                    'filter'  => '',
                    'format'  => ''
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, with a default value, value is empty',
                'result' => '<div class="input-append"><input type="text" title="Wednesday, 22 July 2015" name="foobar-name" id="foobar_id" value="2015-07-22" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '0000-00-00 00:00:00',
                    'filter'  => '',
                    'format'  => ''
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, with a default value, value is db null date',
                'result' => '<div class="input-append"><input type="text" title="Wednesday, 22 July 2015" name="foobar-name" id="foobar_id" value="2015-07-22" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '0000-00-00',
                    'filter'  => '',
                    'format'  => ''
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, with a default value, value all zeros (0000-00-00)',
                'result' => '<div class="input-append"><input type="text" title="Wednesday, 22 July 2015" name="foobar-name" id="foobar_id" value="2015-07-22" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '2015-08-22 12:00:00',
                    'filter'  => '',
                    'format'  => '%Y/%m/%d'
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, apply custom format',
                'result' => '<div class="input-append"><input type="text" title="Saturday, 22 August 2015" name="foobar-name" id="foobar_id" value="2015/08/22" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '2015-08-22 12:00:00',
                    'filter'  => 'server_utc',
                    'format'  => '%Y-%m-%d %H:%M:%S'
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, apply server timezone',
                'result' => '<div class="input-append"><input type="text" title="Saturday, 22 August 2015" name="foobar-name" id="foobar_id" value="2015-08-22 14:00:00" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => 'America/New_York',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '2015-08-22 12:00:00',
                    'filter'  => 'USER_UTC',
                    'format'  => '%Y-%m-%d %H:%M:%S'
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, apply user timezone',
                'result' => '<div class="input-append"><input type="text" title="Saturday, 22 August 2015" name="foobar-name" id="foobar_id" value="2015-08-22 08:00:00" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '2015-08-22 12:00:00',
                    'filter'  => 'USER_UTC',
                    'format'  => '%Y-%m-%d %H:%M:%S'
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Static field, user timezone not set, fallback to the server one',
                'result' => '<div class="input-append"><input type="text" title="Saturday, 22 August 2015" name="foobar-name" id="foobar_id" value="2015-08-22 14:00:00" class="input-medium hasTooltip" /><button type="button" class="btn" id="foobar_id_img"><i class="icon-calendar"></i></button></div>'
            )
        );

        $data[]= array(
            'input' => array(
                'display' => 'static',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '',
                    'value'   => '',
                    'filter'  => '',
                    'format'  => ''
                ),
                'attribs' => array(
                    'size'      => '5',
                    'maxlength' => '10',
                    'class'     => 'foo-class',
                    'readonly'  => 'true',
                    'disabled'  => 'true',
                    'onchange'  => '__ONCHANGE__',
                    'required'  => 'true',
                ),
            ),
            'check' => array(
                'case' => 'Static field, with attribs set',
                'result' => '<div><input type="text" title="" name="foobar-name" id="foobar_id" value="" size="5" maxlength="10" class="foo-class hasTooltip" readonly="readonly" disabled="disabled" onchange="__ONCHANGE__" required="required" aria-required="true" /><button type="button" class="btn" id="foobar_id_img" style="display:none;"><i class="icon-calendar"></i></button></div>'
            )
        );

        // This is an annoying case: we default to the PHP local time, which means that it could be different between
        // dev machines and Travis build. So we can't hardcode any number, but follow the whole logic and hope for the best
        $jDate = new \JDate('2015-08-22 12:00:00');
        $value = strftime('%Y-%m-%d %H:%M:%S', $jDate->getTimestamp());

        $data[]= array(
            'input' => array(
                'display' => 'repeatable',
                'userTimezone' => '',
                'field'   => array(
                    'default' => '2015-07-22 12:00:00',
                    'value'   => '2015-08-22 12:00:00',
                    'filter'  => '',
                    'format'  => '%Y-%m-%d %H:%M:%S'
                ),
                'attribs' => array(
                    'size'      => '',
                    'maxlength' => '',
                    'class'     => '',
                    'readonly'  => '',
                    'disabled'  => '',
                    'onchange'  => '',
                    'required'  => '',
                ),
            ),
            'check' => array(
                'case' => 'Repeatable field',
                'result' => '<span class="foobar_id ">'.$value.'</span>'
            )
        );

        return $data;
    }
}
