<?php
// CDC J FLOAT BAR 1.0
// Created Dec 01 2010

// @copyright http://www.citeducommerce.com
// @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
// Form coding from Joomla mod_login

// no direct access
defined('_JEXEC') or die('Restricted access');
$ipath = JURI::base().'modules/mod_cdc_jfloating_bar/';

$nStyle = $params->get('nStyle');
$document =& JFactory::getDocument();
$document->addStyleSheet($ipath.'tmpl/css/'.$nStyle.'.css');
//$document->addCustomTag('<script language="javascript" type="text/javascript" src="'.$mainframe->getBasePath( 0, true ).'modules/mod_cdc_jfloating_bar/ut.js"></script>');

class modcdcjfloatingbarHelper
{
	function getReturnURL($params, $type)
	{
		if($itemid =  $params->get($type))
		{
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); //var_dump($menu);die;
			if ($item)
			{
				$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
			}
			else
			{
			// stay on the same page
			$uri = JFactory::getURI();
			$url = $uri->toString(array('path', 'query', 'fragment'));
			}

		}
		else
		{
			// stay on the same page
			$uri = JFactory::getURI();
			$url = $uri->toString(array('path', 'query', 'fragment'));
		}

		return base64_encode($url);
	}

	function getType()
	{
		$user = & JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
}
