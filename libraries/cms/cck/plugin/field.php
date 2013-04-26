<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: field.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Plugin
class JCckPluginField extends JPlugin
{
	protected static $construction	=	'cck_field';
	protected static $friendly		=	0;
	
	// onCCK_FieldConstruct_TypeForm
	public static function onCCK_FieldConstruct_TypeForm( &$field, $style, $data = array() )
	{
		self::g_onCCK_FieldConstruct_TypeForm( $field, $style, $data );
		
		krsort( $field->params );
		$field->params	=	implode( '', $field->params );
	}
	
	// onCCK_FieldConstruct_TypeContent
	public static function onCCK_FieldConstruct_TypeContent( &$field, $style, $data = array() )
	{
		self::g_onCCK_FieldConstruct_TypeContent( $field, $style, $data );
		
		krsort( $field->params );
		$field->params	=	implode( '', $field->params );
	}
			
	// onCCK_FieldConstruct_SearchSearch
	public static function onCCK_FieldConstruct_SearchSearch( &$field, $style, $data = array() )
	{
		self::g_onCCK_FieldConstruct_SearchSearch( $field, $style, $data );
		
		krsort( $field->params );
		$field->params	=	implode( '', $field->params );
	}

	// onCCK_FieldConstruct_SearchOrder
	public static function onCCK_FieldConstruct_SearchOrder( &$field, $style, $data = array() )
	{
		self::g_onCCK_FieldConstruct_SearchOrder( $field, $style, $data );
		
		krsort( $field->params );
		$field->params	=	implode( '', $field->params );
	}

	// onCCK_FieldConstruct_SearchContent
	public static function onCCK_FieldConstruct_SearchContent( &$field, $style, $data = array() )
	{
		self::g_onCCK_FieldConstruct_SearchContent( $field, $style, $data );
		
		krsort( $field->params );
		$field->params	=	implode( '', $field->params );
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Construct
	
	// g_onCCK_FieldConstruct
	public function g_onCCK_FieldConstruct( &$data )
	{
		$data['display']	=	3;
		if ( $data['selectlabel'] == '' ) {
			$data['selectlabel']	=	' ';
		}
		
		// JSON
		if ( isset( $data['json'] ) && is_array( $data['json'] ) ) {
			foreach ( $data['json'] as $k => $v ) {
				if ( is_array( $v ) ) {
					$data[$k]	=	JCckDev::toJSON( $v );
				}
			}
		}
		// STRING
		if ( isset( $data['string'] ) && is_array( $data['string'] ) ) {
			foreach ( $data['string'] as $k => $v ) {
				if ( is_array( $v ) ) {
					$string	=	'';
					foreach( $v as $s ) {
						if ( $s ) {
							$string	.=	$s.'||';
						}
					}
					if ( $string ) {
						$string	=	substr( $string, 0, -2 );
					}
					$data[$k]	=	$string;
				}
			}
		}
		
		if ( empty( $data['storage'] ) ) {
			$data['storage']	=	'none';
		}
		if ( $data['storage'] == 'dev' ) {
			$data['published'] 			=	0;
			$data['storage_location']	=	'';
			$data['storage_table']		=	'';
		} else {
			// No Table for None!
			if ( $data['storage'] == 'none' ) {
				$data['storage_table']	=	'';
			}
			// Storage Field is required!
			if ( ! $data['storage_field'] ) {
				$data['storage_field']	=	$data['name'];
			}
			// Storage Field2 is better for flexibility!
			if ( $data['storage'] != 'standard' && $data['storage_field'] ) {
				if ( ( $cut = strpos( $data['storage_field'], '[' ) ) !== false ) {
					$data['storage_field2']	=	substr( $data['storage_field'], $cut + 1, -1 );
					$data['storage_field']	=	substr( $data['storage_field'], 0, $cut );
				} else {
					$data['storage_field2']	=	'';
				}
			}
			
			// Un-existing Fields must be mapped!
			$data['storage_alter_type']	=	$data['storage_alter_type'] ? $data['storage_alter_type'] : 'VARCHAR(255)';
			$alter	=	$data['storage_alter'] && in_array( 1, $data['storage_alter'] );
			if ( $data['storage_alter_table'] && $alter ) {
				if ( $data['storage_table'] && $data['storage_field'] ) {
					JCckDatabase::doQuery( 'ALTER IGNORE TABLE '.$data['storage_table'].' ADD '.$data['storage_field'].' '.$data['storage_alter_type'].' NOT NULL' ); //Todo: CHANGE
				}
			} else {
				if ( $data['storage_table'] && $data['storage_field'] ) {
					if ( ( $data['type'] == 'jform_rules' && $data['storage_field'] == 'rules' )
						|| ( $data['storage_location'] == 'joomla_user' && $data['storage_field'] == 'groups' ) ) {
						return;
					}
					$columns	=	JCckDatabase::loadColumn( 'SHOW FIELDS FROM '.$data['storage_table'] );
					if ( ! in_array( $data['storage_field'], $columns ) ) {
						$app	=	JFactory::getApplication();
						$prefix	=	$app->getCfg( 'dbprefix' );
						if ( $data['storage_cck'] != '' ) {
							// #__cck_store_form_
							$table	=	'cck_store_form_'.$data['storage_cck'];
							JCckDatabase::doQuery( 'CREATE TABLE IF NOT EXISTS '.$prefix.$table.' ( id int(11) NOT NULL, PRIMARY KEY (id) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;' );
							$table	=	'#__'.$table;
							JCckDatabase::doQuery( 'ALTER IGNORE TABLE '.$table.' ADD '.$data['storage_field'].' '.$data['storage_alter_type'].' NOT NULL' );
						} else {
							// #__cck_store_item_
							$table	=	'cck_store_item_'.str_replace( '#__', '', $data['storage_table'] );
							JCckDatabase::doQuery( 'CREATE TABLE IF NOT EXISTS '.$prefix.$table.' ( id int(11) NOT NULL, cck VARCHAR(50) NOT NULL, PRIMARY KEY (id) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;' );
							$table	=	'#__'.$table;
							JCckDatabase::doQuery( 'ALTER IGNORE TABLE '.$table.' ADD '.$data['storage_field'].' '.$data['storage_alter_type'].' NOT NULL' );
						}
						$data['storage_table']	=	$table;
					} else {
						if ( $alter ) {
							JCckDatabase::doQuery( 'ALTER TABLE '.$data['storage_table'].' CHANGE '.$data['storage_field'].' '.$data['storage_field'].' '.$data['storage_alter_type'].' NOT NULL' );
						}
					}
				}
			}
		}
	}
	
	// g_onCCK_FieldConstruct_TypeForm
	public static function g_onCCK_FieldConstruct_TypeForm( &$field, $style, $data )
	{
		$id					=	$field->id;
		$name				=	$field->name;
		$field->params		=	array();
		
		// 1
		$column1			=	'<input class="thin blue" type="text" name="ffp['.$name.'][label]" value="'.( ( @$field->label2 != '' ) ? htmlspecialchars( $field->label2 ) : htmlspecialchars( $field->label ) ).'" size="22" />'
							.	'<input class="thin blue" type="hidden" name="ffp['.$name.'][label2]" value="'.$field->label.'" />';
		$column2			=	JHtml::_( 'select.genericlist', $data['variation'], 'ffp['.$name.'][variation]', 'size="1" class="thin"', 'value', 'text', @$field->variation );
		$field->params[]	=	self::g_getParamsHtml( 1, $style, $column1, $column2 );
		
		// 2
		$column1			=	JHtml::_( 'select.genericlist', $data['live'], 'ffp['.$name.'][live]', 'size="1" class="thin c_live_ck"', 'value', 'text', @$field->live );
		$hide				=	( @$field->live != '' ) ? ' hidden' : '';
		$column_text		=	( JCck::callFunc( 'plgCCK_Field'.$field->type, 'isFriendly' ) ) ? '&laquo;' : '';	// ( static::$friendly ) ? '&laquo;' : '';
		
		$column2			=	'<input class="thin blue" type="text" name="ffp['.$name.'][live_value]" value="'.( ( @$field->live_value != '' ) ? htmlspecialchars( $field->live_value ) : '' ).'" size="22" id="'.$name.'_live_value" />';
		$column2			.=	' <span class="c_live'.$hide.'" name="'.$name.'">'.$column_text.'</span>';
		$field->params[]	=	self::g_getParamsHtml( 2, $style, $column1, $column2 );
		
		// 3
		$column1			=	JHtml::_( 'select.genericlist', $data['required'], 'ffp['.$name.'][required]', 'size="1" class="thin"', 'value', 'text', @$field->required )
							.	'<input class="thin blue" type="text" name="ffp['.$name.'][required_alert]" value="'.@$field->required_alert.'" size="12" />';
		$hide				=	( @$field->validation != '' ) ? '' : ' hidden';
		$column2			=	JHtml::_( 'select.genericlist', $data['validation'], 'ffp['.$name.'][validation]', 'size="1" class="thin c_val_ck"', 'value', 'text', @$field->validation, $name.'_validation' )
							.	'<input type="hidden" id="'.$name.'_validation_options" name="ffp['.$name.'][validation_options]" value="'.htmlspecialchars( @$field->validation_options ).'" />'
							.	' <span class="c_val'.$hide.'" name="'.$name.'">+</span>';
		$field->params[]	=	self::g_getParamsHtml( 3, $style, $column1, $column2 );
		
		// 4
		$column1			=	JHtml::_( 'select.genericlist', $data['access'], 'ffp['.$name.'][access]', 'size="1" class="thin"', 'value', 'text', ( @$field->access ) ? $field->access : 1 );
		$column2			=	JHtml::_( 'select.genericlist', $data['stage'], 'ffp['.$name.'][stage]', 'size="1" class="thin"', 'value', 'text', @$field->stage );		
		$field->params[]	=	self::g_getParamsHtml( 4, $style, $column1, $column2 );
		
		// 5
		$count				=	'';
		$text				=	JText::_( 'COM_CCK_ADD' );
		if ( @$field->conditional != '' ) {
			$count				=	'( ' . count( explode( ',', $field->conditional ) ) . ' )';
			$text				=	JText::_( 'COM_CCK_EDIT' );
		}
		$column1			=	'<input type="hidden" name="ffp['.$name.'][conditional]" value="'.( ( @$field->conditional != '' ) ? $field->conditional : '' ).'" id="ffp_'.$name.'_conditional" />'
							.	'<span class="text blue c_cond" name="'.$name.'">'. $text .'</span>';
		$column2			=	'<input type="hidden" name="ffp['.$name.'][conditional_options]" value="'.htmlspecialchars( @$field->conditional_options ).'" id="ffp_'.$name.'_conditional_options" />'
							.	'<span class="text blue">'.$count.'</span>';
		$field->params[]	=	self::g_getParamsHtml( 5, $style, $column1, $column2 );
	}
	
	// g_onCCK_FieldConstruct_TypeContent
	public static function g_onCCK_FieldConstruct_TypeContent( &$field, $style, $data )
	{
		$id					=	$field->id;
		$name				=	$field->name;
		$field->params		=	array();
		
		// 1
		$column1			=	'<input class="thin blue" type="text" name="ffp['.$name.'][label]" value="'.( ( @$field->label2 != '' ) ? htmlspecialchars( $field->label2 ) : htmlspecialchars( $field->label ) ).'" size="22" />'
							.	'<input class="thin blue" type="hidden" name="ffp['.$name.'][label2]" value="'.$field->label.'" />';
		$column2			=	'';
		$field->params[]	=	self::g_getParamsHtml( 1, $style, $column1, $column2 );
		
		// 2
		$hide				=	( @$field->link != '' ) ? '' : ' hidden';
		$column1			=	JHtml::_( 'select.genericlist', $data['link'], 'ffp['.$name.'][link]', 'size="1" class="thin c_link_ck"', 'value', 'text', @$field->link, $name.'_link' )
							.	'<input type="hidden" id="'.$name.'_link_options" name="ffp['.$name.'][link_options]" value="'.htmlspecialchars( @$field->link_options ).'" />'
							.	' <span class="c_link'.$hide.'" name="'.$name.'">+</span>';
		$column2			=	'<input class="thin blue" type="text" name="ffp['.$name.'][markup_class]" value="'.htmlspecialchars( trim( @$field->markup_class ) ).'" size="22" />';
		$field->params[]	=	self::g_getParamsHtml( 2, $style, $column1, $column2 );
		
		// 3
		$hide				=	( @$field->typo != '' ) ? '' : ' hidden';
		$column1			=	JHtml::_( 'select.genericlist', $data['typo'], 'ffp['.$name.'][typo]', 'size="1" class="thin c_typo_ck"', 'value', 'text', @$field->typo, $name.'_typo' )
							.	'<input type="hidden" id="'.$name.'_typo_options" name="ffp['.$name.'][typo_options]" value="'.htmlspecialchars( @$field->typo_options ).'" />'
							.	' <span class="c_typo'.$hide.'" name="'.$name.'">+</span>';
		$column2			=	JHtml::_( 'select.genericlist', $data['typo_label'], 'ffp['.$name.'][typo_label]', 'size="1" class="thin"', 'value', 'text', @$field->typo_label, $name.'_typo_label' );
		$field->params[]	=	self::g_getParamsHtml( 3, $style, $column1, $column2 );
		
		// 4
		$column1			=	JHtml::_( 'select.genericlist', $data['access'], 'ffp['.$name.'][access]', 'size="1" class="thin"', 'value', 'text', ( @$field->access ) ? $field->access : 1 );
		$column2			=	'';
		$field->params[]	=	self::g_getParamsHtml( 4, $style, $column1, $column2 );
	}
	
	// g_onCCK_FieldConstruct_SearchSearch
	public static function g_onCCK_FieldConstruct_SearchSearch( &$field, $style, $data )
	{
		$id					=	$field->id;
		$name				=	$field->name;
		$field->params		=	array();
		
		// 1
		$column1			=	'<input class="thin blue" type="text" name="ffp['.$name.'][label]" value="'.( ( @$field->label2 != '' ) ? htmlspecialchars( $field->label2 ) : htmlspecialchars( $field->label ) ).'" size="22" />'
							.	'<input class="thin blue" type="hidden" name="ffp['.$name.'][label2]" value="'.$field->label.'" />';
		$column2			=	JHtml::_( 'select.genericlist', $data['variation'], 'ffp['.$name.'][variation]', 'size="1" class="thin"', 'value', 'text', @$field->variation );
		$field->params[]	=	self::g_getParamsHtml( 1, $style, $column1, $column2 );
		
		// 2
		$column1			=	JHtml::_( 'select.genericlist', $data['live'], 'ffp['.$name.'][live]', 'size="1" class="thin c_live_ck"', 'value', 'text', @$field->live );
		$hide				=	( @$field->live != '' ) ? ' hidden' : '';
		$column_text		=	( JCck::callFunc( 'plgCCK_Field'.$field->type, 'isFriendly' ) ) ? '&laquo;' : '';	// ( static::$friendly ) ? '&laquo;' : '';
		$column2			=	'<input class="thin blue" type="text" name="ffp['.$name.'][live_value]" value="'.( ( @$field->live_value != '' ) ? htmlspecialchars( $field->live_value ) : '' ).'" size="22" id="'.$name.'_live_value" />';
		$column2			.=	' <span class="c_live'.$hide.'" name="'.$name.'">'.$column_text.'</span>';
		$field->params[]	=	self::g_getParamsHtml( 2, $style, $column1, $column2 );
		
		// 3
		$column1			=	JHtml::_( 'select.genericlist', $data['match_mode'], 'ffp['.$name.'][match_mode]', 'size="1" class="thin"', 'value', 'text', @$field->match_mode )
							.	'<input class="thin blue" type="text" name="ffp['.$name.'][match_value]" value="'.( ( @$field->match_value != '' ) ? $field->match_value : '' ).'" size="3" />';
		$column2			=	JHtml::_( 'select.genericlist', $data['match_collection'], 'ffp['.$name.'][match_collection]', 'size="1" class="thin"', 'value', 'text', @$field->match_collection );
		$field->params[]	=	self::g_getParamsHtml( 3, $style, $column1, $column2 );
		
		// 4
		$column1			=	JHtml::_( 'select.genericlist', $data['access'], 'ffp['.$name.'][access]', 'size="1" class="thin"', 'value', 'text', ( @$field->access ) ? $field->access : 1 );
		$column2			=	JHtml::_( 'select.genericlist', $data['stage'], 'ffp['.$name.'][stage]', 'size="1" class="thin"', 'value', 'text', @$field->stage );		
		$field->params[]	=	self::g_getParamsHtml( 4, $style, $column1, $column2 );
		
		// 5
		$count				=	'';
		$text				=	JText::_( 'COM_CCK_ADD' );
		if ( @$field->conditional != '' ) {
			$count				=	'( ' . count( explode( ',', $field->conditional ) ) . ' )';
			$text				=	JText::_( 'COM_CCK_EDIT' );
		}	
		$column1			=	'<input type="hidden" name="ffp['.$name.'][conditional]" value="'.( ( @$field->conditional != '' ) ? $field->conditional : '' ).'" id="ffp_'.$name.'_conditional" />'
							.	'<span class="text blue c_cond" name="'.$name.'">'. $text .'</span>';
		$column2			=	'<input type="hidden" name="ffp['.$name.'][conditional_options]" value="'.htmlspecialchars( @$field->conditional_options ).'" id="ffp_'.$name.'_conditional_options" />'
							.	'<span class="text blue">'.$count.'</span>';
		$field->params[]	=	self::g_getParamsHtml( 5, $style, $column1, $column2 );	
		
		// 6
		$column1			=	JHtml::_( 'select.genericlist', $data['required'], 'ffp['.$name.'][required]', 'size="1" class="thin"', 'value', 'text', @$field->required )
							.	'<input class="thin blue" type="text" name="ffp['.$name.'][required_alert]" value="'.@$field->required_alert.'" size="12" />';
		$hide				=	( @$field->validation != '' ) ? '' : ' hidden';
		$column2			=	JHtml::_( 'select.genericlist', $data['validation'], 'ffp['.$name.'][validation]', 'size="1" class="thin c_val_ck"', 'value', 'text', @$field->validation, $name.'_validation' )
							.	'<input type="hidden" id="'.$name.'_validation_options" name="ffp['.$name.'][validation_options]" value="'.htmlspecialchars( @$field->validation_options ).'" />'
							.	' <span class="c_val'.$hide.'" name="'.$name.'">+</span>';
		$field->params[]	=	self::g_getParamsHtml( 6, $style, $column1, $column2 );
	}
	
	// g_onCCK_FieldConstruct_SearchOrder
	public static function g_onCCK_FieldConstruct_SearchOrder( &$field, $style, $data )
	{
		$id					=	$field->id;
		$name				=	$field->name;
		$field->params		=	array();
		
		// 1
		$column1			=	JHtml::_( 'select.genericlist', $data['match_mode'], 'ffp['.$name.'][match_mode]', 'size="1" class="thin"', 'value', 'text', @$field->match_mode );
		$column2			=	'';
		$field->params[]	=	self::g_getParamsHtml( 1, $style, $column1, $column2 );
	}
	
	// g_onCCK_FieldConstruct_SearchContent
	public static function g_onCCK_FieldConstruct_SearchContent( &$field, $style, $data )
	{
		$id					=	$field->id;
		$name				=	$field->name;
		$field->params		=	array();
		
		// 1
		$column1			=	'<input class="thin blue" type="text" name="ffp['.$name.'][label]" value="'.( ( @$field->label2 != '' ) ? htmlspecialchars( $field->label2 ) : htmlspecialchars( $field->label ) ).'" size="22" />'
							.	'<input class="thin blue" type="hidden" name="ffp['.$name.'][label2]" value="'.$field->label.'" />';
		$column2			=	'';
		$field->params[]	=	self::g_getParamsHtml( 1, $style, $column1, $column2 );
		
		// 2
		$hide				=	( @$field->link != '' ) ? '' : ' hidden';
		$column1			=	JHtml::_( 'select.genericlist', $data['link'], 'ffp['.$name.'][link]', 'size="1" class="thin c_link_ck"', 'value', 'text', @$field->link, $name.'_link' )
							.	'<input type="hidden" id="'.$name.'_link_options" name="ffp['.$name.'][link_options]" value="'.htmlspecialchars( @$field->link_options ).'" />'
							.	' <span class="c_link'.$hide.'" name="'.$name.'">+</span>';
		$column2			=	'<input class="thin blue" type="text" name="ffp['.$name.'][markup_class]" value="'.htmlspecialchars( trim( @$field->markup_class ) ).'" size="22" />';
		$field->params[]	=	self::g_getParamsHtml( 2, $style, $column1, $column2 );
		
		// 3
		$hide			=	( @$field->typo != '' ) ? '' : ' hidden';
		$column1			=	JHtml::_( 'select.genericlist', $data['typo'], 'ffp['.$name.'][typo]', 'size="1" class="thin c_typo_ck"', 'value', 'text', @$field->typo, $name.'_typo' )
							.	'<input type="hidden" id="'.$name.'_typo_options" name="ffp['.$name.'][typo_options]" value="'.htmlspecialchars( @$field->typo_options ).'" />'
							.	' <span class="c_typo'.$hide.'" name="'.$name.'">+</span>';
		$column2			=	JHtml::_( 'select.genericlist', $data['typo_label'], 'ffp['.$name.'][typo_label]', 'size="1" class="thin"', 'value', 'text', @$field->typo_label, $name.'_typo_label' );
		
		$field->params[]	=	self::g_getParamsHtml( 3, $style, $column1, $column2 );
		
		// 4
		$column1			=	JHtml::_( 'select.genericlist', $data['access'], 'ffp['.$name.'][access]', 'size="1" class="thin"', 'value', 'text', ( @$field->access ) ? $field->access : 1 );
		$column2			=	'';
		$field->params[]	=	self::g_getParamsHtml( 4, $style, $column1, $column2 );
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
	
	// g_onCCK_FieldPrepareContent
	public static function g_onCCK_FieldPrepareContent( &$field, &$config = array() )
	{
		$field->label		=	( @$field->label2 ) ? $field->label2 : ( ( $field->label ) ? $field->label : $field->title );
		if ( $field->label == 'clear' || $field->label == 'none' ) {
			$field->label	=	'';
		}
		if ( $config['doTranslation'] ) {
			if ( trim( $field->label ) ) {
				$field->label	=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', trim( $field->label ) ) );
			}
			if ( trim( $field->description ) ) {
				$desc	=	trim( strip_tags( $field->description ) );
				if ( $desc ) {
					$field->description	=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', $desc ) );
				}
			}
		}
		
		$field->linked		=	false;
		$field->typo_target	=	'value';
	}
	
	// g_onCCK_FieldPrepareForm
	public static function g_onCCK_FieldPrepareForm( &$field, &$config = array() )
	{
		$field->label		=	( @$field->label2 ) ? $field->label2 : ( ( $field->label ) ? $field->label : $field->title );
		if ( $field->label == 'clear' || $field->label == 'none' ) {
			$field->label	=	'';
		}
		if ( $config['doTranslation'] ) {
			if ( trim( $field->label ) ) {
				$field->label		=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', trim( $field->label ) ) );
			}
			if ( trim( $field->description ) ) {
				$desc	=	trim( strip_tags( $field->description ) );
				if ( $desc ) {
					$field->description	=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', $desc ) );
				}
			}
		}
		
		$field->typo_target	=	'value';
		$field->link		=	'';
		$field->validate	=	array();
		
		return $field;
	}
		
	// g_onCCK_FieldPrepareForm_Validation
	public static function g_onCCK_FieldPrepareForm_Validation( &$field, $id, &$config = array() )
	{
		if ( $field->validation ) {
			require_once JPATH_PLUGINS.DS.'cck_field_validation'.DS.$field->validation.DS.$field->validation.'.php';
			JCck::callFunc_Array( 'plgCCK_Field_Validation'.$field->validation, 'onCCK_Field_ValidationPrepareForm', array( &$field, $id, &$config ) );
		}
	}
	
	// g_onCCK_FieldPrepareSearch
	public function g_onCCK_FieldPrepareSearch( &$field, &$config = array() )
	{
		$field->label		=	( @$field->label2 ) ? $field->label2 : ( ( $field->label ) ? $field->label : $field->title );
		if ( $field->label == 'clear' || $field->label == 'none' ) {
			$field->label	=	'';
		}
		if ( $config['doTranslation'] ) {
			if ( trim( $field->label ) ) {
				$field->label		=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', trim( $field->label ) ) );
			}
			if ( trim( $field->description ) ) {
				$desc	=	trim( strip_tags( $field->description ) );
				if ( $desc ) {
					$field->description	=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', $desc ) );
				}
			}
		}
	}
	
	// g_onCCK_FieldPrepareStore
	public function g_onCCK_FieldPrepareStore( &$field, $name, $value, &$config = array() )
	{
		$field->label		=	( @$field->label2 ) ? $field->label2 : ( ( $field->label ) ? $field->label : $field->title );
		if ( $field->label == 'clear' || $field->label == 'none' ) {
			$field->label	=	'';
		}
		if ( $config['doTranslation'] ) {
			if ( trim( $field->label ) ) {
				$field->label		=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', trim( $field->label ) ) );
			}
			if ( trim( $field->description ) ) {
				$desc	=	trim( strip_tags( $field->description ) );
				if ( $desc ) {
					$field->description	=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', $desc ) );
				}
			}
		}
		
		$storage	=	$field->storage;
		
		if ( $storage == 'none' ) {
			if ( ! isset( $config['storages']['none'] ) ) {
				$config['storages']['none']	=	array();
			}			
			if ( is_array( $value ) ) {
				@$config['storages']['none'][$field->storage_field]	=	$value;
			} else {
				@$config['storages']['none'][$field->storage_field]	.=	trim( $value );
			}
		} else {
			if ( ! $field->storage_field2 ) {
				$field->storage_field2	=	$field->name;
			}
			require_once JPATH_PLUGINS.DS.'cck_storage'.DS.$storage.DS.$storage.'.php';
			JCck::callFunc_Array( 'plgCCK_Storage'.$storage, 'onCCK_StoragePrepareStore', array( &$field, $value, &$config ) );
		}
	}
	
	// g_onCCK_FieldPrepareStore_X
	public function g_onCCK_FieldPrepareStore_X( &$field, $name, $value, $store, &$config = array() )
	{
		$storage	=	$field->storage;
		if ( $storage != 'none' ) {
			if ( ! $field->storage_field2 ) {
				$field->storage_field2	=	$field->name;
			}
			require_once JPATH_PLUGINS.DS.'cck_storage'.DS.$storage.DS.$storage.'.php';
			JCck::callFunc_Array( 'plgCCK_Storage'.$storage, 'onCCK_StoragePrepareStore_X', array( &$field, $value, $store, &$config ) );
		}
	}
	
	// g_onCCK_FieldPrepareStore_Validation
	public function g_onCCK_FieldPrepareStore_Validation( &$field, $name, $value, &$config = array() )
	{
		if ( $config['doValidation'] == 1 || $config['doValidation'] == 3 ) {
			if ( $field->required ) {
				plgCCK_Field_ValidationRequired::onCCK_Field_ValidationPrepareStore( $field, $name, $value, $config );
			}
		
			$validation	=	$field->validation;
			if ( ! $validation ) {
				return;
			}
			require_once JPATH_PLUGINS.DS.'cck_field_validation'.DS.$validation.DS.$validation.'.php';
			JCck::callFunc_Array( 'plgCCK_Field_Validation'.$validation, 'onCCK_Field_ValidationPrepareStore', array( &$field, $name, $value, &$config ) );
		}
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Render
	
	// g_onCCK_FieldRenderContent
	public static function g_onCCK_FieldRenderContent( &$field, $target = 'value' )
	{
		return ( isset( $field->typo ) && $field->typo != '' ) ? $field->typo : ( @$field->link ? $field->html : $field->$target );
	}
	
	// g_onCCK_FieldRenderForm
	public static function g_onCCK_FieldRenderForm( &$field )
	{
		return $field->form;
	}

	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff
	
	public static function g_addProcess( $event, $type, &$config, $params )
	{
		if ( $event && $type ) {
			$process						=	new stdClass;
			$process->group					=	self::$construction;
			$process->type					=	$type;
			$process->params				=	$params;
			$config['process'][$event][]	=	$process;
		}
	}
	
	//g_doConditionalStates
	public static function g_doConditionalStates( $cck, $fieldname, $value )
	{
		$values	=	explode( ',', $value );
		$value	=	$cck->getValue( $fieldname );
		
		if ( is_array( $value ) ) {
			if ( array_diff( $value, $values ) ) {
				if ( array_diff( $values, $value ) ) {
					return 'style="display: none;"';					
				}
			}
		} else {
			if ( !in_array( $value, $values ) ) {
				return 'style="display: none;"';
			}
		}
		
		return '';
	}
	
	// g_get
	public static function g_get( $var = '' )
	{
		//return static::${$var};
	}
	
	// g_getDisplayVariation
	public static function g_getDisplayVariation( &$field, $variation, $value, $text, $form, $itemId, $name, $html, $hidden = '', $more = '' )
	{
		$field->form	=	( $hidden ) ? $hidden : '<input class="inputbox" type="hidden" id="'.$itemId.'" name="'.$name.'" value="'.$value.'" />';		
		
		if ( $variation == 'value' ) {
			$field->form	.=	'<span class="variation_value">'.$text.'</span>';
		} elseif ( $variation == 'disabled' ) {
			if ( $html ) {
				$field->form	.=	str_replace( $html, $html.' disabled="disabled"', $form );
				$field->form	=	str_replace( array( 'required required-enabled', 'validate-one-required required-enabled' ), array( '', '' ), $field->form );
			}
		} elseif ( $variation == 'clear' ) {
			$field->display =	0;
		} else {
			$field->display =	1;
		}
		$field->form	.=	$more;
	}
	
	// g_getOptionText
	public static function g_getOptionText( &$value, $options, $separator = '', $config = array() )
	{
		$opts	=	explode( '||', $options );
		$text	=	'';
		
		if ( $value == '' ) {
			return $text;
		}
		
		if ( $separator ) {
			$values		=	( is_array( $value ) ) ? $value : explode( $separator, $value );
		} elseif ( $separator != '0' ) {
			$values		=	array( 0=>$value );
			$separator	=	'';
		} else {
			$values		=	$value;
		}
		
		$value	=	array();
		if ( count( $opts ) ) {
			foreach ( $values as $i=>$val ) {
				if ( $val != '' ) {
					$exist	=	false;
					foreach ( $opts as $opt ) {
						if ( strpos( '='.$opt.'||', '='.$val.'||' ) !== false ) {
							$texts	=	explode( '=', $opt );
							if ( $config['doTranslation'] && trim( $texts[0] ) ) {
								$texts[0]	=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', trim( $texts[0] ) ) );
							}
							$exist	=	true;
							$text	.=	$texts[0].$separator;
							break;
						}
					}
					if ( $exist === true ) {
						$value[]	=	$val;
					}
				}
			}
		}
		
		if ( $separator ) {
			$length	=	strlen( $separator );
			$value	=	implode( $separator, $value );
			$text	=	substr( $text, 0, -$length );
		} elseif ( $separator != '0' ) {
			$value	=	(string)@$value[0];
		}
		
		return $text;
	}
	
	// g_getParamsHtml
	public static function g_getParamsHtml( $num, $style, $column1, $column2 )
	{
		$html	=	'<div class="pane p'.$num.$style[$num].'">';
		if ( $column1 != '' ) {
			$html	.=	'<div class="col1"><div class="colc">'.$column1.'</div></div>';
		}
		if ( $column2 != '' ) {
			$html	.=	'<div class="col2"><div class="colc">'.$column2.'</div></div>';
		}
		$html	.=	'</div>';
		
		return $html;
	}
	
	// g_getPath
	public static function g_getPath( $type = '' )
	{
		return JURI::root( true ).'/plugins/'.self::$construction.'/'.$type;
	}
	
	// g_addScriptDeclaration
	public static function g_addScriptDeclaration( $script )
	{
		$doc	=	JFactory::getDocument();
		
		$js	=	'
				$j(document).ready(function(){
					'.$script.'
				});
				';	
		$doc->addScriptDeclaration( $js );
	}
	
	// isFriendly
	public static function isFriendly()
	{
		return self::$friendly;
	}
}
?>