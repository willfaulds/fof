<?php
/**
 * Imports the database data to run the tests
 *
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  stubs
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class FteststubsDbimport
{
	public function importdb()
	{
		require_once JPATH_SITE . '/configuration.php';
		$db = JFactory::getDbo();
		$buffer = file_get_contents(__DIR__ . '/../schema/dbtest.sql');

		$queries = JInstallerHelper::splitSql($buffer);

		if (count($queries) == 0)
		{
			// No queries to process
			return;
		}

		// Process each query in the $queries array (split out of sql file).
		foreach ($queries as $query)
		{
			$query = trim($query);

			if ($query != '' && $query{0} != '#')
			{
				$db->setQuery($query);

				if (!$db->execute())
				{
					echo $db->stderr(true);
					die();

					return false;
				}
			}
		}
	}
}

