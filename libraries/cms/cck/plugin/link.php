<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: link.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Plugin
class JCckPluginLink extends JPlugin
{
	protected static $construction	=	'cck_field_link';
	
	// onCCK_Field_LinkBeforeRenderContent
	public static function onCCK_Field_LinkBeforeRenderContent( $process, &$fields, &$storages, &$config = array() )
	{
		$name	=	$process['name'];
		
		if ( count( $process['matches'][1] ) ) {
			self::g_setCustomVars( $process, $fields, $name );
		}
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff

	// g_addProcess
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

	// g_addScript_Construct (deprecated)
	public static function g_addScript_Construct( $link )
	{
		if ( $link->name ) {
			JFactory::getLanguage()->load( 'plg_cck_field_link_'.$link->name, JPATH_ADMINISTRATOR, null, false, true );
		}
		
		$doc	=	JFactory::getDocument();
		Helper_Include::addTooltip( 'span[title].qtip_cck', 'left center', 'right center' );
		
		$js	=	'
				$j(document).ready(function(){
					var elem = "'.$link->id.'_link_options";
					var encoded = parent.$j("#"+elem).val();
					var data = ( encoded != "" ) ? $j.evalJSON(encoded) : "";
					$j.each(data, function(k, v) {
						$j("#"+k).val( v );
					});
				});
				resetbox = function() {
					var elem = "'.$link->id.'_link_options";
					parent.$j("#"+elem).val("");
					closebox();
				}
				submitbox = function() {
					var elem = "'.$link->id.'_link_options";
					var data = $j("#adminForm").serializeObject();
					var encoded = $j.toJSON(data);
					parent.$j("#"+elem).val(encoded);
					closebox();
					return;
				}
			';

		$doc->addScriptDeclaration( $js );
	}
	
	// g_getCustomSelfVars
	public static function g_getCustomSelfVars( $type, $field, $custom, &$config = array() )
	{
		if ( $custom != '' && strpos( $custom, '*' ) !== false ) {
			$matches	=	'';
			$search		=	'#\*([a-zA-Z0-9_]*)\*#U';
			preg_match_all( $search, $custom, $matches );
			if ( count( $matches[1] ) ) {
				foreach ( $matches[1] as $target ) {
					$custom	=	str_replace( '*'.$target.'*', $field->$target, $custom );
				}
			}
		}
		
		return $custom;
	}
	
	// g_getCustomVars
	public static function g_getCustomVars( $type, $field, $custom, &$config = array() )
	{
		if ( $custom != '' && strpos( $custom, '*' ) !== false ) {
			$matches	=	'';
			$search		=	'#\*([a-zA-Z0-9_]*)\*#U';
			preg_match_all( $search, $custom, $matches );
			if ( count( $matches[1] ) ) {
				foreach ( $matches[1] as $target ) {
					$custom	=	str_replace( '*'.$target.'*', $field->$target, $custom );
				}
			}
		}
		if ( $custom != '' && strpos( $custom, '$cck->get' ) !== false ) {
			$matches	=	'';
			$search		=	'#\$cck\->get([a-zA-Z0-9_]*)\( ?\'([a-zA-Z0-9_]*)\' ?\)(;)?#';
			preg_match_all( $search, $custom, $matches );
			if ( count( $matches[1] ) ) {
				self::g_addProcess( 'beforeRenderContent', $type, $config, array( 'name'=>$field->name, 'matches'=>$matches ) );
			}
		}
		
		return $custom;
	}
	
	// g_setCustomVars
	public static function g_setCustomVars( $process, &$fields, $name )
	{
		foreach ( $process['matches'][1] as $k=>$v ) {
			$fieldname					=	$process['matches'][2][$k];
			$target						=	strtolower( $v );
			$fields[$name]->html		=	str_replace( $process['matches'][0][$k], $fields[$fieldname]->{$target}, $fields[$name]->html );
			if ( isset( $fields[$name]->typo ) ) {
				$fields[$name]->typo	=	str_replace( $process['matches'][0][$k], $fields[$fieldname]->{$target}, $fields[$name]->typo );
			}
		}
	}
	
	// g_getPath
	public static function g_getPath( $type = '' )
	{
		return JURI::root( true ).'/plugins/'.self::$construction.'/'.$type;
	}
	
	// g_getLink
	public static function g_getLink( $params, $format = '' )
	{
		if ( $format != '' )  {
			return JCckDev::fromJSON( $params, $format );
		} else {
			$reg	=	new JRegistry;
		
			if ( $params ) {			
				$reg->loadString( $params );
			}
			
			return $reg;
		}
	}
	
	// g_setLink
	public static function g_setLink( &$field, &$config = array() )
	{
		if ( !$field->link ) {
			return;
		}
		
		require_once JPATH_PLUGINS.DS.'cck_field_link'.DS.$field->link.DS.$field->link.'.php';
		JCck::callFunc_Array( 'plgCCK_Field_Link'.$field->link, 'onCCK_Field_LinkPrepareContent', array( &$field, &$config ) );
	}
	
	// g_setHtml
	public static function g_setHtml( &$field, $target = '' )
	{
		if ( is_array( $field->value ) ) {
			foreach ( $field->value as $f ) {
				$target		=	$f->typo_target;
				if ( isset( $f->link ) ) {
					$link_onclick	=	( @$f->link_onclick ) ? 'onclick="'.$f->link_onclick.'" ' : '';
					$link_class		=	( @$f->link_class ) ? 'class="'.$f->link_class.'" ' : '';
					$link_rel		=	( @$f->link_rel ) ? 'rel="'.$f->link_rel.'" ' : '';
					$link_target	=	( @$f->link_target ) ? 'target="'.$f->link_target.'" ' : '';
					$attr			=	trim( $link_onclick.$link_class.$link_rel.$link_target );
					$attr			=	( $attr != '' ) ? ' '.$attr : '';
					
					$f->html		=	( $f->$target != '' ) ? '<a href="'.$f->link.'"'.$attr.'>'.$f->$target.'</a>' : '';
				}
			}
		} else {
			$link_onclick	=	( @$field->link_onclick ) ? 'onclick="'.$field->link_onclick.'" ' : '';
			$link_class		=	( @$field->link_class ) ? 'class="'.$field->link_class.'" ' : '';
			$link_rel		=	( @$field->link_rel ) ? 'rel="'.$field->link_rel.'" ' : '';
			$link_target	=	( @$field->link_target ) ? 'target="'.$field->link_target.'" ' : '';
			$attr			=	trim( $link_onclick.$link_class.$link_rel.$link_target );
			$attr			=	( $attr != '' ) ? ' '.$attr : '';
			
			$field->html	=	( $field->$target != '' ) ? '<a href="'.$field->link.'"'.$attr.'>'.$field->$target.'</a>' : '';
		}
	}	
}
?>