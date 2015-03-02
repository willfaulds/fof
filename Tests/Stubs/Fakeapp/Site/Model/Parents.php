<?php

namespace Fakeapp\Site\Model;

use FOF30\Model\DataModel;
use FOF30\Container\Container;

class Parents extends DataModel
{
    public function __construct(Container $container, array $config = array())
    {
        if(!isset($config['idFieldName']))
        {
            $config['idFieldName'] = 'foftest_parent_id';
        }

        if(!isset($config['tableName']))
        {
            $config['tableName'] = '#__foftest_parents';
        }

        // I have to manually disable autoChecks, otherwise FOF will try to search for the form, raising
        // a fatal error
        $config['autoChecks'] = false;

        parent::__construct($container, $config);
    }
}