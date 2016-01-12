<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

use FOF30\Tests\Helpers\VfsHelper;

class ImageListDataprovider
{
    public static function getTest__get()
    {
        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, not cached',
                'static' => 1,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => 'cached',
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, not cached',
                'static' => 0,
                'repeat' => 1
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => 'cached'
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        return $data;
    }

    public static function getTestGetStatic()
    {
        $data[] = array(
            'input' => array(
                'legacy' => true
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'input'    => 1,
                'contents' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'contents' => 1
            )
        );

        return $data;
    }

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(
                'legacy' => true
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'input'    => 1,
                'contents' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'contents' => 1
            )
        );

        return $data;
    }

    public static function getTestGetFieldContents()
    {
        $paths = array(
            'media/foobar.jpg'
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attributes' => array(),
                'value'      => '',
                'filesystem' => VfsHelper::createArrayDir($paths)
            ),
            'check' => array(
                'case' => 'No value, file does not exist',
                'result' => ''
            )
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attributes' => array(
                    'directory' => 'media'
                ),
                'value'      => 'wrong.jpg',
                'filesystem' => VfsHelper::createArrayDir($paths)
            ),
            'check' => array(
                'case' => 'With value, file does not exist',
                'result' => ''
            )
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attributes' => array(
                    'directory' => 'media'
                ),
                'value'      => 'foobar.jpg',
                'filesystem' => VfsHelper::createArrayDir($paths)
            ),
            'check' => array(
                'case' => 'With value, file exists',
                'result' => '<img src="http://www.example.com/media/foobar.jpg" alt="" />'
            )
        );

        $data[] = array(
            'input' => array(
                'options'    => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class'
                ),
                'attributes' => array(
                    'style'     => 'margin:10px',
                    'width'     => '100px',
                    'height'    => '100px',
                    'align'     => 'center',
                    'alt'       => 'alt-text',
                    'title'     => 'Image Title',
                    'directory' => 'media'
                ),
                'value'      => 'foobar.jpg',
                'filesystem' => VfsHelper::createArrayDir($paths)
            ),
            'check' => array(
                'case' => 'All extra options and attributes set',
                'result' => '<img src="http://www.example.com/media/foobar.jpg" alt="alt-text" id="foo-id" class=" foo-class" style="margin:10px" width="100px" height="100px" align="center" title="Image Title" />'
            )
        );

        return $data;
    }
}
