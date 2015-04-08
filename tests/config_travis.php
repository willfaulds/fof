<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Configuration for running the unit tests
 */

$fofTestConfig = array(
	'2.5' => realpath(__DIR__.'/environments/2.5'),
	'3.1' => realpath(__DIR__.'/environments/3.1'),
	'3.2' => realpath(__DIR__.'/environments/3.2'),
	/*'dbparams' => array(
		'host'  => '127.0.0.1',
		'user'  => 'travis',
		'pwd'   => '',
		'db'    => 'fof_test'
	)*/
);