<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace Fakeapp\Site\Model;

use FOF30\Tests\Stubs\Model\ModelStub;

class Foobar extends ModelStub
{
    /**
     * This method is used in {@link CallbackTest::testGetCallbackResults()} to test the callback
     * to a class method
     *
     * @param $data
     *
     * @return array
     */
    public static function formCallback($data)
    {
        return $data;
    }

    /**
     * This method is used in {@link GenericListTest::testGetOptions} to test fetching the options
     * from a class method
     */
    public static function getOptions()
    {
        $options = array(
            'first' => 'First item',
            'second' => 'Second item',
            '1' => 'JYES',
            '0' => 'JNO',
        );

        return $options;
    }

    /**
     * This method is used in {@link GenericListTest::testGetOptions} to test fetching the options
     * from a class method
     */
    public static function getOptionsWithKeys()
    {
        $options = array(
            array('value' => 'first', 'text' => 'First item'),
            array('value' => 'second', 'text' => 'Second item'),
        );

        return $options;
    }
}