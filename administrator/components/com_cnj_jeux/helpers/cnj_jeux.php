<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Cnj_jeux component helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_CNJ_JEUX_SUBMENU_JEUX'),
			'index.php?option=com_cnj_jeux&view=jeux',
			$vName == 'jeux'
		);

		JSubMenuHelper::addEntry(
			JText::_('com_cnj_jeux_SUBMENU_AUTEURS'),
			'index.php?option=com_cnj_jeux&view=auteurs',
			$vName == 'auteurs'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_CNJ_JEUX_SUBMENU_REFERENCES'),
			'index.php?option=com_cnj_jeux&view=references',
			$vName == 'references'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_CNJ_JEUX_SUBMENU_DOCUMENTS'),
			'index.php?option=com_cnj_jeux&view=documents',
			$vName == 'documents'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_CNJ_JEUX_SUBMENU_DISTINCTIONS'),
			'index.php?option=com_cnj_jeux&view=distinctions',
			$vName == 'distinctions'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_CNJ_JEUX_SUBMENU_MOTCLES'),
			'index.php?option=com_cnj_jeux&view=motcles',
			$vName == 'motcles'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_CNJ_JEUX_SUBMENU_MECANISMES'),
			'index.php?option=com_cnj_jeux&view=mecanismes',
			$vName == 'mecanismes'
		);



	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions($jeuId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($jeuId)) {
			$assetName = 'com_cnj_jeux';
		} else {
			$assetName = 'com_cnj_jeux.jeu.'.(int) $jeuId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete', 'core.export'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * @return	boolean
	 * @since	1.6
	 */
	public static function updateReset()
	{
		/*$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$nullDate = $db->getNullDate();
		$now = JFactory::getDate();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('cnj_jeu');
		$query->where("'".$now."' >= ".$db->quoteName('reset'));
		$query->where($db->quoteName('reset').' != '.$db->quote($nullDate).' AND '.$db->quoteName('reset').'!=NULL');
		$query->where('('.$db->quoteName('checked_out').' = 0 OR '.$db->quoteName('checked_out').' = '.(int) $db->Quote($user->id).')');
		$db->setQuery((string)$query);
		$rows = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
			return false;
		}

		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

		foreach ($rows as $row) {
			

			// Update the row ordering field.
			$query->clear();
			$query->update($db->quoteName('#__banners'));
			$query->set($db->quoteName('reset').' = '.$db->quote($reset));
			$query->set($db->quoteName('impmade').' = '.$db->quote(0));
			$query->set($db->quoteName('clicks').' = '.$db->quote(0));
			$query->where($db->quoteName('id').' = '.$db->quote($row->id));
			$db->setQuery((string)$query);
			$db->query();

			// Check for a database error.
			if ($db->getErrorNum()) {
				JError::raiseWarning(500, $db->getErrorMsg());
				return false;
			}
		}*/

		return true;
	}
}
