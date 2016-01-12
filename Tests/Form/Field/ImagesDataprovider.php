<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

use FOF30\Tests\Helpers\VfsHelper;

class ImagesDataprovider
{
    public static function getTestGetFieldContents()
    {
        $paths = array(
            'media/foobar.jpg',
            'media/dummy.jpg'
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attributes' => array(),
                'value'      => '',
                'filesystem' => VfsHelper::createArrayDir($paths)
            ),
            'check' => array(
                'case'   => 'No value, file does not exist',
                'result' => '<span class=""></span>'
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
                'result' => '<span class=""></span>'
            )
        );

        $data[] = array(
            'input' => array(
                'options'    => array(),
                'attributes' => array(
                    'directory' => 'media'
                ),
                'value'      => array('foobar.jpg', 'dummy.jpg'),
                'filesystem' => VfsHelper::createArrayDir($paths)
            ),
            'check' => array(
                'case' => 'With value, file exists',
                'result' => '<span class=""><img src="http://www.example.com/media/foobar.jpg" alt="" /><img src="http://www.example.com/media/dummy.jpg" alt="" /></span>'
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
                'value'      => array('foobar.jpg', 'dummy.jpg'),
                'filesystem' => VfsHelper::createArrayDir($paths)
            ),
            'check' => array(
                'case' => 'All extra options and attributes set',
                'result' => '<span id="foo-id" class=" foo-class"><img src="http://www.example.com/media/foobar.jpg" alt="alt-text" class=" foo-class" style="margin:10px" width="100px" height="100px" align="center" title="Image Title" /><img src="http://www.example.com/media/dummy.jpg" alt="alt-text" class=" foo-class" style="margin:10px" width="100px" height="100px" align="center" title="Image Title" /></span>'
            )
        );

        return $data;
    }
}
