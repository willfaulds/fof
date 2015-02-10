<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory\Scaffolding;

use FOF30\Container\Container;

/**
 * Scaffolding Builder
 *
 * Creates an automatic XML form definition to render a view based on the database fields you've got in the model. This
 * is not designed for production; it's designed to give you a way to quickly add some test data to your component
 * and get started really fast with FOF development.
 *
 * @package FOF30\Factory\Scaffolding
 */
class Builder
{
	/** @var  \FOF30\Container\Container  The container we belong to */
	protected $container = null;

	/** @var  bool  Should I save the scaffolding results? */
	protected $saveScaffolding = false;

	/**
	 * Create the scaffolding builder instance
	 *
	 * @param \FOF30\Container\Container $c
	 */
	public function __construct(Container $c)
	{
		$this->container = $c;

		$this->saveScaffolding = $this->container->factory->isSaveScaffolding();
	}

	/**
	 * Make a new scaffolding document
	 *
	 * @param   string  $requestedFilename  The requested filename, e.g. form.default.xml
	 * @param   string  $viewName           The name of the view this form will be used to render
	 *
	 * @return  string|null  The XML source or null if we can't make a scaffolding XML
	 */
	public function make($requestedFilename, $viewName)
	{
		// TODO
		return null;
	}
}