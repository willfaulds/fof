<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class CallbackDataprovider
{
    public static function getTest__get()
    {
        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => null,
                'repeat'   => null,
                'input'    => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, not cached',
                'static' => 1,
                'repeat' => 0,
                'input'  => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => 'cached',
                'repeat'   => null,
                'input'    => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, cached',
                'static' => 0,
                'repeat' => 0,
                'input'  => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'input',
                'static'   => null,
                'repeat'   => null,
                'input'    => null
            ),
            'check' => array(
                'case'   => 'Requesting for the input method, not cached',
                'static' => 0,
                'repeat' => 0,
                'input'  => 1
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'input',
                'static'   => null,
                'repeat'   => null,
                'input'    => 'cached'
            ),
            'check' => array(
                'case'   => 'Requesting for the input method, cached',
                'static' => 0,
                'repeat' => 0,
                'input'  => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => null,
                'input'    => null
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, not cached',
                'static' => 0,
                'repeat' => 1,
                'input'  => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => 'cached',
                'input'    => null
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, cached',
                'static' => 0,
                'repeat' => 0,
                'input'  => 0
            )
        );

        return $data;
    }

    public static function getTestGetCallbackResults()
    {
        $data[] = array(
            'input' => array(
                'element' => array(
                    'source_methd' => 'test'
                )
            ),
            'check' => array(
                'case'   => 'No source class set',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'element' => array(
                    'source_class' => 'foo'
                )
            ),
            'check' => array(
                'case'   => 'No source method set',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'element' => array(
                    'source_class'  => 'NonExistingClass',
                    'source_method' => 'notHere'
                )
            ),
            'check' => array(
                'case'   => 'Using a non existing class and method',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'element' => array(
                    'source_class'  => 'Fakeapp\Site\Model\Foobar',
                    'source_method' => 'notHere'
                )
            ),
            'check' => array(
                'case'   => 'Correct class but non existing method',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'element' => array(
                    'source_class'  => 'Fakeapp\Site\Model\Foobar',
                    'source_method' => 'formCallback'
                )
            ),
            'check' => array(
                'case'   => 'Correct class and method',
                'result' => true
            )
        );

        $data[] = array(
            'input' => array(
                'element' => array(
                    'source_class'  => 'Standalone',
                    'source_method' => 'formCallback',
                    'source_file'   => 'admin://Standalone.php'
                )
            ),
            'check' => array(
                'case'   => 'Correct class and method',
                'result' => true
            )
        );

        return $data;
    }
}
