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

jimport('joomla.plugin.plugin');

class plgSystemjVoteSystemDatabase extends JPlugin
{

    function plgSystemjVoteSystemDatabase( &$subject, $config )
    {
        parent::__construct( $subject, $config );

    }//function

    public function onAfterRender()
    {
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
		
		//Small fixes after new Installation -> check it only in the backend
		$app = JFactory::getApplication();
		if($app->isAdmin() && JRequest::getString("option","") == "com_installer") {
			if(!version_compare( JVERSION, '1.6.0', 'lt' )) {
				require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'loader.php';
				$log =& VBLog::getInstance();
				
				//"Protected" Component entfernen
				$sql = "SELECT `extension_id` AS id, `params`, `manifest_cache`
						FROM `#__extensions`
						WHERE `element` = 'com_jvotesystem'
						AND `protected` = 1
						LIMIT 0,1";
				$db->setQuery($sql);
				$protected = $db->loadObject();
				
				if($protected) {
					$sql = "DELETE FROM `#__extensions` WHERE `extension_id` = '".$protected->id."' ";
					$db->setQuery($sql);
					if(!$db->query()) $success = false;
					else $success = true;
					if($db->getErrorMsg()) $log->add("ERROR", "Failed to remove protected component", array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery()));
			
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
						if($db->getErrorMsg()) $log->add("ERROR", "Failed to remove protected component", array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery()));
							
						//Alle Menüeinträge updaten
						$sql = "UPDATE `#__menu` SET `component_id`='".$ext->extension_id."' WHERE `component_id`='".$protected->id."'";
						$db->setQuery($sql);
						if(!$db->query()) $success = false;
						if($db->getErrorMsg()) $log->add("ERROR", "Failed to remove protected component", array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery()));
							
						//Mailto Komponente wiederherstellen
						if($protected->id == 1) {
							$sql = "INSERT INTO `#__extensions` VALUES (1,'com_mailto','component','com_mailto','',0,1,1,1,'{\"legacy\":false,\"name\":\"com_mailto\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2011 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"1.7.0\",\"description\":\"COM_MAILTO_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0)";
							$db->setQuery($sql);
							if(!$db->query()) $success = false;
							if($db->getErrorMsg()) $log->add("ERROR", "Failed to remove protected component", array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery()));
						}
					}
					
					$log->add("DB", "Removed protected component", array ('success'=>$success, 'table'=>'#__extensions')); 
				}
				//Wenn Menüeintrag nicht angezeigt wird, reparieren
				$db->setQuery('SELECT `extension_id` FROM `#__extensions` WHERE `element` = "com_jvotesystem" AND `type` = "component" AND `protected`=0');
				$id = $db->loadResult();
				
				if($id) {
					$sql = 'UPDATE `#__menu` 
							SET `component_id`="'.$id.'"
							WHERE `menutype` = "main" 
							AND `client_id` = 1 
							AND `alias` = "jvotesystem"';
					$db->setQuery($sql);
					$db->query();
					
					if($db->getErrorMsg()) $log->add("ERROR", "Failed to update admin menu", array ('error'=>$db->getErrorMsg(),'sql'=>$db->getQuery()));
					if($db->getAffectedRows() > 0) $log->add("DB", "Repaired admin menu");
				}
			}
		} 
		
		// Use a 20% chance of running; this allows multiple concurrent page
		$random = rand(1, 5);
		if($random != 3) return;
		
		// Do we have to run (at most once per 1 day)?
		jimport('joomla.html.parameter');
		jimport('joomla.application.component.helper');
		$component =& JComponentHelper::getComponent('com_jvotesystem');
		$params = new JParameter($component->params);
		$last = $params->getValue('plg_jvotesystemdatabase', 0);
		$now = time();
		if(abs($now-$last) < 10800) return;
		
		// Update last run status
		$params->setValue('plg_jvotesystemdatabase', $now);
		$db = JFactory::getDBO();
		if( version_compare(JVERSION,'1.6.0','ge') ) {
			// Joomla! 1.6
			$data = $params->toString('JSON');
			$db->setQuery('UPDATE `#__extensions` SET `params` = '.$db->Quote($data).' WHERE '.
				"`element` = ".$db->quote('com_jvotesystem')." AND `type` = 'component'");
		} else {
			// Joomla! 1.5
			$data = $params->toString('INI');
			$db->setQuery('UPDATE `#__components` SET `params` = '.$db->Quote($data).' WHERE '.
				"`option` = ".$db->quote('com_jvotesystem')." AND `parent` = 0 AND `menuid` = 0");
		}
		
		// If a DB error occurs, return null
		if(version_compare(JVERSION, '1.6.0', 'ge')) {
			try {
				$db->query();
			} catch (Exception $e) {
				return;
			}
		} else {
			$db->query();
			if($db->getErrorNum()) return;
		}
		
		require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'loader.php';
		$vbparams =& VBParams::getInstance();
		$log =& VBLog::getInstance();
		
		//Run the SpamDetection Script
		/*$db->setQuery('	SELECT TRIM(BOTH "." FROM LEFT(`ip`, LENGTH(`ip`)-3)) AS shortip
						FROM `#__jvotesystem_users` AS u, `#__jvotesystem_votes` AS v, `#__jvotesystem_sessions` AS s
						WHERE v.user_id=u.id AND s.`user_id`=u.`id`
						AND DATE_SUB(CURDATE(),INTERVAL 1 MONTH) < s.`lastVisitDate`
						GROUP BY shortip
						HAVING DATEDIFF(MAX(`voted_time`), MIN(`voted_time`)) <= 3
						AND SUM(votes) > 100');
		$spamIPs = $db->loadObjectList();
		
		if($spamIPs) {
			foreach($spamIPs AS $ip) {
				//Remove all votes
				$db->setQuery('	DELETE FROM `#__jvotesystem_votes` WHERE `user_id` IN (
								SELECT `id`
								FROM `#__jvotesystem_users`
								WHERE ip LIKE "'.$ip->shortip.'%"
								)');
				$db->query();
				if(@$db->getAffectedRows() > 0)
					$log->add("database",	"Removed ".@$db->getAffectedRows()." rows of the table #__jvotesystem_votes; Reason: SpamDetection-Script");
				//Block all users
				$db->setQuery('	UPDATE `#__jvotesystem_users` WHERE ip LIKE "'.$ip->shortip.'%"
								SET `blocked`=1');
				$db->query();
				if(@$db->getAffectedRows() > 0)
					$log->add("users",		"Blocked ".@$db->getAffectedRows()." users; Reason: SpamDetection-Script");
			}
		}*/
		
		//Delete Session-Table after x-Days
		$keepSessionData = $vbparams->get("sessionDataPeriod");
		$db->setQuery("	DELETE FROM `#__jvotesystem_sessions`
						WHERE DATE_SUB(CURDATE(),INTERVAL ".$keepSessionData." DAY) > `lastVisitDate` ");
		$db->query();
		if(@$db->getAffectedRows() > 0)
			$log->add("DB",	"Removed ".$db->getAffectedRows()." rows of the table #__jvotesystem_sessions; Reason: Cleanup-Script");
    }//function
}//class
