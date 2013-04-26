<?php

// restricted access
defined( '_JEXEC' ) or die;

/*
 * Display wrap module
 */
function modChrome_wrap( $module, &$params, &$attribs ) {
	
	$class = $params->get('moduleclass_sfx', '');
	
	/*
	 * <div>
	 * 		<div>		
	 * 			<h2></h2>
	 * 		</div>
	 * 		<div></div> 
	 * </div>
	 * 
	 */
	$output = '<div class="module ' . $class . '" >';

	if( strpos($class, 'hot') !== false || strpos($class, 'top') !== false)
		$output .= '<div class="ribbon"></div>';

	if ($module->showtitle) {			
	   	$output .= 	'<h2>' . $module->title . '</h2>';
	}

		$output .= 	$module->content;
		
	$output .= '</div>';		
	
	echo $output;
	
}

/*
 * Display various modules
 */
function modChrome_various( $module, &$params, &$attribs ) {
	
	$document 	=	JFactory::getDocument();
	$class  	= 	$params->get('moduleclass_sfx', '');
	$position 	= 	$module->position;
	$align		= 	isset($attribs['align']) ? $attribs['align'] : 'h';
	$width		= 	isset($attribs['width']) ? $attribs['width'] : 'equal';
	
	// get nb modules
	static $nb = array();
	
	if ( !isset($nb[$position]) )	
	 	$nb[$position] = 1 ; 
	else
		$nb[$position]++ ;
	
	/*
	 *	<div>
	 *		<div>
	 * 			<h2></h2>
	 *		</div> 
	 *	</div>
	 * 
	 */
	$output = '<div class="'; 
	
	if($align == 'h')
		$output .= 'float-left ';
	
	if($width == 'equal')
		$output .= 'width-' . getModuleWidth( $document->countModules($position) . ' ');
	
	if( $nb[$position] != $document->countModules($position) )	
		$output .= ' separator';
	
	$output .= '">';
	
	$output .= '<div class="module various';
	
	if($class)
		$output .= ' ' . $class;
		
	$output .= '">';
	
	if( strpos($class, 'hot') !== false || strpos($class, 'top') !== false)
			$output .= '<div class="ribbon"></div>';
	
	if ($module->showtitle)
	   	$output .= '<h2>' . $module->title . '</h2>';
	elseif( strpos($class, 'houses') !== false ) 
		$output .= '<h2>Retrouvez nos <mark>maisons</mark></h2>';
	elseif( strpos($class, 'schools') !== false ) 
		$output .= '<h2>Retrouvez nos <mark>écoles</mark></h2>';
	
	$output .= $module->content;
	
	$output .= '</div>';
	
	$output .= '</div>';
		
	echo $output;
	
}
	
?>