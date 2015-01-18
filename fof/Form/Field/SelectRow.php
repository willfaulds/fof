<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Form\Field;

use FOF30\Form\FieldInterface;
use FOF30\Form\Form;
use FOF30\Model\DataModel;
use \JHtml;

defined('_JEXEC') or die;

/**
 * Form Field class for FOF
 * Renders the checkbox in browse views which allows you to select rows
 */
class SelectRow extends \JFormField implements FieldInterface
{
	/**
	 * @var  string  Static field output
	 */
	protected $static;

	/**
	 * @var  string  Repeatable field output
	 */
	protected $repeatable;

	/**
	 * The Form object of the form attached to the form field.
	 *
	 * @var    Form
	 */
	protected $form;

	/**
	 * A monotonically increasing number, denoting the row number in a repeatable view
	 *
	 * @var  int
	 */
	public $rowid;

	/**
	 * The item being rendered in a repeatable form field
	 *
	 * @var  DataModel
	 */
	public $item;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   2.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'static':
				if (empty($this->static))
				{
					$this->static = $this->getStatic();
				}

				return $this->static;
				break;

			case 'repeatable':
				if (empty($this->repeatable))
				{
					$this->repeatable = $this->getRepeatable();
				}

				return $this->repeatable;
				break;

			default:
				return parent::__get($name);
		}
	}

	/**
	 * Method to get the field input markup for this field type.
	 *
	 * @since 2.0
	 *
	 * @return  string  The field input markup.
	 *
	 * @throws  \Exception
	 */
	protected function getInput()
	{
		throw new \Exception(__CLASS__ . ' cannot be used in input forms');
	}

	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 *
	 * @throws  \Exception
	 */
	public function getStatic()
	{
		throw new \Exception(__CLASS__ . ' cannot be used in single item display forms');
	}

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 *
	 * @throws  \Exception
	 */
	public function getRepeatable()
	{
		if (!($this->item instanceof DataModel))
		{
			throw new \Exception(__CLASS__ . ' needs a FOFTable to act upon');
		}

		// Is this record checked out?
		$checked_out     = false;
		$locked_by_field = $this->item->getFieldAlias('locked_by');
		$myId            = $this->form->getContainer()->platform->getUser()->get('id', 0);

		if (property_exists($this->item, $locked_by_field))
		{
			$locked_by   = $this->item->$locked_by_field;
			$checked_out = ($locked_by != 0 && $locked_by != $myId);
		}

		// Get the key id for this record
		$key_field = $this->item->getKeyName();
		$key_id    = $this->item->$key_field;

		// Get the HTML
		return JHTML::_('grid.id', $this->rowid, $key_id, $checked_out);
	}
}
