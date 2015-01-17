<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\View\DataView;


interface DataViewInterface
{
	/**
	 * Determines if the current Joomla! version and your current table support AJAX-powered drag and drop reordering.
	 * If they do, it will set up the drag & drop reordering feature.
	 *
	 * @return  boolean|array  False if not supported, otherwise a table with necessary information (saveOrder: should
	 * 						   you enable DnD reordering; orderingColumn: which column has the ordering information).
	 */
	public function hasAjaxOrderingSupport();

	/**
	 * Returns the internal list of useful variables to the benefit of header fields.
	 *
	 * @return array
	 */
	public function getLists();

	/**
	 * Returns a reference to the permissions object of this view
	 *
	 * @return \stdClass
	 */
	public function getPerms();
}