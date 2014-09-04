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
 * @version     3.2.7 2013-11-23
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


$EventID = JRequest::getInt('id');

// Preparing the query
$db		= Jfactory::getDbo();
$query	= $db->getQuery(true);
$query->select('state AS evtState, approval AS evtApproval')->from('#__icagenda_events')->where("(id=$EventID)");
$db->setQuery($query);
$evtState=$db->loadObject()->evtState;
$evtApproval=$db->loadObject()->evtApproval;


//Add Error Page
$js_event = "components/com_icagenda/add/js/jsevt.js";
if ($this->data->items == NULL) {
	if (($evtState == 1) AND ($evtApproval == 1)) {
		// get the application object
		echo 'yes';
		$return = JURI::getInstance()->toString();
		$app = JFactory::getApplication();

		// redirect after successful registration
		$app->redirect(htmlspecialchars_decode('index.php?option=com_users&view=login&return=' . urlencode(base64_encode($return))) , JFactory::getApplication()->enqueueMessage(JText::_( 'JGLOBAL_YOU_MUST_LOGIN_FIRST' ), 'info'));
	} else {
		JError::raiseError('404', '<div>&nbsp;</div><div class="hero-unit center"><h1>' . JTEXT::_('COM_ICAGENDA_PAGE_NOT_FOUND') . ' <small><font face="Tahoma" color="red">' . JTEXT::_('JERROR_ERROR') . ' 404</font></small></h1><br /><p>' . JTEXT::_('COM_ICAGENDA_REQUESTED_PAGE_NOT_FOUND') . ', ' . JTEXT::_('COM_ICAGENDA_CONTACT_THE_WEBMASTER_OR_TRY_AGAIN') . '. ' . JTEXT::_('COM_ICAGENDA_USE_YOUR_BROWSERS_BACK_BUTTON') . '</p><p><b>' . JTEXT::_('COM_ICAGENDA_OR_JUST_PRESS_BUTTON') . '</b></p><a href="index.php" class="btn btn-large btn-info button"><i class="icon-home icon-white"></i>&nbsp;' . JTEXT::_('JERROR_LAYOUT_HOME_PAGE') . '</a></div><div align="center"><img src="media/com_icagenda/images/iconicagenda48.png"></div>', '404');
		return false;
	}
}
else {

	// prepare Document
	$document	= JFactory::getDocument();
	$app		= JFactory::getApplication();
	$menus		= $app->getMenu();
	$pathway 	= $app->getPathway();
	$title 		= null;

	foreach ($this->data->items as $i){
		$item	= $i;
	}

	// Set Joomla Site Title (Page Header Title)
	$menu = $menus->getActive();
	if ($menu) {
		$this->params->def('page_heading', $this->params->get('page_title', $item->title));
	} else {
		$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
	}

	$title			= $item->title;

	if (empty($title)) {
		$title = $app->getCfg('sitename');
	}
	elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
		$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
	}
	elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
		$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
	}

	// Open Graph Tags
	$eventTitle		= $item->title;
	$eventType		= 'article';
	$eventImage		= $item->image;
	$imgLink		= filter_var($eventImage, FILTER_VALIDATE_URL);
	$eventUrl		= JURI::getInstance()->toString();
	$eventDesc		= $item->desc;
	$descShort		= $item->descShort;
	$sitename		= $app->getCfg('sitename');

	// Add to the breadcrumb
	$pathway->addItem($eventTitle);

	if ($eventTitle) {
		$document->setTitle($title);
		$document->setMetadata('og:title', $eventTitle );
	}
	if ($eventType) {
	$document->setMetadata('og:type', $eventType );
	}
	if ($eventImage) {
		if (!$imgLink) {
			$document->setMetadata('og:image', JURI::base().$eventImage );
		} else {
			$document->setMetadata('og:image', $eventImage );
		}
	}
	if ($eventUrl) {
		$document->setMetadata('og:url', $eventUrl );
	}
	if ($eventDesc) {
		$document->setDescription(strip_tags($eventDesc));
		$document->setMetadata('og:description', $descShort );
	}
	if ($sitename) {
		$document->setMetadata('og:site_name', $sitename );
	}

	$loadGMapScripts = false;
	if (!empty($item->address) AND $this->GoogleMaps == 1) {
		$loadGMapScripts = true;
	}
	$stamp = $this->data;


	?>
	<div id="icagenda<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">

		<div style="display:block; float:right">
			<?php
			// Manager Icons
			echo $item->ManagerIcons;
			?>
		</div>

	<?php

	// load Theme and css
	if(file_exists( JPATH_SITE . '/components/com_icagenda/themes/packs/'.$this->template.'/'.$this->template.'_event.php' )){

		$tpl_event		= JPATH_SITE . '/components/com_icagenda/themes/packs/'.$this->template.'/'.$this->template.'_event.php';
		$css_component	= '/components/com_icagenda/themes/packs/'.$this->template.'/css/'.$this->template.'_component.css';

	}else{

		$tpl_event 		= JPATH_SITE . '/components/com_icagenda/themes/packs/default/default_event.php';
		$css_component	= '/components/com_icagenda/themes/packs/default/css/default_component.css';

	}
	require_once $tpl_event;
	?>
	</div>
	<?php
}

require_once $js_event;

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
//		if (stripos($scripts[$i], 'maps.google') !== false) {
//			$mapsgooglescriptFound = true;
//		}
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

// Google Maps api V3
if($loadGMapScripts) {
	$document->addScript('https://maps.googleapis.com/maps/api/js?sensor=false');
	$document->addScript( JURI::base( true ) . '/components/com_icagenda/js/icmap.js' );
}

$document->addScript( JURI::base( true ) . '/media/com_icagenda/js/jquery.tipTip.js');

$iCtip	 = array();
$iCtip[] = '	jQuery(document).ready(function(){';
$iCtip[] = '		jQuery(".icTip").tipTip({maxWidth: "200", defaultPosition: "top", edgeOffset: 1});';
$iCtip[] = '	});';

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration(implode("\n", $iCtip));
?>

