<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class modCnjSlideshowHelper
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
        $model->setState('list.limit', (int) $params->get('count', 5));
        $model->setState('filter.published', 1);

        // Category filter
        $model->setState('filter.category_id', $params->get('catid', array()));

        $model->setState('list.ordering', 'a.ordering');
        $model->setState('list.direction', 'ASC');

        $items = $model->getItems();

        foreach ($items as &$item) {
            //$item->slug = $item->id.':'.$item->alias;
            //$item->catslug = $item->catid.':'.$item->category_alias;
            //$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
        }

        return $items;
    }
}
