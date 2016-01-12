<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class TagsDataprovider
{
    public static function getTestOnAfterSave()
    {
        $data[] = array(
            'input' => array(
                'tags' => null,
                'input' => array(
                    'task' => 'publish'
                ),
                'mock' => array(
                    'postProc' => true
                )
            ),
            'check' => array(
                'case' => 'Task we are not interested into',
                'exception' => false,
                'contentType' => 0,
                'checkContent' => false
            )
        );

        $data[] = array(
            'input' => array(
                'tags' => null,
                'input' => array(
                    'task' => 'apply'
                ),
                'mock' => array(
                    'postProc' => true
                )
            ),
            'check' => array(
                'case' => 'Apply task',
                'exception' => false,
                'contentType' => 2,
                'checkContent' => true
            )
        );

        $data[] = array(
            'input' => array(
                'tags' => null,
                'input' => array(
                    'task' => 'save'
                ),
                'mock' => array(
                    'postProc' => true
                )
            ),
            'check' => array(
                'case' => 'Save task',
                'exception' => false,
                'contentType' => 2,
                'checkContent' => true
            )
        );

        $data[] = array(
            'input' => array(
                'tags' => null,
                'input' => array(
                    'task' => 'savenew'
                ),
                'mock' => array(
                    'postProc' => true
                )
            ),
            'check' => array(
                'case' => 'Savenew task',
                'exception' => false,
                'contentType' => 2,
                'checkContent' => true
            )
        );

        $data[] = array(
            'input' => array(
                'tags' => array(99),
                'input' => array(
                    'task' => 'savenew'
                ),
                'mock' => array(
                    'postProc' => false
                )
            ),
            'check' => array(
                'case' => "Tags didn't change",
                'exception' => false,
                'contentType' => 1,
                'checkContent' => false
            )
        );

        $data[] = array(
            'input' => array(
                'tags' => null,
                'input' => array(
                    'task' => 'savenew'
                ),
                'mock' => array(
                    'postProc' => false
                )
            ),
            'check' => array(
                'case' => 'An error occurs in post processing',
                'exception' => true,
                'contentType' => 2,
                'checkContent' => true
            )
        );

        return $data;
    }

    public static function getTestOnAfterDelete()
    {
        $data[] = array(
            'input' => array(
                'mock' => array(
                    'delete' => true
                )
            ),
            'check' => array(
                'exception' => false
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'delete' => false
                )
            ),
            'check' => array(
                'exception' => true
            )
        );

        return $data;
    }

    public static function getTestOnAfterBind()
    {
        $data[] = array(
            'input' => array(
                'load' => 0,
                'tags' => '123'
            ),
            'check' => array(
                'case' => 'Tag field already loaded',
                'contentType' => false,
                'addKnown' => false,
                'result' => '123'
            )
        );

        $data[] = array(
            'input' => array(
                'load' => 1,
                'tags' => null
            ),
            'check' => array(
                'case' => 'Tag field empty - record loaded',
                'contentType' => true,
                'addKnown' => true,
                'result' => 99
            )
        );

        $data[] = array(
            'input' => array(
                'load' => 0,
                'tags' => null
            ),
            'check' => array(
                'case' => 'Tag field empty - record not loaded',
                'contentType' => true,
                'addKnown' => true,
                'result' => 99
            )
        );

        return $data;
    }
}