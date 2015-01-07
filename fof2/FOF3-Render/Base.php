<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Render;

use FOF30\Container\Container;
use FOF30\Input\Input;
use FOF30\Form\Form;
use FOF30\Mvc\DataModel as DataModel;

defined('_JEXEC') or die;

/**
 * Abstract view renderer class. The renderer is what turns XML view templates
 * into actual HTML code, renders the submenu links and potentially wraps the
 * HTML output in a div with a component-specific ID.
 *
 * @since    2.0
 */
abstract class Base
{
	/** @var int Priority of this renderer. Higher means more important */
	protected $priority = 50;

	/** @var int Is this renderer enabled? */
	protected $enabled = false;

	/**
	 * Returns the information about this renderer
	 *
	 * @return object
	 */
	public function getInformation()
	{
		return (object) array(
			'priority'	 => $this->priority,
			'enabled'	 => $this->enabled,
		);
	}

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   Input     $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	abstract public function preRender($view, $task, $input, $config = array());

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   Input     $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	abstract public function postRender($view, $task, $input, $config = array());

	/**
	 * Renders a Form and returns the corresponding HTML
	 *
	 * @param   Form      &$form     The form to render
	 * @param   DataModel  $model     The model providing our data
	 * @param   Input     $input     The input object
	 * @param   string    $formType  The form type: edit, browse or read
	 * @param   boolean   $raw       If true, the raw form fields rendering (without the surrounding form tag) is returned.
	 *
	 * @return  string    The HTML rendering of the form
	 */
	public function renderForm(Form &$form, DataModel $model, Input $input, $formType = null, $raw = false)
	{
		if (is_null($formType))
		{
			$formType = $form->getAttribute('type', 'edit');
		}
		else
		{
			$formType = strtolower($formType);
		}

		switch ($formType)
		{
			case 'browse':
				return $this->renderFormBrowse($form, $model, $input);
				break;

			case 'read':
				if ($raw)
				{
					return $this->renderFormRaw($form, $model, $input, 'read');
				}
				else
				{
					return $this->renderFormRead($form, $model, $input);
				}

				break;

			default:
				if ($raw)
				{
					return $this->renderFormRaw($form, $model, $input, 'edit');
				}
				else
				{
					return $this->renderFormEdit($form, $model, $input);
				}
				break;
		}
	}

	/**
	 * Renders the submenu (link bar) for a category view when it is used in a
	 * extension
	 *
	 * Note: this function has to be called from the addSubmenu function in
	 * 		 the ExtensionNameHelper class located in
	 * 		 administrator/components/com_ExtensionName/helpers/Extensionname.php
	 *
	 * Example Code:
	 *
	 *	class ExtensionNameHelper
	 *	{
	 * 		public static function addSubmenu($vName)
	 *		{
	 *			// Load FOF
	 *			include_once JPATH_LIBRARIES . '/fof30/include.php';
	 *
	 *			if (!defined('FOF30_INCLUDED'))
	 *			{
	 *				throw new \Exception('FOF is not installed');
	 *			}
	 *
	 *			$render = new FOF30\Render\Joomla3;
	 *          $container = new FOF30\Container\Container(array('com_babioonevent'));
	 *
	 *			$render->renderCategoryLinkbar($container);
	 *		}
	 *	}
	 *
	 * @param   Container  $container  The container of the extension
	 *
	 * @return  void
	 */
	public function renderCategoryLinkbar(Container $container)
	{
		// On command line don't do anything
		if ($container->platform->isCli())
		{
			return;
		}

		// Do not render a category submenu unless we are in the the admin area
		if (!$container->platform->isBackend())
		{
			return;
		}

		$toolbar = $container->toolbar;
		$container->toolbar->renderSubmenu();

		$this->renderLinkbarItems($toolbar);
	}

	/**
	 * Renders a Form for a Browse view and returns the corresponding HTML
	 *
	 * @param   Form   &$form  The form to render
	 * @param   DataModel  $model  The model providing our data
	 * @param   Input     $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormBrowse(Form &$form, DataModel $model, Input $input);

	/**
	 * Renders a Form for a Read view and returns the corresponding HTML
	 *
	 * @param   Form   &$form  The form to render
	 * @param   DataModel  $model  The model providing our data
	 * @param   Input     $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormRead(Form &$form, DataModel $model, Input $input);

	/**
	 * Renders a Form for an Edit view and returns the corresponding HTML
	 *
	 * @param   Form   &$form  The form to render
	 * @param   DataModel  $model  The model providing our data
	 * @param   Input     $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormEdit(Form &$form, DataModel $model, Input $input);

	/**
	 * Renders a raw Form and returns the corresponding HTML
	 *
	 * @param   Form   &$form     The form to render
	 * @param   DataModel  $model     The model providing our data
	 * @param   Input     $input     The input object
	 * @param   string    $formType  The form type e.g. 'edit' or 'read'
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormRaw(Form &$form, DataModel $model, Input $input, $formType);

	/**
	 * Renders a raw fieldset of a Form and returns the corresponding HTML
	 *
	 * @TODO: Convert to an abstract method or interface at FOF3
	 *
	 * @param   \stdClass  &$fieldset   The fieldset to render
	 * @param   Form   &$form       The form to render
	 * @param   DataModel  $model       The model providing our data
	 * @param   Input     $input       The input object
	 * @param   string    $formType    The form type e.g. 'edit' or 'read'
	 * @param   boolean   $showHeader  Should I render the fieldset's header?
	 *
	 * @return  string    The HTML rendering of the fieldset
	 */
	protected function renderFieldset(\stdClass &$fieldset, Form &$form, DataModel $model, Input $input, $formType, $showHeader = true)
	{

	}

	/**
	 * Renders a label for a fieldset.
	 *
	 * @param   object  	$field  	The field of the label to render
	 * @param   Form   	&$form      The form to render
	 * @param 	string		$title		The title of the label
	 *
	 * @return 	string		The rendered label
	 */
	protected function renderFieldsetLabel($field, Form &$form, $title)
	{

	}

	/**
	 * Checks if the fieldset defines a tab pane
	 *
	 * @param   \SimpleXMLElement  $fieldset
	 *
	 * @return  boolean
	 */
	protected function isTabFieldset($fieldset)
	{
		if (!isset($fieldset->class) || !$fieldset->class)
		{
			return false;
		}

		$class = $fieldset->class;
		$classes = explode(' ', $class);

		if (!in_array('tab-pane', $classes))
		{
			return false;
		}
		else
		{
			return in_array('active', $classes) ? 2 : 1;
		}
	}

}