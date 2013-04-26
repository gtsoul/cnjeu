<?php

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Cnj_jeux Component Route Helper
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @since 1.5
 */
abstract class Cnj_jeuxHelperRoute {
	
	protected static $lookup;
	protected static $view;

	
	public static function getCnj_jeuxRoute() 
        {
                $link = 'index.php?option=com_cnj_jeux&Itemid='.JRequest::getInt('Itemid');

		return $link;
	}

	/*protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_cnj_jeux');
			$items		= $menus->getItems('component_id', $component->id);

			//break;
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = self::$view;
					if (!isset(self::$lookup[$view])) {
						self::$lookup[$view] = array();
					}
					
					self::$lookup[$view] = $item->id;
					
				}
			}
		}
		return self::$lookup[self::$view];

	}*/
}
