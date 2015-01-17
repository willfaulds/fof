<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Toolbar;

use FOF30\Container\Container;
use FOF30\Controller\Controller;
use FOF30\Inflector\Inflector;
use FOF30\Utils\String;
use FOF30\View\DataView\DataViewInterface;
use FOF30\View\View;
use JToolBarHelper;
use JText;

defined('_JEXEC') or die;

/**
 * The Toolbar class renders the back-end component title area and the back-
 * and front-end toolbars.
 *
 * @since    1.0
 */
class Toolbar
{
	/** @var   Container   Component container */
	protected $container = null;

	/** @var   array   Permissions map, see the __construct method for more information */
	public $perms = array();

	/** @var   array   The links to be rendered in the toolbar */
	protected $linkbar = array();

	/** @var   bool   Should I render the submenu in the front-end? */
	protected $renderFrontendSubmenu = false;

	/** @var   bool   Should I render buttons in the front-end? */
	protected $renderFrontendButtons = false;

	/** @var  null|bool  Are we rendering a data-aware view? */
	protected $isDataView = null;

	/**
	 * Public constructor.
	 *
	 * The $config array can contain the following optional values:
	 *
	 * renderFrontendButtons		bool	Should I render buttons in the front-end of the component?
	 * renderFrontendSubmenu		bool	Should I render the submenu in the front-end of the component?
	 *
	 * @param   Container  $c       The container for the component
	 * @param   array      $config  The configuration overrides, see above
	 */
	public function __construct(Container $c, array $config = array())
	{
		// Store the container reference in this object
		$this->container = $c;

		// Get a reference to some useful objects
		$input = $this->container->input;
		$platform = $this->container->platform;

		// Get default permissions (can be overriden by the view)
		$perms = (object)array(
			'manage'    => $this->container->platform->authorise('core.manage', $input->getCmd('option', 'com_foobar')),
			'create'    => $this->container->platform->authorise('core.create', $input->getCmd('option', 'com_foobar')),
			'edit'      => $this->container->platform->authorise('core.edit', $input->getCmd('option', 'com_foobar')),
			'editstate' => $this->container->platform->authorise('core.edit.state', $input->getCmd('option', 'com_foobar')),
			'delete'    => $this->container->platform->authorise('core.delete', $input->getCmd('option', 'com_foobar')),
		);

		// Save front-end toolbar and submenu rendering flags if present in the config
		if (array_key_exists('renderFrontendButtons', $config))
		{
			$this->renderFrontendButtons = $config['renderFrontendButtons'];
		}

		if (array_key_exists('renderFrontendSubmenu', $config))
		{
			$this->renderFrontendSubmenu = $config['renderFrontendSubmenu'];
		}

		// If not in the administrative area, load the JToolbarHelper
		if (!$platform->isBackend())
		{
			// Needed for tests, so we can inject our "special" helper class
			// TODO Do we really need this?
			if (!class_exists('\\JToolbarHelper'))
			{
				$platformDirs = $platform->getPlatformBaseDirs();
				require_once $platformDirs['root'] . '/administrator/includes/toolbar.php';
			}

			// Things to do if we have to render a front-end toolbar
			if ($this->renderFrontendButtons)
			{
				// Load back-end toolbar language files in front-end
				$platform->loadTranslations('');

				// Needed for tests (we can fake we're not in the backend, but we are still in CLI!)
				if (!$platform->isCli())
				{
					// Load the core Javascript
					\JHtml::_('jquery.framework', true);
				}
			}
		}

		// Store permissions in the local toolbar object
		$this->perms = $perms;
	}

	/**
	 * Renders the toolbar for the current view and task
	 *
	 * @param   string   $view  The view of the component
	 * @param   string   $task  The exact task of the view
	 *
	 * @return  void
	 */
	public function renderToolbar($view = null, $task = null)
	{
		$input = $this->container->input;

		// If tmpl=component the default behaviour is to not render the toolbar
		if ($input->getCmd('tmpl', '') == 'component')
		{
			$render_toolbar = false;
		}
		else
		{
			$render_toolbar = true;
		}

		// If there is a render_toolbar=0 in the URL, do not render a toolbar
		$render_toolbar = $input->getBool('render_toolbar', $render_toolbar);

		if (!$render_toolbar)
		{
			return;
		}

		// Get the view and task
		if (empty($view))
		{
			$view = $input->getCmd('view', 'cpanel');
		}

		if (empty($task))
		{
			$task = $input->getCmd('task', 'default');
		}

		$this->view = $view;
		$this->task = $task;
		$view = Inflector::pluralize($view);

		// Check for an onViewTask method
		$methodName = 'on' . ucfirst($view) . ucfirst($task);

		if (method_exists($this, $methodName))
		{
			$this->$methodName();

			return;
		}

		// Check for an onView method
		$methodName = 'on' . ucfirst($view);

		if (method_exists($this, $methodName))
		{
			$this->$methodName();

			return;
		}

		// Check for an onTask method
		$methodName = 'on' . ucfirst($task);

		if (method_exists($this, $methodName))
		{
			$this->$methodName();

			return;
		}
	}

	/**
	 * Renders the toolbar for the component's Control Panel page
	 *
	 * @return  void
	 */
	public function onCpanelsBrowse()
	{
		if ($this->container->platform->isBackend() || $this->renderFrontendSubmenu)
		{
			$this->renderSubmenu();
		}

		if (!$this->container->platform->isBackend() && !$this->renderFrontendButtons)
		{
			return;
		}

		$option = $this->container->componentName;

		JToolBarHelper::title(JText::_(strtoupper($option)), str_replace('com_', '', $option));

		if (!$this->isDataView())
		{
			return;
		}

		JToolBarHelper::preferences($option);
	}

	/**
	 * Renders the toolbar for the component's Browse pages (the plural views)
	 *
	 * @return  void
	 */
	public function onBrowse()
	{
		// On frontend, buttons must be added specifically
		if ($this->container->platform->isBackend() || $this->renderFrontendSubmenu)
		{
			$this->renderSubmenu();
		}

		if (!$this->container->platform->isBackend() && !$this->renderFrontendButtons)
		{
			return;
		}

		// Setup
		$option = $this->container->componentName;
		$view = $this->container->input->getCmd('view', 'cpanel');

		// Set toolbar title
		$subtitle_key = strtoupper($option . '_TITLE_' . $view);
		JToolBarHelper::title(JText::_(strtoupper($option)) . ': ' . JText::_($subtitle_key), str_replace('com_', '', $option));

		if (!$this->isDataView())
		{
			return;
		}

		// Add toolbar buttons
		if ($this->perms->create)
		{
			JToolBarHelper::addNew();
		}

		if ($this->perms->edit)
		{
			JToolBarHelper::editList();
		}

		if ($this->perms->create || $this->perms->edit)
		{
			JToolBarHelper::divider();
		}

		if ($this->perms->editstate)
		{
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::divider();
		}

		if ($this->perms->delete)
		{
			$msg = JText::_($option . '_CONFIRM_DELETE');
			JToolBarHelper::deleteList(strtoupper($msg));
		}
	}

	/**
	 * Renders the toolbar for the component's Read pages
	 *
	 * @return  void
	 */
	public function onRead()
	{
		// On frontend, buttons must be added specifically
		if ($this->container->platform->isBackend() || $this->renderFrontendSubmenu)
		{
			$this->renderSubmenu();
		}

		if (!$this->container->platform->isBackend() && !$this->renderFrontendButtons)
		{
			return;
		}

		$option = $this->container->componentName;
		$componentName = str_replace('com_', '', $option);
		$view = $this->container->input->getCmd('view', 'cpanel');

		// Set toolbar title
		$subtitle_key = strtoupper($option . '_TITLE_' . $view . '_READ');
		JToolBarHelper::title(JText::_(strtoupper($option)) . ': ' . JText::_($subtitle_key), $componentName);

		if (!$this->isDataView())
		{
			return;
		}

		// Set toolbar icons
		JToolBarHelper::back();
	}

	/**
	 * Renders the toolbar for the component's Add pages
	 *
	 * @return  void
	 */
	public function onAdd()
	{
		// On frontend, buttons must be added specifically
		if (!$this->container->platform->isBackend() && !$this->renderFrontendButtons)
		{
			return;
		}

		$option = $this->container->componentName;
		$componentName = str_replace('com_', '', $option);
		$view = $this->container->input->getCmd('view', 'cpanel');

		// Set toolbar title
		$subtitle_key = strtoupper($option . '_TITLE_' . Inflector::pluralize($view)) . '_EDIT';
		JToolBarHelper::title(JText::_(strtoupper($option)) . ': ' . JText::_($subtitle_key), $componentName);

		if (!$this->isDataView())
		{
			return;
		}

		// Set toolbar icons
		if ($this->perms->edit || $this->perms->editown)
		{
			// Show the apply button only if I can edit the record, otherwise I'll return to the edit form and get a
			// 403 error since I can't do that
			JToolBarHelper::apply();
		}

		JToolBarHelper::save();

		if ($this->perms->create)
		{
			JToolBarHelper::custom('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		JToolBarHelper::cancel();
	}

	/**
	 * Renders the toolbar for the component's Edit pages
	 *
	 * @return  void
	 */
	public function onEdit()
	{
		// On frontend, buttons must be added specifically
		if (!$this->container->platform->isBackend() && !$this->renderFrontendButtons)
		{
			return;
		}

		$this->onAdd();
	}

	/**
	 * Removes all links from the link bar
	 *
	 * @return  void
	 */
	public function clearLinks()
	{
		$this->linkbar = array();
	}

	/**
	 * Get the link bar's link definitions
	 *
	 * @return  array
	 */
	public function &getLinks()
	{
		return $this->linkbar;
	}

	/**
	 * Append a link to the link bar
	 *
	 * @param   string      $name   The text of the link
	 * @param   string|null $link   The link to render; set to null to render a separator
	 * @param   boolean     $active True if it's an active link
	 * @param   string|null $icon   Icon class (used by some renderers, like the Bootstrap renderer)
	 * @param   string|null $parent The parent element (referenced by name)) Thsi will create a dropdown list
	 *
	 * @return  void
	 */
	public function appendLink($name, $link = null, $active = false, $icon = null, $parent = '')
	{
		$linkDefinition = array(
			'name'   => $name,
			'link'   => $link,
			'active' => $active,
			'icon'   => $icon
		);

		if (empty($parent))
		{
			if (array_key_exists($name, $this->linkbar))
			{
				$this->linkbar[$name] = array_merge($this->linkbar[$name], $linkDefinition);

				// If there already are some children, I have to put this view link in the "items" array in the first place
				if (array_key_exists('items', $this->linkbar[$name]))
				{
					array_unshift($this->linkbar[$name]['items'], $linkDefinition);
				}
			}
			else
			{
				$this->linkbar[$name] = $linkDefinition;
			}
		}
		else
		{
			if (!array_key_exists($parent, $this->linkbar))
			{
				$parentElement = $linkDefinition;
				$parentElement['name'] = $parent;
				$parentElement['link'] = null;
				$this->linkbar[$parent] = $parentElement;
				$parentElement['items'] = array();
			}
			else
			{
				$parentElement = $this->linkbar[$parent];

				if (!array_key_exists('dropdown', $parentElement) && !empty($parentElement['link']))
				{
					$newSubElement = $parentElement;
					$parentElement['items'] = array($newSubElement);
				}
			}

			$parentElement['items'][] = $linkDefinition;
			$parentElement['dropdown'] = true;

			if ($active)
			{
				$parentElement['active'] = true;
			}

			$this->linkbar[$parent] = $parentElement;
		}
	}

	/**
	 * Prefixes (some people erroneously call this "prepend" – there is no such word) a link to the link bar
	 *
	 * @param   string      $name   The text of the link
	 * @param   string|null $link   The link to render; set to null to render a separator
	 * @param   boolean     $active True if it's an active link
	 * @param   string|null $icon   Icon class (used by some renderers, like the Bootstrap renderer)
	 *
	 * @return  void
	 */
	public function prefixLink($name, $link = null, $active = false, $icon = null)
	{
		$linkDefinition = array(
			'name'   => $name,
			'link'   => $link,
			'active' => $active,
			'icon'   => $icon
		);
		array_unshift($this->linkbar, $linkDefinition);
	}

	/**
	 * Renders the submenu (toolbar links) for all detected views of this component
	 *
	 * @return  void
	 */
	public function renderSubmenu()
	{
		$views = $this->getMyViews();

		if (empty($views))
		{
			return;
		}

		$activeView = $this->container->input->getCmd('view', 'cpanel');

		foreach ($views as $view)
		{
			// Get the view name
			$key = strtoupper($this->container->componentName) . '_TITLE_' . strtoupper($view);

			//Do we have a translation for this key?
			if (strtoupper(JText::_($key)) == $key)
			{
				$altview = Inflector::isPlural($view) ? Inflector::singularize($view) : Inflector::pluralize($view);
				$key2 = strtoupper($this->container->componentName) . '_TITLE_' . strtoupper($altview);

				// Maybe we have for the alternative view?
				if (strtoupper(JText::_($key2)) == $key2)
				{
					// Nope, let's use the raw name
					$name = ucfirst($view);
				}
				else
				{
					$name = JText::_($key2);
				}
			}
			else
			{
				$name = JText::_($key);
			}

			$link = 'index.php?option=' . $this->container->componentName . '&view=' . $view;

			$active = $view == $activeView;

			$this->appendLink($name, $link, $active);
		}
	}

	/**
	 * Automatically detects all views of the component
	 *
	 * @return  array  A list of all views, in the order to be displayed in the toolbar submenu
	 */
	protected function getMyViews()
	{
		$views = array();
		$t_views = array();
		$using_meta = false;

		$componentPaths = $this->container->platform->getComponentBaseDirs($this->container->componentName);
		$searchPath = $componentPaths['main'] . '/views';
		$filesystem = $this->container->filesystem;

		$allFolders = $filesystem->folderFolders($searchPath);

		if (!empty($allFolders))
		{
			foreach ($allFolders as $folder)
			{
				$view = $folder;

				// View already added
				if (in_array(Inflector::pluralize($view), $t_views))
				{
					continue;
				}

				// Do we have a 'skip.xml' file in there?
				$files = $filesystem->folderFiles($searchPath . '/' . $view, '^skip\.xml$');

				if (!empty($files))
				{
					continue;
				}

				// Do we have extra information about this view? (ie. ordering)
				$meta = $filesystem->folderFiles($searchPath . '/' . $view, '^metadata\.xml$');

				// Not found, do we have it inside the plural one?
				if (!$meta)
				{
					$plural = Inflector::pluralize($view);

					if (in_array($plural, $allFolders))
					{
						$view = $plural;
						$meta = $filesystem->folderFiles($searchPath . '/' . $view, '^metadata\.xml$');
					}
				}

				if (!empty($meta))
				{
					$using_meta = true;
					$xml = simplexml_load_file($searchPath . '/' . $view . '/' . $meta[0]);
					$order = (int)$xml->foflib->ordering;
				}
				else
				{
					// Next place. It's ok since the index are 0-based and count is 1-based

					if (!isset($to_order))
					{
						$to_order = array();
					}

					$order = count($to_order);
				}

				$view = Inflector::pluralize($view);

				$t_view = new \stdClass;
				$t_view->ordering = $order;
				$t_view->view = $view;

				$to_order[] = $t_view;
				$t_views[] = $view;
			}
		}

		\JArrayHelper::sortObjects($to_order, 'ordering');
		$views = \JArrayHelper::getColumn($to_order, 'view');

		// If not using the metadata file, let's put the cpanel view on top
		if (!$using_meta)
		{
			$cpanel = array_search('cpanels', $views);

			if ($cpanel !== false)
			{
				unset($views[$cpanel]);
				array_unshift($views, 'cpanels');
			}
		}

		return $views;
	}

	/**
	 * Return the front-end toolbar rendering flag
	 *
	 * @return  boolean
	 */
	public function getRenderFrontendButtons()
	{
		return $this->renderFrontendButtons;
	}

	/**
	 * Return the front-end submenu rendering flag
	 *
	 * @return  boolean
	 */
	public function getRenderFrontendSubmenu()
	{
		return $this->renderFrontendSubmenu;
	}

	/**
	 * Is the view we are rendering the toolbar for a data-aware view?
	 *
	 * @return  bool
	 */
	public function isDataView()
	{
		if (is_null($this->isDataView))
		{
			$this->isDataView = false;
			$controller = $this->container->dispatcher->getController();
			$view = null;

			if (is_object($controller) && ($controller instanceof Controller))
			{
				$view = $controller->getView();
			}

			if (is_object($view) && ($view instanceof View))
			{
				$this->isDataView = $view instanceof DataViewInterface;
			}
		}

		return $this->isDataView;
	}
}