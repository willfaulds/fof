<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Form\Header;

use JHtml;
use JText;

defined('_JEXEC') or die;

/**
 * Ordering field header
 */
class Ordering extends Field
{
	/**
	 * Get the header
	 *
	 * @return  string  The header HTML
	 */
	protected function getHeader()
	{
		$sortable = ($this->element['sortable'] != 'false');

		$view = $this->form->getView();
		$model = $this->form->getModel();

		$hasAjaxOrderingSupport = $view->hasAjaxOrderingSupport();

		if (!$sortable)
		{
			// Non sortable?! I'm not sure why you'd want that, but if you insist...
			return JText::_('JGRID_HEADING_ORDERING');
		}

		if (!$hasAjaxOrderingSupport)
		{
			// Ye olde Joomla! 2.5 method
			$html = JHTML::_('grid.sort', 'JFIELD_ORDERING_LABEL', 'ordering', $view->getLists()->order_Dir, $view->getLists()->order, 'browse');
			$html .= JHTML::_('grid.order', $model->get());

			return $html;
		}
		else
		{
			// The new, drag'n'drop ordering support WITH a save order button
			$html = JHtml::_(
				'grid.sort',
				'<i class="icon-menu-2"></i>',
				'ordering',
				$view->getLists()->order_Dir,
				$view->getLists()->order,
				null,
				'asc',
				'JGRID_HEADING_ORDERING'
			);

			$ordering = $view->getLists()->order == 'ordering';

			if ($ordering)
			{
				$html .= '<a href="javascript:saveorder(' . (count($model->get()) - 1) . ', \'saveorder\')" ' .
					'rel="tooltip" class="save-order btn btn-micro pull-right" title="' . JText::_('JLIB_HTML_SAVE_ORDER') . '">'
					. '<span class="icon-ok"></span></a>';
			}

			return $html;
		}
	}
}
