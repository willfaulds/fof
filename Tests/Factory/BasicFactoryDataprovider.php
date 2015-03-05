<?php

class BasicFactoryDataprovider
{
    public static function getTestController()
    {
        $data[] = array(
            array(
                'view' => 'foobars',
                'mock' => array(
                    'controller' => array(true)
                )
            ),
            array(
                'case' => 'Controller is immediately found',
                'exception' => '',
                'names' => array('\Fakeapp\Site\Controller\Foobars')
            )
        );

        $data[] = array(
            array(
                'view' => 'foobars',
                'mock' => array(
                    'controller' => array(
                        'FOF30\Factory\Exception\ControllerNotFound',
                        true
                    )
                )
            ),
            array(
                'case' => 'Controller is found at second try',
                'exception' => '',
                'names' => array('\Fakeapp\Site\Controller\Foobars', '\Fakeapp\Site\Controller\Foobar')
            )
        );

        $data[] = array(
            array(
                'view' => 'foobars',
                'mock' => array(
                    'controller' => array(
                        'FOF30\Factory\Exception\ControllerNotFound',
                        'FOF30\Factory\Exception\ControllerNotFound'
                    )
                )
            ),
            array(
                'case' => 'Controller is not found',
                'exception' => 'FOF30\Factory\Exception\ControllerNotFound',
                'names' => array()
            )
        );

        return $data;
    }
}