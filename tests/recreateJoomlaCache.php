<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

$options = getopt('dht:', array('help', 'table:', 'dry'));

$banner = <<<BANNER
=========================================================================
FrameworkOnFramework test suite.
=========================================================================
This script will rebuild the mocked Joomla cache looking directly at database test schema.
In this way we can safely alter the database schema without incurring in regression issues.

Type `recreateJoomlaCache.php -h` or `recreateJoomlaCache.php --help` for usage


BANNER;

echo $banner;

if(isset($options['h']) || isset($options['help']))
{
    $usage = <<< USAGE

    [-t]          Table name     Fetches fields from selected table(s), default to `#__foftest_foobars`.
    [--table]                    In order to specify multiple tables use this syntax: -t "table1,table2"

    [-d]          Dry run        Executes the logic but will output a `var_dump` of the extracted data,
    [--dry]                      instead of writing the cache file

USAGE;

    echo $usage;

    die();
}

require 'unit/bootstrap.php';

$db          = JFactory::getDbo();
$cache       = new JRegistry();
$errMsg      = '';
$fieldsCache = array();

try{
    $tables = $db->getTableList();

    $errMsg = $db->getErrorMsg();
}
catch (Exception $e)
{
    $errMsg = $e->getMessage();
}

if($errMsg)
{
    echo 'An error occurred while fetching table list: '.$errMsg."\n";
    echo 'I will just stop here';
    die();
}

echo 'Got '.count($tables)." tables\n";

$cache->set('tables', json_encode($tables));

if(isset($options['t']) || isset($options['table']))
{
    $tableOpt = isset($options['t']) ? $options['t'] : $options['tables'];

    $fieldTables = explode(',', $tableOpt);

    echo "Got ".count($fieldTables)." custom tables\n";
}
else
{
    $fieldTables = array('#__foftest_foobars');

    echo "Fetching fields for default table `#__foftest_foobars`\n";
}

foreach($fieldTables as $fieldTable)
{
    $prefix = $db->getPrefix();
    $name   = $fieldTable;

    if (substr($fieldTable, 0, 3) == '#__')
    {
        $name = $prefix . substr($name, 3);
    }

    if(version_compare(JVERSION, '3.0', 'ge'))
    {
        $fieldsCache[$fieldTable] = $db->getTableColumns($name, false);
    }
    else
    {
        $fields = $db->getTableFields($name, false);
        $fieldsCache[$fieldTable] = $fields[$name];
    }
}

$cache->set('tablefields', json_encode($fieldsCache));

if(isset($options['d']) || isset($options['dry']))
{
    var_dump($tables);
    var_dump($fieldsCache);
}
else
{
    echo "Saving data to: ".JPATH_TESTS."/unit/core/cache/cache_joomla.txt\n";

    $content = serialize($cache);
    file_put_contents(JPATH_TESTS.'/unit/core/cache/cache_joomla.txt', $content);
}

echo "\nOperation completed\n";