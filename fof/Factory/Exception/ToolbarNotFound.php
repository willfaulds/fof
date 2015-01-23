<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory\Exception;

use Exception;
use RuntimeException;

defined('_JEXEC') or die;

class ToolbarNotFound extends RuntimeException
{
	public function __construct( $message = "", $code = 500, Exception $previous = null )
	{
		if (empty($message))
		{
			$message = \JText::_('LIB_FOF_TOOLBAR_ERR_NOT_FOUND');
		}

		parent::__construct( $message, $code, $previous );
	}

}