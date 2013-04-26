<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: shipping.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Plugin
class JCckPluginShipping extends JPlugin
{
	protected static $construction	=	'cck_ecommerce_shipping';
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff
	
	// g_getPath
	public static function g_getPath( $type = '' )
	{
		return JURI::root( true ).'/plugins/'.self::$construction.'/'.$type;
	}
}
?>