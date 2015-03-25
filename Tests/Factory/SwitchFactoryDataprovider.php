<?php

class SwitchFactoryDataprovider
{
    public static function getTestController()
    {
        $data[] = array(
            array(
                'backend' => false,
                'view' => 'foobars'
            ),
            array(
                'case' => 'Frontend controller, found',
                'result' => 'Fakeapp\Site\Controller\Foobars'
            )
        );

        $data[] = array(
            array(
                'backend' => true,
                'view' => 'foobars'
            ),
            array(
                'case' => 'Backend controller, found',
                'result' => 'Fakeapp\Site\Controller\Foobars'
            )
        );

        return $data;
    }
}
