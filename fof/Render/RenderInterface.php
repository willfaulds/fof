<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Render;


use FOF30\Container\Container;
use FOF30\Model\DataModel;
use FOF30\Form\FormInterface;

interface RenderInterface
{
	/**
	 * Returns the information about this renderer
	 *
	 * @return object
	 */
	function getInformation();

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param   string    $view      The current view
	 * @param   string    $task      The current task
	 * @param   Container $container The container
	 *
	 * @return  void
	 */
	function preRender($view, $task, Container $container);

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param   string    $view      The current view
	 * @param   string    $task      The current task
	 * @param   Container $container The container
	 *
	 * @return  void
	 */
	function postRender($view, $task, Container $container);

	/**
	 * Renders a Form and returns the corresponding HTML
	 *
	 * @param   FormInterface &$form     The form to render
	 * @param   DataModel     $model     The model providing our data
	 * @param   Container     $container The container
	 * @param   string        $formType  The form type: edit, browse or read
	 * @param   boolean       $raw       If true, the raw form fields rendering (without the surrounding form tag) is
	 *                                   returned.
	 *
	 * @return  string    The HTML rendering of the form
	 */
	function renderForm(FormInterface &$form, DataModel $model, Container $container, $formType = null, $raw = false);

	/**
	 * Renders a F0FForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   FormInterface &$form     The form to render
	 * @param   DataModel     $model     The model providing our data
	 * @param   Container     $container The container
	 *
	 * @return  string    The HTML rendering of the form
	 */
	function renderFormBrowse(FormInterface &$form, DataModel $model, Container $container);

	/**
	 * Renders a F0FForm for a Read view and returns the corresponding HTML
	 *
	 * @param   FormInterface &$form     The form to render
	 * @param   DataModel     $model     The model providing our data
	 * @param   Container     $container The container
	 *
	 * @return  string    The HTML rendering of the form
	 */
	function renderFormRead(FormInterface &$form, DataModel $model, Container $container);

	/**
	 * Renders a F0FForm for an Edit view and returns the corresponding HTML
	 *
	 * @param   FormInterface &$form     The form to render
	 * @param   DataModel     $model     The model providing our data
	 * @param   Container     $container The container
	 *
	 * @return  string    The HTML rendering of the form
	 */
	function renderFormEdit(FormInterface &$form, DataModel $model, Container $container);

	/**
	 * Renders a F0FForm for an Edit view and returns the corresponding HTML
	 *
	 * @param   FormInterface &$form     The form to render
	 * @param   DataModel     $model     The model providing our data
	 * @param   Container     $container The container
	 * @param   string        $formType  The form type: edit, browse or read
	 *
	 * @return  string    The HTML rendering of the form
	 */
	function renderFormRaw(FormInterface &$form, DataModel $model, Container $container, $formType = null);


	/**
	 * Renders the submenu (link bar) for a category view when it is used in a
	 * extension
	 *
	 * Note: this function has to be called from the addSubmenu function in
	 *         the ExtensionNameHelper class located in
	 *         administrator/components/com_ExtensionName/helpers/Extensionname.php
	 *
	 * @param   Container $container The name of the extension
	 *
	 * @return  void
	 */
	function renderCategoryLinkbar(Container $container);

	/**
	 * Renders a raw fieldset of a F0FForm and returns the corresponding HTML
	 *
	 * @param   \stdClass     &$fieldset  The fieldset to render
	 * @param   FormInterface &$form      The form to render
	 * @param   DataModel     $model      The model providing our data
	 * @param   Container     $container  The input object
	 * @param   string        $formType   The form type e.g. 'edit' or 'read'
	 * @param   boolean       $showHeader Should I render the fieldset's header?
	 *
	 * @return  string    The HTML rendering of the fieldset
	 */
	function renderFieldset(\stdClass &$fieldset, FormInterface &$form, DataModel $model, Container $container, $formType, $showHeader = true);

	/**
	 * Renders a label for a fieldset.
	 *
	 * @param   object        $field The field of the label to render
	 * @param   FormInterface &$form The form to render
	 * @param    string       $title The title of the label
	 *
	 * @return    string        The rendered label
	 */
	function renderFieldsetLabel($field, FormInterface &$form, $title);

	/**
	 * Checks if the fieldset defines a tab pane
	 *
	 * @param   \SimpleXMLElement $fieldset
	 *
	 * @return  boolean
	 */
	function isTabFieldset($fieldset);
}