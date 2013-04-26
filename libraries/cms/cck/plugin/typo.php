<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: typo.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Plugin
class JCckPluginTypo extends JPlugin
{
	protected static $construction	=	'cck_field_typo';
	
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
	
	// g_getPath
	public static function g_getPath( $type = '' )
	{
		return JURI::root( true ).'/plugins/'.self::$construction.'/'.$type;
	}
	
	// g_hasLink
	public static function g_hasLink( &$field, $typo, $value, &$config = array() )
	{
		$link_onclick	=	( @$field->link_onclick ) ? 'onclick="'.$field->link_onclick.'" ' : '';
		$link_class		=	( @$field->link_class ) ? 'class="'.$field->link_class.'" ' : '';
		$link_rel		=	( @$field->link_rel ) ? 'rel="'.$field->link_rel.'" ' : '';
		$link_target	=	( @$field->link_target ) ? 'target="'.$field->link_target.'" ' : '';
		$attr			=	trim( $link_onclick.$link_class.$link_rel.$link_target );
		$attr			=	( $attr != '' ) ? ' '.$attr : '';
		
		return ( $field->link && ( strpos( $value, '<a href' ) === false ) ) ? '<a href="'.$field->link.'"'.$attr.'>'.$value.'</a>' : $value;
	}
	
	// g_getTypo
	public static function g_getTypo( $params, $format = '' )
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
	
	// g_addScript_Construct (deprecated)
	public static function g_addScript_Construct( $typo )
	{
		if ( $typo->name ) {
			$lang	=	JFactory::getLanguage();
			$lang->load( 'plg_cck_field_typo_'.$typo->name, JPATH_ADMINISTRATOR, null, false, true );
		}
		
		$doc	=	JFactory::getDocument();
		Helper_Include::addTooltip( 'span[title].qtip_cck', 'left center', 'right center' );
		
		$js	=	'
				$j(document).ready(function(){
					var elem = "'.$typo->id.'_typo_options";
					var encoded = parent.$j("#"+elem).val();
					var data = ( encoded != "" ) ? $j.evalJSON(encoded) : "";
					$j.each(data, function(k, v) {
						$j("#"+k).val( v );
					});
				});
				resetbox = function() {
					var elem = "'.$typo->id.'_typo_options";
					parent.$j("#"+elem).val("");
					closebox();
				}
				submitbox = function() {
					var elem = "'.$typo->id.'_typo_options";
					var data = $j("#adminForm").serializeObject();
					var encoded = $j.toJSON(data);
					parent.$j("#"+elem).val(encoded);
					closebox();
					return;
				}
			';

		$doc->addScriptDeclaration( $js );
	}
}
?>