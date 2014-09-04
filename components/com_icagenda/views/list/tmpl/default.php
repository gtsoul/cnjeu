<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.2.6 2013-11-21
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

?>
<!--
 * - - - - - - - - - - - - - -
 * iCagenda 3.2.7 by Jooml!C
 * - - - - - - - - - - - - - -
 * @copyright	Copyright (C) 2012-2013 JOOMLIC - All rights reserved.
 *
-->
<?php


//
// Old function for template 1.2.x.
//
function convertColor($color){
	#convert hexadecimal to RGB
	if(!is_array($color) && preg_match("/^[#]([0-9a-fA-F]{6})$/",$color)){
		$hex_R = substr($color,1,2);
		$hex_G = substr($color,3,2);
		$hex_B = substr($color,5,2);
		$RGB = hexdec($hex_R).",".hexdec($hex_G).",".hexdec($hex_B);
		return $RGB;
	}

	#convert RGB to hexadecimal
	else{
		if(!is_array($color)){$color = explode(",",$color);}

		foreach($color as $value){
			$hex_value = dechex($value);
			if(strlen($hex_value)<2){$hex_value="0".$hex_value;}
			$hex_RGB='';
			$hex_RGB.=$hex_value;
		}
		return "#".$hex_RGB;
	}

}

$RGB='$RGB';
$RGBa=$RGB[0];
$RGBb=$RGB[1];
$RGBc=$RGB[2];
$item_color = '';
if (isset($item->cat_color)) {$item_color = $item->cat_color;}
$js_list = "components/com_icagenda/add/js/jsevt.js";
$RGB = explode(",",convertColor($item_color)); $a = array($RGBa, $RGBa, $RGBa);
$somme = array_sum($a);
//
// End old function

$navposition = $this->navposition;
$dis_catDesc = $this->display_catDesc;
$catDesc_opts = $this->catDesc_opts;
$CatDesc_global = $this->CatDesc_global;
$CatDesc_global_opts = $this->CatDesc_global_opts;
if ($dis_catDesc == 'global') {
	$display_catDesc = $CatDesc_global;
	$cat_opts = $CatDesc_global_opts;
} else {
	$display_catDesc = $dis_catDesc;
	$cat_opts = $catDesc_opts;
}
if (!is_array($cat_opts)) $cat_opts = array();

// Header
?>
<div id="icagenda<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1 class="componentheading">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>
<?php
if(isset($this->data)) $stamp = $this->data;
$d = 0;
$stampitems = $stamp->items;
if($stamp->container->header){
	echo '<div>';
	echo $stamp->container->header;

	$catid_array = array();
	$catinfos_array = array();

	if($stampitems AND $display_catDesc) {
		foreach ($stampitems AS $event) {
			$cat_id = $event->cat_id;
			$cat_title = $event->cat_title;
			$cat_color = $event->cat_color;
			$cat_desc = $event->cat_desc;
			$fontColor = $event->fontColor;

			array_push($catid_array, $cat_id);

			$array = array($cat_title, $cat_color, $cat_desc, $fontColor);
			$comma_separated = implode("::", $array);

			array_push($catinfos_array, $comma_separated);
		}
	}
	$cat_result = array_unique($catid_array);

	for($i = 0; $i < count($cat_result); $i++) {
		$cat_getinfos = explode('::', $catinfos_array[$i]);
		if (in_array('1', $cat_opts)) {
			echo '<div class="cat_header_title' . $cat_getinfos['3'] . '" style="background: ' . $cat_getinfos['1'] . ';">' . $cat_getinfos['0'] . '</div>';
		}
		if (in_array('2', $cat_opts)) {
			echo '<div class="cat_header_desc">' . $cat_getinfos['2'] . '</div>';
		}
		if (in_array('2', $cat_opts)) {	echo '<div style="clear:both"></div>'; }
	}

	if (!in_array('2', $cat_opts)) {	echo '<div>&nbsp;</div>'; }

	if ($navposition == '0' OR $navposition == '2') {
		if ($display_catDesc) {
			echo '<div>' . $stamp->container->navigator . '</div><div style="clear:both"></div>';
		} else {
			echo $stamp->container->navigator;
		}
	} elseif ($display_catDesc) {
		echo '<div style="clear:both">&nbsp;</div>';
	}
	echo '</div>';
}

$someObjectArr = (array)$stampitems;
$control = empty($someObjectArr);

$allevents = array();

if($stampitems) {
	foreach ($stampitems AS $event) {
		$allDates = $event->AllDates;
		$allevents = array_merge($allevents, $allDates);
	}
}
//print_r($allevents);


$allDisplay = '0';

$document	= JFactory::getDocument();

	// New file to display all dates for each events (in dev.)
	if(file_exists( JPATH_SITE . '/components/com_icagenda/themes/packs/'.$this->template.'/'.$this->template.'_alldates.php' )){

		$tpl_alldates	= JPATH_SITE . '/components/com_icagenda/themes/packs/'.$this->template.'/'.$this->template.'_alldates.php';
		$css_component	= '/components/com_icagenda/themes/packs/'.$this->template.'/css/'.$this->template.'_component.css';

	} else {

		$tpl_alldates	= JPATH_SITE . '/components/com_icagenda/themes/packs/default/default_alldates.php';
		$css_component	= '/components/com_icagenda/themes/packs/default/css/default_component.css';

	}
	// Loading of List of Events Template
	if(file_exists( JPATH_SITE . '/components/com_icagenda/themes/packs/'.$this->template.'/'.$this->template.'_list.php' )){

		$tpl_list		= JPATH_SITE . '/components/com_icagenda/themes/packs/'.$this->template.'/'.$this->template.'_list.php';
		$css_component	= '/components/com_icagenda/themes/packs/'.$this->template.'/css/'.$this->template.'_component.css';

	} else {

		$tpl_list		= JPATH_SITE . '/components/com_icagenda/themes/packs/default/default_list.php';
		$css_component	= '/components/com_icagenda/themes/packs/default/css/default_component.css';

	}

if(!$control){

	$dateFormat = 'components/com_icagenda/helpers/ichelper.php';

	if((file_exists($tpl_alldates)) AND ($allDisplay == 1)) {
		include_once $dateFormat;
//		print_r($allevents);
		foreach ($allevents AS $evt) {
			foreach ($stamp->items as $item){
				$evtDates = $item->AllDates;
				if (in_array($evt, $evtDates)) {
					$idevt = $item->id;
				}
				if ($idevt == $item->id) {
					$nextDate_evt = nextDate($evt, $item);
					$evenTime_evt = evenTime($evt, $item);

					$titlebar = $item->titlebar;
					$url = $item->url;
					$cat_title = $item->cat_title;
					$cat_color = $item->cat_color;
					$image = $item->image;
				}
				$Date = $nextDate_evt;
				$evenTime = $evenTime_evt;

			}
			echo 'ID: '.$idevt.' /';
			require $tpl_alldates;
		}
	} else {
		require_once $tpl_list;
	}
}
// Navigator
if($stamp->container->navigator){
echo '<div>';
	if ($navposition == '1' OR $navposition == '2') {
		echo $stamp->container->navigator;
	}
	echo '</div><div style="clear:both"></div>';
}
require_once $js_list;
?>
</div>
<?php
$document->addStyleSheet( JURI::base( true ) . $css_component );
$document->addStyleSheet( JURI::base( true ) . '/components/com_icagenda/add/css/style.css' );
$document->addStyleSheet( JURI::base( true ) . '/media/com_icagenda/icicons/style.css' );
$document->addStyleSheet( JURI::base( true ) . '/media/com_icagenda/css/tipTip.css' );

if(version_compare(JVERSION, '3.0', 'lt')) {

	JHTML::_('behavior.mootools');


	// load jQuery, if not loaded before (NEW VERSION IN 1.2.6)
	$scripts = array_keys($document->_scripts);
	$scriptFound = false;
	$scriptuiFound = false;
	$mapsgooglescriptFound = false;
	for ($i = 0; $i < count($scripts); $i++) {
		if (stripos($scripts[$i], 'jquery.min.js') !== false) {
			$scriptFound = true;
		}
		// load jQuery, if not loaded before as jquery - added in 1.2.7
		if (stripos($scripts[$i], 'jquery.js') !== false) {
			$scriptFound = true;
		}
		if (stripos($scripts[$i], 'jquery-ui.min.js') !== false) {
			$scriptuiFound = true;
		}
	}

	// jQuery Library Loader
	if (!$scriptFound) {
		// load jQuery, if not loaded before
		if (!JFactory::getApplication()->get('jquery')) {
			JFactory::getApplication()->set('jquery', true);
			// add jQuery
			$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
			$document->addScript( JURI::base( true ) . '/components/com_icagenda/js/jquery.noconflict.js');
		}
	}

	if (!$scriptuiFound) {
		$document->addScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
	}

}
else {

	jimport( 'joomla.environment.request' );

	JHtml::_('behavior.formvalidation');
	JHtml::_('bootstrap.framework');
	JHtml::_('jquery.framework');

	$document->addScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');

}

$document->addScript( JURI::base( true ) . '/media/com_icagenda/js/jquery.tipTip.js');

$iCtip	 = array();
$iCtip[] = '	jQuery(document).ready(function(){';
$iCtip[] = '		jQuery(".icTip").tipTip({maxWidth: "200", defaultPosition: "top", edgeOffset: 1});';
$iCtip[] = '	});';

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration(implode("\n", $iCtip));
?>
