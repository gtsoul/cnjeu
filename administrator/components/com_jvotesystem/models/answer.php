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

jimport( 'joomla.application.component.model' );
jimport( 'joomla.utilities.date' );
/**
 * jVoteSystem Model
 *
 * @package    jVoteSystem
 * @subpackage Models
 */
class jVoteSystemModelAnswer extends JModel
{

	var $data, $id, $bid;

    function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication('administrator');
		
		$this->setId();
		$this->user =& VBUser::getInstance();
    }//function
	
	function setId($bid = null) {
		$cid = JRequest::getVar('cid', null);
		if($cid == null) $this->id = JRequest::getInt('id', 0);
        else $this->id = $cid[0];
	}
	
	function getId() {
		return $this->id;
	}
	
	 function unpublish() { 
		$ins = new JObject();
		$ins->id = $this->id;
		$ins->published = 0;
		
		$this->_db->updateObject('#__jvotesystem_answers',$ins,'id'); 
		if($this->_db->getErrorMsg()) return false;
		return true;
	}
	
	function publish() {
		$ins = new JObject();
		$ins->id = $this->id;
		$ins->published = 1;
		$ins->no_spam_admin = 1;
		
		$this->_db->updateObject('#__jvotesystem_answers',$ins,'id');
		if($this->_db->getErrorMsg()) return false;
		return true;
	}
	
	function getBox($id = null) {
		if($id != null) $this->id = $id;
		//Box-Row holen
		$sql = 'SELECT b. * , COUNT( a. `id` ) AS answers '
        . ' FROM `#__jvotesystem_boxes` AS b '
        . ' LEFT JOIN `#__jvotesystem_answers` AS a ON ( b. `id` = a. `box_id`) '
        . ' WHERE b. `id` = '.$this->id
        . ' GROUP BY b. `id` '; 
		$this->_db->setQuery($sql);
		return $this->_db->loadObject();
	}
	
	function getPolls() {
		//Umfragen laden
		$sql = "SELECT `id`, `title` FROM `#__jvotesystem_boxes` ";
		$this->_db->setQuery($sql);
		return $this->_db->loadObjectList();
	}
	
	function getData() {
		$sql = 'SELECT * '
		. ' FROM `#__jvotesystem_answers` '
		. ' WHERE `id`='.$this->id; 
		$this->_db->setQuery($sql);
		return $this->_db->loadObject();
	}
	
	function store() {
		$date = new JDate();
		$this->user->loadUser(true);
	
		$ins = new JObject();
		$ins->id = $this->id;
		$ins->box_id = JRequest::getInt('box_id',null);
		$ins->answer = JRequest::getString('answer');
		$ins->published = JRequest::getInt('published',1);
		
		if($this->id < 1) {
			$ins->created = $date->toMySQL();
			$ins->autor_id = $this->user->id;
			//Neues Element
			$this->_db->insertObject('#__jvotesystem_answers', $ins);
			$this->id = $this->_db->insertid();
		} else {
			//Updaten
			$this->_db->updateObject('#__jvotesystem_answers', $ins, 'id');
			$this->id = $ins->id;
		}
		if($this->_db->getErrorMsg()) return false;
		return true;
	}
	
	function delete() {
		$cid = JRequest::getVar('cid', null);
		if($cid == null) return false;
		foreach($cid AS $id) {
			$sql = 'DELETE FROM `#__jvotesystem_answers` '
			. ' WHERE `id` = '.$id
			. ' LIMIT 1'; 
			$this->_db->setQuery($sql);
			$this->_db->query();
			if($this->_db->getErrorMsg()) return false;
			//Votes löschen
			$sql = 'DELETE FROM `#__jvotesystem_votes` '
			. ' WHERE `answer_id` = '.$id; 
			$this->_db->setQuery($sql);
			$this->_db->query();
			if($this->_db->getErrorMsg()) return false;
			//Kommentare löschen
			$sql = 'DELETE FROM `#__jvotesystem_comments` '
			. ' WHERE `answer_id` = '.$id; 
			$this->_db->setQuery($sql);
			$this->_db->query();
			if($this->_db->getErrorMsg()) return false;
		}
		return true;
	}
}//class
