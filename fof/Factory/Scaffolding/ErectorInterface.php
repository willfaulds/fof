<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory\Scaffolding;

use FOF30\Model\DataModel;

interface ErectorInterface
{
	/**
	 * Construct the erector object
	 *
	 * @param   \FOF30\Factory\Scaffolding\Builder $parent
	 * @param   \FOF30\Model\DataModel             $model
	 */
	public function __construct(Builder $parent, DataModel $model);

	public function build();
}