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

class VBAnswer
{
	//Variablen
	var $db, $user, $document, $id;
	private $vote;
	
	function __construct() {
		$this->document = & JFactory::getDocument();
		$this->db =& JFactory::getDBO();
		$this->user =& VBUser::getInstance();
		$this->access =& VBAccess::getInstance();
		$this->vote =& VBVote::getInstance();
		$this->vbparams =& VBParams::getInstance();
		$this->log =& VBLog::getInstance();
	}
	
	function &getInstance() {
		static $instance;
		if(empty($instance)) {
			$instance = new VBAnswer();
		}
		return $instance;
	}

    function addAnswer($boxID, $answer, $published = 1) {
    	$this->user->loadUser(true);
    	
		$date = new JDate();
	
		$ins = new JObject();
		$ins->id = null;
		$ins->box_id = $boxID;
		$ins->answer = $answer;
		$ins->published = $published;
		$ins->autor_id = $this->user->id;
		$ins->created = $date->toMySQL();
		
		$this->db->insertObject('#__jvotesystem_answers', $ins);
		$this->id = $this->db->insertid();
		
		if(!$this->db->getErrorMsg())  {
			$this->log->add("DB", 'AddedAnswer', array("id"=>$this->id));
			
			$this->vote->checkVote($boxID, $this->id,true);
			
			//other extensions
			JPluginHelper::importPlugin( 'jvotesystem' );
			$dispatcher =& JDispatcher::getInstance();
			$res = $dispatcher->trigger( 'onAnswerAdded', array( $this->id ) );
			
			return true;
		} else {
			$this->log->add("ERROR", 'AddingAnswer', array("db_error"=>$this->db->getErrorMsg()));
			return false;
		}
	}
	
	function getID() {
		return $this->id;
	}
	
	function removeAnswer($answer) {
		$sql = 'DELETE FROM `#__jvotesystem_answers` '
		. ' WHERE `id` = '.$answer
		. ' LIMIT 1';
		$this->db->setQuery($sql);
		$this->db->query();
		if($this->db->getErrorMsg()) { $this->log->add("ERROR", 'RemovingAnswer', array("id"=>$answer, "db_error"=>$this->db->getErrorMsg())); return false; }
		$sql = 'DELETE FROM `#__jvotesystem_votes` '
		.' WHERE `answer_id` = '.$answer;
		$this->db->setQuery($sql);
		$this->db->query();
		if($this->db->getErrorMsg()) { $this->log->add("ERROR", 'RemovingVotesOfAnswer', array("id"=>$answer, "db_error"=>$this->db->getErrorMsg())); return false; }
		else $votes = $this->db->getAffectedRows();
		$sql = 'DELETE FROM `#__jvotesystem_comments` '
		.' WHERE `answer_id` = '.$answer;
		$this->db->setQuery($sql);
		$this->db->query();
		if($this->db->getErrorMsg()) { $this->log->add("ERROR", 'RemovingCommentsOfAnswer', array("id"=>$answer, "db_error"=>$this->db->getErrorMsg())); return false; }
		else $comments = $this->db->getAffectedRows();
		
		$this->log->add("DB", 'RemovedAnswer', array("id"=>$answer, "votes"=>$votes, "comments"=>$comments));
		
		return true;
	}
	
	function changePublishStateAnswer($answerID, $state) {
	
		$upd->id = $answerID;
		$upd->published = $state;
		$upd->no_spam_admin = 1;
		
		$this->db->updateObject('#__jvotesystem_answers', $upd, 'id');
		if($this->db->getErrorMsg()) { $this->log->add("ERROR", 'ChangingPublishStateAnswer', array("id"=>$answerID, "state"=>$state, "db_error"=>$this->db->getErrorMsg())); return false; }
		
		$this->log->add("DB", 'ChangedPublishStateAnswer', array("id"=>$answerID, "state"=>$state));
		return true;
	}
	
	function getAnswersPageCount($box, $aid) {
		//Order by
		switch($box->answers_orderby) {
			default:
			case "votes":
				$order_by = ' ORDER BY votes '.$box->answers_orderby_direction.', lastvote ASC, created ASC'; 
				break;
			case "id":
				$order_by = ' ORDER BY a.`id` '.$box->answers_orderby_direction.', lastvote ASC, created ASC'; 
				break;
			case "name":
				$order_by = ' ORDER BY a.`answer` '.$box->answers_orderby_direction.', lastvote ASC, created ASC'; 
				break;
			case "created":
				$order_by = ' ORDER BY a.`created` '.$box->answers_orderby_direction.', lastvote ASC'; 
				break;
		}
		$sql = "SELECT result.*\n"
		. "FROM ( SELECT (@counter:=(@counter+1)) as counter, answers.`id` FROM (SELECT @counter:=0)r,(\n"
		. "SELECT a.*, IFNULL(SUM(`votes`),0) AS votes, MAX(v.`voted_time`) AS lastvote, MAX(v.`voted_time`) AS firstvote FROM `#__jvotesystem_answers` AS a LEFT JOIN `#__jvotesystem_votes` AS v ON v.`answer_id`=a.`id` LEFT JOIN `#__jvotesystem_users` AS u ON (u.`id`=v.`user_id` AND u.`blocked`=0) "
		. ' WHERE a.`box_id`="'.$box->id.'" AND ('
		. ' (a.`autor_id` = "'.$this->user->id.'" AND a.`published` = 0 AND "'.$this->user->id.'" != 0) ';
		if(!$this->access->isUserAllowedToChangePublishState($box)) $sql .= ' OR a.`published` = 1 ';  else  $sql .= ' OR a.`published` = 1 OR a.`published` = 0';
		$sql .= " ) GROUP BY a.`id` ".$order_by." ) AS answers\n"
		. ") AS result WHERE result.`id`='".$aid."'";
		$this->db->setQuery($sql);
		$result = $this->db->loadObject();
		if(!$result) return null;
		
		$seiten = ($result->counter / $this->vbparams->get('answersPerPage'));
		$pages = ceil($seiten);
		return $pages;
	}
	
	function getAnswer($answerID, $published = true) {
		$sql = 'SELECT a. * , COUNT( c. `id` ) AS comments '
        . ' FROM `#__jvotesystem_answers` AS a '
        . ' LEFT JOIN `#__jvotesystem_comments` AS c ON ( c. `answer_id` = a. `id` ';
        if($published == true) $sql .= ' AND c. `published` = 1 ';
        $sql .= ' ) '
        . ' WHERE a. `id` = '.$this->db->quote($answerID)
        . ' GROUP BY a. `id`'; 
		$this->db->setQuery($sql);
        return $this->db->loadObject();
	}
}//class
