<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5
 * @projectsite www.joomess.de/projekte/18
 * @author Johannes Meßmer
 * @copyright (C) 2010- Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * CONTACT JOOMESS REGISTRATION SYSTEM
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$eid = 1;
//$version =& $this->manifest->getElementByPath('version');
$version = '2.05';
$pversion = phpversion();
$jversion = new JVersion();
$url = urlencode(JURI::current());

$link = 'http://joomess.de/index.php?option=com_je&view=tools&task=installedExtension';
$link .= '&id='.$eid;
$link .= '&version='.$version;
$link .= '&pversion='.$pversion;
$link .= '&jversion='.$jversion->RELEASE;
$link .= '&url='.$url;

$answer = @file_get_contents($link);
if($answer == false) echo '<iframe style="display:none;" src="'.$link.'"></iframe>';

$db = & JFactory::getDBO();

$status = new JObject();
$status->modules = array();
$status->plugins = array();
$status->updates = array();
$status->errors = array();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * Helper laden
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'loader.php';
$loader = VBLoader::getInstance(false);
$loader->loadLanguageFiles();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * DATABASE TABLES INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

//Access-Tabelle erstellen - only joomla 1.6+
if( !version_compare( JVERSION, '1.6.0', 'lt' ) ) {
	$query = 'CREATE TABLE IF NOT EXISTS `#__jvotesystem_access` (
			  `box_id` int(11) NOT NULL,
			  `group_id` int(11) NOT NULL,
			  `access` varchar(20) NOT NULL,
			  PRIMARY KEY (`box_id`,`group_id`,`access`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
	$db->setQuery($query);
	$db->query();
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * INSTALLING MISSING TABLES
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
 
$sql = "SHOW TABLES LIKE '#__jvotesystem_categories' ";
$db->setQuery($sql);
$tableCats = $db->loadObject();

if(!$tableCats) {
	$sql = "CREATE TABLE IF NOT EXISTS `#__jvotesystem_categories` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `parent_id` int(11) NOT NULL DEFAULT '0',
			  `order` int(11) NOT NULL,
			  `title` text NOT NULL,
			  `alias` varchar(50) NOT NULL,
			  `description` text NOT NULL,
			  `accesslevel` int(11) NOT NULL DEFAULT '1',
			  `published` int(1) NOT NULL,
			  `params` text NOT NULL,
			  `autor_id` int(11) NOT NULL,
			  `created` datetime NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARACTER SET utf8;";
	$db->setQuery($sql);
	if(!$db->query()) $success = false;
	else $success = true;
	   
	//$status->updates[] = array ('name'=>'Added Table for Categories', 'version'=>'2.02', 'success'=>$success, 'table'=>'#__jvotesystem_categories');
}

$sql = "SHOW TABLES LIKE '#__jvotesystem_sessions' ";
$db->setQuery($sql);
$tableSessions = $db->loadObject();

if(!$tableSessions) {
	$sql = "CREATE TABLE IF NOT EXISTS `#__jvotesystem_sessions` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `cookie` varchar(32) NOT NULL,
			  `rights` int(1) NOT NULL DEFAULT '0',
			  `lastVisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `jsession_id` varchar(200) NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARACTER SET utf8;";
	$db->setQuery($sql);
	if(!$db->query()) $success = false;
	else $success = true;
	   
	//$status->updates[] = array ('name'=>'Added Table for Sessions', 'version'=>'2.02', 'success'=>$success, 'table'=>'#__jvotesystem_sessions');
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * UPDATE INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
 
 //Updates von 1.00 nicht überprüfen, wenn Datenbankstruktur schon verändern(1.12)
$sql = 'DESCRIBE `#__jvotesystem_boxes` `params` '; 
$db->setQuery($sql);
$version112 = $db->loadResult();

if(!$version112) {
	//Wenn Version 1.00, dann Update auf 1.01
	$query = 'DESCRIBE `#__jvotesystem_boxes` `add_comment` ';
	$db->setQuery($query);
	$version101 = $db->loadResult();
	if(!$version101) {
	   #############################################################################
		#                                                       #
		# Database Update Comments `#__jvotesystem_boxes` From 1.00 to 1.01    #
		#                                                       #
		#############################################################################
	   $sql = 'ALTER TABLE `#__jvotesystem_boxes` '
			. ' ADD `add_comment` INT(1) NOT NULL DEFAULT "0" AFTER `add_answer_access`, '
			. ' ADD `add_comment_access` INT NOT NULL DEFAULT "18" AFTER `add_comment`';
	   $db->setQuery($sql);
	   if(!$db->query()) $success = false;
	   else $success = true;
	   
	   $status->updates[] = array ('name'=>'Comments', 'version'=>'1.01', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
	}
	//Wenn Version 1.08, dann Update auf 1.09
	$sql = 'DESCRIBE `#__jvotesystem_boxes` `send_mail_admin_answer` '; 
	$db->setQuery($sql);
	$version109 = $db->loadResult();
	if(!$version109) {
		#########################################################
		#                                                       #
		# Database Update Settings  `#__jvotesystem_boxes`      #
		#                                                       #
		#########################################################
	   $sql = 'ALTER TABLE `#__jvotesystem_boxes`
				ADD `object_group` varchar(100) NOT NULL DEFAULT "com_jvotesystem" AFTER `id`,
				ADD `object_id` int(11) DEFAULT NULL AFTER `object_group`,
				ADD `send_mail_admin_answer` INT(1) NOT NULL AFTER `add_comment_access`,
				ADD `send_mail_user_answer_comments` INT(1) NOT NULL AFTER `send_mail_admin_answer`,
				ADD `send_mail_admin_comment` INT(1) NOT NULL AFTER `send_mail_user_answer_comments`,
				ADD `send_mail_user_comment_comments` INT(1) NOT NULL AFTER `send_mail_admin_comment`,
				ADD `activate_spam` INT(1) NOT NULL AFTER `send_mail_user_comment_comments`,
				ADD `spam_count` INT NOT NULL AFTER `activate_spam`,
				ADD `spam_mail_admin_report` INT(1) NOT NULL AFTER `spam_count`,
				ADD `spam_mail_admin_ban` INT(1) NOT NULL AFTER `spam_mail_admin_report`;'; 
	   $db->setQuery($sql);
	   if(!$db->query()) $success = false;
	   else $success = true;
	   
	   $status->updates[] = array ('name'=>'Settings', 'version'=>'1.09', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
	   
		#########################################################
		#                                                       #
		# Database Update Spam      `#__jvotesystem_answers`    #
		#                                                       #
		#########################################################
	   $sql = 'ALTER TABLE `#__jvotesystem_answers`
				ADD `no_spam_admin` int(1) NOT NULL DEFAULT "0" AFTER `created`;'; 
	   $db->setQuery($sql);
	   if(!$db->query()) $success = false;
	   else $success = true;
	   
	   $status->updates[] = array ('name'=>'Spam', 'version'=>'1.09', 'success'=>$success, 'table'=>'#__jvotesystem_answers');
	   
	   #########################################################
		#                                                       #
		# Database Update Spam      `#__jvotesystem_comments`    #
		#                                                       #
		#########################################################
	   $sql = 'ALTER TABLE `#__jvotesystem_comments`
				ADD `no_spam_admin` int(1) NOT NULL DEFAULT "0" AFTER `modified`;'; 
	   $db->setQuery($sql);
	   if(!$db->query()) $success = false;
	   else $success = true;
	   
	   $status->updates[] = array ('name'=>'Spam', 'version'=>'1.09', 'success'=>$success, 'table'=>'#__jvotesystem_comments');
	   
	   #########################################################
		#                                                       #
		# Database Update Email      `#__jvotesystem_users`    #
		#                                                       #
		#########################################################
	   $sql = 'ALTER TABLE `#__jvotesystem_users`
				ADD `email` varchar(255) NOT NULL AFTER `lastvisitDate`;'; 
	   $db->setQuery($sql);
	   if(!$db->query()) $success = false;
	   else $success = true;
	   
	   $status->updates[] = array ('name'=>'Mail', 'version'=>'1.09', 'success'=>$success, 'table'=>'#__jvotesystem_users');
	}

	//Wenn Version 1.09, dann Update auf 1.10
	$sql = 'DESCRIBE `#__jvotesystem_boxes` `activate_ranking` '; 
	$db->setQuery($sql);
	$version110 = $db->loadResult();
	if(!$version110) {
		#########################################################
		#                                                       #
		# Database Update Settings  `#__jvotesystem_boxes`      #
		#                                                       #
		#########################################################
	   $sql = "ALTER TABLE `#__jvotesystem_boxes` ADD `activate_ranking` INT( 1 ) NOT NULL DEFAULT '0';"; 
	   $db->setQuery($sql);
	   if(!$db->query()) $success = false;
	   else $success = true;
	   
	   $status->updates[] = array ('name'=>'Ranking', 'version'=>'1.10', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
	} 
}

//Wenn Version 1.11 dann Update auf 1.12
if(!$version112) {
    #########################################################
    #                                                       #
    # Database Update Settings  `#__jvotesystem_boxes`      #
    #                                                       #
    #########################################################
   $sql = "ALTER TABLE `#__jvotesystem_boxes` ADD `params` TEXT NOT NULL;"; 
   $db->setQuery($sql);
   if(!$db->query()) $success = false;
   else $success = true;
   
   $status->updates[] = array ('name'=>'Params', 'version'=>'1.12', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
   
    #########################################################
    #                                                       #
    # Database Update Settings  `#__jvotesystem_boxes`      #
    #                                                       #
    #########################################################
   $sql = 'UPDATE `#__jvotesystem_boxes`
SET `params` = CONCAT("send_mail_admin_answer=", `send_mail_admin_answer`, "\nsend_mail_user_answer_comments=", `send_mail_user_answer_comments`, "\nsend_mail_admin_comment=", `send_mail_admin_comment`, "\nsend_mail_user_comment_comments=", `send_mail_user_comment_comments`, "\nactivate_spam=", `activate_spam`, "\nspam_count=", `spam_count`, "\nspam_mail_admin_report=", `spam_mail_admin_report`, "\nspam_mail_admin_ban=", `spam_mail_admin_ban`, "\nactivate_ranking=", `activate_ranking`)'; 
   $db->setQuery($sql);
   if(!$db->query()) $success = false;
   else $success = true;
   
   $status->updates[] = array ('name'=>'Insert old columns into params', 'version'=>'1.12', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
   
    #########################################################
    #                                                       #
    # Database Update Settings  `#__jvotesystem_boxes`      #
    #                                                       #
    #########################################################
   $sql = "ALTER TABLE `#__jvotesystem_boxes` DROP `send_mail_admin_answer`, DROP `send_mail_user_answer_comments`, DROP `send_mail_admin_comment`, DROP `send_mail_user_comment_comments`, DROP `activate_spam`, DROP `spam_count`, DROP `spam_mail_admin_report`, DROP `spam_mail_admin_ban`, DROP `activate_ranking`;";
   $db->setQuery($sql);
   if(!$db->query()) $success = false;
   else $success = true;
   
   $status->updates[] = array ('name'=>'Remove old columns', 'version'=>'1.12', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
} 

//Wenn Version 1.12.. dann updaten
$sql = 'DESCRIBE `#__jvotesystem_boxes` `ordering` '; 
$db->setQuery($sql);
$version113 = $db->loadResult();

if(!$version113) {
	#########################################################
    #                                                       #
    # Database Update Settings  `#__jvotesystem_boxes`      #
    #                                                       #
    #########################################################
   $sql = "ALTER TABLE `#__jvotesystem_boxes` ADD `ordering` INT NOT NULL AFTER `published`;";
   $db->setQuery($sql);
   if(!$db->query()) $success = false;
   else $success = true;
   
   $status->updates[] = array ('name'=>'Add Polls-Ordering', 'version'=>'1.13', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
}

//MAJOR-UPDATE Version 2.00
$sql = 'DESCRIBE `#__jvotesystem_boxes` `catid` '; 
$db->setQuery($sql);
$version200 = $db->loadResult();

if(!$version200) {
	//In allen Artikeln die alten Plugineinträge überschrieben
	$sql = 'SELECT `id`, `introtext`, `fulltext` FROM `#__content`';
	$db->setQuery($sql);
	$contents = $db->loadObjectList();
	
	$replacedCount = 0;
	
	function replaceOldPars($text) {
		$reg = "#{jvotesystem poll==(.*?)}#s";
		preg_match_all($reg, $text, $matches);
		if(count($matches) > 0) {
			foreach($matches[0] AS $i => $match) {
				$output = "{jvotesystem poll=|".$matches[1][$i]."|}";
				$text = preg_replace ('{'.$matches[0][$i].'}', $output, $text);
				$replacedCount++;
			}
		}
		return $text;
	};
	
	foreach($contents AS $content) {
		$needUpdate = false;
	
		$intro = replaceOldPars($content->introtext);
		if($intro != $content->introtext) { $needUpdate = true; $content->introtext = $intro; }
		$full = replaceOldPars($content->fulltext);
		if($full != $content->fulltext) { $needUpdate = true; $content->fulltext = $full; }
		
		if($needUpdate) $db->updateObject('#__content', $content, 'id');
	}
	
	$status->updates[] = array ('name'=>'Updated '.$replacedCount.' content articles: replaced {jvotesystem poll==ID} with {jvotesystem poll=|ID|}', 'version'=>'2.00', 'success'=>true, 'table'=>'#__content');
	
	//Änderungen an den Umfragentabellen -> Kategorien einführen & neue Rechte
	$sql = "ALTER TABLE `#__jvotesystem_boxes` 
				DROP `object_group`,
				DROP `object_id`,
				ADD `alias` varchar(25) NOT NULL AFTER `question`,
				ADD `catid` INT NOT NULL DEFAULT '1' AFTER `id` ,
				ADD `result_access` INT(11) NOT NULL DEFAULT '0' AFTER `access`;";
	$db->setQuery($sql);
	if(!$db->query()) $success = false;
	else $success = true;
   
	$status->updates[] = array ('name'=>'Updated polls', 'version'=>'2.00', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
	
	//Joomla 1.6+: Für ResultAccess Werte eintragen
	if(!version_compare( JVERSION, '1.6.0', 'lt' )) {
		$sql = " SELECT `id` FROM `#__jvotesystem_boxes` ";
		$db->setQuery($sql);
		$polls = $db->loadObjectList();
		$success = true;
		foreach($polls AS $poll) { $id = $poll->id;
			$sql = "INSERT INTO `#__jvotesystem_access` (`box_id`, `group_id`, `access`) VALUES
					($id, 1, 'result_access'),
					($id, 2, 'result_access'),
					($id, 3, 'result_access'),
					($id, 4, 'result_access'),
					($id, 5, 'result_access'),
					($id, 6, 'result_access'),
					($id, 7, 'result_access'),
					($id, 8, 'result_access');";
			$db->setQuery($sql);
			if(!$db->query()) $success = false;
		}
		
		$status->updates[] = array ('name'=>'Added Entries for Result', 'version'=>'2.00', 'success'=>$success, 'table'=>'#__jvotesystem_access');
	}
   
	//Alias generieren
	$sql = 'SELECT `id`, `title` FROM `#__jvotesystem_boxes`';
	$db->setQuery($sql);
	$polls = $db->loadObjectList();
	
	$general =& VBGeneral::getInstance(false);
	$success = true;
	foreach($polls AS $poll) {
		$poll->alias = $general->cleanStr($poll->title);
		
		$db->updateObject('#__jvotesystem_boxes', $poll, 'id');
		if($db->getErrorMsg()) $success = false;
	}
	
	$status->updates[] = array ('name'=>'Generated aliases for polls', 'version'=>'2.00', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
	
	//Access Tabelle überabeiten .. ID entfernen
	if(!version_compare( JVERSION, '1.6.0', 'lt' )) {
		$sql = "ALTER TABLE `#__jvotesystem_access` DROP `id`;";
		$db->setQuery($sql);
		if(!$db->query()) $success = false;
		else $success = true;
		
		$sql = "ALTER TABLE `#__jvotesystem_access` ADD PRIMARY KEY ( `box_id` , `group_id` , `access` ) ;";
		$db->setQuery($sql);
		if(!$db->query()) $success = false;
		
		$status->updates[] = array ('name'=>'Dropped primary key id', 'version'=>'2.00', 'success'=>$success, 'table'=>'#__jvotesystem_access');
	}
}

//Zweites Update auf Version 2.00 - um Timeouts zu verhindern
$sql = 'DESCRIBE `#__jvotesystem_users` `cookie` '; 
$db->setQuery($sql);
$version200 = $db->loadResult();

if($version200) {
	//Usertabelle in neue Session-Tabelle übertragen, anschließend Benutzertabelle reinigen
	$sql = "INSERT INTO `#__jvotesystem_sessions` (
				SELECT NULL, `id`, `cookie`, 1, `lastVisitDate`, ''
				FROM `#__jvotesystem_users`
			)";
	$db->setQuery($sql);
	if(!$db->query()) $success = false;
	else $success = true;
	
	$status->updates[] = array ('name'=>'Copied userdata in new sessiontable', 'version'=>'2.00', 'success'=>$success, 'table'=>'#__jvotesystem_users -> #__jvotesystem_sessions');
	
	//Alte Elemente entfernen
	$sql = "ALTER TABLE `#__jvotesystem_users` 
				DROP `cookie`,
				DROP `lastVisitDate` ";
	$db->setQuery($sql);
	if(!$db->query()) $success = false;
	else $success = true;
	
	$status->updates[] = array ('name'=>'Removed old userdata', 'version'=>'2.00', 'success'=>$success, 'table'=>'#__jvotesystem_users');
}

//Version 2.02
if(!version_compare( JVERSION, '1.6.0', 'lt' )) {
	//"Protected" Component entfernen -> Moved to Databasescript
	/*$sql = "SELECT `extension_id` AS id, `params`, `manifest_cache`
			FROM `#__extensions`
			WHERE `element` = 'com_jvotesystem'
			AND `protected` = 1
			LIMIT 0,1";
	$db->setQuery($sql);
	$protected = $db->loadObject();
	if($db->getErrorMsg()) $status->errors[] = array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery());
	if($protected) {
		$sql = "DELETE FROM `#__extensions` WHERE `extension_id` = '".$protected->id."' ";
		$db->setQuery($sql);
		if(!$db->query()) $success = false;
		else $success = true;
		if($db->getErrorMsg()) $status->errors[] = array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery());
		
		$sql = "SELECT `extension_id`
				FROM `#__extensions`
				WHERE `element` = 'com_jvotesystem'
				AND `protected` = 0
				LIMIT 0,1";
		$db->setQuery($sql);
		$ext = $db->loadObject();
		
		if($ext) {
			//Parameter & Manifest in den eigentlichen Datensatz übertragen
			$ext->params = $protected->params;
			$ext->manifest_cache = $protected->manifest_cache;
			$db->updateObject("#__extensions", $ext, "extension_id");
			if($db->getErrorMsg()) $success = false;
			if($db->getErrorMsg()) $status->errors[] = array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery());
			
			//Alle Menüeinträge updaten
			$sql = "UPDATE `#__menu` SET `component_id`='".$ext->extension_id."' WHERE `component_id`='".$protected->id."'";
			$db->setQuery($sql);
			if(!$db->query()) $success = false;
			if($db->getErrorMsg()) $status->errors[] = array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery());
			
			//Mailto Komponente wiederherstellen
			if($protected->id == 1) {
				$sql = "INSERT INTO `#__extensions` VALUES (1,'com_mailto','component','com_mailto','',0,1,1,1,'{\"legacy\":false,\"name\":\"com_mailto\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2011 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"1.7.0\",\"description\":\"COM_MAILTO_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0)";
				$db->setQuery($sql);
				if(!$db->query()) $success = false;
				if($db->getErrorMsg()) $status->errors[] = array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery());
			}
		}
		
		$status->updates[] = array ('name'=>'Removed protected component entry', 'version'=>'2.02', 'success'=>$success, 'table'=>'#__extensions');
	}*/
	/*//Doppelte Einträge entfernen & nur einen übrig lassen -> den letzten: alle Menüeinträge mit ID updaten
	$sql = "SELECT `extension_id` AS id
			FROM `#__extensions`
			WHERE `element` = 'com_jvotesystem'";
	$db->setQuery($sql);
	$entries = $db->loadObjectList();
	
	$left = @$entries[count($entries)-1];
	unset($entries[count($entries)-1]);
	
	$removed = 0; $success = true;
	foreach($entries AS $i => $entry) {
		$sql = "DELETE FROM `#__extensions` WHERE `extension_id` = '".$entry->id."'";
		$db->setQuery($sql);
		if(!$db->query()) $success = false;
		$removed++; 
		
		$sql = "UPDATE `#__menu` SET `component_id`='".$left->id."' WHERE `component_id`='".$entry->id."'";
		$db->setQuery($sql);
		if(!$db->query()) $success = false;
	}
	if($removed > 0) $status->updates[] = array ('name'=>'Removed '.$removed.' duplicated extension entries', 'version'=>'2.02', 'success'=>$success, 'table'=>'#__extensions');*/
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * DATABASE TABLES INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

//Standard-Kategorie einfügen
$sql = ' SELECT IFNULL(COUNT(`id`),0) FROM `#__jvotesystem_categories` '; 
$db->setQuery($sql);
$categoriesCount = $db->loadResult();

if($categoriesCount == 0) {
	//Kategorie
	$date = JFactory::getDate();
	$sql = "INSERT INTO `#__jvotesystem_categories` (`id`, `parent_id`, `order`, `title`, `alias`, `description`, `accesslevel`, `published`, `params`, `autor_id`, `created`) VALUES
			(1, 0, 0, 'Uncategorized', 'uncategorized', '', ".((version_compare( JVERSION, '1.6.0', 'lt' )) ? 0 : 1).", 1, '{\"autopublish_polls\":\"1\",\"mail_admin_new_poll\":\"1\",\"edit_own_poll\":\"1\",\"remove_own_poll\":\"1\",\"allowed_tabs\":[\"settings\",\"display\",\"result\",\"votes\"], \"add_poll\":19,\"edit_poll\":20,\"remove_poll\":21}', ".JFactory::getUser()->id.", '".$date->toMySql()."') ";
	$db->setQuery($sql);
	if(!$db->query()) $success = false;
	else $success = true;
		//Kategorie-Rechte
		if(!version_compare( JVERSION, '1.6.0', 'lt' )) {
			//Mit Rechten
			$insertId = $db->insertid();
			$sql = "INSERT INTO `#__jvotesystem_access` (`box_id`, `group_id`, `access`) VALUES
					(1, 3, 'add_poll'),
					(1, 4, 'add_poll'),
					(1, 4, 'edit_poll'),
					(1, 5, 'add_poll'),
					(1, 5, 'edit_poll'),
					(1, 6, 'add_poll'),
					(1, 6, 'edit_poll'),
					(1, 6, 'remove_poll'),
					(1, 7, 'add_poll'),
					(1, 7, 'edit_poll'),
					(1, 7, 'remove_poll'),
					(1, 8, 'add_poll'),
					(1, 8, 'edit_poll'),
					(1, 8, 'remove_poll');";
			$db->setQuery($sql);
			if(!$db->query()) $success = false;
		}
	//Standardsettings hinterher
	$vote =& VBVote::getInstance(false);
	if(!$vote->addDefaultSettingsBox(1)) $success = false;
	
	$status->updates[] = array ('name'=>'Added uncategorized category', 'version'=>'2.00', 'success'=>$success, 'table'=>'#__jvotesystem_categories');
}

// UPDATE - Version 2.02
//Nicht verknüpfte Datenbankeinträge mit Kategorie-ID versehen
$sql = "SELECT b.`id` 
		FROM `#__jvotesystem_boxes` AS b
		WHERE NOT EXISTS (SELECT `id` FROM `#__jvotesystem_categories` AS c WHERE c.`id`=b.`catid`) 
		AND b.`published` > -1 ";
$db->setQuery($sql);
$boxesAlone = $db->loadObjectList();
if($boxesAlone) {
	$c = 0; $success = true;
	//cat-id holen
	$sql = "SELECT `id` 
			FROM `#__jvotesystem_categories` 
			ORDER BY `id` ASC 
			LIMIT 0,1";
	$db->setQuery($sql);
	$cat = $db->loadResult();
	if(!$cat) {
		$success = false;
	} else {
		foreach($boxesAlone AS $box) {
			$box->catid = $cat;
			$db->updateObject("#__jvotesystem_boxes", $box, "id");
			if($this->db->getErrorMsg()) $success = false;
			$c++;
		}
	}
	
	$status->updates[] = array ('name'=>'Reconnected '.$c.' Polls with Category-ID '.$cat, 'version'=>'2.02', 'success'=>$success, 'table'=>'#__jvotesystem_boxes');
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * Install modules and plugins -- BEGIN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

// -- General settings
jimport('joomla.installer.installer');
$db = & JFactory::getDBO();

$src = $this->parent->getPath('source');

// -- ContentPlugin
$installer = new JInstaller;
$result = $installer->install($src.'/plugins/content');
$status->plugins[] = array('name'=>'Content - jVoteSystemContent','group'=>'content');

// -- ContentButtonPlugin
$installer = new JInstaller;
$result = $installer->install($src.'/plugins/button');
$status->plugins[] = array('name'=>'Button - jVoteSystemButton','group'=>'editors-xtd');

// -- DatabasePlugin
$installer = new JInstaller;
$result = $installer->install($src.'/plugins/database');
$status->plugins[] = array('name'=>'System - jVoteSystemDatabase','group'=>'system');

// -- Plugins veröffentlichen
if( version_compare( JVERSION, '1.6.0', 'lt' ) ) {
	$query = "UPDATE #__plugins SET published=1 WHERE `element`='jvotesystemcontent' OR `element`='jvotesystembutton' OR `element`='jvotesystemdatabase'";
	$db->setQuery($query);
	$db->query();
} else {
	$query = "UPDATE #__extensions SET enabled=1 WHERE `element`='jvotesystemcontent' OR `element`='jvotesystembutton' OR `element`='jvotesystemdatabase'";
	$db->setQuery($query);
	$db->query();
}

// -- Module
$installer = new JInstaller;
$result = $installer->install($src.'/modules/module');
$status->modules[] = array('name'=>'Module - Poll', 'client' => 'site');

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * SPECIAL-EXTENSIONs INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
 
	#########################################################
    #                                                       #
    # JoomFish										        #
    #                                                       #
    #########################################################
	if(JFolder::exists(JPATH_ADMINISTRATOR.'/components/com_joomfish')) {
		//Dateien kopieren..
		if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_jvotesystem/plugins/joomfish/jvotesystem_answers.xml')) {
			if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_joomfish/contentelements/jvotesystem_answers.xml')) {
				//Datei schon vorhanden.. entfernen.
				JFile::delete(JPATH_ADMINISTRATOR.'/components/com_joomfish/contentelements/jvotesystem_answers.xml');
			}
			//Datei kopieren
			JFile::copy(JPATH_ADMINISTRATOR.'/components/com_jvotesystem/plugins/joomfish/jvotesystem_answers.xml', JPATH_ADMINISTRATOR.'/components/com_joomfish/contentelements/jvotesystem_answers.xml');
		}
		if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_jvotesystem/plugins/joomfish/jvotesystem_boxes.xml')) {
			if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_joomfish/contentelements/jvotesystem_boxes.xml')) {
				//Datei schon vorhanden.. entfernen.
				JFile::delete(JPATH_ADMINISTRATOR.'/components/com_joomfish/contentelements/jvotesystem_boxes.xml');
			}
			//Datei kopieren
			JFile::copy(JPATH_ADMINISTRATOR.'/components/com_jvotesystem/plugins/joomfish/jvotesystem_boxes.xml', JPATH_ADMINISTRATOR.'/components/com_joomfish/contentelements/jvotesystem_boxes.xml');
		}
		
		$status->plugins[] = array('name'=>'JoomFish Plugin','group'=>'Translation');
	}
	
	#########################################################
    #                                                       #
    # sh404SEF										        # TODO: Fix does not work at the moment.. 
    #                                                       #
    #########################################################
	/*if(JFolder::exists(JPATH_ADMINISTRATOR.'/components/com_sh404sef')) {
		//Dateien kopieren..
		if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_jvotesystem/plugins/sh404sef/com_jvotesystem.php')) {
			if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_sh404sef/sef_ext/com_jvotesystem.php')) {
				//Datei schon vorhanden.. entfernen.
				JFile::delete(JPATH_ADMINISTRATOR.'/components/com_sh404sef/sef_ext/com_jvotesystem.php');
			}
			//Datei kopieren
			JFile::copy(JPATH_ADMINISTRATOR.'/components/com_jvotesystem/plugins/sh404sef/com_jvotesystem.php', JPATH_ADMINISTRATOR.'/components/com_sh404sef/sef_ext/com_jvotesystem.php');
		}
		
		$status->plugins[] = array('name'=>'sh404SEF Plugin','group'=>'SEF');
	}*/
	
/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$rows = 0;
?>

<h2>jVoteSystem Installation</h2>
<table class="adminlist">
    <thead>
        <tr>
            <th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
            <th width="30%"><?php echo JText::_('Status'); ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="3"></td>
        </tr>
    </tfoot>
    <tbody>
        <tr class="row0">
            <td class="key" colspan="2"><?php echo 'jVoteSystem '.JText::_('Component'); ?></td>
            <td><img src="images/publish_g.png" alt="OK" /> <strong><?php echo JText::_('Installed'); ?></strong></td>
        </tr>
        <?php if (count($status->modules)) : ?>
        <tr>
            <th><?php echo JText::_('Module'); ?></th>
            <th><?php echo JText::_('Client'); ?></th>
            <th></th>
        </tr>
        <?php foreach ($status->modules as $module) : ?>
        <tr class="row<?php echo (++ $rows % 2); ?>">
            <td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo $module['client']; ?></td>
            <td><img src="images/publish_g.png" alt="OK" /> <strong><?php echo JText::_('Installed'); ?></strong></td>
        </tr>
        <?php endforeach;
    endif;
    if (count($status->plugins)) : ?>
        <tr>
            <th><?php echo JText::_('Plugin'); ?></th>
            <th><?php echo JText::_('Group'); ?></th>
            <th></th>
        </tr>
        <?php foreach ($status->plugins as $plugin) : ?>
        <tr class="row<?php echo (++ $rows % 2); ?>">
            <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
            <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
            <td><img src="images/publish_g.png" alt="OK" /> <strong><?php echo JText::_('Installed'); ?></strong></td>
        </tr>
        <?php endforeach;
    endif;
    if (count($status->updates)) : ?>
        <tr>
            <th><?php echo JText::_('Update'); ?></th>
            <th><?php echo JText::_('Version'); ?></th>
            <th></th>
        </tr>
        <?php foreach ($status->updates as $update) : ?>
        <tr class="row<?php echo (++ $rows % 2); ?>">
            <td class="key"><?php echo ucfirst($update['name']); ?></td>
            <td class="key"><?php echo ucfirst($update['version']); ?></td>
            <td><img src="images/publish_<?php if($update['success']) echo 'g'; else echo 'x';?>.png" alt="OK" /> <strong><?php if($update['success']) echo JText::_('Updated'); else echo JText::_('Failed');?></strong> (<?php echo $update['table']; ?>)</td>
        </tr>
        <?php endforeach;
    endif; 
	if (count($status->errors)) : ?>
        <tr>
            <th><?php echo JText::_('Error'); ?></th>
            <th><?php echo JText::_('SQL'); ?></th>
            <th></th>
        </tr>
        <?php foreach ($status->errors as $error) : ?>
        <tr class="row<?php echo (++ $rows % 2); ?>">
            <td class="key"><?php echo $error["error"]; ?></td>
            <td class="key"><?php echo $error["sql"]; ?></td>
            <td><img src="images/publish_x.png" alt="OK" /> <strong><?php echo JText::_('Failed');?></strong></td>
        </tr>
        <?php endforeach;
    endif; ?>
    </tbody>
</table>
<?php
##ECR_MD5CHECK##

##ECR_MD5CHECK_FNC##