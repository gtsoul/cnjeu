<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: batch.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// JCckTableBatch
class JCckTableBatch extends JObject
{
	protected $_tbl			=	'';
	protected $_tbl_rows	=	'';
	protected $_db;
	
	// __construct
	function __construct( &$db, $table )
	{
		// Set internal variables.
		$this->_tbl	=	$table;
		$this->_db	=	&$db;

		// Initialise the table properties.
		if ( $fields = $this->getFields() ) {
			foreach ( $fields as $name => $v ) {
				// Add the field if it is not already present.
				if ( !property_exists( $this, $name ) ) {
					$this->$name	=	null;
				}
			}
		}
	}
	
	// getInstance
	public static function getInstance( $table )
	{
		$db			=	JFactory::getDbo();
		$tableClass	=	'JCckTableBatch';
		
		// Instantiate
		$instance	=	new $tableClass( $db, $table );
		
		return $instance;
	}
	
	// getFields
	public function getFields()
	{
		$name			=	$this->_tbl;
		static $cache	=	array();
		
		if ( ! isset( $cache[$name] ) ) {
			// Lookup the fields for each table only once.
			$fields	=	$this->_db->getTableFields( $name, false );
			
			if ( !isset( $fields[$name] ) ) {
				$e	=	new JException( JText::_( 'JLIB_DATABASE_ERROR_COLUMNS_NOT_FOUND' ) );
				$this->setError( $e );
				return false;
			}
			$cache[$name]	=	$fields[$name];
		}
		
		return $cache[$name];
	}
	
	// bind
	public function bind( $rows, $ignore = array() )
	{
		$str	=	'';
		
		foreach ( $rows as $row ) {
			$str2	=	'';
			foreach ( $this->getProperties() as $k=>$v ) {
				if ( !in_array($k, $ignore ) ) {
					if ( isset( $row->$k ) ) {
						$str2	.=	'"'.$this->_db->escape( $row->$k ).'", ';
					}
				}
			}
			if ( $str2 != '' ) {
				$str2	=	substr( trim( $str2 ), 0, -1 );
				$str	.=	'(' . $str2 . '), ';
			}
		}
		if ( $str != '' ) {
			$str	=	substr( trim( $str ), 0, -1 );
		}
		
		$this->_tbl_rows	=	$str;
	}
	
	// check
	public function check()
	{
	}
	
	// prepare
	public function delete( $where_clause )
	{
		$where	=	'';
		if ( is_array( $where_clause ) ) {
			$where	=	'';	// todo
		} else {
			$where	=	$where_clause;
		}
		
		if ( !$where ) {
			return false;
		}
		
		$query	=	'DELETE FROM '.$this->_tbl.' WHERE '.$where;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			return false;
		}
	}
	
	// store
	public function store()
	{
		if ( !$this->_tbl_rows ) {
			return false;
		}
		
		$query	=	'INSERT IGNORE INTO '.$this->_tbl.' VALUES '.$this->_tbl_rows;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			return false;
		}
	}
	
	// storeIt
	public function storeIt( $rows, $ignore = array() )
	{
		$this->bind( $rows, $ignore );
		$this->check();
		$this->store();
	}
}
?>