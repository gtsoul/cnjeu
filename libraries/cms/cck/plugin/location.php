<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: location.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Plugin
class JCckPluginLocation extends JPlugin
{
	protected static $construction	=	'cck_storage_location';
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
	
	// g_onCCK_Storage_LocationPrepareContent
	public function g_onCCK_Storage_LocationPrepareContent( $table, $pk )
	{
		$instance	=	JCckTable::getInstance( $table, 'id' );
		
		if ( $pk > 0 ) {
			$instance->load( $pk );
		}

		return $instance;
	}

	// g_onCCK_Storage_LocationPrepareForm
	public function g_onCCK_Storage_LocationPrepareForm( $table, $pk )
	{
		$instance	=	JCckTable::getInstance( $table, 'id' );
		
		if ( $pk > 0 ) {
			$instance->load( $pk );
		}

		return $instance;
	}

	// g_onCCK_Storage_LocationPrepareStore
	public function g_onCCK_Storage_LocationPrepareStore( &$config = array() )
	{
		$core		=	JCckTable::getInstance( '#__cck_core', 'id' );
		$core->cck	=	' ';
		$core->storeIt();
		
		return $core->id;
	}

	// -------- -------- -------- -------- -------- -------- -------- -------- // Store
	
	// g_onCCK_Storage_LocationRollback
	public function g_onCCK_Storage_LocationRollback( $pk )
	{
		JCckDatabase::doQuery( 'DELETE FROM #__cck_core WHERE id = '.(int)$pk );
	}
	
	// g_onCCK_Storage_LocationStore
	public function g_onCCK_Storage_LocationStore( $location, $default, $pk, &$config, $params = array() )
	{
		$config['author']	=	( (int)$config['author'] > 0 ) ? $config['author'] : JCck::getConfig_Param( 'integration_user_default_author', 42 );
		$table				=	$location['_']->table;
		if ( ! $pk ) {
			return;
		}
		
		if ( $table == $default ) {
			// Core
			if ( isset( $params['bridge'] ) && $params['bridge'] == 1 ) {				
				self::g_doBridge( $pk, $location, $config, $params );
			} else {
				$core	=	JCckTable::getInstance( '#__cck_core', 'id' );
				$core->load( $config['id'] );
				$core->cck	=	$config['type'];
				if ( ! $core->pk ) {
					$core->date_time	=	JFactory::getDate()->toSql();
				}
				$core->pk	=	$pk;
				$core->storage_location	=	$location['_']->location;
				$core->author_id		=	$config['author'];
				$core->parent_id		=	$config['parent'];
				$core->storeIt();
			}
		} else {
			// More
			$more	=	JCckTable::getInstance( $table, 'id' );
			$more->load( $pk, true );
			if ( isset( $more->cck ) ) {
				$more->cck	=	$config['type'];
			}
			$more->bind( $config['storages'][$table] );
			$more->check();
			$more->store();	
		}
		//
		if ( ! isset( $config['primary'] ) ) {
			$config['primary']	=	$location['_']->location;
		}
	}
	
	// g_onCCK_Storage_LocationUpdate
	public function g_onCCK_Storage_LocationUpdate( $pk, $table, $field, $search, $replace, &$config = array() )
	{
		if ( ! $pk ) {
			return;
		}
		$update			=	JCckTable::getInstance( $table, 'id', $pk );
		$update->$field	=	str_replace( $search, $replace, $update->$field );
		$update->store();	
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff
	
	// g_checkIn
	public function g_checkIn( $table )
	{
		$app	=	JFactory::getApplication();
		$user	=	JFactory::getUser();
		
		if ( $table->checked_out > 0 ) {
			if ( $table->checked_out != $user->get( 'id' ) && !$user->authorise( 'core.admin', 'com_checkin' ) ) {
				$app->enqueueMessage( JText::_( 'JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH' ), 'error' );
				return false;
			}
			
			if ( !$table->checkin( $pk ) ) {
				$app->enqueueMessage( $table->getError(), 'error' );
				return false;
			}
		}
		
		// releaseEditId
		
		return true;
	}
	
	// g_isMax
	public function g_isMax( $author_id, $parent_id, $config = array() )
	{
		$app	=	JFactory::getApplication();
		$user	=	JFactory::getUser();
		$typeId	=	JCckDatabase::loadResult( 'SELECT id FROM #__cck_core_types WHERE name ="'.$config['type'].'"' );
		
		jimport('cck.joomla.access.access');
		$max_parent_author	=	(int)CCKAccess::check( $user->id, 'core.create.max.parent.author', 'com_cck.form.'.$typeId );
		$max_parent			=	(int)CCKAccess::check( $user->id, 'core.create.max.parent', 'com_cck.form.'.$typeId );
		$max_author			=	(int)CCKAccess::check( $user->id, 'core.create.max.author', 'com_cck.form.'.$typeId );
		
		if ( $max_parent_author > 0 ) {
			$count	=	JCckDatabase::loadResult( 'SELECT COUNT(id) FROM #__cck_core WHERE cck="'.$config['type'].'" AND parent_id = '.$parent_id.' AND author_id = '.$author_id );
			if ( $count >= $max_parent_author ) {
				JCckDatabase::doQuery( 'DELETE FROM #__cck_core WHERE id = '.(int)$config['id'] );
				$app->enqueueMessage( JText::_( 'COM_CCK_ERROR_MAX_PARENT_AUTHOR' ), 'error' );
				$config['error']	=	true;
				return 1;
			}
		}
		if ( $max_parent > 0 ) {
			$count	=	JCckDatabase::loadResult( 'SELECT COUNT(id) FROM #__cck_core WHERE cck="'.$config['type'].'" AND parent_id = '.$parent_id );
			if ( $count >= $max_parent ) {
				JCckDatabase::doQuery( 'DELETE FROM #__cck_core WHERE id = '.(int)$config['id'] );
				$app->enqueueMessage( JText::_( 'COM_CCK_ERROR_MAX_PARENT' ), 'error' );
				$config['error']	=	true;
				return 1;
			}
		}
		if ( $max_author > 0 ) {
			$count	=	JCckDatabase::loadResult( 'SELECT COUNT(id) FROM #__cck_core WHERE cck="'.$config['type'].'" AND author_id = '.$author_id );
			if ( $count >= $max_author ) {
				JCckDatabase::doQuery( 'DELETE FROM #__cck_core WHERE id = '.(int)$config['id'] );
				$app->enqueueMessage( JText::_( 'COM_CCK_ERROR_MAX_AUTHOR' ), 'error' );
				$config['error']	=	true;
				return 1;
			}
		}
		
		return 0;
	}
	
	// g_doBridge
	public function g_doBridge( $pk, $location, $config, $params )
	{
		$core	=	JCckTable::getInstance( '#__cck_core', 'id' );
		$core->load( $config['id'] );
				
		require_once JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'content.php';
		$bridge		=	JTable::getInstance( 'content' );
		$dispatcher	=	JDispatcher::getInstance();
		
		if ( $core->pkb > 0 ) {
			$bridge->load( $core->pkb );
			$bridge->introtext	=	'';
			$isNew				=	false;
		} else {
			$bridge->access		=	'';
			$bridge->state		=	'';
			$bridge->created_by	=	$config['author'];
			self::g_initTable( $bridge, $params, false, 'bridge_' );
			$isNew				=	true;
		}
			
		if ( isset( $config['storages']['#__content'] ) ) {
			$bridge->bind( $config['storages']['#__content'] );
		}
		if ( ! $bridge->title ) {
			if ( isset( $params['bridge_default_title'] ) && $params['bridge_default_title'] != '' ) {
				$P	=	$params['bridge_default_title'];
				//
			}
			if ( ! $bridge->title ) {
				$bridge->title	=	ucfirst( str_replace( '_', ' ', $location['_']->location ) ).' - '.$pk;
			}
		}
		if ( ! $bridge->catid ) {
			$bridge->catid	=	2;
		}
		$bridge->introtext	=	'::cck::'.$config['id'].'::/cck::'.$bridge->introtext;
		//$bridge->fulltext	=	'::cck::'.$config['id'].'::/cck::'.$bridge->fulltext;
		$bridge->version++;
		
		if ( $bridge->state == 1 && intval( $bridge->publish_up ) == 0 ) {
			$bridge->publish_up	=	JFactory::getDate()->toSql();
		}
		if ( !$core->pkb ) {
			$bridge->reorder( 'catid = '.(int)$bridge->catid.' AND state >= 0' );
		}
		$bridge->check();
		if ( empty( $bridge->language ) ) {
			$bridge->language	=	'*';
		}
		
		JPluginHelper::importPlugin( 'content' );
		$dispatcher->trigger( 'onContentBeforeSave', array( 'com_content.article', &$bridge, $isNew ) );
		$bridge->store();
		
		$core->pkb	=	( $bridge->id > 0 ) ? $bridge->id : 0;
		$core->cck	=	$config['type'];
		if ( ! $core->pk ) {
			$core->author_id	=	$config['author'];
			$core->date_time	=	JFactory::getDate()->toSql();
		}
		$core->pk	=	$pk;
		$core->storage_location	=	$location['_']->location;
		$core->author_id		=	$config['author'];
		$core->parent_id		=	$config['parent'];
		$core->storeIt();
		
		$dispatcher->trigger( 'onContentAfterSave', array( 'com_content.article', &$bridge, $isNew ) );
	}
	
	// g_getBridgeAuthor
	public function g_getBridgeAuthor( $pk, $location )
	{
		return JCckDatabase::loadResult( 'SELECT b.created_by FROM #__cck_core AS a LEFT JOIN #__content AS b ON b.id = a.pkb WHERE a.storage_location = "'.$location.'" AND a.pk = '.$pk );
	}
	
	// g_initTable
	public function g_initTable( &$table, $params = array(), $force = false, $prefix = 'base_' )
	{
		if ( count( $params ) ) {
			if ( $force === true ) {
				foreach ( $params as $k => $v ) {
					if ( ( $pos = strpos( $k, $prefix.'default-' ) ) !== false ) {
						$length		=	strlen( $prefix ) + 8;
						$k			=	substr( $k, $length );
						$table->$k	=	$v;
					}
				}
			} else {				
				foreach ( $params as $k => $v ) {
					if ( ( $pos = strpos( $k, $prefix.'default-' ) ) !== false ) {
						$length	=	strlen( $prefix ) + 8;
						$k		=	substr( $k, $length );
						if ( $table->$k == '' || !isset( $table->$k ) ) {
							$table->$k	=	$v;
						}
					}
				}
			}
		}
	}
	
	// g_completeTable
	public function g_completeTable( &$table, $custom, $config = array() )
	{
		if ( $custom ) {
			$table->$custom	=	'::cck::'.$config['id'].'::/cck::'.$table->$custom;
		}
	}
}
?>