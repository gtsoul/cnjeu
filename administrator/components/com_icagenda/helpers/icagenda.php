<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.0 2013-05-23
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_icagenda')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

/**
 * iCagenda helper.
 */

class iCagendaHelper
{
	/**
	 * Configure the Linkbar.
	 */

	public static function addSubmenu($submenu)
	{


	if(version_compare(JVERSION, '3.0', 'lt')) {

		JSubMenuHelper::addEntry(
			JText::_('COM_ICAGENDA_TITLE_ICAGENDA'),
			'index.php?option=com_icagenda&view=icagenda',
			$submenu == 'icagenda'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ICAGENDA_TITLE_CATEGORIES'),
			'index.php?option=com_icagenda&view=categories',
			$submenu == 'categories'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ICAGENDA_TITLE_EVENTS'),
			'index.php?option=com_icagenda&view=events',
			$submenu == 'events'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ICAGENDA_TITLE_REGISTRATION'),
			'index.php?option=com_icagenda&view=registrations',
			$submenu == 'registrations'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ICAGENDA_TITLE_NEWSLETTER'),
			'index.php?option=com_icagenda&view=mail&layout=edit',
			$submenu == 'newsletter'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ICAGENDA_THEMES'),
			'index.php?option=com_icagenda&view=themes',
			$submenu == 'themes'
		);
//		JSubMenuHelper::addEntry(
//			JText::_('COM_ICAGENDA_HELP'),
//			'index.php?option=com_icagenda&view=help',
//			$submenu == 'help'
//		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ICAGENDA_INFO'),
			'index.php?option=com_icagenda&view=info',
			$submenu == 'info'
		);

		$document = JFactory::getDocument();

/**
 * Set Titles iCagenda
 */

                if ($submenu == 'icagenda'){
                        $document->setTitle(JText::_('COM_ICAGENDA'));
                }
                if ($submenu == 'categories'){
                        $document->setTitle(JText::_('COM_ICAGENDA') . ' | ' . JText::_('COM_ICAGENDA_TITLE_CATEGORIES'));
                }
                if ($submenu == 'events'){
                        $document->setTitle(JText::_('COM_ICAGENDA') . ' | ' . JText::_('COM_ICAGENDA_TITLE_EVENTS'));
                }
                if ($submenu == 'registrations'){
                        $document->setTitle(JText::_('COM_ICAGENDA') . ' | ' . JText::_('COM_ICAGENDA_TITLE_REGISTRATION'));
                }
                if ($submenu == 'newsletter'){
                        $document->setTitle(JText::_('COM_ICAGENDA') . ' | ' . JText::_('COM_ICAGENDA_TITLE_NEWSLETTER'));
                }
                if ($submenu == 'themes'){
                        $document->setTitle(JText::_('COM_ICAGENDA') . ' | ' . JText::_('COM_ICAGENDA_THEMES'));
                }
                if ($submenu == 'info'){
                        $document->setTitle(JText::_('COM_ICAGENDA') . ' | ' . JText::_('COM_ICAGENDA_INFO'));
                }


		$document->addStyleDeclaration('
			.icon48icagenda{background: url(../media/com_icagenda/images/XXX.png);}
			.icon-48-events {background: url(../media/com_icagenda/images/all_events-48.png) no-repeat;}
			.icon-48-event {background: url(../media/com_icagenda/images/new_event-48.png) no-repeat;}
			.icon-48-registration {background: url(../media/com_icagenda/images/registration-48.png) no-repeat;}
			.icon-48-categories {background: url(../media/com_icagenda/images/all_cats-48.png) no-repeat;}
			.icon-48-category {background: url(../media/com_icagenda/images/new_cat-48.png) no-repeat;}
			.icon-48-generic {background: url(../media/com_icagenda/images/iconicagenda48.png) no-repeat;}
			.icon-48-mail {background: url(../media/com_icagenda/images/newsletter-48.png) no-repeat;}
			.icon-48-themes {background: url(../media/com_icagenda/images/themes-48.png) no-repeat;}
			.icon-48-info {background: url(../media/com_icagenda/images/info-48.png) no-repeat;}
		');

	} else {

		JHtmlSidebar::addEntry(
			JText::_('COM_ICAGENDA_TITLE_ICAGENDA'),
			'index.php?option=com_icagenda&view=icagenda',
			$submenu == 'icagenda'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ICAGENDA_TITLE_CATEGORIES'),
			'index.php?option=com_icagenda&view=categories',
			$submenu == 'categories'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ICAGENDA_TITLE_EVENTS'),
			'index.php?option=com_icagenda&view=events',
			$submenu == 'events'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ICAGENDA_TITLE_REGISTRATION'),
			'index.php?option=com_icagenda&view=registrations',
			$submenu == 'registrations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ICAGENDA_TITLE_NEWSLETTER'),
			'index.php?option=com_icagenda&view=mail&layout=edit',
			$submenu == 'newsletter'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ICAGENDA_THEMES'),
			'index.php?option=com_icagenda&view=themes',
			$submenu == 'themes'
		);
//		JSubMenuHelper::addEntry(
//			JText::_('COM_ICAGENDA_HELP'),
//			'index.php?option=com_icagenda&view=help',
//			$submenu == 'help'
//		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ICAGENDA_INFO'),
			'index.php?option=com_icagenda&view=info',
			$submenu == 'info'
		);

	}
}
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 *
	 * MODIF LYR!C 09/08/2012
	 */

        public static function getActions($messageId = 0)
        {
                $user   = JFactory::getUser();
                $result = new JObject;

                if (empty($messageId)) {
                        $assetName = 'com_icagenda';
                }
                else {
                        $assetName = 'com_icagenda.message.'.(int) $messageId;
                }

                $actions = array(
                        'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete', 'core.edit.state', 'core.edit.own'
                );

                foreach ($actions as $action) {
                        $result->set($action,   $user->authorise($action, $assetName));
                }

                return $result;
        }
}
