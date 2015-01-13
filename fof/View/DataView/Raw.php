<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\View\DataView;

use FOF30\Model\DataModel;
use FOF30\Model\DataModel\Collection;
use FOF30\View\View;

defined('_JEXEC') or die;

/**
 * View for a raw data-driven view
 *
 * @property-read Collection $items      The records loaded
 * @property-read int                           $itemsCount The total number of items in the model (more than those loaded)
 * @property-read \JPagination    Pagination  object
 */
class Raw extends View
{
	/** @var   array  Data lists */
	protected $lists = null;

	/** @var \JPagination The pagination object */
	protected $pagination = null;

	/**
	 * Executes before rendering the page for the Browse task.
	 *
	 * @return  boolean  Return true to allow rendering of the page
	 */
	public function onBeforeBrowse()
	{
		// Create the lists object
		$this->lists = new \stdClass();

		// Load the model
		/** @var DataModel $model */
		$model = $this->getModel();

		// We want to persist the state in the session
		$model->savestate(1);

		// Ordering information
		$this->lists->order		 = $model->getState('filter_order', $model->getIdFieldName(), 'cmd');
		$this->lists->order_Dir	 = $model->getState('filter_order_Dir', 'DESC', 'cmd');

		// Display limits
		$this->lists->limitStart = $model->getState('limitstart', 0, 'int');
		$this->lists->limit      = $model->getState('limit', 0, 'int');

		// Assign items to the view
		$this->items      = $model->get();
		$this->itemsCount = $model->count();

		// Pagination
		$this->pagination = new \JPagination($this->itemsCount, $this->lists->limitStart, $this->lists->limit);

		return true;
	}
} 