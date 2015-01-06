<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Configuration\Domain;

use SimpleXMLElement;

defined('_JEXEC') or die;

/**
 * Configuration parser for the models-specific settings
 *
 * @since    2.1
 */
class Models implements DomainInterface
{
	/**
	 * Parse the XML data, adding them to the $ret array
	 *
	 * @param   SimpleXMLElement  $xml   The XML data of the component's configuration area
	 * @param   array             &$ret  The parsed data, in the form of a hash array
	 *
	 * @return  void
	 */
	public function parseDomain(SimpleXMLElement $xml, array &$ret)
	{
		// Initialise
		$ret['models'] = array();

		// Parse model configuration
		$modelData = $xml->xpath('model');

		// Sanity check
		if (empty($modelData))
		{
			return;
		}

		foreach ($modelData as $aModel)
		{
			$key = (string) $aModel['name'];

			$ret['models'][$key]['behaviors'] = (string) $aModel->behaviors;
			$ret['models'][$key]['tablealias'] = $aModel->xpath('tablealias');
			$ret['models'][$key]['fields'] = array();
			$ret['models'][$key]['relations'] = array();

			$fieldData = $aModel->xpath('field');

			if (!empty($fieldData))
			{
				foreach ($fieldData as $field)
				{
					$k = (string) $field['name'];
					$ret['models'][$key]['fields'][$k] = (string) $field;
				}
			}

			$relationsData = $aModel->xpath('relation');

			if (!empty($relationsData))
			{
				foreach ($relationsData as $relationData)
				{
					$type = (string)$relationData['type'];
					$itemName = (string)$relationData['name'];

					if (empty($type) || empty($itemName))
					{
						continue;
					}

					$modelClass		= (string)$relationData['modelClass'];
					$localKey		= (string)$relationData['localKey'];
					$remoteKey		= (string)$relationData['remoteKey'];
					$ourPivotKey	= (string)$relationData['ourPivotKey'];
					$theirPivotKey	= (string)$relationData['theirPivotKey'];
					$pivotModel		= (string)$relationData['pivotModel'];
					$default		= (string)$relationData['default'];

					$default = !in_array($default, array('no', 'false', 0));

					$relation = array(
						'type'			=> $type,
						'itemName'		=> $itemName,
						'modelClass'	=> empty($modelClass) ? null : $modelClass,
						'localKey'		=> empty($localKey) ? null : $localKey,
						'remoteKey'		=> empty($remoteKey) ? null : $remoteKey,
						'default'		=> $default,
					);

					if (!empty($ourPivotKey) || !empty($theirPivotKey) || !empty($pivotModel))
					{
						$relation['ourPivotKey']	= empty($ourPivotKey) ? null : $ourPivotKey;
						$relation['theirPivotKey']	= empty($theirPivotKey) ? null : $theirPivotKey;
						$relation['pivotModel']	= empty($pivotModel) ? null : $pivotModel;
					}

					$ret['models'][$key]['relations'][] = $relation;
				}
			}
		}
	}

	/**
	 * Return a configuration variable
	 *
	 * @param   string  &$configuration  Configuration variables (hashed array)
	 * @param   string  $var             The variable we want to fetch
	 * @param   mixed   $default         Default value
	 *
	 * @return  mixed  The variable's value
	 */
	public function get(&$configuration, $var, $default)
	{
		$parts = explode('.', $var);

		$view = $parts[0];
		$method = 'get' . ucfirst($parts[1]);

		if (!method_exists($this, $method))
		{
			return $default;
		}

		array_shift($parts);
		array_shift($parts);

		$ret = $this->$method($view, $configuration, $parts, $default);

		return $ret;
	}

	/**
	 * Internal method to return the magic field mapping
	 *
	 * @param   string  $model           The model for which we will be fetching a field map
	 * @param   array   &$configuration  The configuration parameters hash array
	 * @param   array   $params          Extra options; key 0 defines the model we want to fetch
	 * @param   string  $default         Default magic field mapping; empty if not defined
	 *
	 * @return  array   Field map
	 */
	protected function getField($model, &$configuration, $params, $default = '')
	{
		$fieldmap = array();

		if (isset($configuration['models']['*']) && isset($configuration['models']['*']['fields']))
		{
			$fieldmap = $configuration['models']['*']['fields'];
		}

		if (isset($configuration['models'][$model]) && isset($configuration['models'][$model]['fields']))
		{
			$fieldmap = array_merge($fieldmap, $configuration['models'][$model]['fields']);
		}

		$map = $default;

		if (empty($params[0]))
		{
			$map = $fieldmap;
		}
		elseif (isset($fieldmap[$params[0]]))
		{
			$map = $fieldmap[$params[0]];
		}

		return $map;
	}

	/**
	 * Internal method to get model alias
	 *
	 * @param   string  $model           The model for which we will be fetching table alias
	 * @param   array   &$configuration  The configuration parameters hash array
	 * @param   array   $params          Extra options; key 0 defines the table we want to fetch
	 * @param   string  $default         Default table alias
	 *
	 * @return  string  Table alias
	 */
	protected function getTablealias($model, &$configuration, $params, $default = '')
	{
		$tablealias = $default;

		if (isset($configuration['models']['*'])
			&& isset($configuration['models']['*']['tablealias'])
			&& isset($configuration['models']['*']['tablealias'][0]))
		{
			$tablealias = (string) $configuration['models']['*']['tablealias'][0];
		}

		if (isset($configuration['models'][$model])
			&& isset($configuration['models'][$model]['tablealias'])
			&& isset($configuration['models'][$model]['tablealias'][0]))
		{
			$tablealias = (string) $configuration['models'][$model]['tablealias'][0];
		}

		return $tablealias;
	}

	/**
	 * Internal method to get model behaviours
	 *
	 * @param   string  $model           The model for which we will be fetching behaviours
	 * @param   array   &$configuration  The configuration parameters hash array
	 * @param   array   $params          Unused
	 * @param   string  $default         Default behaviour
	 *
	 * @return  string  Model behaviours
	 */
	protected function getBehaviors($model, &$configuration, $params, $default = '')
	{
		$behaviors = $default;

		if (isset($configuration['models']['*'])
			&& isset($configuration['models']['*']['behaviors']))
		{
			$behaviors = (string) $configuration['models']['*']['behaviors'];
		}

		if (isset($configuration['models'][$model])
			&& isset($configuration['models'][$model]['behaviors']))
		{
			$behaviors = (string) $configuration['models'][$model]['behaviors'];
		}

		return $behaviors;
	}

	/**
	 * Internal method to get model relations
	 *
	 * @param   string  $model           The model for which we will be fetching relations
	 * @param   array   &$configuration  The configuration parameters hash array
	 * @param   array   $params          Unused
	 * @param   string  $default         Default relations
	 *
	 * @return  array   Model relations
	 */
	protected function getRelations($model, &$configuration, $params, $default = '')
	{
		$relations = $default;

		if (isset($configuration['models']['*'])
			&& isset($configuration['models']['*']['relations']))
		{
			$relations = $configuration['models']['*']['relations'];
		}

		if (isset($configuration['models'][$model])
			&& isset($configuration['models'][$model]['relations']))
		{
			$relations = $configuration['models'][$model]['relations'];
		}

		return $relations;
	}

}