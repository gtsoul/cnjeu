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
jimport( 'joomla.application.component.view');

class jVoteSystemViewTasks extends JView
{
	var $user;

    function display($tpl = null)
    {
		$this->general =& VBGeneral::getInstance();
		$this->vbparams =& VBParams::getInstance();
		
		$hash = JRequest::getString("hash", null);
		if($hash == null) { 
			JController::setRedirect($this->general->buildLink("list"), 'NOHASH', "error");
			JController::redirect();
		}
		$task = $this->general->getTask($hash);
		if($task == null) {
			JController::setRedirect($this->general->buildLink("list"), 'NOTASK', "error");
			JController::redirect();
		}
		if($task->active == 0) {
			JController::setRedirect($this->general->buildLink("list"), 'TASKINACTIVE', "error");
			JController::redirect();
		}
		$dateToday = JFactory::getDate();
		$dateCreated = JFactory::getDate($task->created);
		if($dateToday->toUnix() > ($dateCreated->toUnix() + $this->vbparams->get('validityPeriod')*24*60*60)) {
			JController::setRedirect($this->general->buildLink("list"), 'TASKVALIDITYPERIOD', "error");
			JController::redirect();
		}
		
		$this->vote =& VBVote::getInstance();
		$this->vbanswer =& VBAnswer::getInstance();
		$this->comment =& VBComment::getInstance();
		$this->access =& VBAccess::getInstance();
		$this->access->setUser($task->user_id);
				
		switch($task->task) {
			case "changePublishStateAnswer" :
				//Daten holen
				$answer = $this->vbanswer->getAnswer($task->params['answer']);
				$box = $this->vote->getBox($answer->box_id);
				
				$url = $this->general->buildLink("poll", $box->id, "", array("aid" => $answer->id));
				//Rechte überprüfen
				if(!$answer or !$box) {
					JController::setRedirect($this->general->buildLink("list"), JText::_('ERRORNOBOXORANSWERFOUND'), "error");
				} elseif(!$this->access->isUserAllowedToChangePublishState($box)) {
					JController::setRedirect($url, JText::_('ERRORNOTALLOWEDTOCHANGEPUBLISHSTATE'), "error");
				} elseif($this->access->changePublishStateAnswer($answer->id, $task->params['state'])) {
					JController::setRedirect($url, JText::_('ANSWERPUBLISHSTATECHANGED'));
					
					$this->general->unactivateTask($task->id);
				}
				break;
			case "changePublishStateComment" :
				//Daten holen
				$comment = $this->comment->getComment($task->params['comment']);
				$box = $this->vote->getBox($task->params['box']);
				
				$url = $this->general->buildLink("poll", $box->id, "", array("aid" => $comment->answer_id, "cid" => $comment->id));
				//Rechte überprüfen
				if(!$comment or !$box) {
					JController::setRedirect($this->general->buildLink("list"), JText::_('ERRORNOBOXORANSWERFOUND'), "error");
				} elseif(!$this->access->isUserAllowedToChangePublishState($box)) {
					JController::setRedirect($url, JText::_('ERRORNOTALLOWEDTOCHANGEPUBLISHSTATE'), "error");
				} elseif($this->comment->changePublishState($comment->id, $task->params['state'])) {
					JController::setRedirect($url, JText::_('COMMENTPUBLISHSTATECHANGED'));
					
					$this->general->unactivateTask($task->id);
				}
				break;
			case 'removeAnswer':
				//Daten holen
				$answer = $this->vbanswer->getAnswer($task->params['answer']);
				$box = $this->vote->getBox($answer->box_id);
				
				$url = $this->general->buildLink("poll", $box->id);
				//Rechte überprüfen
				if(!$answer or !$box) {
					JController::setRedirect($this->general->buildLink("list"), JText::_('ERRORNOBOXORANSWERFOUND'), "error");
				} elseif(!$this->access->isUserAllowedToMoveAnswerToTrash($answer, $box)) {
					JController::setRedirect($url, JText::_('ERRORNOTALLOWEDTODELETE'), "error");
				} elseif($this->vbanswer->removeAnswer($answer->id)) {
					JController::setRedirect($url, JText::_('ANSWERREMOVED'));
					
					$this->general->unactivateTask($task->id);
				}
				break;
			case 'removeComment':
				//Daten holen
				$comment = $this->comment->getComment($task->params['comment']);
				$box = $this->vote->getBox($task->params['box']);
				
				$url = $this->general->buildLink("poll", $box->id, "", array("aid" => $comment->answer_id));
				//Rechte überprüfen
				if(!$comment or !$box) {
					JController::setRedirect($this->general->buildLink("list"), JText::_('ERRORNOBOXORANSWERFOUND'), "error");
				} elseif(!$this->access->isUserAllowedToMoveCommentToTrash($comment, $box)) {
					JController::setRedirect($url, JText::_('ERRORNOTALLOWEDTODELETE'), "error");
				} elseif($this->comment->removeComment($comment->id)) {
					JController::setRedirect($url, JText::_('COMMENDREMOVED'));
					
					$this->general->unactivateTask($task->id);
				}
				break;
			default:
				JController::setRedirect($this->general->buildLink(), 'NOTASK', "error");
				break;
		}
		JController::redirect();
    }//function
}//class
