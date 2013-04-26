<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.event.plugin');

// include jomsocial core
require_once( JPATH_BASE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'loader.php';

class plgjVoteSystemJSCommunity extends JPlugin {
 
	public function plgjVoteSystemJSCommunity(&$subject, $config = array()) 
	{
		parent::__construct($subject, $config);
		
		$this->vbparams =& VBParams::getInstance();
		$this->general =& VBGeneral::getInstance();
		$this->user =& JFactory::getUser();
		
		JPlugin::loadLanguage('plg_jvotesystem_jscommunity', JPATH_ADMINISTRATOR);
	}
	
	public function onAnswerAdded($answerId) {
		if(!$this->params->get("add_answer",1)) return true;
		if(!$this->params->get("guests",1) && $this->user->id == 0) return true;
		//Parameter setzen
		$activate_bbcode = $this->vbparams->get("activate_bbcode");
		$this->vbparams->set("global", "activate_bbcode", 0);
		
		//Points
		CFactory::load ( 'libraries', 'userpoints' );
		CUserPoints::assignPoint ( 'com_jvotesystem.answer.add' );
		
		$db = & JFactory::getDBO();
		$user = & JFactory::getUser();
			
		$sql = "SELECT a.`id` AS aid, a.`answer` , b.`id` AS bid, b.`title`\n"
			. "FROM `#__jvotesystem_boxes` AS b, `#__jvotesystem_answers` AS a\n"
			. "WHERE a.`box_id`=b.`id`\n"
			. "AND a.`id`='".$answerId."'";
		$db->setQuery($sql);
		
		if (!$data = $db->loadObject()) {
			return false;
		}
		
		//BBCode entfernen
		$data->answer = nl2br(trim($this->general->BBCode($data->answer)));
		
		$link = '<a href="' .$this->general->buildLink("poll", $data->bid, "", array("aid" => $data->aid)).'">'.$data->title.'</a>';
		
		$act = new stdClass();
		$act->cmd   = 'jvotesystem.answer.add';
		$act->actor   = $user->get('id');
		$act->target  = 0; // no target
		$act->title   = sprintf(JText::_('JVS_ADD_ANSWER'), $link);
		$act->content   = $data->answer;
		$act->app   = 'jvotesystem';
		$act->cid   = $answerId;
		 
		CFactory::load('libraries', 'activities');

		$act->like_type     = $act->cmd;
		$act->like_id     = CActivities::LIKE_SELF;
		
		CActivityStream::add($act);
		
		$this->vbparams->set("global", "activate_bbcode", $activate_bbcode);
		
		return true;
	}
	
	public function onCommentAdded($commentId) {
		if(!$this->params->get("add_comment",1)) return true;
		if(!$this->params->get("guests",1) && $this->user->id == 0) return true;
		//Parameter setzen
		$activate_bbcode = $this->vbparams->get("activate_bbcode");
		$this->vbparams->set("global", "activate_bbcode", 0);
		
		//Points
		CFactory::load ( 'libraries', 'userpoints' );
		CUserPoints::assignPoint ( 'com_jvotesystem.comment.add' );
		
		$db = & JFactory::getDBO();
		$user = & JFactory::getUser();
			
		$sql = "SELECT a.`id` AS aid, a.`answer` , b.`id` AS bid, b.`title`, c.`id` AS cid, c.`comment` \n"
			. "FROM `#__jvotesystem_boxes` AS b, `#__jvotesystem_answers` AS a, `#__jvotesystem_comments` AS c\n"
			. "WHERE a.`box_id`=b.`id`\n"
			. "AND c.`answer_id`=a.`id`\n"
			. "AND c.`id`='$commentId'";
		$db->setQuery($sql);
		
		if (!$data = $db->loadObject()) {
			return false;
		}
		
		//BBCode entfernen
		$data->answer = nl2br(trim($this->general->shortText($data->answer, 80, false)));
		$data->comment = nl2br(trim($this->general->BBCode($data->comment)));
		
		$linkAnswer = '<a href="' .$this->general->buildLink("poll", $data->bid, "", array("aid" => $data->aid)).'">'.$data->answer.'</a>';
		$link = '<a href="' .$this->general->buildLink("poll", $data->bid, "", array("aid" => $data->aid,"cid" => $data->cid)).'">'.$data->title.'</a>';
		
		$act = new stdClass();
		$act->cmd   = 'jvotesystem.comment.add';
		$act->actor   = $user->get('id');
		$act->target  = 0; // no target
		$act->title   = sprintf(JText::_('JVS_ADD_COMMENT'), $linkAnswer, $link);
		$act->content   = $data->comment;
		$act->app   = 'jvotesystem';
		$act->cid   = $commentId;
		 
		CFactory::load('libraries', 'activities');

		$act->like_type     = $act->cmd;
		$act->like_id     = CActivities::LIKE_SELF;
		
		CActivityStream::add($act);
		
		$this->vbparams->set("global", "activate_bbcode", $activate_bbcode);
		
		return true;
	}
	
	public function onAnswerVoted($answerId) {
		if(!$this->params->get("voted",1)) return true;
		if(!$this->params->get("guests",1) && $this->user->id == 0) return true;
		//Parameter setzen
		$activate_bbcode = $this->vbparams->get("activate_bbcode");
		$this->vbparams->set("global", "activate_bbcode", 0);
		
		//Points
		CFactory::load ( 'libraries', 'userpoints' );
		CUserPoints::assignPoint ( 'com_jvotesystem.answer.vote' );
		
		$db = & JFactory::getDBO();
		$user = & JFactory::getUser();
			
		$sql = "SELECT a.`id` AS aid, a.`answer` , b.`id` AS bid, b.`title`\n"
			. "FROM `#__jvotesystem_boxes` AS b, `#__jvotesystem_answers` AS a\n"
			. "WHERE a.`box_id`=b.`id`\n"
			. "AND a.`id`='".$answerId."'";
		$db->setQuery($sql);
		
		if (!$data = $db->loadObject()) {
			return false;
		}
		
		//BBCode entfernen
		$data->answer = nl2br(trim($this->general->shortText($data->answer, 80, false)));
		
		$linkAnswer = '<a href="' .$this->general->buildLink("poll", $data->bid, "", array("aid" => $data->aid)) .'">'.$data->answer.'</a>';
		$link = '<a href="' .$this->general->buildLink("poll", $data->bid, "", array("aid" => $data->aid)).'">'.$data->title.'</a>';
		
		$act = new stdClass();
		$act->cmd   = 'jvotesystem.answer.vote';
		$act->actor   = $user->get('id');
		$act->target  = 0; // no target
		$act->title   = sprintf(JText::_('JVS_VOTED'), $linkAnswer, $link);
		$act->content   = '';
		$act->app   = 'jvotesystem';
		$act->cid   = $answerId;
		 
		CFactory::load('libraries', 'activities');

		$act->like_type     = $act->cmd;
		$act->like_id     = CActivities::LIKE_SELF;
		
		CActivityStream::add($act);
		
		$this->vbparams->set("global", "activate_bbcode", $activate_bbcode);
		
		return true;
	}
}
?>