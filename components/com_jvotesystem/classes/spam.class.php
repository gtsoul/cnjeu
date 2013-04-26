<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

jimport( 'joomla.utilities.date' );

class VBSpam
{
	//Variablen
	private $db, $user, $document, $spamData;
	
	function __construct() {
		//Feste Variablen laden
		$this->document = & JFactory::getDocument();
		$this->db =& JFactory::getDBO();
		$this->user =& VBUser::getInstance();
	}
	
	function &getInstance() {
		static $instance;
		if(empty($instance)) {
			$instance = new VBSpam();
		}
		return $instance;
	}
	
	function report($group, $id, $msg = null) {
		$date = new JDate();
	
		$ins = new JObject();
		$ins->id = null;
		$ins->user_id = $this->user->id;
		$ins->block_group = $group;
		$ins->block_id = $id;
		$ins->time = $date->toMySQL();
		$ins->msg = $msg;
		
		$this->db->insertObject('#__jvotesystem_spam_reports', $ins);
		if($this->db->getErrorMsg()) return false;
		return true;
	}
	
	function checkReports($group, $id, $box) {
		$mail =& VBMail::getInstance();
		
		$sql = "SELECT o.*, COUNT(sp.`id`) AS reports\n"
			. "FROM `#__jvotesystem_spam_reports` AS sp\n"
			. ", `#__jvotesystem_".$group."s` AS o\n"
			. "WHERE o.`id`=`block_id`\n"
			. "AND `block_group`=\"".$group."\"\n"
			. "AND `block_id`=\"".$id."\"\n"
			. "AND no_spam_admin=0\n"
			. "GROUP BY `block_id`\n";
		$this->db->setQuery($sql);
		$row = $this->db->loadObject();
		
		if(!empty($row) AND $row->reports >= $box->spam_count) {
			//Eintrag speeren
			$row->published = 0;
			$this->db->updateObject('#__jvotesystem_'.$group.'s', $row, 'id');
			
			$mail->addJob('bannedObject', array($group, $row, $box));
		} elseif(!empty($row)) {
			//Eintrag gemeldet
			$mail->addJob('reportedObject', array($group, $row, $box));
		}
		
	}
	
	function loadData($group) {
		if(empty($this->spamData[$group])) {
			$sql = "SELECT `block_id`, IF(COUNT(*)>0, 1, 0) AS user_report\n"
				. "FROM `#__jvotesystem_spam_reports`\n"
				. "WHERE `user_id`=\"".$this->user->id."\"\n"
				. "AND `block_group`=\"".$group."\"\n"
				. "GROUP BY `block_id`";
			$this->db->setQuery($sql);
			$this->spamData[$group] = $this->db->loadObjectList();
			
			$this->prepareData($group);
		}
	}
	
	function prepareData($group) {
		$nData = array();
				
		foreach($this->spamData[$group] AS $row) {
			$nData[$row->block_id] = $row->user_report;
		}
		
		$this->spamData[$group] = $nData;
	}
	
	function checkRow($group, $id) {
		if(isset($this->spamData[$group][$id]))
			if($this->spamData[$group][$id] == 1) return true;
			else return false;
		else return false;
	}
}//class
