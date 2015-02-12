<?php
/**
 * @package		FOF3 (Work In Progress)
 * @copyright	2015 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

class temp
{
	/**
	 * Installs FOF if necessary
	 *
	 * @param   \JInstallerAdapterComponent  $parent  The parent object
	 *
	 * @return  array  The installation status
	 */
	protected function installFOF($parent)
	{
		// Get the source path
		$src = $parent->getParent()->getPath('source');
		$source = $src . '/' . $this->fofSourcePath;

		if (!JFolder::exists($source))
		{
			return array(
				'required'  => false,
				'installed' => false,
				'version'   => '0.0.0',
				'date'      => '2011-01-01',
			);
		}

		// Get the target path
		if (!defined('JPATH_LIBRARIES'))
		{
			$target = JPATH_ROOT . '/libraries/fof30';
		}
		else
		{
			$target = JPATH_LIBRARIES . '/fof30';
		}

		// Do I have to install FOF?
		$haveToInstallFOF = false;

		if (!JFolder::exists($target))
		{
			// FOF is not installed; install now
			$haveToInstallFOF = true;
		}
		else
		{
			// FOF is already installed; check the version
			$fofVersion = array();

			if (JFile::exists($target . '/version.txt'))
			{
				$rawData = @file_get_contents($target . '/version.txt');
				$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
				$info = explode("\n", $rawData);
				$fofVersion['installed'] = array(
					'version' => trim($info[0]),
					'date'    => new JDate(trim($info[1]))
				);
			}
			else
			{
				$fofVersion['installed'] = array(
					'version' => '0.0',
					'date'    => new JDate('2011-01-01')
				);
			}

			$rawData = @file_get_contents($source . '/version.txt');
			$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
			$info = explode("\n", $rawData);

			$fofVersion['package'] = array(
				'version' => trim($info[0]),
				'date'    => new JDate(trim($info[1]))
			);

			$haveToInstallFOF = $fofVersion['package']['date']->toUNIX() > $fofVersion['installed']['date']->toUNIX();
		}

		$installedFOF = false;

		if ($haveToInstallFOF)
		{
			$versionSource = 'package';
			$installer = new JInstaller;
			$installedFOF = $installer->install($source);
		}
		else
		{
			$versionSource = 'installed';
		}

		if (!isset($fofVersion))
		{
			$fofVersion = array();

			if (JFile::exists($target . '/version.txt'))
			{
				$rawData = @file_get_contents($source . '/version.txt');
				$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
				$info = explode("\n", $rawData);
				$fofVersion['installed'] = array(
					'version' => trim($info[0]),
					'date'    => new JDate(trim($info[1]))
				);
			}
			else
			{
				$fofVersion['installed'] = array(
					'version' => '0.0',
					'date'    => new JDate('2011-01-01')
				);
			}

			$rawData = @file_get_contents($source . '/version.txt');
			$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
			$info = explode("\n", $rawData);

			$fofVersion['package'] = array(
				'version' => trim($info[0]),
				'date'    => new JDate(trim($info[1]))
			);

			$versionSource = 'installed';
		}

		if (!($fofVersion[$versionSource]['date'] instanceof JDate))
		{
			$fofVersion[$versionSource]['date'] = new JDate();
		}

		return array(
			'required'  => $haveToInstallFOF,
			'installed' => $installedFOF,
			'version'   => $fofVersion[$versionSource]['version'],
			'date'      => $fofVersion[$versionSource]['date']->format('Y-m-d'),
		);
	}

	/**
	 * Installs Akeeba Strapper if necessary
	 *
	 * @param   \JInstallerAdapterComponent  $parent  The parent object
	 *
	 * @return  array  The installation status
	 */
	protected function installStrapper($parent)
	{
		$src = $parent->getParent()->getPath('source');
		$source = $src . '/' . $this->strapperSourcePath;

		$target = JPATH_ROOT . '/media/akeeba_strapper';

		if (!JFolder::exists($source))
		{
			return array(
				'required'  => false,
				'installed' => false,
				'version'   => '0.0.0',
				'date'      => '2011-01-01',
			);
		}

		$haveToInstallStrapper = false;

		if (!JFolder::exists($target))
		{
			$haveToInstallStrapper = true;
		}
		else
		{
			$strapperVersion = array();

			if (JFile::exists($target . '/version.txt'))
			{
				$rawData = @file_get_contents($target . '/version.txt');
				$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
				$info = explode("\n", $rawData);
				$strapperVersion['installed'] = array(
					'version' => trim($info[0]),
					'date'    => new JDate(trim($info[1]))
				);
			}
			else
			{
				$strapperVersion['installed'] = array(
					'version' => '0.0',
					'date'    => new JDate('2011-01-01')
				);
			}

			$rawData = @file_get_contents($source . '/version.txt');
			$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
			$info = explode("\n", $rawData);
			$strapperVersion['package'] = array(
				'version' => trim($info[0]),
				'date'    => new JDate(trim($info[1]))
			);

			$haveToInstallStrapper = $strapperVersion['package']['date']->toUNIX() > $strapperVersion['installed']['date']->toUNIX();
		}

		$installedStraper = false;

		if ($haveToInstallStrapper)
		{
			$versionSource = 'package';
			$installer = new JInstaller;
			$installedStraper = $installer->install($source);
		}
		else
		{
			$versionSource = 'installed';
		}

		if (!isset($strapperVersion))
		{
			$strapperVersion = array();

			if (JFile::exists($target . '/version.txt'))
			{
				$rawData = @file_get_contents($target . '/version.txt');
				$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
				$info = explode("\n", $rawData);
				$strapperVersion['installed'] = array(
					'version' => trim($info[0]),
					'date'    => new JDate(trim($info[1]))
				);
			}
			else
			{
				$strapperVersion['installed'] = array(
					'version' => '0.0',
					'date'    => new JDate('2011-01-01')
				);
			}

			$rawData = @file_get_contents($source . '/version.txt');
			$rawData = ($rawData === false) ? "0.0.0\n2011-01-01\n" : $rawData;
			$info = explode("\n", $rawData);

			$strapperVersion['package'] = array(
				'version' => trim($info[0]),
				'date'    => new JDate(trim($info[1]))
			);

			$versionSource = 'installed';
		}

		if (!($strapperVersion[$versionSource]['date'] instanceof JDate))
		{
			$strapperVersion[$versionSource]['date'] = new JDate();
		}

		return array(
			'required'  => $haveToInstallStrapper,
			'installed' => $installedStraper,
			'version'   => $strapperVersion[$versionSource]['version'],
			'date'      => $strapperVersion[$versionSource]['date']->format('Y-m-d'),
		);
	}
}