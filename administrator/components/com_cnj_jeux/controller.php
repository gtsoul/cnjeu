<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Cnj_jeux master display controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxController extends JController
{
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'jeux';
        
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/cnj_jeux.php';
		Cnj_jeuxHelper::updateReset();

		// Load the submenu.
		Cnj_jeuxHelper::addSubmenu(JRequest::getCmd('view', 'jeux'));

		$view	= JRequest::getCmd('view', 'jeux');
		$layout = JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');
    
    $document = JFactory::getDocument();
    $document->addStyleSheet(JUri::base() . "components/com_cnj_jeux/com_cnj_jeux.css");  

		// Check for edit form.
		if ($view == 'jeu' && $layout == 'edit' && !$this->checkEditId('com_cnj_jeux.edit.jeu', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_cnj_jeux&view=jeux', false));

			return false;
		}

		parent::display();

		return $this;
	}
}
