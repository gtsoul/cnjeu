<?php
	 
// restricted access
defined( '_JEXEC' ) or die;

// app
$app		=	JFactory::getApplication();

// document html
$document 	=	JFactory::getDocument();

 
// menu infos
$menu 		= 	$app->getMenu();
$active 	=	$menu->getActive();
$menutype 	=	isset( $active ) ? $active->menutype : null;

/* css update */
$class = array();

if( isset( $active ) )
	$suffix	= $active->params->get('pageclass_sfx');
	
if( isset( $suffix ) )
	$class[] = $suffix;

// hp
$hp = false;
if( $active == $menu->getDefault() ) {
	$hp = true;
	$class[] = 'hp';	
}	
// left column layout	
if( $document->countModules('left') && isset($document->params) )
	$class[] = $document->params->get( 'layout', 'left' );

// right column
if( $document->countModules('right') )
	$class[] = 'rightcolumn';

// class en fonction de la catégorie
if( $active != $menu->getDefault() ) {
    $classNote = '';
    $parent_id = $menu->getItem($active->parent_id);
    while($parent_id && $classNote == '') {
      $classNote = $parent_id->note;
      $parent_id = $menu->getItem($parent_id->parent_id);
    }
    if($classNote && $classNote != '') {
      $class[] = $classNote;
    }    
}
?>