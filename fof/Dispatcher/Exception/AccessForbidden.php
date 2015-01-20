<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Dispatcher\Exception;

defined('_JEXEC') or die;

/**
 * Exception thrown when the user is not allowed to access the requested resource
 */
class AccessForbidden extends \RuntimeException {}