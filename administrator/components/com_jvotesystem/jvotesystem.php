<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5 - 2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

/**
 *  Require the base controller.
 */
require_once JPATH_COMPONENT.DS.'controller.php';

//-- Require specific controller if requested
if($controller = JRequest::getCmd('controller'))
{
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

    if(file_exists($path))
    {
        require_once $path;
    }
    else
    {
        $controller = '';
    }
}

//-- Create the controller
$classname = 'jVoteSystemController'.$controller;
$controller = new $classname();

$document =& JFactory::getDocument();
$cssFile = JURI::base(true).'/components/com_jvotesystem/assets/css/icons.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());
$cssFile = JURI::base(true).'/components/com_jvotesystem/assets/css/general.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

if(version_compare( JVERSION, '1.6.0', 'lt' ))
	$document->addScriptDeclaration ( 'var joomla15 = true;' );
else
	$document->addScriptDeclaration ( 'var joomla15 = false;' );
	
$document->addScript(JURI::root(true).'/components/com_jvotesystem/assets/js/jquery-1.7.1.min.noconflict.js');
$document->addScript(JURI::root(true).'/components/com_jvotesystem/assets/js/jvotesystem.min.js');
$document->addScript(JURI::base(true).'/components/com_jvotesystem/assets/js/general.js');

JHTML::_( 'behavior.modal' );

//-- Load language files
$jlang =& JFactory::getLanguage();
$jlang->load('com_jvotesystem', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_jvotesystem', JPATH_ADMINISTRATOR, null, true);
$jlang->load('com_jvotesystem', JPATH_COMPONENT_ADMINISTRATOR, null, true);

//-- Perform the Request task
$controller->execute(JRequest::getCmd('task'));

//-- Redirect if set by the controller
$controller->redirect();

function addSub($title, $v, $controller = null, $image = null) {
	
	$enabled = false;
	$view = JRequest::getWord("view", 'jvotesystem');
	if($view == $v) {
		$img = $v;
		if($image != null) $img = $image;
		JToolBarHelper::title(   JText::_( $title).' - '.( 'jVoteSystem' ), $img.'.png' );
		$enabled = true;
	}
	$link = 'index.php?option=com_jvotesystem&view='.$v;
	if($controller != null) $link .= '&controller='.$controller;
	JSubMenuHelper::addEntry( JText::_($title), $link, $enabled);
}
