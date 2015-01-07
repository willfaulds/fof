<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class ToolbarDataprovider
{
    public static function getTestRenderToolbar()
    {
        // Test for built-in method --- START
        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'cpanels',
                'task'    => 'browse',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods' => array('onCpanelsBrowse')
            )
        );

        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobars',
                'task'    => 'browse',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods' => array('onBrowse')
            )
        );

        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'read',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods' => array('onRead')
            )
        );

        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'add',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods' => array('onAdd')
            )
        );

        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'edit',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods' => array('onEdit')
            )
        );
        // Test for built-in method --- END

        // tmpl=component, I don't want any toolbar
        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'edit',
                'tmpl'    => 'component',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods'   => array(),
                'nomethods' => 'onEdit'
            )
        );

        // render_toolbar=0, I don't want any toolbar
        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'edit',
                'tmpl'    => '',
                'render'  => 0,
                'input'   => ''
            ),
            array(
                'methods'   => array(),
                'nomethods' => 'onEdit'
            )
        );

        // However, if I set render_toolbar=1, I REALLY want a toolbar
        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'edit',
                'tmpl'    => 'tmpl',
                'render'  => 1,
                'input'   => ''
            ),
            array(
                'methods'   => array('onEdit')
            )
        );

        // Check for method name construction
        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'edit',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods'   => array('onFoobarsEdit')
            )
        );

        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'edit',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods'   => array('onFoobars')
            )
        );

        $data[] = array(
            array(
                'methods' => array(),
                'view'    => 'foobar',
                'task'    => 'dummy',
                'tmpl'    => '',
                'render'  => null,
                'input'   => ''
            ),
            array(
                'methods'   => array('onDummy')
            )
        );

        // If no arguments are passed, view and task should be read from the input
        $data[] = array(
            array(
                'methods' => array(),
                'view'    => null,
                'task'    => null,
                'tmpl'    => '',
                'render'  => null,
                'input'   => '',
                'config_input'   => array(
                        'view'   => 'foo',
                        'task'   => 'dummy'
                    )
            ),
            array(
                'methods'   => array('onFoosDummy')
            )
        );

        // If an input is passed, view and task should be read from it
        $data[] = array(
            array(
                'methods' => array(),
                'view'    => null,
                'task'    => null,
                'tmpl'    => '',
                'render'  => null,
                'input'   => new F0FInput(array(
                        'view' => 'bar',
                        'task' => 'render'
                    )),
                'config_input'   => array(
                    'view'   => 'foo',
                    'task'   => 'dummy'
                )
            ),
            array(
                'methods'   => array('onBarsRender')
            )
        );

        return $data;
    }

    public static function getTestOnCpanelsBrowse()
    {
        $data[] = array(
            array(
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST', 'foftest')),
                    'preferences' => array(array('com_foftest', 550, 875))
                )
            )
        );

        $data[] = array(
            array(
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => false
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST', 'foftest')),
                    'preferences' => array(array('com_foftest', 550, 875))
                )
            )
        );

        // Submenu should not be called
        $data[] = array(
            array(
                'isBackend'   => false,
                'submenu'     => false,
                'callSubmenu' => false,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST', 'foftest')),
                    'preferences' => array(array('com_foftest', 550, 875))
                )
            )
        );

        $data[] = array(
            array(
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST', 'foftest')),
                    'preferences' => array(array('com_foftest', 550, 875))
                )
            )
        );

        $data[] = array(
            array(
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => false
            ),
            array(
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnBrowse()
    {
        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_CPANEL', 'foftest')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR', 'foftest')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        // No permissions to create
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR', 'foftest')),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        // Create but no edit
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => false,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR', 'foftest')),
                    'addNew'        => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        // No create, no edit
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR', 'foftest')),
                    'divider'       => array(array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        // No create, no edit, no edistate
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR', 'foftest')),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        // No permissions
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'delete'    => false
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR', 'foftest'))
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => false,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_CPANEL', 'foftest')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => false,
                'submenu'     => false,
                'callSubmenu' => false,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_CPANEL', 'foftest')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_CPANEL', 'foftest')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTEST_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => false,
                'perms'       => array()
            ),
            array(
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnRead()
    {
        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_CPANEL_READ', 'foftest')),
                    'back'  => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => false
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR_READ', 'foftest')),
                    'back'  => array(array())
                )
            )
        );

        // Submenu should not be called
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'submenu'     => false,
                'callSubmenu' => false,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR_READ', 'foftest')),
                    'back'  => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBAR_READ', 'foftest')),
                    'back'  => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => false
            ),
            array(
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnAdd()
    {
        // With default view
        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_CPANELS_EDIT', 'foftest')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        // Passing a view
        // Start testing for backend/showbuttons combinations
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBARS_EDIT', 'foftest')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBARS_EDIT', 'foftest')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => false,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBARS_EDIT', 'foftest')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'buttons'     => false,
                'perms'       => array()
            ),
            array(
                'methods' => array()
            )
        );

        // End testing for backend/showbuttons combinations

        // Start testing with different permissions
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBARS_EDIT', 'foftest')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editown'   => false
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBARS_EDIT', 'foftest')),
                    'save'     => array(array()),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTEST: COM_FOFTEST_TITLE_FOOBARS_EDIT', 'foftest')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'cancel'   => array(array())
                )
            )
        );

        // End testing with different permissions

        return $data;
    }

    public static function getTestAppendLink()
    {
        // Normal link structure
        $data[] = array(
            array(
                'links' => array(
                    array(
                        'name'   => 'Cpanel',
                        'link'   => 'index.php?option=com_foftest&view=cpanel',
                        'active' => true,
                        'icon'   => null,
                        'parent' => '',
                    ),
                    array(
                        'name'   => 'Foobars',
                        'link'   => 'index.php?option=com_foftest&view=foobars',
                        'active' => false,
                        'icon'   => null,
                        'parent' => '',
                    )
                )
            ),
            array(
                'links' => array(
                    'Cpanel' => array(
                        'name'   => 'Cpanel',
                        'link'   => 'index.php?option=com_foftest&view=cpanel',
                        'active' => true,
                        'icon'   => null,
                    ),
                    'Foobars' => array(
                        'name'   => 'Foobars',
                        'link'   => 'index.php?option=com_foftest&view=foobars',
                        'active' => false,
                        'icon'   => null,
                    )
                )
            )
        );

        // Nested links (parent link is already there)
        $data[] = array(
            array(
                'links' => array(
                    array(
                        'name'   => 'Cpanel',
                        'link'   => 'index.php?option=com_foftest&view=cpanel',
                        'active' => true,
                        'icon'   => null,
                        'parent' => '',
                    ),
                    array(
                        'name'   => 'Foobars',
                        'link'   => 'index.php?option=com_foftest&view=foobars',
                        'active' => false,
                        'icon'   => null,
                        'parent' => 'Cpanel',
                    )
                )
            ),
            array(
                'links' => array(
                    'Cpanel' => array(
                        'name'     => 'Cpanel',
                        'link'     => 'index.php?option=com_foftest&view=cpanel',
                        'active'   => true,
                        'icon'     => null,
                        'dropdown' => 1,
                        'items'    => array(
                            array(
                                'name'   => 'Cpanel',
                                'link'   => 'index.php?option=com_foftest&view=cpanel',
                                'active' => true,
                                'icon'   => null,

                            ),
                            array(
                                'name'   => 'Foobars',
                                'link'   => 'index.php?option=com_foftest&view=foobars',
                                'active' => false,
                                'icon'   => null,
                            )

                        )
                    )
                )
            )
        );

        // Nested links (parent link is already there) with several parents
        $data[] = array(
            array(
                'links' => array(
                    array(
                        'name'   => 'Cpanel',
                        'link'   => 'index.php?option=com_foftest&view=cpanel',
                        'active' => true,
                        'icon'   => null,
                        'parent' => '',
                    ),
                    array(
                        'name'   => 'Foobars',
                        'link'   => 'index.php?option=com_foftest&view=foobars',
                        'active' => false,
                        'icon'   => null,
                        'parent' => 'Cpanel',
                    ),
                    array(
                        'name'   => 'Bares',
                        'link'   => 'index.php?option=com_foftest&view=bares',
                        'active' => false,
                        'icon'   => null,
                        'parent' => '',
                    )
                )
            ),
            array(
                'links' => array(
                    'Cpanel' => array(
                        'name'     => 'Cpanel',
                        'link'     => 'index.php?option=com_foftest&view=cpanel',
                        'active'   => true,
                        'icon'     => null,
                        'dropdown' => 1,
                        'items'    => array(
                            array(
                                'name'   => 'Cpanel',
                                'link'   => 'index.php?option=com_foftest&view=cpanel',
                                'active' => true,
                                'icon'   => null,

                            ),
                            array(
                                'name'   => 'Foobars',
                                'link'   => 'index.php?option=com_foftest&view=foobars',
                                'active' => false,
                                'icon'   => null,
                            )

                        )
                    ),
                    'Bares' => array(
                        'name'   => 'Bares',
                        'link'   => 'index.php?option=com_foftest&view=bares',
                        'active' => false,
                        'icon'   => null,
                    )
                )
            )
        );

        // Nested links (parent link is NOT already there)
        $data[] = array(
            array(
                'links' => array(
                    array(
                        'name'   => 'Foobars',
                        'link'   => 'index.php?option=com_foftest&view=foobars',
                        'active' => false,
                        'icon'   => null,
                        'parent' => 'Cpanel',
                    ),
                    array(
                        'name'   => 'Cpanel',
                        'link'   => 'index.php?option=com_foftest&view=cpanel',
                        'active' => true,
                        'icon'   => null,
                        'parent' => '',
                    )
                )
            ),
            array(
                'links' => array(
                    'Cpanel' => array(
                        'name'     => 'Cpanel',
                        'link'     => 'index.php?option=com_foftest&view=cpanel',
                        'active'   => true,
                        'icon'     => null,
                        'dropdown' => 1,
                        'items'    => array(
                            array(
                                'name'   => 'Cpanel',
                                'link'   => 'index.php?option=com_foftest&view=cpanel',
                                'active' => true,
                                'icon'   => null,

                            ),
                            array(
                                'name'   => 'Foobars',
                                'link'   => 'index.php?option=com_foftest&view=foobars',
                                'active' => false,
                                'icon'   => null,
                            )

                        )
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestRenderSubmenu()
    {
        $data[]= array(
            array(
                'view' => 'cpanel',
                'views' => array(
                    'cpanel',
                    'bares',
                    'foobars',
                )
            ),
            array(
                'links' => array(
                    'Cpanel' => array(
                        'name'   => 'Cpanel',
                        'link'   => 'index.php?option=com_foftest&view=cpanel',
                        'active' => true,
                        'icon'   => null
                    ),
                    'Bares' => array(
                        'name'   => 'Bares',
                        'link'   => 'index.php?option=com_foftest&view=bares',
                        'active' => false,
                        'icon'   => null
                    ),
                    'Foobars' => array(
                        'name'   => 'Foobars',
                        'link'   => 'index.php?option=com_foftest&view=foobars',
                        'active' => false,
                        'icon'   => null
                    )
                )
            )
        );

        $data[]= array(
            array(
                'view' => 'foobars',
                'views' => array(
                    'cpanel',
                    'bares',
                    'foobars',
                )
            ),
            array(
                'links' => array(
                    'Cpanel' => array(
                        'name'   => 'Cpanel',
                        'link'   => 'index.php?option=com_foftest&view=cpanel',
                        'active' => false,
                        'icon'   => null
                    ),
                    'Bares' => array(
                        'name'   => 'Bares',
                        'link'   => 'index.php?option=com_foftest&view=bares',
                        'active' => false,
                        'icon'   => null
                    ),
                    'Foobars' => array(
                        'name'   => 'Foobars',
                        'link'   => 'index.php?option=com_foftest&view=foobars',
                        'active' => true,
                        'icon'   => null
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestGetMyViews()
    {
	    $origpaths = array(
		    'administrator/components/com_foftest/views/bare',
		    'administrator/components/com_foftest/views/bares',
		    'administrator/components/com_foftest/views/foobar',
		    'administrator/components/com_foftest/views/foobars'
	    );

	    // Standard folders, no cpanel view
	    $paths = $origpaths;
        $data[] = array(
            array(
                'structure' => self::createArrayDir($paths),
                'folders'   => array(
	                'bare',
	                'bares',
	                'foobar',
	                'foobars'
                )
            ),
	        array(
		        'views' => array(
			        'bares',
			        'foobars'
		        )
	        )
        );

	    // If cpanel is here, it should be the first one
        $paths = $origpaths;
	    $data[] = array(
		    array(
                'structure' => self::createArrayDir($paths),
			    'folders' => array(
				    'bare',
				    'bares',
				    'cpanel',
				    'foobar',
				    'foobars',
			    )
		    ),
		    array(
			    'views' => array(
				    'cpanels',
				    'bares',
				    'foobars'
			    )
		    )
	    );

	    // Skip file in both plural and singular view => view should be skipped
	    $paths = $origpaths;
	    $paths[0] .= '/skip.xml';
	    $paths[1] .= '/skip.xml';
	    $data[] = array(
		    array(
			    'structure' => self::createArrayDir($paths),
			    'folders' => array(
				    'bare',
				    'bares',
				    'cpanel',
				    'foobar',
				    'foobars',
			    )
		    ),
		    array(
			    'views' => array(
				    'cpanels',
				    'foobars'
			    )
		    )
	    );

	    // Metadata with ordering
	    $paths = $origpaths;
	    $paths[3] .= '/metadata.xml';

	    $contents['metadata.xml'] = '<?xml version="1.0" encoding="UTF-8"?><metadata><foflib><ordering>-10</ordering></foflib></metadata>';

	    $data[] = array(
		    array(
			    'structure' => self::createArrayDir($paths, $contents),
			    'folders' => array(
				    'bare',
				    'bares',
				    'cpanel',
				    'foobar',
				    'foobars',
			    )
		    ),
		    array(
			    'views' => array(
                    'foobars',
                    'bares',
                    'cpanels'
			    )
		    )
	    );

        return $data;
    }

    protected static function createArrayDir($paths, $contents = array())
    {
        $tree = array();
        foreach ($paths as $path) {
            $pathParts = explode('/', $path);
            $subTree   = array(array_pop($pathParts));

            if(strpos($subTree[0], '.') !== false)
            {
	            $content = '';

	            if(isset($contents[$subTree[0]]))
	            {
		            $content = $contents[$subTree[0]];
	            }

                $subTree = array($subTree[0] => $content);
            }
	        else
	        {
		        $subTree = array($subTree[0] => array());
	        }

            foreach (array_reverse($pathParts) as $dir) {
                $subTree = array($dir => $subTree);
            }
            $tree = array_merge_recursive($tree, $subTree);
        }

        return $tree;
    }
}