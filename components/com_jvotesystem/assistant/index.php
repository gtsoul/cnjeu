<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define ('ABSOLUTE_PATH', dirname(__FILE__) );
define ('RELATIVE_PATH', 'components' . DS . 'com_jvotesystem' . DS . 'assistant');
define ('JPATH_BASE', str_replace(RELATIVE_PATH, "", ABSOLUTE_PATH)); 


require_once ( JPATH_BASE . DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE . DS.'includes'.DS.'framework.php' );

$interface = JRequest::getString('interface', 'site');

$mainframe =& JFactory::getApplication($interface);
$mainframe->initialise();

//-- No direct access
defined('_JEXEC') or die('=;)');

$document = & JFactory::getDocument();

$view = JRequest::getString('view', null);
$task = JRequest::getString('task', null);
	
//Standard-Files hinzuf�gen
$document->addStyleSheet(JURI::root(true).'/PATH_ASSISTANT/assets/css/general.css');
$document->addStyleSheet(JURI::root(true).'/administrator/components/com_jvotesystem/assets/css/general.css');
$document->addStyleSheet(JURI::root(true).'/administrator/components/com_jvotesystem/assets/css/icons.css');
$document->addStyleSheet(JURI::root(true).'/administrator/templates/system/css/system.css');
if(version_compare( JVERSION, '1.6.0', 'lt' )) { $document->addStyleSheet(JURI::root(true).'/administrator/templates/khepri/css/template.css'); }
else { $document->addStyleSheet(JURI::root(true).'/administrator/templates/bluestork/css/template.css'); }
$document->addStyleSheet(JURI::root(true).'/PATH_ASSISTANT/assets/css/themes/base/jquery.ui.all.css');
//$document->addStyleSheet(JURI::root(true).'/components/com_jvotesystem/assets/css/dialog.css');
$document->addScript(JURI::root(true).'/components/com_jvotesystem/assets/js/jquery-1.7.1.min.noconflict.js');
$document->addScript(JURI::root(true).'/PATH_ASSISTANT/assets/js/jquery.ui.core.min.js');
$document->addScript(JURI::root(true).'/PATH_ASSISTANT/assets/js/jquery.ui.widget.min.js');
$document->addScript(JURI::root(true).'/PATH_ASSISTANT/assets/js/jquery.ui.tabs.min.js');
$document->addScript(JURI::root(true).'/PATH_ASSISTANT/assets/js/prettyComments.js');
$document->addScript(JURI::root(true).'/components/com_jvotesystem/assets/js/jvotesystem.min.js');
//$document->addScript(JURI::root(true).'/components/com_jvotesystem/assets/js/ajax_new.js');
//$document->addScript(JURI::root(true).'/components/com_jvotesystem/assets/js/zebra_dialog.js');
$document->addScript(JURI::root(true).'/administrator/components/com_jvotesystem/assets/js/general.js');
$document->addScript(JURI::root(true).'/PATH_ASSISTANT/assets/js/general.js');
$document->addScript('https://www.google.com/jsapi');
$document->addScriptDeclaration('google.load("visualization", "1", {packages:["corechart"]});');

if(version_compare( JVERSION, '1.6.0', 'lt' ))
	$document->addScriptDeclaration ( 'var joomla15 = true;' );
else
	$document->addScriptDeclaration ( 'var joomla15 = false;' );

//Klassen laden
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'loader.php';
$loader =& VBLoader::getInstance();
$loader->loadLanguageFiles();

//Rechte �berpr�fen TODO!
$access =& VBAccess::getInstance();
if(!$access->assistant()) {
	if(version_compare( JVERSION, '1.6.0', 'lt' ))
		$mainframe->redirect(editLink(JURI::base(false))."index.php?option=com_user&tmpl=component&view=login&return=".base64_encode(JURI::root(false)."index.php"), "NOACCESSRIGHTS", "error");
	else
		$mainframe->redirect(editLink(JURI::base(false))."index.php?option=com_users&tmpl=component&view=login&return=".base64_encode(JURI::root(false)."index.php"), "NOACCESSRIGHTS", "error");
	return;
}
	
/*$user = JFactory::getUser();
if(version_compare( JVERSION, '1.6.0', 'lt' )) {
	if($user->gid != 25 OR $user->id == 0)
		$mainframe->redirect(editLink(JURI::base(false))."index.php?option=com_user&view=login&return=".base64_encode(JURI::root(false)."index.php"));
} else {
	if(!JAccess::check($user->id, 'core.admin'))
		$mainframe->redirect(editLink(JURI::base(false))."index.php?option=com_users&view=login&return=".base64_encode(JURI::root(false)."index.php"));
}*/ 

//View �ffnen
class ViewLoader {
	function getView($view, $task, $interface) {
		ob_start();
		switch($view) {
			case "poll":
				require_once ( ABSOLUTE_PATH.DS.'poll'.DS.'view.html.php' );
				
				$view = new AssistantViewPoll($task, $interface);
				break;
			case "ajax":
				require_once ( ABSOLUTE_PATH.DS.'ajax'.DS.'view.html.php' );
				
				$view = new AssistantViewAjax($task, $interface);
				break;
			case "button":
				require_once ( ABSOLUTE_PATH.DS.'button'.DS.'view.html.php' );
				
				$view = new AssistantViewButton($task);
				break;
		}
		$component = ob_get_contents();
		ob_clean();
		return $component;
	}
}

$loader = new ViewLoader();
$component = $loader->getView($view, $task, $interface);

//ausgeben
$headData = $document->getHeadData();
//HeadData & Text verarbeiten
function editLink($link) {
	$link = str_replace('components/com_jvotesystem/assistant/', "", $link);
	$link = str_replace('PATH_ASSISTANT', "components/com_jvotesystem/assistant/", $link);
	return $link;
}

$newStyleSheets = array();
foreach($headData["styleSheets"] AS $key => $stylesheet) {
	$newKey = editLink($key);
	$newStyleSheets[$newKey] = $stylesheet;
}
$headData["styleSheets"] = $newStyleSheets; 

$newScripts = array();
foreach($headData["scripts"] AS $key => $script) {
	$newKey = editLink($key);
	$newScripts[$newKey] = $script;
}
$headData["scripts"] = $newScripts; 

$component = editLink($component);

if($view == "ajax") {
	echo $component;
} else {
	require_once ( ABSOLUTE_PATH .DS.'html.php' );
}

?>