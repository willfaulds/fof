<?php
/**
 * @package		FOF3 (Work In Progress)
 * @copyright	2015 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace FOF30\View;

/**
 * TEMPORARY CLASS – WORK IN PROGRESS – NOTHING TO SEE HERE, MOVE ON
 *
 * Class ViewPlus
 *
 * @package FOF30\View
 */
class ViewPlus extends View
{
	/**
	 * All of the finished, captured sections.
	 *
	 * @var array
	 */
	protected $sections = array();

	/**
	 * The stack of in-progress sections.
	 *
	 * @var array
	 */
	protected $sectionStack = array();

	/**
	 * The number of active rendering operations.
	 *
	 * @var int
	 */
	protected $renderCount = 0;

	/**
	 * Aliases of view templates. For example:
	 *
	 * array('userProfile' => 'site://com_foobar/users/profile')
	 *
	 * allows you to do something like $this->loadAnyTemplate('userProfile') to display the frontend view template
	 * site://com_foobar/users/profile. You can also alias one view template with another, e.g.
	 * 'site://com_something/users/profile' => 'admin://com_foobar/clients/record'
	 *
	 * @var  array
	 */
	protected $viewTemplateAliases = array();


	/**
	 * Add an alias for a view template.
	 *
	 * @param  string  $viewTemplate  Existing view template, in the format componentPart://componentName/viewName/layoutName
	 * @param  string  $alias         The alias of the view template (any string will do)
	 *
     * @return void
	 */
	public function alias($viewTemplate, $alias)
	{
		$this->aliases[$alias] = $viewTemplate;
	}

	/**
	 * Loads a template given any path. The path is in the format componentPart://componentName/viewName/layoutName,
	 * for example
	 * site:com_example/items/default
	 * admin:com_example/items/default_subtemplate
	 * auto:com_example/things/chair
	 * any:com_example/invoices/printpreview
	 *
	 * @param   string    $uri          The template path
	 * @param   array     $forceParams  A hash array of variables to be extracted in the local scope of the template file
	 * @param   callable  $callback     A method to post-process the evaluated view template
	 *
	 * @return  string  The output of the template
	 *
	 * @throws  \Exception  When the layout file is not found
	 */
	public function loadAnyTemplate($uri = '', $forceParams = array(), $callback = null)
	{
		if (isset($this->viewTemplateAliases[$uri]))
		{
			$uri = $this->viewTemplateAliases[$uri];
		}

		$layoutTemplate = $this->getLayoutTemplate();

		$extraPaths = array();

		if (isset($this->_path) || property_exists($this, '_path'))
		{
			$extraPaths = $this->_path['template'];
		}
		elseif (isset($this->path) || property_exists($this, 'path'))
		{
			$extraPaths = $this->path['template'];
		}

		// First get the raw view template path
		$path = $this->viewFinder->resolveUriToPath($uri, $layoutTemplate, $extraPaths);

		// Now get the parsed view template path
		$this->_tempFilePath = $this->getEngine($path)->get($path, $forceParams);

		// We will keep track of the amount of views being rendered so we can flush
		// the section after the complete rendering operation is done. This will
		// clear out the sections for any separate views that may be rendered.
		$this->incrementRender();

		// Get the evaluated template
		$contents = $this->evaluateTemplate($forceParams);

		// Once we've finished rendering the view, we'll decrement the render count
		// so that each sections get flushed out next time a view is created and
		// no old sections are staying around in the memory of an environment.
		$this->decrementRender();

		$response = isset($callback) ? $callback($this, $contents) : null;

		if (!is_null($response))
		{
			$contents = $response;
		}

		// Once we have the contents of the view, we will flush the sections if we are
		// done rendering all views so that there is nothing left hanging over when
		// another view gets rendered in the future by the application developer.
		$this->flushSectionsIfDoneRendering();

		return $contents;
	}

	/**
	 * Increment the rendering counter.
	 *
	 * @return void
	 */
	public function incrementRender()
	{
		$this->renderCount++;
	}

	/**
	 * Decrement the rendering counter.
	 *
	 * @return void
	 */
	public function decrementRender()
	{
		$this->renderCount--;
	}

	/**
	 * Check if there are no active render operations.
	 *
	 * @return bool
	 */
	public function doneRendering()
	{
		return $this->renderCount == 0;
	}

	/**
	 * Go through a data array and render a subtemplate against each record (think master-detail views). This is
	 * accessible through Blade templates as @each
	 *
	 * @param  string  $viewTemplate  The view template to use for each subitem, format componentPart://componentName/viewName/layoutName
	 * @param  array   $data          The array of data you want to render. It can be a DataModel\Collection, array, ...
	 * @param  string  $eachItemName  How to call each item in the loaded subtemplate (passed through $forceParams)
	 * @param  string  $empty         What to display if the array is empty
	 *
     * @return string
	 */
	public function renderEach($viewTemplate, $data, $eachItemName, $empty = 'raw|')
	{
		$result = '';

		// If is actually data in the array, we will loop through the data and append
		// an instance of the partial view to the final result HTML passing in the
		// iterated value of this data array, allowing the views to access them.
		if (count($data) > 0)
		{
			foreach ($data as $key => $value)
			{
				$data = array('key' => $key, $eachItemName => $value);

				$result .= $this->loadAnyTemplate($viewTemplate, $data);
			}
		}
		// If there is no data in the array, we will render the contents of the empty
		// view. Alternatively, the "empty view" could be a raw string that begins
		// with "raw|" for convenience and to let this know that it is a string.
		else
		{
			if (starts_with($empty, 'raw|'))
			{
				$result = substr($empty, 4);
			}
			else
			{
				$result = $this->loadAnyTemplate($empty);
			}
		}

		return $result;
	}

	/**
	 * Start injecting content into a section.
	 *
	 * @param  string  $section
	 * @param  string  $content
	 * @return void
	 */
	public function startSection($section, $content = '')
	{
		if ($content === '')
		{
			if (ob_start())
			{
				$this->sectionStack[] = $section;
			}
		}
		else
		{
			$this->extendSection($section, $content);
		}
	}

	/**
	 * Stop injecting content into a section and return its contents.
	 *
	 * @return string
	 */
	public function yieldSection()
	{
		return $this->yieldContent($this->stopSection());
	}

	/**
	 * Stop injecting content into a section.
	 *
	 * @param  bool  $overwrite
	 * @return string
	 */
	public function stopSection($overwrite = false)
	{
		$last = array_pop($this->sectionStack);

		if ($overwrite)
		{
			$this->sections[$last] = ob_get_clean();
		}
		else
		{
			$this->extendSection($last, ob_get_clean());
		}

		return $last;
	}

	/**
	 * Stop injecting content into a section and append it.
	 *
	 * @return string
	 */
	public function appendSection()
	{
		$last = array_pop($this->sectionStack);

		if (isset($this->sections[$last]))
		{
			$this->sections[$last] .= ob_get_clean();
		}
		else
		{
			$this->sections[$last] = ob_get_clean();
		}

		return $last;
	}

	/**
	 * Append content to a given section.
	 *
	 * @param  string  $section
	 * @param  string  $content
	 * @return void
	 */
	protected function extendSection($section, $content)
	{
		if (isset($this->sections[$section]))
		{
			$content = str_replace('@parent', $content, $this->sections[$section]);
		}

		$this->sections[$section] = $content;
	}

	/**
	 * Get the string contents of a section.
	 *
	 * @param  string  $section
	 * @param  string  $default
	 * @return string
	 */
	public function yieldContent($section, $default = '')
	{
		$sectionContent = $default;

		if (isset($this->sections[$section]))
		{
			$sectionContent = $this->sections[$section];
		}

		return str_replace('@parent', '', $sectionContent);
	}

	/**
	 * Flush all of the section contents.
	 *
	 * @return void
	 */
	public function flushSections()
	{
		$this->sections = array();

		$this->sectionStack = array();
	}

	/**
	 * Flush all of the section contents if done rendering.
	 *
	 * @return void
	 */
	public function flushSectionsIfDoneRendering()
	{
		if ($this->doneRendering()) $this->flushSections();
	}
}