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
 * @version     3.2.4 2013-10-25
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.helper');

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

/**
 * HTML View class - iCagenda.
 */
class icagendaViewList extends iCJView
{
	protected $return_page;

	// Methode JView
	function display($tpl = null)
	{

		// loading data
		$this->data = $this->get('Data');

		// loading params
		$app = JFactory::getApplication();
		$iCmenuParams = $app->getParams();

		// Menu Options
		$this->atlist = $iCmenuParams->get('atlist');
		$this->template = $iCmenuParams->get('template');
		$this->title = $iCmenuParams->get('title');
		$this->display_catDesc = $iCmenuParams->get('displayCatDesc_menu', '');
		$this->catDesc_opts = $iCmenuParams->get('displayCatDesc_checkbox', '');

		// Component Options
		$iCparams = JComponentHelper::getParams('com_icagenda');
		$this->copy = $iCparams->get('copy');
		$this->navposition = $iCparams->get('navposition');
		$this->GoogleMaps = $iCparams->get('GoogleMaps', 1);
		$this->CatDesc_global = $iCparams->get('CatDesc_global', '0');
		$this->CatDesc_global_opts = $iCparams->get('CatDesc_checkbox', '');


		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// ASSIGN
		$this->assignRef('params',		$iCmenuParams);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	protected function _prepareDocument() {

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		$title 		= null;
		// loading data
		$this->data = $this->get('Data');

		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description', '')) {
			$this->document->setDescription($this->params->get('menu-meta_description', ''));
		}

		if ($this->params->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords', ''));
		}

		if ($app->getCfg('MetaTitle') == '1' && $this->params->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->params->get('page_title', ''));
		}

	}
}
