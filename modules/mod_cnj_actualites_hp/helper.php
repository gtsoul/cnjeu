<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$com_path = JPATH_SITE.'/components/com_content/';

jimport('joomla.application.component.model');

JModel::addIncludePath($com_path . '/models', 'ContentModel');

class modCnjActualitesHpHelper
{
	public static function getList(&$params)
    {
        // Get the dbo
        $db = JFactory::getDbo();

        // Get an instance of the generic articles model
        $model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

        // Set application parameters in model
        $app = JFactory::getApplication();
        $appParams = $app->getParams();
        $model->setState('params', $appParams);

        // Set the filters based on the module params
        $model->setState('list.start', 0);
        $model->setState('list.limit', (int) $params->get('count', 3));
        $model->setState('filter.published', 1);

        // Category filter
        $model->setState('filter.category_id', $params->get('catid', array()));

        // Ordering
        $model->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
        $model->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));

        $items = $model->getItems();

        foreach ($items as &$item) {
            $item->slug = $item->id.':'.$item->alias;
            $item->catslug = $item->catid.':'.$item->category_alias;
            $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
        }

        return $items;
    }

    public static function getTitleModule(&$params)
    {
        switch($params->get("typeactualite")) {
            case 'joue1a7': $title = 'On joue de 0 à 8 ans'; break;
            case 'joueclub': $title = 'On joue en club'; break;
            case 'actualitescnj': $title = 'Actualités du CNJ'; break;
        }
        
        return $title;
    }

    public static function getGetVarSuffix(&$params)
    {
        switch($params->get("typeactualite")) {
            case 'joue1a7': $suffix = '&id=18&ItemId=179'; break;
            case 'joueclub': $suffix = '&id=19&ItemId=180'; break;
            case 'actualitescnj': $suffix = '&id=21&ItemId=181'; break;
            default : $suffix = '&id=11&ItemId=108';break;	 
	}
        return $suffix; 
    }

    



}
