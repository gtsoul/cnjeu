<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: database.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// JCckDatabase
abstract class JCckDatabase
{
	// doQuery
	public static function doQuery( $query )
	{
		$db		=	JFactory::getDBO();
		
		$db->setQuery( $query );
		if ( ! $db->execute() ) {
			return false;
		}
		
		return true;
	}
	
	// escape
	public static function escape( $text, $extra = false )
	{
		return JFactory::getDBO()->escape( $text, $extra );
	}
	
	// getTableCreate
	public static function getTableCreate( $tables )
	{
		$res	=	JFactory::getDBO()->getTableCreate( $tables );
		
		$res	=	str_replace( JFactory::getApplication()->getCfg( 'dbprefix' ), '#__', $res );
		$res	=	str_replace( 'CREATE TABLE `#__', 'CREATE TABLE IF NOT EXISTS `#__', $res );
		
		return $res;
	}
	
	// loadColumn
	public static function loadColumn( $query )
	{
		$db		=	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadColumn();
		
		return $res;
	}
	
	// loadResult
	public static function loadResult( $query )
	{
		$db		=	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadResult();
		
		return $res;
	}
	
	// loadResultArray (deprecated)
	public static function loadResultArray( $query )
	{
		return self::loadColumn( $query );
	}
	
	// loadObject
	public static function loadObject( $query )
	{
		$db		=	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadObject();
		
		return $res;
	}
	
	// loadObjectList
	public static function loadObjectList( $query, $key = null )
	{
		$db		=	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadObjectList( $key );
		
		return $res;
	}
	
	// loadObjectListArray
	public static function loadObjectListArray( $query, $akey, $key = null )
	{
		$db		=	JFactory::getDBO();
		
		$db->setQuery( $query );
		//$res	=	$db->loadObjectListArray( $akey );
		
		// WAITING FOR JOOMLA 1.7.x IMPROVEMENT
		$list	=	$db->loadObjectList( $key );
		$res	=	array();
		if ( count( $list ) ) {
			foreach ( $list as $row ) {
				$res[$row->$akey][]	=	$row;
			}
		}
		// WAITING FOR JOOMLA 1.7.x IMPROVEMENT
		
		return $res;
	}
}
?>