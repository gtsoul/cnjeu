<?php
/**
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once JPATH_SITE.'/components/com_content/helpers/route.php';

/**
 * HTML Article View class for the Cnj_jeux component
 *
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @since		1.5
 */
class Cnj_jeuxViewFiche extends JView
{
	protected $item;
	protected $params;
	protected $print;
	protected $state;
	protected $user;
	
	function display($tpl = null)
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$dispatcher	= JDispatcher::getInstance();

		$this->item		= $this->get('Item');
		$this->print	= JRequest::getBool('print');
		$this->state	= $this->get('State');
		$this->user		= $user;

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

                // on vérifie l'état de publication de l'item
                if($this->item->publication == 'non_publie') {
                    $limitstart = JRequest::getVar('limitstart');
                    //var_dump('index.php?option=com_cnj_jeux&view=categorie&Itemid='.JRequest::getVar('Itemid').(isset($limitstart)?'&limitstart='.$limitstart:''));
                    //die;
                    $app->redirect ('index.php?option=com_cnj_jeux&view=categorie&Itemid='.JRequest::getVar('Itemid').(isset($limitstart)?'&limitstart='.$limitstart:''));
                }
                
		// Create a shortcut for $item.
		$item = &$this->item;
                
		// Get articles category actu
                //require_once 'components/com_cnj_jeux/models/categorie.php';
                //$model = JModel::getInstance('Categorie', 'Cnj_jeuxModel', array('ignore_request' => true));
                //$model->setState('category.id', (int)$this->item->category_actu);
                //$this->categorie_fiches = $model->getItems();

		// Add router helpers.
		//$item->slug			= $item->alias ? ($item->id.':'.$item->alias) : $item->id;

		// Merge article params. If this is single-article view, menu params override article params
		// Otherwise, article params override menu item params
		$this->params	= $this->state->get('params');
		$active	= $app->getMenu()->getActive();
		$temp	= clone ($this->params);

		// Check to see which parameters should take priority
		if ($active) {
			$currentLink = $active->link;
			// If the current view is the active item and an article view for this article, then the menu item params take priority
			if (strpos($currentLink, 'view=fiche') && (strpos($currentLink, '&id='.(string) $item->id))) {
				// $item->params are the article params, $temp are the menu item params
				// Merge so that the menu item params take priority
				$item->params->merge($temp);
				// Load layout from active query (in case it is an alternative menu item)
				if (isset($active->query['layout'])) {
					$this->setLayout($active->query['layout']);
				}
			}
			else {
				// Current view is not a single article, so the article params take priority here
				// Merge the menu item params with the article params so that the article params take priority
				$temp->merge($item->params);
				$item->params = $temp;

				// Check for alternative layouts (since we are not in a single-article menu item)
				// Single-article menu item layout takes priority over alt layout for an article
				if ($layout = $item->params->get('article_layout')) {
					$this->setLayout($layout);
				}
			}
		}
		else {
			// Merge so that article params take priority
			$temp->merge($item->params);
			$item->params = $temp;
			// Check for alternative layouts (since we are not in a single-article menu item)
			// Single-article menu item layout takes priority over alt layout for an article
			if ($layout = $item->params->get('article_layout')) {
				$this->setLayout($layout);
			}
		}

		/*if ($item->params->get('show_intro','1')=='1') {
			$item->text = $item->introtext.' '.$item->fulltext;
		}
		else if ($item->fulltext) {
			$item->text = $item->fulltext;
		}
		else  {
			$item->text = $item->introtext;
		}*/

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx'));

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$pathway = $app->getPathway();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			//$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			//$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// if the menu item does not concern this article
		if ($menu && ($menu->query['option'] != 'com_cnj_jeux' || $menu->query['view'] != 'fiche' || $id != $this->item->id))
		{
			// If this is not a single article menu item, set the page title to the article title
			/*if ($this->item->title) {
				$title = $this->item->title;
			}
			$path = array(array('title' => $this->item->title, 'link' => ''));*/
			/*$category = JCategories::getInstance('Cnj_jeux')->get($this->item->catid);
			while ($category && ($menu->query['option'] != 'com_cnj_jeux' || $menu->query['view'] == 'fiche' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => Cnj_jeuxHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}
			$path = array_reverse($path);*/
			/*foreach($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}*/
		}

		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		if (empty($title)) {
			$title = $this->item->title;
		}
                
		$this->document->setTitle($title);

		/*if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif (!$this->item->metadesc && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		if ($app->getCfg('MetaAuthor') == '1')
		{
			$this->document->setMetaData('author', $this->item->author);
		}*/

		/*$mdata = $this->item->metadata->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}*/

		// If there is a pagebreak heading or title, add it to the page title
		if (!empty($this->item->page_title))
		{
			$this->item->title = $this->item->title . ' - ' . $this->item->page_title;
			//$this->document->setTitle($this->item->page_title . ' - ' . JText::sprintf('PLG_PAYS_PAGEBREAK_PAGE_NUM', $this->state->get('list.offset') + 1));
		}

		if ($this->print)
		{
			$this->document->setMetaData('robots', 'noindex, nofollow');
		}
	}
}
