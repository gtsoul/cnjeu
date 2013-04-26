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

//-- Include the helper file
@require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'loader.php';

// Make sure jVoteSystem is installed
if(!file_exists(JPATH_ADMINISTRATOR.'/components/com_jvotesystem')) {
	return;
}
		
$db = JFactory::getDBO();
// Is jVoteSystem enabled?
if(version_compare(JVERSION, '1.6.0', 'ge')) {
	$db->setQuery('SELECT `enabled` FROM `#__extensions` WHERE `element` = "com_jvotesystem" AND `type` = "component"');
	$enabled = $db->loadResult();
} else {
	$db->setQuery('SELECT `enabled` FROM `#__components` WHERE `link` = "option=com_jvotesystem"');
	$enabled = $db->loadResult();
}
if(!$enabled) return;

$loader =& VBLoader::getInstance();
$vbparams =& VBParams::getInstance('module', true);

$vote =& VBVote::getInstance();

//-- Get a parameter from the module's configuration
$type = $params->get('type', "poll");
$poll_id = $params->get('poll_id', null);
$all_cats = $params->get('cat_all', true);
$cat_id = $params->get('categories', null);

if($type == "poll") {
	echo $vote->getVotebox($poll_id, false, 1, $params->get('show_link', true), "module", false);
} else {
	$filter = array();
	if(!$all_cats)
		$filter["cid"] = $params->get('cat_id', 0);
	$filter["order"] =  $params->get('order_by', true);
	$filter["subcats"] =  $params->get('sub_cats', true);
	$filter["time"] =  $params->get('time', 'all-time');
	$filter["excludes"] = $vote->getLoaded();
	
	//Verhindern, dass das gleiche Poll wie auf der Seite geladen wird.. nur bei Pollview, nicht Plugin
	if(JRequest::getString("option") == "com_jvotesystem" && JRequest::getString("view") == "poll") {
		$id = JRequest::getInt('bid', null);
		if($id == null) $id = JRequest::getInt('id', null);
		$alias = JRequest::getString("alias", "");
		
		$box = $vote->getBox($id, $alias);
		if($box) $filter["excludes"][] = $box->id;
	}
	
	$polls = $vote->getPolls($filter, 0, 1); 
	if(!$polls) return false;
	
	echo $vote->getVotebox($polls[0]->id, false, 1, $params->get('show_link', true), "module", false);
//    echo '<p style="text-align: center;font-size: 11pt; font-style: italic; text-align: center;"><a href="http://joomess.de/projects/jvotesystem">jVS</a> by <a href="http://www.joomess.de">www.joomess.de</a>.</p>';
	/* The copyright information may not be removed or made invisible! To remove the code, please purchase a version on www.joomess.de. Thanks!*/
}
