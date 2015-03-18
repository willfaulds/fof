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

    public static function getTestOnBrowse()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(),
                'model'   => ''
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
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
                'model'   => ''
            ),
            array(
                'case' => 'On backend, is a dataview',
                'submenu' => true,
                'methods' => array(
                    'title' => 1,
                    'addNew' => 1,
                    'editList' => 1,
                    'divider' => 2,
                    'publishList' => 1,
                    'unpublishList' => 1,
                    'deleteList' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
                'model'   => 'checkin'
            ),
            array(
                'case' => 'On backend, is a dataview, model with checkin support',
                'submenu' => true,
                'methods' => array(
                    'title' => 1,
                    'addNew' => 1,
                    'editList' => 1,
                    'divider' => 2,
                    'publishList' => 1,
                    'unpublishList' => 1,
                    'deleteList' => 1,
                    'checkin' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
                'model'   => 'bare'
            ),
            array(
                'case' => 'On backend, is a dataview, model no checkin support',
                'submenu' => true,
                'methods' => array(
                    'title' => 1,
                    'addNew' => 1,
                    'editList' => 1,
                    'divider' => 2,
                    'publishList' => 1,
                    'unpublishList' => 1,
                    'deleteList' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(
                    'manage'    => false,
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'delete'    => false,
                ),
                'model'   => ''
            ),
            array(
                'case' => 'On backend, is a dataview, user can\'t do anything',
                'submenu' => true,
                'methods' => array(
                    'title' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'submenu' => false,
                'buttons' => false,
                'perms'   => array(),
                'model'   => ''
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
                'buttons' => false,
                'perms'   => array(),
                'model'   => ''
            ),
            array(
                'case' => 'On frontend, with submenu',
                'submenu' => true,
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnRead()
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
                'methods' => array('title' => 1, 'back' => 1)
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

    public static function getTestOnAdd()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'buttons' => false,
                'perms'   => array()
            ),
            array(
                'case' => 'No admin, no buttons',
                'methods' => array()
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => false,
                    'dataView' => false
                ),
                'buttons' => true,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
            ),
            array(
                'case' => 'No admin, with buttons',
                'methods' => array(
                    'title' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => false
                ),
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
            ),
            array(
                'case' => 'On backend, is not a dataview',
                'methods' => array(
                    'title' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'buttons' => false,
                'perms'   => array(
                    'manage'    => true,
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true,
                ),
            ),
            array(
                'case' => 'On backend, is a dataview',
                'methods' => array(
                    'title' => 1,
                    'apply' => 1,
                    'save'  => 1,
                    'custom' => 1,
                    'cancel' => 1
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin'  => true,
                    'dataView' => true
                ),
                'buttons' => false,
                'perms'   => array(
                    'manage'    => false,
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'editown'   => false,
                    'delete'    => false,
                ),
            ),
            array(
                'case' => 'On backend, is a dataview, user can\'t do anything',
                'methods' => array(
                    'title' => 1,
                    'save'  => 1,
                    'cancel' => 1
                )
            )
        );

        return $data;
    }

    public static function getTestOnEdit()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin' => false
                ),
                'buttons' => false
            ),
            array(
                'onAdd' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin' => true
                ),
                'buttons' => false
            ),
            array(
                'onAdd' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isAdmin' => false
                ),
                'buttons' => true
            ),
            array(
                'onAdd' => true
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