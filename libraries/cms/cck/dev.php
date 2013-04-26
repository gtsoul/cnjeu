<?php
/**
* @version 			SEBLOD 2.x Core ~ $Id: dev.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2012 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Dev
abstract class JCckDev
{
	// forceStorage
	public static function forceStorage( $value = 'none' )
	{
		$doc	=	JFactory::getDocument();
		
		if ( $value == 'none' ) {
			$js		=	'$j(document).ready(function(){ $j("#storage").val( "'.$value.'" ).attr("disabled", "disabled"); $j("#force_storage").val( "1" ); });';
		} else {
			$js		=	'$j(document).ready(function(){ if ( !$j("#myid").val() ) { $j("#storage").val( "'.$value.'" ); $j("#force_storage").val( "1" ); } });';
		}
		
		$doc->addScriptDeclaration( $js );
	}
	
	// importPlugin
	public static function importPlugin( $type, $plugins )
	{
		if ( count( $plugins ) > 0 ) {
			foreach ( $plugins as $plugin ) {
				JPluginHelper::importPlugin( $type, $plugin );	// todo: improve
			}
		} else {
			JPluginHelper::importPlugin( $type );
		}
	}
	
	// init
	public static function init( $plugins = array(), $core = true, $more = array() )
	{
		self::importPlugin( 'cck_field', $plugins );
		
		$config	=	array( 'asset'=>'', 'asset_id'=>0, 'client'=>'', 'doTranslation'=>1, 'doValidation'=>0, 'validation'=>array(), 'item'=>'' );
		
		if ( $core === true ) {
			JFactory::getLanguage()->load( 'plg_cck_field_validation_required', JPATH_ADMINISTRATOR, null, false, true );

			$config['doValidation']	=	2;
			require_once JPATH_PLUGINS.DS.'cck_field_validation'.DS.'required'.DS.'required.php';
		}
		$config['pk']				=	0;
		
		if ( count( $more ) ) {
			foreach ( $more as $k => $v ) {
				$config[$k]	=	$v;
			}
		}
		
		return $config;
	}
	
	// initScript
	public static function initScript( $type, $elem )
	{
		$doc	=	JFactory::getDocument();
		
		if ( $elem->name ) {
			JFactory::getLanguage()->load( 'plg_cck_field_'.$type.'_'.$elem->name, JPATH_ADMINISTRATOR, null, false, true );
		}
		Helper_Include::addTooltip( 'span[title].qtip_cck', 'left center', 'right center' );
		
		$js	=	'
				$j(document).ready(function(){
					var elem = "'.$elem->id.'_'.$type.'_options";
					var encoded = parent.$j("#"+elem).val();
					var data = ( encoded != "" ) ? $j.evalJSON(encoded) : "";
					$j.each(data, function(k, v) { $j("#"+k).val( v ); });
				});
				resetbox = function() {
					var elem = "'.$elem->id.'_'.$type.'_options";
					parent.$j("#"+elem).val("");
					closebox();
				}
				submitbox = function() {
					if ( $j("#adminForm").validationEngine("validate") === true ) {
						var elem = "'.$elem->id.'_'.$type.'_options";
						var data = $j("#adminForm").serializeObject();
						var encoded = $j.toJSON(data);
						parent.$j("#"+elem).val(encoded);
						closebox();
						return;
					}
				} 
			';
		
		$doc->addScriptDeclaration( $js );
	}
	
	// preload
	public static function preload( $fieldnames )
	{
		$preload	=	array();
		$fields_in	=	implode( '","', $fieldnames );
		$fields		=	JCckDatabase::loadObjectList( 'SELECT a.* FROM #__cck_core_fields AS a WHERE a.name IN ("'.$fields_in.'")', 'name' );
		
		foreach ( $fieldnames as $f ) {
			$preload[$f]	=	( isset( $fields[$f] ) ) ? $fields[$f] : $f;
		}
		
		return $preload;
	}
	
	// validate
	public static function validate( $config, $id = 'adminForm' )
	{
		$config['validation']			=	count( $config['validation'] ) ? implode( ',', $config['validation'] ) : '"null":{}';
		$config['validation_options']	=	new JRegistry;
		
		Helper_Include::addValidation( $config['validation'], $config['validation_options'], $id );
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Fields & Markup
	
	// get
	public static function get( $field, $value, &$config = array( 'doValidation' => 2 ), $override = array(), $inherit = array() )
	{
		return JCckDevField::get( $field, $value, $config, $inherit, $override );
	}
	
	// getEmpty
	public static function getEmpty( $properties )
	{
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cck'.DS.'tables'.DS.'field.php';
		$field	=	JTable::getInstance( 'field', 'CCK_Table' );
		
		if ( is_array( $properties ) ) {
			foreach ( $properties as $k => $v ) {
				$field->$k	=	$v;
			}
		}
		
		return $field;
	}
	
	// getForm
	public static function getForm( $field, $value, &$config = array( 'doValidation' => 2 ), $override = array(), $inherit = array() )
	{
		$field	=	JCckDevField::get( $field, $value, $config, $inherit, $override );
		if ( ! $field ) {
			return '';
		}
		
		$html	=	( isset( $field->form ) ) ? $field->form : '';
		
		return $html;
	}
	
	// renderForm
	public static function renderForm( $field, $value, &$config = array( 'doValidation' => 2 ), $override = array(), $inherit = array(), $class = '' )
	{	
		$field	=	JCckDevField::get( $field, $value, $config, $inherit, $override );
		if ( ! $field ) {
			return '';
		}
		
		$tag	=	( $field->required ) ? '<span class="star"> *</span>' : '';
		$class	=	( $class ) ? ' class="'.$class.'"' : '';
		$html	=	( isset( $field->form ) ) ? $field->form : '';
		$html	=	'<li'.$class.'><label>'.$field->label.$tag.'</label>'.$html.'</li>';
		
		return $html;
	}
	
	// renderBlank
	public static function renderBlank( $html = '' )
	{
		return '<li><label></label>'.$html.'</li>';
	}
	
	// renderHelp
	public static function renderHelp( $type, $url = '' )
	{
		if ( !$url ) {
			return;
		}
		
		JFactory::getApplication()->set( 'cck_markup_closed', true );
		
		$link	=	'http://www.seblod.com/support/documentation/'.$url.'?tmpl=component';
		$opts	=	'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=685,height=600';
		$help	=	'<div class="clr"></div><div class="how-to-setup">'
				.	'<a href="'.$link.'" onclick="window.open(this.href, \'targetWindow\', \''.$opts.'\'); return false;">' . JText::_( 'COM_CCK_HOW_TO_SETUP_THIS_'.$type ) . '</a>'
				.	'</div>';
		
		return '</ul>'.$help.'</div>';
	}
	
	// renderLegend
	public static function renderLegend( $legend, $tooltip = '', $tag = '1' )
	{
		if ( $tooltip != '' ) {
			$tag		=	'<span class="star"> &sup'.$tag.';</span>';
			$tooltip	=	' class="qtip_cck" title="'.$tooltip.'"';
		} else {
			$tag		=	'';
			$tooltip	=	'';
		}
		
		return '<div class="legend top left"><span'.$tooltip.'>'.$legend.$tag.'</span></div>';
	}
	
	// renderSpacer
	public static function renderSpacer( $legend, $tooltip = '', $tag = '2', $options = array( 'class_sfx'=>'-2cols' ) )
	{
		$app	=	JFactory::getApplication();
		
		if ( $app->get( 'cck_markup_closed' ) === true ) {
			$close	=	'';
			$app->set( 'cck_markup_closed', false );
		} else {
			$close	=	'</ul></div>';
		}
		if ( $tooltip ) {
			$legend	=	'<span class="qtip_cck" title="'.$tooltip.'">'.$legend.'<span class="star"> &sup'.$tag.';</span></span>';
		}
		
		return $close.'<div class="seblod"><div class="legend top left">'.$legend.'</div><ul class="adminformlist adminformlist'.$options['class_sfx'].'">';
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Variables Manipulation
	
	// fromJSON
	public static function fromJSON( $data = '', $format = 'array' )
	{
		if ( ! $data || ! is_string( $data )  ) {
			return ( $format == 'array' ) ? array() : new stdClass;
		}
		
		$method		=	'to'.ucfirst( $format );
		$registry	=	new JRegistry;
		$registry->loadString( $data, 'JSON' );
		
		return $registry->$method();
	}
	
	// toJSON
	public static function toJSON( $data = '' )
	{
		$registry	=	new JRegistry;
		$registry->loadArray( $data );

		return $registry->toString();
	}
	
	// fromSTRING
	public static function fromSTRING( $data = '', $glue = '||', $format = 'array' )
	{
		// todo: object
		if ( ! $data || ! is_string( $data )  ) {
			return ( $format == 'array' ) ? array() : new stdClass;
		}
		
		return ( $glue != '' ) ? explode( $glue, $data ) : array( $data );
	}
	
	// toSTRING
	public static function toSTRING( $data = '', $glue = '||' )
	{
		// todo: object
		if ( ! is_array( $data ) ) {
			return '';
		}
		
		return implode( $glue, $data );
	}
	
	// toSafeSTRING
	public static function toSafeSTRING( $string )
	{
		$str	=	str_replace( '_', ' ', $string );
		$str	=	JFactory::getLanguage()->transliterate( $str );
		$str	=	preg_replace( array( '/\s+/', '/[^A-Za-z0-9_]/' ), array( '_', '' ), $str );
		$str	=	trim( strtolower( $str ) );
		
		return $str;
	}
	
	// fromXML
	public static function fromXML( $data = '', $isFile = true )
	{
		libxml_use_internal_errors( true );
		
		if ( $isFile ) {
			$xml	=	simplexml_load_file( $data, 'JCckDevXml' );
		} else {
			$xml	=	simplexml_load_string( $data, 'JCckDevXml' );
		}
		
		if ( empty( $xml ) ) {
			JError::raiseWarning( 100, JText::_( 'JLIB_UTIL_ERROR_XML_LOAD' ) );
			
			if ( $isFile ) {
				JError::raiseWarning( 100, $data );
			}
			foreach ( libxml_get_errors() as $error ) {
				JError::raiseWarning( 100, 'XML: ' . $error->message );
			}
		}
		
		return $xml;
	}
}
?>