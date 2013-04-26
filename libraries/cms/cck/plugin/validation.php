<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: validation.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Plugin
class JCckPluginValidation extends JPlugin
{
	protected static $construction	=	'cck_field_validation';
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare

	// g_onCCK_Field_ValidationPrepareForm
	public static function g_onCCK_Field_ValidationPrepareForm( &$field, $fieldId, &$config, $rule, $def )
	{		
		$validation	=	self::g_getValidation( $field->validation_options );
		
		if ( $validation->alert != '' ) {
			$validation->name	=	$field->validation.'_'.$fieldId;
			$alert				=	$validation->alert;
			if ( $config['doTranslation'] ) {
				if ( trim( $alert ) ) {
					$alert	=	JText::_( 'COM_CCK_' . str_replace( ' ', '_', trim( $alert ) ) );
				}
			}
		} else {
			$validation->name	=	$field->validation;
			$lang   			=	JFactory::getLanguage();
			$lang->load( 'plg_cck_field_validation_'.$field->validation, JPATH_ADMINISTRATOR, null, false, true );
			$alert				=	JText::_( 'PLG_CCK_FIELD_VALIDATION_'.$field->validation.'_ALERT' );
		}
		
		$prefix	=	JCck::getConfig_Param( 'validation_prefix', '* ' );
		$rule	=	'
					"'.$validation->name.'":{
						"'.$rule.'": '.$def.',
						"alertText":"'.$prefix.$alert.'"}
					';

		$config['validation'][$validation->name]	=	$rule;
		
		return $validation;
	}
	
	// g_onCCK_Field_ValidationPrepareStore
	public static function g_onCCK_Field_ValidationPrepareStore( $name, $value, &$config, $type, $rule, $def )
	{
		$app	=	JFactory::getApplication();
		$error	=	0;
		
		if ( $value == '' ) {
			return $error;
		}
		switch ( $rule ) {
			case 'regex':
				if ( ! preg_match( $def, $value ) ) {
					$error	=	1;
				}
				break;
			default:
				break;
		}
		
		if ( $error == 1 ) {
			$lang   =	JFactory::getLanguage();
			$lang->load( 'plg_cck_field_validation_'.$type, JPATH_ADMINISTRATOR, null, false, true );
			$alert	=	JText::_( 'PLG_CCK_FIELD_VALIDATION_'.$type.'_ALERT' );
			$alert	.=	' - '.$name;
			$app->enqueueMessage( $alert, 'error' );
			$config['validate']	=	'error';
		}
		
		return $error;
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff
		
	// g_getPath
	public static function g_getPath( $type = '' )
	{
		return JURI::root( true ).'/plugins/'.self::$construction.'/'.$type;
	}
	
	// g_getValidation
	public static function g_getValidation( $params )
	{
		if ( ! $params ) {
			$validation			=	new stdClass;
			$validation->alert	=	'';
			
			return $validation;
		}
		
		$registry	=	new JRegistry;
		$registry->loadString( $params );
		$validation	=	$registry->toObject();
		
		return $validation;
	}
	
	// g_addScript_Construct (deprecated)
	public static function g_addScript_Construct( $validation )
	{
		if ( $validation->name ) {
			$lang	=	JFactory::getLanguage();
			$lang->load( 'plg_cck_field_validation_'.$validation->name, JPATH_ADMINISTRATOR, null, false, true );
		}
		
		$doc	=	JFactory::getDocument();
		Helper_Include::addTooltip( 'span[title].qtip_cck', 'left center', 'right center' );
		
		$js	=	'
				$j(document).ready(function(){
					var elem = "'.$validation->id.'_validation_options";
					var encoded = parent.$j("#"+elem).val();
					var data = ( encoded != "" ) ? $j.evalJSON(encoded) : "";
					$j.each(data, function(k, v) {
						$j("#"+k).val( v );
					});
				});
				resetbox = function() {
					var elem = "'.$validation->id.'_validation_options";
					parent.$j("#"+elem).val("");
					closebox();
				}
				submitbox = function() {
					var elem = "'.$validation->id.'_validation_options";
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