<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
JLoader::register('Cnj_jeuxHelper', JPATH_COMPONENT.'/helpers/cnj_jeux.php');

/**
 * View to edit a reference.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.5
 */
class Cnj_JeuxViewReference extends JView
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id_reference == 0);
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		// Since we don't track these assets at the item level, use the category id.
		$canDo		= Cnj_jeuxHelper::getActions($this->item->id_reference,0);

		JToolBarHelper::title($isNew ? JText::_('COM_CNJ_JEUX_MANAGER_REFERENCE_NEW') : JText::_('COM_CNJ_JEUX_MANAGER_REFERENCE_EDIT'), 'jeux.png');

		if(array_key_exists('ofrom', $_GET ) && $_GET['ofrom'] == "jeu")
		{ 
			if (/*!$checkedOut && */($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_cnj_jeux', 'core.create')) > 0)) {
			JToolBarHelper::apply('reference.apply');
			}
		}
		else
		{	
		// If not checked out, can save the item.
		if (/*!$checkedOut && */($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_cnj_jeux', 'core.create')) > 0)) {
			JToolBarHelper::apply('reference.apply');
			JToolBarHelper::save('reference.save');

			if ($canDo->get('core.create')) {
				JToolBarHelper::save2new('reference.save2new');
			}
		}

		// If an existing item, can save to a copy.
		/*if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::save2copy('reference.save2copy');
		}*/

		if (empty($this->item->id_reference))  {
			JToolBarHelper::cancel('reference.cancel');
		}
		else {
			JToolBarHelper::cancel('reference.cancel', 'JTOOLBAR_CLOSE');
		}}
	}
}
