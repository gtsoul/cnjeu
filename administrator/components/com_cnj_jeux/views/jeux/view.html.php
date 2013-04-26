<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of jeux.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 * @since       1.6
 */
class Cnj_jeuxViewJeux extends JView
{
	//protected $categories;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		//$this->categories	= $this->get('CategoryOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/cnj_jeux.php';

		$canDo = Cnj_jeuxHelper::getActions($this->state->get('filter.category_id'));
		$user = JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_CNJ_JEUX_MANAGER_JEUX'), 'jeux.png');
      
                if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('jeu.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolBarHelper::editList('jeu.edit');
		}

		if (($canDo->get('core.export')))
		{
                        JToolBarHelper::divider();
                        
                        $bar = JToolBar::getInstance('toolbar');
			//$bar->appendButton('Popup', 'export', 'JTOOLBAR_EXPORT', 'index.php?option=com_cnj_jeux&amp;view=export&amp;tmpl=component', 600, 300);
                        $bar->appendButton('Popup', 'export', 'JTOOLBAR_EXPORT', 'index.php?option=com_cnj_jeux&amp;task=jeux.display&amp;format=raw', 600, 300);
		}
                
                if ($canDo->get('core.edit.state'))
		{
                        JToolBarHelper::divider();
                        JToolBarHelper::deleteList('Etes-vous sur de vouloir supprimer ?', 'jeux.delete');
		}
	}
}
