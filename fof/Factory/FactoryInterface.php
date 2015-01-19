<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory;

use FOF30\Container\Container;
use FOF30\Controller\Controller;
use FOF30\Dispatcher\Dispatcher;
use FOF30\Form\Form;
use FOF30\Model\Model;
use FOF30\Toolbar\Toolbar;
use FOF30\TransparentAuthentication\TransparentAuthentication;
use FOF30\View\View;

defined('_JEXEC') or die;

/**
 * Interface for the MVC object factory
 */
interface FactoryInterface
{
	/**
	 * Public constructor for the factory object
	 *
	 * @param  \FOF30\Container\Container $container  The container we belong to
	 */
	function __construct(Container $container);

	/**
	 * Create a new Controller object
	 *
	 * @param   string  $viewName  The name of the view we're getting a Controller for.
	 * @param   array   $config    Optional MVC configuration values for the Controller object.
	 *
	 * @return  Controller
	 */
	function controller($viewName, array $config = array());

	/**
	 * Create a new Model object
	 *
	 * @param   string  $viewName  The name of the view we're getting a Model for.
	 * @param   array   $config    Optional MVC configuration values for the Model object.
	 *
	 * @return  Model
	 */
	function model($viewName, array $config = array());

	/**
	 * Create a new View object
	 *
	 * @param   string  $viewName  The name of the view we're getting a View object for.
	 * @param   string  $viewType  The type of the View object. By default it's "html".
	 * @param   array   $config    Optional MVC configuration values for the View object.
	 *
	 * @return  View
	 */
	function view($viewName, $viewType = 'html', array $config = array());

	/**
	 * Creates a new Toolbar
	 *
	 * @param   array  $config  The configuration values for the Toolbar object
	 *
	 * @return  Toolbar
	 */
	function toolbar(array $config = array());

	/**
	 * Creates a new Dispatcher
	 *
	 * @param   array  $config  The configuration values for the Dispatcher object
	 *
	 * @return  Dispatcher
	 */
	function dispatcher(array $config = array());

	/**
	 * Creates a new TransparentAuthentication handler
	 *
	 * @param   array  $config  The configuration values for the TransparentAuthentication object
	 *
	 * @return  TransparentAuthentication
	 */
	function transparentAuthentication(array $config = array());

	/**
	 * Creates a new Form object
	 *
	 * @param   string  $name     The name of the form.
	 * @param   string  $source   The form source filename without path and .xml extension e.g. "form.default"
	 * @param   array   $options  Options to the Form object
	 * @param   bool    $replace  Should form fields be replaced if a field already exists with the same group/name?
	 * @param   bool    $xpath    An optional xpath to search for the fields.
	 *
	 * @return  Form|null  The loaded form or null if the form filename doesn't exist
	 */
	function form($name, $source, array $options = array(), $replace = true, $xpath = false);
}