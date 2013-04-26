<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: cck.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// JCck
abstract class JCck
{
	public static $_me			=	'cck';
	public static $_config		=	null;
	public static $_user		=	null;
	
	protected static $_host		=	null;
	protected static $_site		=	null;
	protected static $_sites	=	array();
	
	public static function callFunc( $class, $method, &$args = NULL, $ref = false )
	{
		return $class::$method( $args );
	}
	
	public static function callFunc_Array( $class, $method, $args )
	{
		return call_user_func_array( $class.'::'.$method, $args );
		/*
		switch ( count( $args ) ) {
			case 1: return $class::$method( $args[0] ); break;
			case 2: return $class::$method( $args[0], $args[1] ); break;
			case 3: return $class::$method( $args[0], $args[1], $args[2] ); break;
			case 4: return $class::$method( $args[0], $args[1], $args[2], $args[3] ); break;
			case 5: return $class::$method( $args[0], $args[1], $args[2], $args[3], $args[4] ); break;
			default: return call_user_func_array( $class.'::'.$method, $args ); break;
		}
		*/
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Config
	
	// _setConfig
	public static function _setConfig()
	{		
		if ( self::$_config ) {
			return self::$_config;
		}

		$config			=	new stdClass;
		$config->params =	JComponentHelper::getParams( 'com_'.self::$_me );
		
		self::$_config	=&	$config;
	}
	
	// getConfig
	public static function getConfig()
	{		
		if ( ! self::$_config ) {
			self::_setConfig();
		}
		
		return self::$_config;
	}
	
	// getConfig_Param
	public static function getConfig_Param( $name, $default = '' )
	{
		if ( ! self::$_config ) {
			self::_setConfig();
		}
		
		return self::$_config->params->get( $name, $default );
	}
	
	// getUIX
	public static function getUIX()
	{
		return ( self::getConfig_Param( 'uix', '' ) == 'nano' ) ? 'compact' : 'full';
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Site

	// _setMultisite
	public static function _setMultisite()
	{
		if ( (int)self::getConfig_Param( 'multisite', 0 ) ) {
			self::$_host	=	JURI::getInstance()->getHost();
			self::$_sites	=	JCckDatabase::loadObjectList( 'SELECT id, title, name, guest, guest_only_viewlevel, groups, viewlevels, configuration FROM #__cck_core_sites WHERE published = 1', 'name' );
			
			return true;
		} else {
			return false;
		}
	}
	
	// getSite
	public static function getSite()
	{
		return self::$_sites[self::$_host];
	}
	
	// isSite
	public static function isSite()
	{
		return ( self::$_host != '' && isset( self::$_sites[self::$_host] ) ) ? true : false;
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // User
	// REFACT ALL USER STUFF !! //
	
	// _setUser
	public static function _setUser( $userid = 0, $profile = true, $preferences = false )
	{		
		if ( self::$_user ) {
			return self::$_user;
		}

		jimport( 'cck.content.user' );
		self::$_user	=	CCK_User::getUser( $userid, $profile, $preferences );
	}
	
	// getUser
	public static function getUser( $userid = 0, $profile = true, $preferences = false )
	{
		if ( $userid ) {
			jimport( 'cck.content.user' );
			return CCK_User::getUser( $userid, $profile, $preferences );
		}
		
		if ( ! self::$_user ) {
			self::_setUser( $userid, $profile, $preferences );
		}
		
		return self::$_user;
	}
	
	// getUser
	public static function getUser_Value( $name, $default = '' )
	{
		if ( ! self::$_user ) {
			self::_setUser();
		}
				
		return ( @self::$_user->$name != '' ) ? @self::$_user->$name : $default;
	}
	
	// setUser_Preference
	public static function setUser_Preference( $name, $value )
	{
		if ( ! self::$_user ) {
			self::_setUser();
		}
		
		$name	=	'preferences_'.$name;
		return self::$_user->$name	=	$value;
	}
	
	// setUser_Preferences
	public static function setUser_Preferences( $preferences )
	{
		if ( !$preferences ) {
			return;
		}
		
		$registry		=	new JRegistry;
		$registry->loadString( $preferences );				
		$preferences	=	$registry->toArray();
		if ( count( $preferences ) ) {
			foreach ( $preferences as $k => $v ) {
				$k					=	'preferences_'.$k;
				self::$_user->$k	=	$v;
			}
		}
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff
	
	// loadjQuery + noConflit + jQueryMore + jQueryDev
	public static function loadjQuery( $noconflict = true, $more = true, $dev = false )
	{
		$app	=	JFactory::getApplication();
		$doc	=	JFactory::getDocument();
		
		if ( $app->get( 'jquery' ) !== true ) {
			$doc->addScript( JURI::root( true ).'/media/cck/scripts/jquery/js/jquery-1.7.2.min.js' );
			$app->set( 'jquery', true );
		}
		if ( $noconflict === true && $app->get( 'jquery_noconflict' ) !== true ) {
			$doc->addScript( JURI::root( true ).'/media/cck/scripts/jquery/js/jquery-noconflict.js' );
			$app->set( 'jquery_noconflict', true );
		}
		if ( $more === true && $app->get( 'jquery_more' ) !== true ) {
			$doc->addScript( JURI::root( true ).'/media/cck/scripts/jquery/js/jquery-more.js' );
			$app->set( 'jquery_more', true );
		}
		if ( $dev !== false && $app->get( 'jquery_dev' ) !== true ) {
			if ( $dev === true ) {
				$doc->addScript( JURI::root( true ).'/media/cck/scripts/jquery/js/jquery-dev.js' );
				$doc->addScript( JURI::root( true ).'/media/cck/scripts/jquery-ui/js/jquery-ui-effects.min.js' );
				$app->set( 'jquery_dev', true );
			} elseif ( is_array( $dev ) && count( $dev ) ) {
				foreach ( $dev as $v ) {
					$doc->addScript( JURI::root( true ).'/media/cck/scripts'.$v );
				}
				$app->set( 'jquery_dev', true );
			}
		}
	}
	
	// loadjQueryUI
	public static function loadjQueryUI()
	{
		$app	=	JFactory::getApplication();
		if ( $app->get( 'jquery_ui' ) !== true ) {
			$doc	=	JFactory::getDocument();
			$doc->addScript( JURI::root( true ).'/media/cck/scripts/jquery-ui/js/jquery-ui.min.js' );
			$app->set( 'jquery_ui', true );
		}
	}
	
	// loadModalBox
	public static function loadModalBox()
	{
		$app	=	JFactory::getApplication();
		if ( $app->get( 'cck_modal_box' ) !== true ) {
			$doc	=	JFactory::getDocument();
			$doc->addStyleSheet( JURI::root().'media/cck/scripts/jquery-colorbox/styles/'.self::getConfig_Param( 'site_modal_box_css', 'style0' ).'/colorbox.css' );
			$doc->addScript( JURI::root().'media/cck/scripts/jquery-colorbox/js/jquery.colorbox-min.js' );
			$app->set( 'cck_modal_box', true );
		}
	}
	
	// googleAnalytics
	public static function googleAnalytics( $url, $account )
	{
		$doc	=	JFactory::getDocument();
		$js	=	"
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', '".$account."']);
				_gaq.push(['_setDomainName', 'none']);
				_gaq.push(['_setAllowLinker', true]);
				_gaq.push(['_trackPageview', '".$url."']);
				
				(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
				";
				
		$doc->addScriptDeclaration( $js );
	}
}
?>