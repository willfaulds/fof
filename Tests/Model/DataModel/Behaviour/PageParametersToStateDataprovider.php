<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class PageParametersToStateDataprovider
{
    public static function getTestOnAfterConstruct()
    {
        $data[] = array(
            'input' => array(
                'input' => array(),
                'mock'  => array(
                    'admin' => true
                ),
                'state'  => array(
                    'foo' => 'bar'
                ),
                'params' => array()
            ),
            'check' => array(
                'case' => 'We are on the backend',
                'state' => array(
                    'foo' => 'bar'
                )
            )
        );

        $data[] = array(
            'input' => array(
                'input' => array(),
                'mock'  => array(
                    'admin' => false
                ),
                'state'  => array(
                    'foo' => 'bar'
                ),
                'params' => array()
            ),
            'check' => array(
                'case' => 'Page params are empty',
                'state' => array(
                    'foo' => 'bar'
                )
            )
        );

        $data[] = array(
            'input' => array(
                'input' => array(),
                'mock'  => array(
                    'admin' => false
                ),
                'state'  => array(
                    'foo' => 'bar'
                ),
                'params' => array(
                    'hello' => 'world'
                )
            ),
            'check' => array(
                'case' => 'Page params not present inside model state',
                'state' => array(
                    'foo' => 'bar',
                    'hello' => 'world'
                )
            )
        );

        $data[] = array(
            'input' => array(
                'input' => array(),
                'mock'  => array(
                    'admin' => false
                ),
                'state'  => array(
                    'foo' => 'bar'
                ),
                'params' => array(
                    'foo' => 'new'
                )
            ),
            'check' => array(
                'case' => 'Param already set in the model state',
                'state' => array(
                    'foo' => 'bar',
                )
            )
        );

        $data[] = array(
            'input' => array(
                'input' => array(),
                'mock'  => array(
                    'admin' => false
                ),
                'state'  => array(
                    'foo' => 0
                ),
                'params' => array(
                    'foo' => 'new'
                )
            ),
            'check' => array(
                'case' => 'Param already set in the model state (empty value)',
                'state' => array(
                    'foo' => 0,
                )
            )
        );

        $data[] = array(
            'input' => array(
                'input' => array(
                    'foo' => 'bar'
                ),
                'mock'  => array(
                    'admin' => false
                ),
                'state'  => array(),
                'params' => array(
                    'foo' => 'new'
                )
            ),
            'check' => array(
                'case' => 'Param already set in the input',
                'state' => array(
                    'foo' => 'bar',
                )
            )
        );

        return $data;
    }
}