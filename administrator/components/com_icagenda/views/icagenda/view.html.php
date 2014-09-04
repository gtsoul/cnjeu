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
 * @version     3.2.5 2013-11-11
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport( 'joomla.filesystem.path' );

// iCagenda Class control (Joomla 2.5/3.x)
if(!class_exists('iCJView')) {
   if(version_compare(JVERSION,'3.0.0','ge')) {
      class iCJView extends JViewLegacy {
      };
   } else {
      jimport('joomla.application.component.view');
      class iCJView extends JView {};
   }
}

// Access check.
if (JFactory::getUser()->authorise('core.admin', 'com_icagenda')) {
	JToolBarHelper::preferences('com_icagenda');
}

/**
 * View class for a list of iCagenda.
 */
class iCagendaViewicagenda extends iCJView
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		if(version_compare(JVERSION, '3.0', 'lt')) {

			JHTML::_('stylesheet', 'icagenda.css', 'administrator/components/com_icagenda/add/css/');
			JHTML::_('stylesheet', 'template.css', 'administrator/components/com_icagenda/add/css/');
			JHTML::_('stylesheet', 'icagenda.j25.css', 'administrator/components/com_icagenda/add/css/');
			JHTML::_('behavior.tooltip');
			JHTML::_('behavior.modal');
			JHTML::script('template.js', 'administrator/components/com_icagenda/add/js/');
			jimport( 'joomla.filesystem.path' );

		} else {
 			JHtml::_('behavior.modal');
	       	$document = JFactory::getDocument();
			$document->addStyleSheet( JURI::base().'components/com_icagenda/add/css/icagenda.css' );
		}

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
		require_once JPATH_COMPONENT . '/helpers/icagenda.php';

		$state	= $this->get('State');
		$canDo	= iCagendaHelper::getActions($state->get('filter.category_id'));

		//JToolBarHelper::title(JText::_('COM_ICAGENDA_TITLE_ICAGENDA_IMAGE'));
		// Set Title
		if(version_compare(JVERSION, '3.0', 'lt')) {
			JToolBarHelper::title(JText::_('COM_ICAGENDA_TITLE_ICAGENDA_IMAGE'));
		} else {
			$logo_icagenda_url = '../media/com_icagenda/images/iconicagenda36.png';
			if(file_exists($logo_icagenda_url)) {
				$logo_icagenda = '<img src="'.$logo_icagenda_url.'" height="36px" alt="iCagenda" />';
			} else {
				$logo_icagenda = 'iCagenda :: '.JText::_('COM_ICAGENDA_TITLE_ICAGENDA').'';
			}
			JToolBarHelper::title($logo_icagenda, 'icagenda');
		}

		$icTitle = JText::_('COM_ICAGENDA_TITLE_ICAGENDA');

		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$sitename = $app->getCfg('sitename');
		$title = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;
		$document->setTitle($title);

	}
}
