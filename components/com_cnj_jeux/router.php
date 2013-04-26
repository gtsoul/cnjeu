<?php
/**
 * @version		$Id: router.php 21321 2011-05-11 01:05:59Z dextercowley $
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Build the route for the com_content component
 *
 * @param	array	An array of URL arguments
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 * @since	1.5
 */
function Cnj_jeuxBuildRoute(&$query)
{
		
	$segments	= array();

	// get a menu item based on Itemid or currently active
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$params		= JComponentHelper::getParams('com_content');
	$advanced	= $params->get('sef_advanced_link', 0);

	// we need a menu item.  Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
		$menuItemGiven = false;
	}
	else {
		$menuItem = $menu->getItem($query['Itemid']);
		$menuItemGiven = true;
	}

	if (isset($query['view'])) {
		$view = $query['view'];
	}
	else {
		// we need to have a view in the query or it is an invalid URL
		return $segments;
	}
	//unset($query['view']);
	if ($view == 'fiche' && isset($query['alias']) )
	{
		if (!$menuItemGiven) {
			$segments[] = $view;
		}

		$segments[] = $query['alias'];
		unset($query['alias']);

	}
        
        /*if (isset($query['start']) )
	{
            $segments[] = $query['start'];
	}*/


	return $segments;
}



/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 * @since	1.5
 */
function Cnj_jeuxParseRoute($segments)
{	
	$vars = array();

	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_cnj_jeux');
	$advanced = $params->get('sef_advanced_link', 0);
	$db = JFactory::getDBO();

	// Count route segments
	$count = count($segments);

	// if there is only one segment, then it points to either an article or a category
	// we test it first to see if it is a category.  If the id and alias match a category
	// then we assume it is a category.  If they don't we assume it is an article
	if ($count == 1) {
		
            /*$lang=& JFactory::getLanguage();
            $langTag = substr($lang->getTag() ,0, strpos($lang->getTag(), '-') ) ;

            $query = 'SELECT * FROM socotec_pays WHERE alias_' . $langTag . ' = "'.$segments[0] . '"';

            $db->setQuery($query);
            $fiche = $db->loadObject();

            if ($fiche) {

                            $vars['view'] = 'fiche';
                            $vars['code'] = (int)$fiche->id;

                            return $vars;

            }*/
		
	}

	return $vars;
}
