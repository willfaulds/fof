<?php

class DispatcherDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'defaultView' => null,
                    'input' => array()
                )
            ),
            array(
                'case' => 'Nothing passed in the input, no default view',
                'defaultView' => 'main',
                'view' => 'main',
                'layout' => '',
                'containerView' => 'main'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'defaultView' => 'test',
                    'input' => array()
                )
            ),
            array(
                'case' => 'Nothing passed in the input, with default view',
                'defaultView' => 'test',
                'view' => 'test',
                'layout' => '',
                'containerView' => 'test'
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'defaultView' => null,
                    'input' => array(
                        'view' => 'foobars',
                        'layout' => 'default'
                    )
                )
            ),
            array(
                'case' => 'Data passed in the input, no default view',
                'defaultView' => 'main',
                'view' => 'foobars',
                'layout' => 'default',
                'containerView' => 'foobars'
            )
        );

        return $data;
    }
}