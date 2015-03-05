<?php

class BasicFactoryDataprovider
{
    public static function getTestController()
    {
        $data[] = array(
            array(
                'view' => 'foobars',
                'mock' => array(
                    'create' => array(true)
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
                    'create' => array(
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
                    'create' => array(
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

    public static function getTestModel()
    {
        $data[] = array(
            array(
                'view' => 'foobars',
                'mock' => array(
                    'create' => array(true)
                )
            ),
            array(
                'case' => 'Model is immediately found',
                'exception' => '',
                'names' => array('\Fakeapp\Site\Model\Foobars')
            )
        );

        $data[] = array(
            array(
                'view' => 'foobars',
                'mock' => array(
                    'create' => array(
                        'FOF30\Factory\Exception\ModelNotFound',
                        true
                    )
                )
            ),
            array(
                'case' => 'Model is found at second try',
                'exception' => '',
                'names' => array('\Fakeapp\Site\Model\Foobars', '\Fakeapp\Site\Model\Foobar')
            )
        );

        $data[] = array(
            array(
                'view' => 'foobars',
                'mock' => array(
                    'create' => array(
                        'FOF30\Factory\Exception\ModelNotFound',
                        'FOF30\Factory\Exception\ModelNotFound'
                    )
                )
            ),
            array(
                'case' => 'Model is not found',
                'exception' => 'FOF30\Factory\Exception\ModelNotFound',
                'names' => array()
            )
        );

        return $data;
    }

    public static function getTestView()
    {
        $data[] = array(
            array(
                'view' => 'foobars',
                'type' => 'html',
                'mock' => array(
                    'create' => array(true)
                )
            ),
            array(
                'case' => 'View is immediately found',
                'exception' => '',
                'names' => array('\Fakeapp\Site\View\Foobars\Html')
            )
        );

        $data[] = array(
            array(
                'view' => 'foobars',
                'type' => 'json',
                'mock' => array(
                    'create' => array(true)
                )
            ),
            array(
                'case' => 'View is immediately found, JSON type',
                'exception' => '',
                'names' => array('\Fakeapp\Site\View\Foobars\Json')
            )
        );

        $data[] = array(
            array(
                'view' => 'foobars',
                'type' => 'html',
                'mock' => array(
                    'create' => array(
                        'FOF30\Factory\Exception\ViewNotFound',
                        true
                    )
                )
            ),
            array(
                'case' => 'View is found at second try',
                'exception' => '',
                'names' => array('\Fakeapp\Site\View\Foobars\Html', '\Fakeapp\Site\View\Foobar\Html')
            )
        );

        $data[] = array(
            array(
                'view' => 'foobars',
                'type' => 'html',
                'mock' => array(
                    'create' => array(
                        'FOF30\Factory\Exception\ViewNotFound',
                        'FOF30\Factory\Exception\ViewNotFound'
                    )
                )
            ),
            array(
                'case' => 'View is not found',
                'exception' => 'FOF30\Factory\Exception\ViewNotFound',
                'names' => array()
            )
        );

        return $data;
    }
}