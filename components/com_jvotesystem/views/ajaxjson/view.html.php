<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @author Johannes MeÃŸmer
 * @copyright (C) 2010- Johannes MeÃŸmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');


jimport( 'joomla.application.component.view');

class jVoteSystemViewAjaxJSON extends JView
{
	function display($tpl = null)
    {
		$task = JRequest::getWord("task", null);
		//Token überprüfen
		if(!JRequest::checkToken("get")) {
			$task = "redirect";
		}
    	
    	$this->vbparams =& VBParams::getInstance(JRequest::getWord("paramView", 'pollslist'));
		
		$json = false;
		if ($task === "getlang") { 
			ob_end_clean();
		
			$lang = Array();
			$lang['qremoveanswer'] = JText::_('QUESTIONREMOVEANSWER');
			$lang['qreportanswer'] = JText::_('QUESTIONREPORTANSWER');
			$lang['qremovecomment'] = JText::_('QUESTIONREMOVECOMMENT');
			$lang['qreportcomment'] = JText::_('QUESTIONREPORTCOMMENT');
			$lang['newcomment'] = JText::_('WRITE_NEW_COMMENT');
			$lang['newanswertovotebox'] = JText::_('WRITE_NEW_ANSWER_TO_POLL');
			$lang['noemptyordefault'] = JText::_('NO_EMPTY_OR_DEFAULT_VALUE');
			$lang['votessingular'] = JText::_('VOTES_SINGULAR');
			$lang['votesplural'] = JText::_('VOTES');
			$lang['vote'] = JText::_('Vote');
			$lang['load_next'] = sprintf(JText::_("LOAD_NEXT"), "%STEPS%");
			$lang['load_next_short'] = JText::_("LOAD_NEXT_SHORT");
			$lang['hideall'] = JText::_("HIDEALL");
			//JText::_('ERRORCOMMENTNOTEXT');
			$lang['second'] = JText::_('second'); $lang['seconds'] = JText::_('seconds');
			$lang['minute'] = JText::_('minute'); $lang['minutes'] = JText::_('minutes');
			$lang['hour'] = JText::_('hour'); $lang['hours'] = JText::_('hours');
			$lang['day'] = JText::_('day'); $lang['days'] = JText::_('days');
			$lang['week'] = JText::_('week'); $lang['weeks'] = JText::_('weeks');
			$lang['month'] = JText::_('month'); $lang['months'] = JText::_('months');
			$lang['year'] = JText::_('year'); $lang['years'] = JText::_('years');
			$lang['captchaLoading'] = JText::_("CAPTCHA_LOADING");
			$lang['captchaEnterCode'] = JText::_("CAPTCHA_ENTER_CODE");
			
			//Buttons
			$lang['yes'] = JText::_("JYES");
			$lang['no'] = JText::_("JNO");
			$lang['cancel'] = JText::_("Cancel");
			$lang['send'] = JText::_("Send");
			$lang['ok'] = JText::_("ok");
			
			$lang['reportAddMessage'] = JText::_("reportAddMessage");
			$lang['needToLogin'] = JText::_("ERRORNEEDTOLOGIN");
			
			$lang['qremovepoll'] = JText::_("QUESTIONREMOVEPOLL");
			$lang['titleQuestion'] = JText::_("ARE_YOU_SURE");
			$lang['commentsofanswer'] = JText::_("COMMENTS_ANSWER");

			
			//header('Content-Type: application/json; charset=utf-8');
			header('Content-Type: text/plain; charset=utf-8');
			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600)); //Cache fÃ¼r 1 Stunde
			echo json_encode($lang);
			exit();
		}
		
		$this->mail =& VBMail::getInstance();
		$this->access =& VBAccess::getInstance();
		
		$oAr = array();
		
		//Links von fremden Seiten bzw. durch Google Bots abblocken
		$referer = @$_SERVER['HTTP_REFERER'];
		if(isset($referer) AND $partsRef = parse_url ($referer)) {
			//Hosts mit Server vergleichen
			$current = JUri::current();
			$partsCur = parse_url ($current);
			
			if($partsRef["host"] != $partsCur["host"]) {
				$task = "redirect";
			}
		}
		
		//Template setzen
		$tmpl = JRequest::getString("template", null);
		
		switch($task) {
			case "redirect":
				$json = true;
				
				$oAr["error"] = JText::_("JLIB_ENVIRONMENT_SESSION_EXPIRED");
				break;
			case 'vote':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$vote =& VBVote::getInstance();
				$vbanswer =& VBAnswer::getInstance();
				
				$answerID = JRequest::getInt('answer', null);
				$boxID = JRequest::getInt('box', null);
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID);
				//$data = $vote->getData($boxID, false);//Daten der Votebox laden
				
				//Captcha
				$oAr = $this->checkCaptcha($box, "vote");		
				
				if($oAr['captcha'] == 0 OR $oAr['erfolg'] == 0) {
					
				} else {
					$oAr = $this->flat($vote->checkVote($boxID, $answerID)); 
					$votes = $vote->getVotesByUser($box->id);
					
					if ($this->access->isUserAllowedToViewUserList($box)) {
						$oAr["make_userlist"] = true;
					}
					
					if ($this->access->isUserAllowedToResetVotes($box, $answer)) {
						$oAr["show_votereset"] = true;
					} else {
						$oAr["show_votereset"] = false;
					}
					
					$oAr["answer"] = $answerID;
					$oAr["box"] = $boxID;
					
					if (@$oAr["leftVotes"] === 0) {
						if ($box->show_thankyou_message) {
							$oAr["thankyou_title"] = JText::_('SUCCESS_THANKS');
							$oAr["thankyou_message"] = JText::_('THANKYOUFORVOTING');
						}
						if ($box->goto_chart AND $this->vbparams->get('diagramm') AND $this->access->isUserAllowedToViewResult($box, $votes)) {
							$oAr["goto_chart"] = true;
						} else {
							$oAr["goto_box"] = true;
							$oAr["page"] = (int) ceil($box->answers/$this->vbparams->get('answersPerPage'));
						}
					}
				}
				
				$oAr["voteLimitMax"] = sprintf(JText::_('VOTELIMITMAX'), $box->max_votes_on_answer);

				break;
			case 'resetvotes':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$vote =& VBVote::getInstance();
				$vbanswer =& VBAnswer::getInstance();
				
				$answerID = JRequest::getInt('answer', null);
				$boxID = JRequest::getInt('box', null);
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID);
				$reset = JRequest::getInt('reset', 1);
				
				if($this->access->isUserAllowedToResetVotes($box, $answer)) {
					if($vote->resetVotes($box, $answer, $reset)) {
						$oAr["erfolg"] = 1;
					}
				} else {
					$oAr["erfolg"] = 0;
				}
				
				$oAr["answer"] = $answerID;
				$oAr["box"] = $boxID;
				
				break;
			case 'answers':
				$json = true;
				$vote =& VBVote::getInstance();
				$page = JRequest::getInt("page",1);
				$boxID = JRequest::getInt('box', null);
				$template = JRequest::getString('template','default');

				$box = $vote->getBox($boxID);
				
				$oAr['erfolg'] = 1;
				$oAr['currentPage'] = JRequest::getInt("currentPage",1);
				$oAr['answers'] = (int) $box->answers;
				$oAr['anchor'] = JRequest::getString("anchor", 'vb'.$box->id);
				$oAr['code'] = $vote->getVoteBox($box->id,true,$page,false,$template);
				$oAr['page'] = $vote->getPage();
				$oAr['count'] = $vote->getCountAnswers();
				$oAr['box'] = (int) $box->id;
				if($this->vbparams->get('adsense') && $this->vbparams->get('adsense_key') != "") {
					$vbtemplate =& VBTemplate::getInstance();
					$pars = array("adsense_key" => $this->vbparams->get('adsense_key'), "load" => false);
					$oAr['banner_code'] = $vbtemplate->loadTemplate("banner_code", JArrayHelper::toObject($pars));
				}
				break;
			case 'addAnswer':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$vote =& VBVote::getInstance();
				$vbanswer =& VBAnswer::getInstance();
				
				$answer = JRequest::getVar('answer', null);
				$boxID = JRequest::getInt('box', null);
				$box = $vote->getBox($boxID);
				
				//Captcha
				$oAr = jVoteSystemViewAjaxJSON::checkCaptcha($box, "addAnswer");
				
				$oAr['text'] = $answer;
				
				 				
				//Automatisch verÃ¶ffentlichen
				if($this->access->isUserAllowedToChangePublishState($box) OR $this->access->isAdmin($box)) $autoPublish = 1;
				else $autoPublish = $this->vbparams->get('autoPublish');
				
				if($oAr['captcha'] == 0 OR $oAr['erfolg'] == 0) {
					//captcha notwendig oder Fehler bei Captcha
				} elseif($answer == "") {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORANSWERNOTEXT');
				} elseif(!$this->access->isUserAllowedToAddNewAnswer($box)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOADDNEWANSWER');
				} elseif($vbanswer->addAnswer($boxID, $answer, $autoPublish)) {
					//Mail als Job eintragen in VBMail
					$this->mail->addJob('addedAnswer', array($vbanswer->getID(), $answer, $box, $this->user));
										
					$votes = $vote->getVotesByUser($boxID);
					
					$oAr["erfolg"] = 1;
					$oAr["page"] = $vbanswer->getAnswersPageCount($box, $vbanswer->getID());
					if($autoPublish == 1) { 
						$oAr["success"] = JText::_('ANSWERADDED');
					} else $oAr["success"] = JText::_('ANSWERADDEDNOTPUBLISHED');
					$oAr["leftVotes"] = $votes->allowed_votes;
					$oAr["answer"] = $vbanswer->getID();
				} else {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORADDANSWER');		
				}
				$oAr["box"] = $boxID;
				
				break;
			case 'addComment':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$vbanswer =& VBAnswer::getInstance();
				$vbcomment =& VBComment::getInstance();
				$vote =& VBVote::getInstance();
				
				$answerID = JRequest::getInt('answer', null);
				$boxID = JRequest::getInt('box', null);
				$comment = JRequest::getVar('comment', '');
				
				$answerID = 	JRequest::getInt('answer',0);
				$boxID = 		JRequest::getInt('box',0);
				$comment = 		JRequest::getVar('comment');
				$template = 	JRequest::getString('template','default');
				
				if (!$answerID || !$boxID) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('JVS_DEBUG_ADDCOMMENT_MISSING_ID');
					break;
				}
				
				//Daten holen
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID, (!$this->access->isUserAllowedToChangePublishState($box)));
				if($this->vbparams->get('orderComment') == 'ASC') $page = $vbcomment->getCommentsPageCount($answer->comments+1);
				else $page = 1;
				
				//Captcha
				$oAr = jVoteSystemViewAjaxJSON::checkCaptcha($box, "addComment");
				$oAr["comment"] = $comment;
				
				
				//Automatisch verÃ¶ffentlichen
				if($this->access->isUserAllowedToChangePublishState($box) OR $this->access->isAdmin($box)) $autoPublish = 1;
				else $autoPublish = $this->vbparams->get('autoPublishComment');
				
				if($oAr['captcha'] == 0 OR $oAr['erfolg'] == 0) {
					//captcha notwendig oder Fehler bei Captcha
				} elseif(!$this->access->isUserAllowedToAddNewComment($box, $answer)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOADDNEWCOMMENT');
				} elseif($vbcomment->addComment($answer->id, $comment, $autoPublish)) {
					//Mail als Job eintragen in VBMail
					$this->mail->addJob('addedComment', array($vbcomment->getID(), $comment, $answer, $box, $this->user));
					
					$oAr["erfolg"] = 1;

					$oAr["page"] = $page;
					if($autoPublish == 1) $oAr["success"] = JText::_('COMMENTADDED');
					else $oAr["success"] = JText::_('COMMENTADDEDNOTPUBLISHED');
				} else {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORADDCOMMENT');
				}
				
				$oAr["box"] =$box->id;
				$oAr["answer"] = $answer->id;
				break;
			case 'removeAnswer':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$vbanswer =& VBAnswer::getInstance();
				$vote =& VBVote::getInstance();
				
				$answerID = JRequest::getInt('answer', null);
				$boxID = JRequest::getInt('box', null);
				//Daten holen
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID);
				//Rechte Ã¼berprÃ¼fen
				if(!$answer or !$box) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORANSWERFOUND');
				} elseif(!$this->access->isUserAllowedToMoveAnswerToTrash($answer, $box)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTODELETE');
				} elseif($vbanswer->removeAnswer($answerID)) {
					$votes = $vote->getVotesByUser($boxID);
					$oAr["erfolg"] = 1;
					$oAr["success"] = JText::_('ANSWERREMOVED');
					$oAr["leftVotes"] = $votes->allowed_votes;
				} else {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORREMOVEANSWER');
				}
				$oAr["box"] = $boxID;
				$oAr["answer"] = $answerID;
				break;
			case 'reportAnswer':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$vbanswer =& VBAnswer::getInstance();
				$spam =& VBSpam::getInstance();
				$spam->loadData('answer');
				$vote =& VBVote::getInstance();
				
				$answerID = JRequest::getInt('answer', null);
				$boxID = JRequest::getInt('box', null);
				//Daten holen
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID);
				$msg = JRequest::getString("reportMessage", null);
				//Rechte Ã¼berprÃ¼fen
				if(!$answer or !$box) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORANSWERFOUND');
				} elseif(!$this->access->isUserAllowedToReportAnswer($box, $answer)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOREPORT');
				} elseif($spam->report('answer', $answerID, $msg)) {
					$oAr["erfolg"] = 1;
					$oAr["success"] = JText::_('ANSWERREPORTED');
					//Kommentar Ã¼berprÃ¼fen, wenn Limit Ã¼berschritten.. speeren
					$spam->checkReports('answer', $answerID, $box);
				} else {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORREPORTANSWER');		
				}
				$oAr["box"] = $boxID;
				$oAr["answer"] = $answerID;
				break;
			case 'reportComment':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$spam =& VBSpam::getInstance();
				$spam->loadData('comment');
				$vote =& VBVote::getInstance();
				$vbcomment =& VBComment::getInstance();
				
				$commentID = JRequest::getInt('comment', null);
				$boxID = JRequest::getInt('box', null);
				$msg = JRequest::getString("reportMessage", null);
				//Daten holen
				$box = $vote->getBox($boxID);
				$comment = $vbcomment->getComment($commentID); 
				//Rechte Ã¼berprÃ¼fen
				if(!$comment or !$box) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORCOMMENTFOUND');
				} elseif(!$this->access->isUserAllowedToReportComment($box, $comment)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOREPORT');
				} elseif($spam->report('comment', $commentID, $msg)) {
					$oAr["erfolg"] = 1;
					$oAr["success"] = JText::_('COMMENTREPORTED');
					//Kommentar Ã¼berprÃ¼fen, wenn Limit Ã¼berschritten.. speeren
					$spam->checkReports('comment', $commentID, $box);
				} else {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORREPORTCOMMENT');
				}
				$oAr["box"] = $boxID;
				$oAr["comment"] = $commentID;
				break;
			case 'removeComment':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$this->comment =& VBComment::getInstance();
				$vote =& VBVote::getInstance();
				
				$commentID = JRequest::getInt('comment', null);
				$boxID = JRequest::getInt('box', null);
				//Daten holen
				$box = $vote->getBox($boxID);
				$comment = $this->comment->getComment($commentID);
				//Rechte Ã¼berprÃ¼fen
				if(!$comment or !$box) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORANSWERFOUND');
				} elseif(!$this->access->isUserAllowedToMoveCommentToTrash($comment, $box)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTODELETE');
				} elseif($this->comment->removeComment($commentID)) {
					$oAr["erfolg"] = 1;
					$oAr["success"] = JText::_('COMMENDREMOVED');
				} else {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORREMOVECOMMEND');
				}
				$oAr["box"] =$boxID;
				$oAr["answer"] = $comment->answer_id;
				$oAr["comment"] = $comment->id;
				break;
			case 'changePublishStateAnswer':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$vbanswer =& VBAnswer::getInstance();
				$vote =& VBVote::getInstance();
				
				$answerID = JRequest::getInt('answer', null);
				$boxID = JRequest::getInt('box', null);
				//Daten holen
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID);
				//Status
				if($answer->published == 1) { 
					$state = 'unpublished';
					$answer->published = 0;
				} else {
					$state = 'published';
					$answer->published = 1;
				}
				//Rechte Ã¼berprÃ¼fen
				if(!$answer or !$box) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORANSWERFOUND');
				} elseif(!$this->access->isUserAllowedToChangePublishState($box)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOCHANGEPUBLISHSTATE');
				} elseif($vbanswer->changePublishStateAnswer($answerID, $answer->published)) {
					$oAr["erfolg"] = 1;
					$oAr["success"] = JText::_('ANSWERPUBLISHSTATECHANGED');
					$oAr["state"] = $state;
				}
				$oAr["box"] = $boxID;
				$oAr["answer"] = $answerID;
				break;
			case 'changePublishStateComment':
				$json = true;
				$this->user =& VBUser::getInstance(true);
				$this->comment =& VBComment::getInstance();
				$vote =& VBVote::getInstance();
				
				$commentID = JRequest::getInt('comment', null);
				$boxID = JRequest::getInt('box', null);
				//Daten holen
				$box = $vote->getBox($boxID);
				$comment = $this->comment->getComment($commentID);
				//Status
				if($comment->published == 1) { 
					$state = 'unpublished';
					$comment->published = 0;
				} else {
					$state = 'published';
					$comment->published = 1;
				}
				//Rechte Ã¼berprÃ¼fen
				if(!$comment or !$box) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORANSWERFOUND');
				} elseif(!$this->access->isUserAllowedToChangePublishState($box)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOCHANGEPUBLISHSTATE');
				} elseif($this->comment->changePublishState($comment->id, $comment->published)) {
					$oAr["erfolg"] = 1;
					$oAr["success"] = JText::_('COMMENTPUBLISHSTATECHANGED');
				}
				$oAr["box"] = $boxID;
				$oAr["comment"] = $commentID;
				break;
			case 'loadComments':
				$json = true;
				$user =& VBUser::getInstance();
				$this->comment =& VBComment::getInstance();
				$this->template =& VBTemplate::getInstance();
				$this->general =& VBGeneral::getInstance();
				$vote =& VBVote::getInstance();
				$vbanswer =& VBAnswer::getInstance();
				
				$answerID = 	JRequest::getInt('answer',0);
				$boxID = 		JRequest::getInt('box',0);
				$page = 		JRequest::getInt('page',1);
				$currentpage = 	JRequest::getInt('currentpage',0);
				$template = 	JRequest::getString('template','default');
				
				if (!$answerID || !$boxID) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('JVS_DEBUG_LOADCOMMENTS_MISSING_ID');
					break;
				}
				
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID);
				
				$this->template->setTemplate($template);

				$oAr["erfolg"] = 1;
				$oAr["code"] = $this->comment->getComments($box, $answer, $page, $currentpage === 0);
				break;
			case 'loadCharts':
				$json = true;
				$this->charts =& VBCharts::getInstance();
				$vote =& VBVote::getInstance();
				$this->template =& VBTemplate::getInstance();
				
				$boxID = JRequest::getInt('box', null);
				$mode = JRequest::getVar('mode', null);
				
				//Daten holen
				$box = $vote->getBox($boxID);
				
				//Template setzen
				$template = JRequest::getString('template', null);
				if($template == null) $template = $box->template;
				$this->template->setTemplate($template);
				
				if($box) {
					$votes = $vote->getVotesByUser($boxID);
					if($this->access->isUserAllowedToViewResult($box, $votes)) {
						$oAr += $this->charts->getFrontendChartJSON($boxID);
						$oAr["allowed"] = 1;
					} else {
						$oAr["allowed"] = 0;
					}
				} else {
					$oAr["allowed"] = 0;
				}
				if ($mode) $oAr["mode"] = $mode;
				$oAr["erfolg"] = 1;
				
				//Navi
				$par = new JObject();
				$par->translation_scaling = JText::_("Scaling");
				$par->translation_next = sprintf(JText::_("LOAD_NEXT"), $this->vbparams->get("chart_barscount"));
				$par->show_next = ($box->answers > $this->vbparams->get("chart_barscount"));
				
				$oAr["code"] = $this->template->loadTemplate("chartnavi", $par);
				$oAr["count"] = $this->vbparams->get("chart_barscount");
				
				$oAr["onload"] = JRequest::getInt('onload', 0);
				$oAr["base"] = JURI::base( true );
				$oAr["box"] = $boxID;
				break;
			case 'loadUserList':
				$json = true;
				$user =& VBUser::getInstance(true);
				$vbanswer =& VBAnswer::getInstance();
				$vote =& VBVote::getInstance();
				
				$answerID = JRequest::getInt('answer', null);
				$boxID = JRequest::getInt('box', null);
				//Daten holen
				$box = $vote->getBox($boxID);
				$answer = $vbanswer->getAnswer($answerID);
				
				//Rechte Ã¼berprÃ¼fen
				if(!$answer or !$box) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORANSWERFOUND');
				} elseif(!$this->access->isUserAllowedToViewUserList($box)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOVIEWUSERLIST');
				} else {
					$oAr["erfolg"] = 1;
					$oAr["code"] = $user->loadUserListVotedOnAnswer($answer);
				}
				$oAr["title"] = JText::_('USERS_VOTED_FOR_ANSWER');
				$oAr["box"] = $boxID;
				$oAr["answer"] = $answerID;
				break;
			case 'loadUserTooltip':
				$json = true;
				
				$uid = JRequest::getInt("uid", null);
				$user =& VBUser::getInstance();
				$general =& VBGeneral::getInstance();
				$curuser = $user->getUserData($uid);
				
				//Stats
				$stats = $user->getUserStats($curuser->id);
				
				//Html-Code erzeugen
				$html = array();
				$html[] = "<table>";
					//Avatar & Infos
					$html[] = "<tr>";
						//Avatar
						$html[] = "<td>";
							$html[] = $user->getAvatar($curuser->id, 50, false);
						$html[] = "</td>";
						//Infos
						$html[] = '<td class="tool-content">';
							$html[] = '<div class="tool-title"><a href="'.$general->buildLink("user", $curuser->id).'">'.$curuser->name.'</a></div>';
							//Tabelle mit Stats
							$html[] = '<table class="tool-infotable">';
								//Werte
								$html[] = '<tr class="tool-infotable-values">';
									$html[] = "<td>".$stats->votes."</td>";
									$html[] = "<td>".$stats->answers."</td>";
									$html[] = "<td>".$stats->comments."</td>";
								$html[] = "</tr>";
								//Beschriftungen
								$html[] = '<tr class="tool-infotable-legends">';
									$html[] = "<td>".JText::_("Vote".($stats->votes != 1 ? "s" : ""))."</td>";
									$html[] = "<td>".JText::_("Answer".($stats->answers != 1 ? "s" : ""))."</td>";
									$html[] = "<td>".JText::_("Comment".($stats->comments != 1 ? "s" : ""))."</td>";
								$html[] = "</tr>";
							$html[] = "</table>";
						$html[] = "</td>";
					$html[] = "</tr>";
				$html[] = "</table>";
				
				$oAr["erfolg"] = 1;
				$oAr["html"] = implode("", $html);
				
				break;
			case 'loadCategoryTooltip':
				$json = true;
				
				$category =& VBCategory::getInstance();
				$general =& VBGeneral::getInstance();
					
				$cid = JRequest::getInt("cid", null);
				$cat = $category->getCategory($cid);
				
				//Html-Code erzeugen
				$html = array();
				$html[] = "<table>";
					$html[] = "<tr>";
						//Icon
						$html[] = '<td class="icon-48-category">';
						$html[] = "</td>";
						//Infos
						$html[] = '<td class="tool-content">';
							$html[] = '<div class="tool-title"><a href="'.$general->buildLink("category", $cat->id).'">'.$cat->title.'</a></div>';
							//Tabelle mit Stats
							$html[] = '<table class="tool-infotable">';
								//Werte
								$html[] = '<tr class="tool-infotable-values">';
									$html[] = "<td>".$cat->polls."</td>";
									$html[] = "<td>".$cat->votes."</td>";
									$html[] = "<td>".$cat->comments."</td>";
								$html[] = "</tr>";
								//Beschriftungen
								$html[] = '<tr class="tool-infotable-legends">';
									$html[] = "<td>".JText::_("Poll".($cat->polls != 1 ? "s" : ""))."</td>";
									$html[] = "<td>".JText::_("Vote".($cat->votes != 1 ? "s" : ""))."</td>";
									$html[] = "<td>".JText::_("Comment".($cat->comments != 1 ? "s" : ""))."</td>";
								$html[] = "</tr>";
							$html[] = "</table>";
						$html[] = "</td>";
					$html[] = "</tr>";
				$html[] = "</table>";
			
				$oAr["erfolg"] = 1;
				$oAr["html"] = implode("", $html);
			
				break;
			case "removePoll":
				$json = true;
				$vote =& VBVote::getInstance();
				$general =& VBGeneral::getInstance();
				$category =& VBCategory::getInstance();
				$template =& VBTemplate::getInstance();
				
				$boxID = JRequest::getInt("box");
				$tmpl = JRequest::getString('template','default');
				
				$box = $vote->getBox($boxID);
				$cat = $category->getCategory(@$box->catid);
				$template->setTemplate($tmpl);
				
				if(!$box || $cat->id == 0) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOBOXORCATEGORYFOUND');
				} elseif(!$this->access->remove($cat, $box)) {
					$oAr["erfolg"] = 0;
					$oAr["error"] = JText::_('ERRORNOTALLOWEDTOREMOVEPOLL');
				} else {
					if($vote->removePoll($box->id)) {
						$pars = array("type" => "success", "msg" => sprintf(JText::_("POLL_SUCCESSFULLY_REMOVED"), $general->buildLink("category", $box->catid)));
						$oAr["code"] = $template->loadTemplate("notification", JArrayHelper::toObject($pars));
						$oAr["erfolg"] = 1;
					} else {
						$oAr["erfolg"] = 0;
						$oAr["error"] = JText::_('ERRORREMOVINGPOLL');
					}
				}
				
				break;
		}
		if(JRequest::getVar("ref", null) != null) {//Braucht man das wiklich? Leute ohne JS kÃ¶nnen nur abstimmen und sonst nix...keine comments, keine antworten hinzufÃ¼gen...
			$out = array();
			foreach($oAr AS $a) {
				$out[$a['key']] = $a['value'];
			}
			if($out["erfolg"] == 1) {
				JController::setRedirect(base64_decode(JRequest::getVar("ref")), JText::_('SUCCESS'));
			} else {
				JController::setRedirect(base64_decode(JRequest::getVar("ref")), $out["error"], "error");
			}
			JController::redirect();
		} else {
			$this->output($oAr,$json);
		}
    }//function
	
	function output($ar,$json) {
		//EMails nach Beenden des Skripts senden.. *nÃ¤chste Version
		$this->mail->runJobs($this->mail->getJobs());
 
		//ÃœberprÃ¼fen ob Fehler ausgegeben wurden
		$error = ob_get_contents();
		if($error) {
			$log =& VBLog::getInstance();
			$log->add("ERROR", 'AjaxScriptError', array_merge($ar, array("php_error"=>urlencode($error))));
			
			if($this->vbparams->get("showErrors")) {
				$json ? $ar["erfolg"] = 0 : $ar[] = array("key"=>"erfolg","value"=>0);
				$json ? $ar["error"] = $error : $ar[] = array("key"=>"error","value"=>"Error: ".$error);
			}
		}
		ob_end_clean();
		
		global $mainframe;

		if (!$json) {
			$out = '';
			foreach($ar AS $a) {
				if($out != '') $out .= '&';
				if($a['key'] == "error" or $a['key'] == "success") $a['value'] = urlencode($a['value']);
				$out .= $a['key'].'='.$a['value'];
			}
			echo $out;
		} else {
			//header('Content-Type: application/json; charset=utf-8');
			header('Content-Type: text/plain; charset=utf-8');
			echo json_encode($ar);
		}

		exit();
	} 
	
	function checkCaptcha($box, $task) {
		if(!$this->access->needCaptcha($box, $task)) {
			$oAr['erfolg'] = 1;
			$oAr['captcha'] = 1;
			return $oAr;
		}
		$oAr = array();
		if($this->vbparams->get('recaptcha') and $this->vbparams->get('recaptcha_publickey') and $this->vbparams->get('recaptcha_privatekey')) {
			$captcha = JRequest::getVar('captcha', null);
			require_once(JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'recaptchalib.php');
			if($captcha == null) {
				//Captcha notwendig
				$oAr['captcha'] = 0;
				$u =& JURI::getInstance();
			} else {
				//Captcha Ã¼berprÃ¼fen
				$privatekey = $this->vbparams->get('recaptcha_privatekey');
				$resp = recaptcha_check_answer ($privatekey,
												$_SERVER["REMOTE_ADDR"],
												JRequest::getVar("recaptcha_challenge_field"),
												$captcha);
				if (!$resp->is_valid) {
					$oAr['erfolg'] = 0;
					$oAr['captcha'] = 0;//sonst error...
					$oAr["error"] = JText::_('WRONGCAPTCHA');
				} else {
					$oAr['erfolg'] = 1;
					$oAr['captcha'] = 1;
				}
			}
		} else {
			$oAr['erfolg'] = 1;
			$oAr['captcha'] = 1;
		}
		return $oAr;
	}
	
	//converter function
	function flat($ar) {
		$oo = array();
		foreach($ar AS $a) {
			$oo[$a['key']]=$a['value'];
		}
		return $oo;
	}

}//class
