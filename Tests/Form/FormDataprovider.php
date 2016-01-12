<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class FormDataprovider
{
    public static function getTestGetAttribute()
    {
        $data[] = array(
            'input' => array(
                'attribute' => 'type'
            ),
            'check' => array(
                'case' => 'Existing attribute',
                'result' => 'browse'
            )
        );

        $data[] = array(
            'input' => array(
                'attribute' => 'iamnothere'
            ),
            'check' => array(
                'case' => 'Non existing attribute',
                'result' => 'default'
            )
        );

        return $data;
    }

    public static function getTestLoadCSSFiles()
    {
        $data[] = array(
            'input' => array(
                'mock' => array(
                    'attributes' => array()
                )
            ),
            'check' => array(
                'case' => 'There are no files to add',
                'view' => array(
                    'css'  => array(),
                    'less' => array()
                )
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'attributes' => array(
                        'cssfiles'  => 'path/to/file.css',
                        'lessfiles' => 'path/to/file.less'
                    )
                )
            ),
            'check' => array(
                'case' => 'CSS and LESS files',
                'view' => array(
                    'css'  => array('path/to/file.css'),
                    'less' => array(
                        array('path/to/file.less', null)
                    )
                )
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'attributes' => array(
                        'cssfiles'  => 'path/to/file.css',
                        'lessfiles' => 'path/to/file.less'
                    )
                )
            ),
            'check' => array(
                'case' => 'CSS and LESS files',
                'view' => array(
                    'css'  => array('path/to/file.css'),
                    'less' => array(
                        array('path/to/file.less', null)
                    )
                )
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'attributes' => array(
                        'cssfiles'  => 'path/to/file.css, path/to/file2.css',
                        'lessfiles' => 'path/to/file.less||alt.css, path/to/file2.less'
                    )
                )
            ),
            'check' => array(
                'case' => 'Multiple CSS and LESS files',
                'view' => array(
                    'css'  => array('path/to/file.css', 'path/to/file2.css'),
                    'less' => array(
                        array('path/to/file.less', 'alt.css'),
                        array('path/to/file2.less', null),
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestLoadJSFiles()
    {
        $data[] = array(
            'input' => array(
                'mock' => array(
                    'attributes' => array()
                )
            ),
            'check' => array(
                'case' => 'There are no files to add',
                'view' => array(
                    'js'  => array(),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'attributes' => array(
                        'jsfiles' => 'path/to/file.js'
                    )
                )
            ),
            'check' => array(
                'case' => 'Single JS file to add',
                'view' => array(
                    'js'  => array(
                        'path/to/file.js'
                    ),
                )
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'attributes' => array(
                        'jsfiles' => 'path/to/file.js, path/to/file2.js'
                    )
                )
            ),
            'check' => array(
                'case' => 'Multiple JS files to add',
                'view' => array(
                    'js'  => array(
                        'path/to/file.js',
                        'path/to/file2.js'
                    ),
                )
            )
        );

        return $data;
    }

    public function getTestLoadFile()
    {
        $data[] = array(
            'input' => array(
                'file' => 'nothere.xml'
            ),
            'check' => array(
                'case' => 'File does not exists',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'file' => JPATH_TESTS
            ),
            'check' => array(
                'case' => 'Path is a directory',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'file' => JPATH_TESTS.'/_data/form/form.default.xml'
            ),
            'check' => array(
                'case' => 'Path is correct',
                'result' => true
            )
        );

        return $data;
    }

    public function getTestGetHeaderset()
    {
        $data[] = array(
            'input' => array(
                'mock' => array(
                    'groups' => false,
                    'header' => array()
                )
            ),
            'check' => array(
                'case' => 'There are no header fields',
                'header' => array(),
                'fields' => array()
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'groups' => true,
                    'header' => array(
                        (object)array('id' => 1),
                        (object)array('id' => 2),
                        (object)array('id' => 3),
                        (object)array('id' => 4),
                        (object)array('id' => 5),
                        (object)array('id' => 6),
                        (object)array('id' => 7),
                    )
                )
            ),
            'check' => array(
                'case' => 'There are no header fields',
                'header' => array('headers','headers','headers','headers','headers','headers','headers'),
                'fields' => array(
                    1 => (object)array('id' => 1),
                    2 => (object)array('id' => 2),
                    3 => (object)array('id' => 3),
                    4 => (object)array('id' => 4),
                    5 => (object)array('id' => 5),
                    6 => (object)array('id' => 6),
                    7 => (object)array('id' => 7),
                )
            )
        );

        return $data;
    }

    public static function getTestGetHeader()
    {
        $data[] = array(
            'input' => array(
                'load' => false,
                'mock' => array(
                    'find' => array()
                )
            ),
            'check' => array(
                'case' => 'XML form not loaded',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'load' => true,
                'mock' => array(
                    'find' => array()
                )
            ),
            'check' => array(
                'case' => 'Empty header',
                'result' => false
            )
        );

        $data[] = array(
            'input' => array(
                'load' => true,
                'mock' => array(
                    'find' => array('dummy')
                )
            ),
            'check' => array(
                'case' => 'Everything is ok',
                'result' => 'mocked'
            )
        );

        return $data;
    }

    public static function getTestLoadClass()
    {
        $data[] = array(
            'input' => array(
                'entity' => 'field',
                'type'   => 'text'
            ),
            'check' => array(
                'case'   => 'Standard FOF30 field (autoloader)',
                'result' => 'FOF30\Form\Field\Text'
            )
        );

        $data[] = array(
            'input' => array(
                'entity' => 'field',
                'type'   => 'calendar'
            ),
            'check' => array(
                'case'   => 'Specialized FOF30 field (autoloader)',
                'result' => 'Fakeapp\Site\Form\Field\Calendar'
            )
        );

        $data[] = array(
            'input' => array(
                'entity' => 'field',
                'type'   => 'customfield'
            ),
            'check' => array(
                'case'   => 'Custom app field (autoloader)',
                'result' => 'Fakeapp\Site\Form\Field\Customfield'
            )
        );

        $data[] = array(
            'input' => array(
                'entity' => 'field',
                'type'   => 'meter'
            ),
            'check' => array(
                'case'   => 'Joomla field',
                'result' => 'JFormFieldMeter'
            )
        );

        return $data;
    }
}