<?php

class ToolbarDataprovider
{
    public static function getTestRenderToolbar()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => '',
                    'config'        => ''
                ),
                'input'     => array(
                    'tmpl' => 'component'
                ),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'Component template, no render_toolbar flag',
                'config'       => '',
                'counter'      => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => '',
                    'config'        => ''
                ),
                'input'     => array(
                    'tmpl' => '',
                    'render_toolbar' => 0
                ),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No template, render_toolbar flag set to off',
                'config'       => '',
                'counter'      => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No view/task passed, no view/task in the input',
                'config'       => 'models.Cpanels.toolbar.main',
                'counter'      => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => 'view',
                'task'      => null
            ),
            array(
                'case'         => 'View passed, no view/task in the input',
                'config'       => 'models.Views.toolbar.main',
                'counter'      => array('onViews' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => 'foobar',
                'task'      => 'task'
            ),
            array(
                'case'         => 'View/task passed',
                'config'       => 'models.Foobars.toolbar.task',
                'counter'      => array('onTask' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => 'foobar',
                'task'      => 'dummy'
            ),
            array(
                'case'         => 'View/task passed',
                'config'       => 'models.Foobars.toolbar.dummy',
                'counter'      => array('onFoobarsDummy' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => '',
                    'getTask'       => '',
                    'getController' => null,
                    'config'        => ''
                ),
                'input'     => array(
                    'view' => 'foobar',
                    'task' => 'dummy'
                ),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No View/task passed, fetching them from the input',
                'config'       => 'models.Foobars.toolbar.dummy',
                'counter'      => array('onFoobarsDummy' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => 'foobar',
                    'getTask'       => 'dummy',
                    'getController' => true,
                    'config'        => ''
                ),
                'input'     => array(),
                'useConfig' => false,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'No View/task passed, no view/task in the input, using controller default',
                'config'       => 'models.Foobars.toolbar.dummy',
                'counter'      => array('onFoobarsDummy' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getName'       => 'foobar',
                    'getTask'       => 'dummy',
                    'getController' => true,
                    'config'        => array(1)
                ),
                'input'     => array(),
                'useConfig' => true,
                'view'      => null,
                'task'      => null
            ),
            array(
                'case'         => 'Using the configuration file',
                'config'       => 'models.Foobars.toolbar.dummy',
                'counter'      => array()
            )
        );

        return $data;
    }

    public static function getTestOnCpanelsBrowse()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'On backend, no dataview',
                'submenu' => true,
                'methods' => array('title' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'On backend, is a dataview',
                'submenu' => true,
                'methods' => array('title' => 1, 'preferences' => 1)
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false
            ),
            array(
                'case' => 'No admin, no buttons',
                'submenu' => false,
                'methods' => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => true,
                'buttons' => false
            ),
            array(
                'case' => 'On frontend, with submenu',
                'submenu' => true,
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestIsDataView()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'getController' => false,
                    'getView' => null,
                    'cache'   => null
                )
            ),
            array(
                'case' => "The disptacher doesn't return a controller",
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => true,
                    'getView' => null,
                    'cache'   => null
                )
            ),
            array(
                'case' => "The controller doesn't return a view",
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => true,
                    'getView' => 'FOF30\Tests\Stubs\View\ViewStub',
                    'cache'   => null
                )
            ),
            array(
                'case' => "The view is not a data-aware one",
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => true,
                    'getView' => 'FOF30\Tests\Stubs\View\DataView\RawStub',
                    'cache'   => null
                )
            ),
            array(
                'case' => "The view is a data-aware one",
                'result' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'getController' => false,
                    'getView' => null,
                    'cache'   => 'dummy'
                )
            ),
            array(
                'case' => "Result is cached",
                'result' => 'dummy'
            )
        );

        return $data;
    }
}