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
jimport( 'joomla.filesystem.folder' );

class VBVote
{
	//Variablen
	var $db, $user, $document, $id, $page, $loadonceperpage;
	private $general, $access;
	
	function __construct($load) { 
		$this->document = & JFactory::getDocument();
		$this->db = JFactory::getDBO();
		if(!$load) return ;
		
		$this->general =& VBGeneral::getInstance();
		$this->access =& VBAccess::getInstance();
		$this->comment =& VBComment::getInstance();
		$this->spam =& VBSpam::getInstance();
		$this->charts =& VBCharts::getInstance();
		$this->template =& VBTemplate::getInstance();
		$this->category =& VBCategory::getInstance();
		$this->vbparams =& VBParams::getInstance();
		$this->log =& VBLog::getInstance();
		$this->user =& VBUser::getInstance();
		
		static $loadVSjsOnce;
		if(!($loadVSjsOnce)) {
			$js = " var jvs_live_url = \"".JURI::base() ."\";\n";
				
			$app =& JFactory::getApplication();
			$js .= "var jvs_live_time = \"".JFactory::getDate(null, $app->getCfg('offset'))->toUnix() ."\";\n";
			
			$session =& JFactory::getSession();
			if(version_compare( JVERSION, '1.6.0', 'lt' ))
				$js .= "var jvs_token = \"".JUtility::getToken()."\";\n";
			else
				$js .= "var jvs_token = \"".$session->getFormToken()."\";\n";
			
			$this->document->addScriptDeclaration($js);
				
			//Captcha laden
			if($this->vbparams->get('recaptcha') and $this->vbparams->get('recaptcha_publickey') and $this->vbparams->get('recaptcha_privatekey')) {
				$this->document->addScript('http://api.recaptcha.net/js/recaptcha_ajax.js');
				$js = 'function showRecaptcha(element, themeName, callback) {
										  Recaptcha.create("'.$this->vbparams->get('recaptcha_publickey').'", element, {
												theme: themeName,
												tabindex: 0,
												callback: function() { Recaptcha.focus_response_field(); callback(); }
										  });
										}';
				$this->document->addScriptDeclaration($js);
			}
			
			if(version_compare( JVERSION, '1.6.0', 'lt' ))
				$this->document->addScriptDeclaration ( 'var joomla15 = true;' );
			else
				$this->document->addScriptDeclaration ( 'var joomla15 = false;' );
				
			$loadVSjsOnce = true;
		}
		
		$this->poll_instances = array();
	}
	
	function &getInstance($load = true) {
		static $instance;
		if(empty($instance)) {
			$instance = new VBVote($load);
		}
		return $instance;
	}
	
	private $poll_instances;
	function checkLoaded($id) {
		if(!isset($this->poll_instances[$id])) $this->poll_instances[$id] = true;
		else return true;
		return false;
	}
	
	function getLoaded() {
		$ids = array();
		foreach($this->poll_instances AS $id => $instance) $ids[] = $id;
		return $ids;
	}
	
	function setLoaded($id) {
		$this->poll_instances[$id] = "loaded";
	}

	private $countAnswers;
    function getVotebox($id, $onlyAnswers=false, $page=null, $link = false, $template = null, $showToolbar = true) {
		$this->page = JRequest::getInt("jVoteSystemPage", null);
		
		$this->id = $id;
		$this->setLoaded($id);
		//Page in Session speichern bzw. abrufen
		$session = JSession::getInstance('none',array());
		$pageArray = $session->get('jVoteBoxPageArray', array());
		if(!isset($pageArray[$this->id])) $pageArray[$this->id] = array();
		$view = $this->vbparams->getView();
		if($page != null) { 
			$this->page = $page;
			$pageArray[$this->id][$view] = $this->page;
		} elseif($page == null AND $this->page == null AND isset($pageArray[$this->id][$view])) {
			$this->page = $pageArray[$this->id][$view];
		} elseif($this->page == null) {
			$this->page = 1;
			$pageArray[$this->id][$view] = $this->page;
		}
		$session->set("jVoteBoxPageArray", $pageArray);	
		
		//Daten aus Datenbank laden
		$data = $this->getData($this->id);
		if($data == null) return '<p>'.JText::_('NOBOXFOUNDORPUBLISHED').'</p>';
		//Andere Seite laden, wenn keine Antworten
		if(!$data->answers AND $this->page > 1) {
			$lastPage = ceil($data->box->answers/$this->vbparams->get('answersPerPage'));
			if($this->page != $lastPage) {
				$this->page = $lastPage;
				return $this->getVotebox($id, $onlyAnswers, $this->page, $link);
			}
		}
		$votes = $this->getVotesByUser($this->id);
		/*if($votes->allowed_votes == null) $votes->allowed_votes = $data->box->allowed_votes;
		if($votes->allowed_votes <= 0) $votes->allowed_votes = 0;*/
		//Kategorie laden
		$cat = $this->category->getCategory($data->box->catid);
		//Template setzen
		if($template != null) $this->template->setTemplate($template);
		else $this->template->setTemplate($data->box->template);
		
		//CSS laden
		$this->document->addStyleSheet(JURI::base( true ).'/components/com_jvotesystem/assets/css/general.css');
		
		$out = "";

		//Toolbar
		if($showToolbar) {
			$toolbar = new VBToolbar($cat, $data->box);
			$toolbar->remove();
			$toolbar->edit();
			$toolbar->info();
			
			$out .= $toolbar->out();
		}

		//Div jVotebox
		$out .= '<div class="jvotesystem jvs-'.$this->template->getTemplate().'" data-box="'.$this->id.'" data-view="'.$this->vbparams->getView().'" data-lastviewpage="'.$this->page.'" data-chart="'.(($data->box->chart_type == "bar" || $data->box->chart_type == "both") ? "bar" : "pie").'">';

		//Überprüfen, ob Umfrage angezeigt werden darf
		if(!$this->access->isUserAllowedToViewPoll($data->box)) {
			//Notification ausgeben
			//Parameter
			$par = new JObject();
			$par->msg = sprintf(JText::_("NOTALLOWEDTOVIEWPOLL"), $data->box->title);
			$par->type = "error";
				
			//laden
			$out .= $this->template->loadTemplate("notification", $par);
			
			//Div schließen & abschließen
			$out .= '</div>';
			return $out;
		}
		//JS-Code..für Typ erkennen
		$this->countAnswers = count($data->answers);

		//VBSpam - BoxDaten laden
		$this->spam->loadData('answer');
		//Navigator - nötig?

		$navi = $this->buildnavi($data->box->answers, $onlyAnswers, $this->page, $this->vbparams->get('answersPerPage'), $this->vbparams->get('shortNavi'), JRequest::getURI(), 'answers');

		//Datum berechnen
		$app =& JFactory::getApplication();
		$date = JFactory::getDate();
		$date->setOffset($app->getCfg('offset'));
		$start = JFactory::getDate($data->box->start_time);
		$start->setOffset($app->getCfg('offset'));
		$end = JFactory::getDate($data->box->end_time);
		$end->setOffset($app->getCfg('offset'));
		$voteState = null;
		if(($date->toUnix() - $end->toUnix()) > 0 AND $data->box->end_time != '0000-00-00 00:00:00') {
			$voteState = 'over';
		} elseif(($date->toUnix() - $start->toUnix()) < 0) {
			$voteState = 'notStarted';
		} elseif(!$this->access->checkAccessGroup('access', $data->box)) {
			$voteState = 'noRights';
		} elseif($votes->allowed_votes <= 0) {
			$voteState = 'novotesleft';
		}
			
			//Parameter
			$par = new JObject();
			$par->bid = $this->id;
			//Menubar
			$par->chart_show = ($this->vbparams->get('diagramm') AND $this->access->isUserAllowedToViewResult($data->box, $votes, true));
			$par->chart_show_pie = ($par->chart_show && ($data->box->chart_type == "pie" || $data->box->chart_type == "both"));
			$par->chart_show_bar = ($par->chart_show && ($data->box->chart_type == "bar" || $data->box->chart_type == "both"));
			$par->chart_visible = ($this->vbparams->get('diagramm') AND $this->access->isUserAllowedToViewResult($data->box, $votes));
			$par->like_show = $this->vbparams->get('facebookLike');
			$par->like_url = JURI::base(false).'index.php?option=com_jvotesystem&view=box&bid='.$this->id;
			$par->norights = false;
			//Title
			$par->title = $data->box->title;
			//Question
			$par->question = $this->general->BBCode($data->box->question, ' ');
			//Stimmen übrig
			$par->votes_left_show = ($this->access->isRunning($data->box));
			$par->votes_left = $votes->allowed_votes;
				
			//laden
			$out .= $this->template->loadTemplate("topbox", $par);
			
			//Antworten ausgeben
			$out .= '<div class="pagebox">';

			$onlyAnswers ? $pos = 'absolute' : $pos = 'relative';
			$answers = '<div style="position:'.$pos.';width:100%;" data-p="'.$this->page.'">';
			
			//Wenn Vote bereits vorbei, gleich Diagramm anzeigen
			if(($voteState == 'over' OR $voteState == 'novotesleft') AND $onlyAnswers == false AND $this->vbparams->get('diagramm') AND $this->access->isUserAllowedToViewResult($data->box, $votes) AND JRequest::getInt("aid", null) == null) {
				//Navi
				$par = new JObject();
				$par->translation_scaling = JText::_("Scaling");
				$par->next_count = $this->vbparams->get("chart_barscount");
				$par->translation_next = sprintf(JText::_("LOAD_NEXT"), $par->next_count);
				$par->show_next = ($data->box->answers > $par->next_count);
				
				$answers .= '<script type="text/javascript">jVSQuery(document).ready(function($){';
				$answers .= 'var chartdata = '.json_encode($this->charts->getFrontendChartJSON($this->id)).';';
				if($data->box->chart_type == "bar" || $data->box->chart_type == "both")
					$answers .= 'jVS.jsbarchart(chartdata.values,chartdata.answers,'.$this->id.',\''.preg_replace("/\n|\r/", " ", $this->template->loadTemplate("chartnavi",$par)).'\', '.$this->vbparams->get("chart_barscount").');';
				else
					$answers .= 'jVS.jspiechart(chartdata.values,chartdata.answers,'.$this->id.');';
				$answers .= '});</script>';
				$answers .= '<div style="visibility: hidden;">'.$this->getBanner($onlyAnswers, $data->box).'</div>';
			} else {
				//Banner
				$zufall = rand(1,count($data->answers));
				//Navi
				if ($this->template->getTemplate() !== 'module' ) $answers .= $navi;
				$i = 1;
				foreach($data->answers AS $answer) {
					$html = $this->getAnswerBox($answer, $data->box, $voteState, $votes, $this->access->isUserAllowedToViewResult($data->box, $votes));
					$answers .= $html;
					if($zufall == $i) $answers .= $this->getBanner($onlyAnswers, $data->box);
					$i++;
				}
				$this->comment->unsetData();
				//Navi
				$answers .= $navi;
			}
			$answers .= '</div>';
			
			if($onlyAnswers == true) return $answers;
			
			$out .= $answers;
			$out .= '</div>';
			//VoteButton bei Checkboxen
			//$out .= $data->box->allowed_votes == 1 ? '<noscript><div>VOTEBUTTON</div></noscript>' : '';
			//Neue Antwort
			$out .= $this->getNewAnswerBox($data->box,($voteState == 'over' OR $voteState == 'novotesleft') ? true : false);
			
			//EndBox ausgeben
			//Parameter
			$par = new JObject();
				$par->bid = $this->id;
				//Vote
				$par->votes_left_show = ($this->access->isRunning($data->box));
				$par->votes_left = $votes->allowed_votes;
				$par->vote_state = $voteState;
				if($voteState == 'over') {
					$par->vote_state_text =  str_replace('ENDDATE', $end->toFormat('%A, %d.%B %Y (%H:%M)'), JText::_('VOTESCHONVORBEI'));
				} elseif($voteState == 'notStarted') {
					$par->vote_state_text =  str_replace('STARTDATE', $start->toFormat('%A, %d.%B %Y (%H:%M)'), JText::_('NOCHNICHTGESTARTET'));
				} elseif($voteState == 'noRights') {
					$par->vote_state_text =  JText::_('NOVOTERIGHTS');
				} elseif ($voteState == 'novotesleft') {
					$par->vote_state_text = JText::_('ALREADYVOTED');
				} else {
					$par->vote_state_text = null;
				}
				//Goto
				$par->goto_show = $link;
				$par->goto_link = $this->general->buildLink("poll", $data->box->id);
				
				
			//laden
			$out .= $this->template->loadTemplate("endbox", $par);
			
		//Div jVotebox beenden
		$out .= '</div>';
		
		unset($this->page);
		return $out;
	}
	
	function getCountAnswers() {
		return $this->countAnswers;
	}
	
	function getPage() {
		return $this->page;
	}

	
	function getFooter() {
		/* The copyright information may not be removed or made invisible! To remove the code, please purchase a version on www.joomess.de. Thanks!*/
	/*	$out = '<p style="text-align: center;" class="jVoteSystemFooter"><a href="http://joomess.de/projekte/18-jvotesystem">jVoteSystem</a> developed and designed by <a href="http://www.joomess.de">www.joomess.de</a>.</p>';
		return $out;*/
	}
	
	function getAnswerBox($answer, $box, $voteState, $votes, $showResult) {
		$user = $this->user->getUserData($answer->autor_id);
		
		//Parameter festlegen
		$par = new JObject();
			$par->bid = $box->id;
			$par->aid = $answer->id;
			//Ranking
			$par->activate_ranking = $box->activate_ranking;
			$par->rank = $answer->rank;
			//Radiobutton
			$par->radiobutton = ($box->allowed_votes == 1 && $this->vbparams->get("single_radiobutton"));
			//VoteBox
			$par->show_result = $showResult;
			$par->votes = $answer->votes;
			$par->show_userlist = ($this->access->isUserAllowedToViewUserList($box) AND $answer->votes > 0);
			$par->translation_votes = $answer->votes == 1 ? JText::_('VOTES_SINGULAR') : JText::_('VOTES');
			$par->uservotes = $this->getVotesByUser($box->id, $answer->id)->votes;
			$par->resetAllowed = $this->access->isUserAllowedToResetVotes($box, $answer); 
			//VoteButton
			$par->vote_state = $voteState;
			$par->voting_allowed = $this->access->isUserAllowedToVoteAnswer($box, $answer);
			$par->votebutton_link = $par->voting_allowed ? $this->general->buildLink("poll", $this->id, "vote", array("answer"=>$answer->id)) : 'javascript:void(0)';
			$par->votebutton_class = $par->voting_allowed ? 'vote' : 'novote';//helper
			$par->votebutton_disabled = $this->getVotesByUser($box->id,$answer->id)->votes >= $box->max_votes_on_answer ? ' data-disabled="1"' : '';//helper
			$par->votebuttonradio_disabled = !$this->access->isUserAllowedToVoteAnswer($box, $answer) ? 'disabled="disabled"' : '';
			$par->translation_vote = JText::_('Vote');
			//AnswerField
				//Icons
					//Trash
					$par->icon_trash_active = $this->access->isUserAllowedToMoveAnswerToTrash($answer,$box);
					$par->icon_trash_link = $this->general->buildLink("poll", $this->id, "removeAnswer", array("answer"=>$answer->id));
					//PublishState
					$par->icon_state_active = $this->access->isUserAllowedToChangePublishState($box);
					$par->icon_state_show = ($par->icon_state_active OR $answer->published == 0);
					$par->icon_state_state = ($answer->published == 1) ? 'published' : 'unpublished';
					$par->icon_state_link = $this->general->buildLink("poll", $this->id, "changePublishStateAnswer", array("answer"=>$answer->id));
					//ReportSpam
					$par->icon_spam_active = $this->access->isUserAllowedToReportAnswer($box, $answer);
					$par->icon_spam_link = $this->general->buildLink("poll", $this->id, "reportAnswer", array("answer"=>$answer->id));
				//Answer
				$par->answer = nl2br($this->general->shortText($answer->answer, $this->vbparams->get('shortCountAnswer')));
				//Comment-Icon
				$par->comment_icon = $this->comment->getCommentIcon($box, $answer);
				//Author
				$par->author_show = $box->show_author;
				$par->author_id = $user->id;
				$par->author_name = $user->name;
				$par->author_link = $this->general->buildLink("user", $user->id);
				//Created
				$par->creation_time = sprintf(JText::_("TIME_AGO"), $this->general->convertTime($answer->created));
			//Comments
			$aid = JRequest::getInt('aid', null);
			$par->show_comments = ($aid != null AND $aid == $answer->id);
			if($par->show_comments)
				$par->comments = $this->comment->getComments($box, $answer);
			//Stimmen übrig
			$par->votes_left = $votes->allowed_votes;
		
		//Template-Datei laden
		$out = $this->template->loadTemplate("answer", $par);
		
		return $out;
	}
	
	function resetVotes($box, $answer, $reset = 1) {
		$votes = self::getVotesByUser($box->id, $answer->id);
		
		if($reset < 0) $reset = 0;
		
		$newVotes = $votes->votes - $reset;
		if($newVotes <= 0) {
			$sql = "DELETE FROM `#__jvotesystem_votes` WHERE `answer_id`=".$this->db->quote($answer->id)." AND `user_id`=".$this->db->quote($this->user->id);
			$this->db->setQuery($sql);
			$this->db->query();
		} else {
			$sql = "UPDATE `#__jvotesystem_votes` SET `votes`='$newVotes' WHERE `answer_id`=".$this->db->quote($answer->id)." AND `user_id`=".$this->db->quote($this->user->id);
			$this->db->setQuery($sql);
			$this->db->query();
		}
		
		if($this->db->getErrorMsg()) return false;
		else return true;
	}
	
	function getNewAnswerBox($box, $hidden = false) {
	
		//Parameter festlegen
		$par = new JObject();
		$par->Qaddnew = $this->access->isUserAllowedToAddNewAnswer($box,true);
		if ($par->Qaddnew == "true") {
			$par->bid = $box->id;
			$par->BBToolbar = $this->vbparams->get('activate_bbcode') ? $this->general->getBBCodeToolbar2() : '';
		}
		$par->hidden = $hidden ? ' style="display:none"' : '';
		//Template-Datei laden
		return $this->template->loadTemplate("newanswer", $par);
	}
	
	function getData($id, $limit = true) {
		//Data-Objekt erstellen
		$data = new JObject();
		$data->box = $this->getBox($id);
		if(!$data->box) return null;
		//Answer-Rows holen
		$sql = ' SELECT result.* FROM ( '
		. 'SELECT IF(`published` = 1, (@counter:=(@counter+1)), "#") as rank, d.* FROM (SELECT @counter:=0)r, (SELECT a.*, IFNULL(SUM(`votes`),0) AS votes, MAX(v.`voted_time`) AS lastvote, MAX(v.`voted_time`) AS firstvote '
		. ' FROM `#__jvotesystem_answers` AS a'
        . ' LEFT JOIN `#__jvotesystem_votes` AS v ON v.`answer_id`=a.`id`'
        . ' LEFT JOIN `#__jvotesystem_users` AS u ON (u.`id`=v.`user_id` AND u.`blocked`=0)'
		. ' WHERE a.`box_id`='.$id.' AND ('
		. ' (a.`autor_id` = "'.$this->user->id.'" AND a.`published` = 0 AND "'.$this->user->id.'" != 0) ';
		if(!$this->access->isUserAllowedToChangePublishState($data->box)) $sql .= ' OR a.`published` = 1 ';  else  $sql .= ' OR a.`published` = 1 OR a.`published` = 0';
        $sql .= ' ) GROUP BY a.`id`'
		. $this->general->getSqlOrderBy($data->box->ranking_orderby, $data->box->ranking_orderby_direction)
		. ' ) as d ) AS result '
		. $this->general->getSqlOrderBy($data->box->answers_orderby, $data->box->answers_orderby_direction); 
		
        if($limit == true) $sql .= ' LIMIT '.($this->page*$this->vbparams->get('answersPerPage')-$this->vbparams->get('answersPerPage')).','.$this->vbparams->get('answersPerPage');  
		$this->db->setQuery($sql);
		$data->answers = $this->db->loadObjectList();
		
		//JoomFish-Support
		if(JFolder::exists(JPATH_ADMINISTRATOR.'/components/com_joomfish')) {
			$where = "";
			foreach($data->answers AS $answer) {
				if($where != "") $where .= " OR ";
				$where .= " `id`=".$answer->id;
			}
			$sql = ' SELECT `id`, `answer` '
			. ' FROM `#__jvotesystem_answers` ';
			if($where != "") {
				$sql .= ' WHERE '.$where;
				
				$this->db->setQuery($sql);
				$aTranslations = $this->db->loadAssocList();
				
				//Array umsortieren
				$aTranslationsNew = array();
				foreach($aTranslations AS $translation) {
					$aTranslationsNew[$translation["id"]] = $translation["answer"];
				}
				
				//Daten ersetzen
				foreach($data->answers AS $answer) {
					if(isset($aTranslationsNew[$answer->id])) $answer->answer = $aTranslationsNew[$answer->id];
				}
			}
		}
		
		//Daten zurückgeben
		return $data;
	}
	
	
	private $boxes;
	function getBox($id, $alias = "") { 
		if(!isset($this->boxes[(int)$id])) {
			$sql = 'SELECT * FROM `#__jvotesystem_boxes` WHERE (`id` = '.($this->db->quote($id)).' OR `alias`="'.$alias.'") AND `published` > -1';
			$this->db->setQuery($sql);
			$box = $this->db->loadObject(); //echo str_replace("#__", "jos_", $sql);
			if(!$box) return null;
			//Box-Row holen
			$sql = 'SELECT b. * , COUNT( a. `id` ) AS answers, c.`title` AS cattitle '
	        . ' FROM `#__jvotesystem_boxes` AS b '
	        . ' LEFT JOIN `#__jvotesystem_answers` AS a ON (b. `id` = a. `box_id` AND ('
			. ' (a.`autor_id` = "'.$this->user->id.'" AND a.`published` = 0 AND "'.$this->user->id.'" != 0) ';
			if(!$this->access->isUserAllowedToChangePublishState($box)) $sql .= ' OR a.`published` = 1 ';  else  $sql .= ' OR a.`published` = 1 OR a.`published` = 0';
	        $sql .= ')), `#__jvotesystem_categories` AS c WHERE c.`id`=b.`catid` AND (b. `id` = '.($this->db->quote($box->id)).')';
	        if(!$this->access->isAdmin($box, true))$sql .= ' AND b. `published` = 1 ';
	        $sql .= ' GROUP BY b. `id` '; 
			$this->db->setQuery($sql);
			unset($box);
			$box = $this->db->loadObject();
			
			if($box) {
				$box->title = nl2br($box->title);
				$box->question = nl2br($box->question);
				
				//Params verarbeiten
				$box = $this->vbparams->convertBoxParams($box);
				
				//Wenn Benutzer Ergebnis nicht sehen darf..
				$votes = $this->getVotesByUser($box->id);
				if(!$this->access->isUserAllowedToViewResult($box, $votes)) {
					//... orderBy verändern.
					if($box->answers_orderby == "votes") {
						$box->answers_orderby = "created";
						$box->answers_orderby_direction = "ASC";
					}
					//... Ranking entfernen.
					$box->activate_ranking = 0;
				}
			} //echo str_replace("#_", "jos", $sql);
			$this->boxes[(int)$id] = $box;
		}
		return $this->boxes[(int)$id];
	}
	
	public $votesByUser;
	function getVotesByUser($box, $answer = null) { //TODO
		if(empty($this->votesByUser)) $this->votesByUser = array();
		if(empty($this->votesByUser[$box])) $this->votesByUser[$box] = array();
		
		if(empty($this->votesByUser[$box][$answer])) {
			$sql = 'SELECT IFNULL(SUM(`votes`), 0) AS votes, (b.`allowed_votes` - IFNULL(SUM(`votes`), 0)) AS allowed_votes '
	        . ' FROM `#__jvotesystem_answers` AS a'
	        . ' LEFT JOIN `#__jvotesystem_votes` AS v ON v.`answer_id`=a.`id`'
	        . ' LEFT JOIN `#__jvotesystem_boxes` AS b ON a.`box_id`=b.`id`'
	        . ' LEFT JOIN `#__jvotesystem_users` AS u ON (u.`id`=v.`user_id` AND u.`blocked`=0)'
	        . ' WHERE u.`id`="'.$this->user->id.'"'
	        . ' AND b.`id`='.$box; 
			if($answer != null) $sql .= " AND a. `id` = '$answer' ";
			$sql .= " GROUP BY b.`id` ";
			$this->db->setQuery($sql); 
			$votes = $this->db->loadObject();
			if(@$votes->allowed_votes == null && @$votes->votes == 0) {
				$votes = new JObject();
				$this->db->setQuery('SELECT `allowed_votes` FROM `#__jvotesystem_boxes` WHERE `id` = '.$this->db->quote($box).' AND `published` > -1');
				$votes->allowed_votes = $this->db->loadResult();	
				$votes->votes = 0;
			}
			$this->votesByUser[$box][$answer] = $votes;
		}
		return $this->votesByUser[$box][$answer];
	}
	
	public $votesByAnswer;
	function getVotesFromAnswer($answerID) {
		if(empty($this->votesByAnswer)) $this->votesByAnswer = array();
		
		if(empty($this->votesByAnswer[$answerID])) {
			$sql = 'SELECT IFNULL( SUM( `votes` ) , 0 ) AS votes '
			. ' FROM `#__jvotesystem_answers` AS a '
			. ' LEFT JOIN `#__jvotesystem_votes` AS v ON v. `answer_id` = a. `id` '
			. ' LEFT JOIN `#__jvotesystem_users` AS u ON ( u. `id` = v. `user_id` AND u. `blocked` = 0 ) '
			. ' WHERE a. `id` = '.$answerID;
			$sql .= " GROUP BY a.`id` ";
			$this->db->setQuery($sql);
			$this->votesByAnswer[$answerID] = $this->db->loadObject();
			$this->votesByAnswer[$answerID]->votes = (int) $this->votesByAnswer[$answerID]->votes;
		}
		return $this->votesByAnswer[$answerID];
	}
	
	function vote($boxID, $answerID) {
		unset($this->votesByUser[$boxID]);
		unset($this->votesByAnswer[$answerID]);
		
		$date = new JDate();
		//Schon bestehenden Vote-Eintrag abrufen
		$sql = 'SELECT v.`id`, v.`votes`'
        . ' FROM `#__jvotesystem_answers` AS a'
        . ' LEFT JOIN `#__jvotesystem_votes` AS v ON v.`answer_id`=a.`id`'
        . ' LEFT JOIN `#__jvotesystem_boxes` AS b ON a.`box_id`=b.`id`'
        . ' LEFT JOIN `#__jvotesystem_users` AS u ON (u.`id`=v.`user_id` AND u.`blocked`=0)'
        . ' WHERE a.`id`='.$answerID
        . ' AND u.`id`='.$this->user->id
        . ' AND b.`id`='.$boxID; 
		$this->db->setQuery($sql);
		$result = $this->db->loadObject();
		if(!isset($result->votes)) {
			//Neuen Eintrag erstellen
			$nV = new JObject();
			$nV->id = null;
			$nV->user_id = $this->user->id;
			$nV->answer_id = $answerID;
			$nV->votes = 1;
			$nV->voted_time = $date->toMySQL();
			
			$this->db->insertObject('#__jvotesystem_votes', $nV);
			
			//other extensions
			JPluginHelper::importPlugin( 'jvotesystem' );
			$dispatcher =& JDispatcher::getInstance();
			$res = $dispatcher->trigger( 'onAnswerVoted', array( $answerID ) );
			
			$this->log->add("DB", 'AddedVoting', array("bid" => $boxID, "aid" => $answerID));
		} else {
			//Alten Eintrag updaten
			$nV = new JObject();
			$nV->id = $result->id;
			$nV->votes = $result->votes + 1;
			$nV->voted_time = $date->toMySQL();
			
			$this->db->updateObject('#__jvotesystem_votes', $nV, 'id');
			$this->log->add("DB", 'UpdatedVoting', array("bid" => $boxID, "aid" => $answerID, "votes" => $result->votes + 1));
		}
		return $nV->votes;
	}
	
	function checkVote($boxID, $answerID, $voteown=false) {
		$this->user->loadUser(true);
	
		$out = array();
		//Variablen überprüfen
		if($boxID == null OR $answerID == null) {
			$out[] = array("key"=>"erfolg","value"=>0);
			$out[] = array("key"=>"error","value"=>JText::_('ERRORVOTE'));
			$this->log->add("ERROR", 'VotingMissingParameters', array("bid" => $boxID, "aid" => $answerID));
			return $out;
		}
		//Box & Answer laden
		$box = $this->getBox($boxID);
		$answer = VBAnswer::getAnswer($answerID);
		if(!$box OR !$answer OR $this->user->id == 0) {
			$out[] = array("key"=>"erfolg","value"=>0);
			$out[] = array("key"=>"error","value"=>JText::_('ERRORVOTE'));
			$this->log->add("ERROR", 'VotingNoBoxOrAnswerFound', array("bid" => $boxID, "aid" => $answerID));
			return $out;
		}
		//User laden
		$jUser = JFactory::getUser($this->user->jid);
		//User blocked
		if($jUser->block == 1 or $this->user->blocked == 1) {
			$out[] = array("key"=>"erfolg","value"=>0);
			$out[] = array("key"=>"error","value"=>JText::_('USERBLOCKED'));
			$this->log->add("NOTICE", 'BlockedUserTriedToVote', array("bid" => $boxID, "aid" => $answerID));
			return $out;
		}
		//Rechte überprüfen
		if(!$this->access->checkAccessGroup('access', $box)) {
			$out[] = array("key"=>"erfolg","value"=>0);
			$out[] = array("key"=>"error","value"=>JText::_('NOVOTERIGHTS'));
			$this->log->add("NOTICE", 'UserTriedToVoteWithoutAccess', array("bid" => $boxID, "aid" => $answerID));
			return $out;
		}
		//Answer von Benutzer erstellt?
		if($answer->autor_id == $this->user->id AND $voteown == false AND $this->vbparams->get('voteOnOwn') == 0) {
			$out[] = array("key"=>"erfolg","value"=>0);
			$out[] = array("key"=>"error","value"=>JText::_('NOVOTEONOWN'));
			$this->log->add("NOTICE", 'UserTriedToVoteOnOwn', array("bid" => $boxID, "aid" => $answerID));
			return $out;
		}
		$aVotes = $this->getVotesFromAnswer($answerID);
		//Vote erlaubt?
		$votes = $this->getVotesByUser($boxID);
		if($votes->allowed_votes > 0) {
			//Max-Votes pro Antwort
			if($box->allowed_votes > $box->max_votes_on_answer) {
				$votesA = $this->getVotesByUser($boxID, $answerID);
				
				if($votesA->votes >= $box->max_votes_on_answer) {
					$out[] = array("key"=>"erfolg","value"=>0);
					if($this->access->isUserAllowedToViewResult($box, $votes)) $out[] = array("key"=>"totalVotes","value"=>$aVotes->votes);
					$out[] = array("key"=>"leftVotes","value"=>$votes->allowed_votes);
					$out[] = array("key"=>"error","value"=>sprintf(JText::_('VOTELIMITMAX'), $box->max_votes_on_answer));
					$this->log->add("NOTICE", 'UserReachedVoteLimitPerAnswer', array("bid" => $boxID, "aid" => $answerID, "limit" => $box->max_votes_on_answer));
					return $out;
				}
			}
			//ACCESS überprüfen
			if(!$this->access->isUserAllowedToVoteAnswer($box, $answer, $voteown)) {
				$out[] = array("key"=>"erfolg","value"=>0);
				$out[] = array("key"=>"error","value"=>JText::_('NOVOTERIGHTS'));
				$this->log->add("NOTICE", 'UserTriedToVoteWithoutRights', array("bid" => $boxID, "aid" => $answerID, "voteOwn" => $voteown));
				return $out;
			}
			$userVotes = $this->vote($box->id, $answer->id);
			
			if($userVotes != false) {
				$out[] = array("key"=>"userVotes","value"=>$userVotes);
				$out[] = array("key"=>"leftVotes","value"=>$votes->allowed_votes - 1);
				if ($box->max_votes_on_answer == $userVotes) { //falls maximale anzahl antworten pro antwort erreicht, dann keyword zum deaktivieren (per JS) der Antwort ausgeben.
					$out[] = array("key"=>"disableanswer","value"=>true);
				}
				$out[] = array("key"=>"erfolg","value"=>1);
			} else {
				$out[] = array("key"=>"erfolg","value"=>0);
				$out[] = array("key"=>"leftVotes","value"=>$votes->allowed_votes);
				$out[] = array("key"=>"error","value"=>JText::_('ERRORVOTEMYSQL'));
			}
		} else {
			$out[] = array("key"=>"erfolg","value"=>0);
			$out[] = array("key"=>"leftVotes","value"=>$votes->allowed_votes);
			if($this->access->isUserAllowedToViewResult($box, $votes)) $out[] = array("key"=>"totalVotes","value"=>$aVotes->votes);
			$out[] = array("key"=>"error","value"=>JText::_('VOTELIMIT'));
			$this->log->add("NOTICE", 'UserReachedVoteLimit', array("bid" => $boxID, "aid" => $answerID, "left" => $votes->allowed_votes));
		}
		//TotalVotes
		$aVotes = $this->getVotesFromAnswer($answer->id);
		if($this->access->isUserAllowedToViewResult($box, $votes)) $out[] = array("key"=>"totalVotes","value"=>$aVotes->votes);
		return $out;
	}
	
	function getBanner($noScript, $box) {
		if(!$this->vbparams->get('adsense') OR $this->vbparams->get('adsense_key') == "") return '';
		
		//Parameter festlegen
		$par = new JObject();
			$par->translation_advert = JText::_('Anzeige');
			$par->activate_ranking = $box->activate_ranking;
			$pars = array("adsense_key" => $this->vbparams->get('adsense_key'), "load" => true);
			$par->script = $noScript ? "" : $this->template->loadTemplate("banner_code", JArrayHelper::toObject($pars));
	
		//Template-Datei laden
		$out = $this->template->loadTemplate("banner", $par);
		
		return $out;
	}

	function getPageLink($i, $onlyAnswers, $page, $linkOJ, $name = null) 
	{ 
		//ohne JS
		$link = 'href="'.$linkOJ.$i.'" ';
		$data = 'data-p="'.$i.'"';
		if($onlyAnswers OR $i == null OR $linkOJ == null) {$link = '';}
		//Link
		$class = '';
		if($i == $page) $class = 'class="pageSelected" ';
		elseif($i == null) {$class = 'class="pageNull" ';$data = '';}
		$out = '<a '.$class.$link.$data.'>';
		$out .= ($name != null) ? $name : $i;
		$out .= '</a>';
				
		return $out;
	}
	
	function buildnavi ($answers, $ajax, $page, $answersperpage, $maxdisplaypages, $uri = null, $type) {
		if( $answers > $answersperpage || $this->template->getTemplate() == 'module') {
			//Link für ohne JS auswerten bzw. erstellen
			$linkOJ = $uri;
			if ( $linkOJ != null ) {
				$linkOJsplit = explode('jVoteSystemPage',$linkOJ);
				$linkOJ = $linkOJsplit[0];
				if(isset($linkOJsplit[1])) $linkOJ = substr($linkOJ, 0, -1);
				$linkOJparse = parse_url($linkOJ);
				if(isset($linkOJparse["query"])) $linkOJ .= '&jVoteSystemPage=';
				else $linkOJ .= '?jVoteSystemPage=';
			}
			
			$pages = (int) ceil($answers/$answersperpage);
			
			//Variablen-Button
			$buttons = new JObject();
			
			$buttons->prev = $page == 1 ? false : true;
			$buttons->start = false;
			$buttons->end = false;
			$buttons->next = ($page*$answersperpage) >= $answers ? false : true;
			
			if($pages > $maxdisplaypages) {
				//Seitenzahl-Anzeige abkürzen
				if($page > (floor($maxdisplaypages/2) + 2)) {
					if($page <= $pages AND $page >= ($pages - (floor($maxdisplaypages/2) + 2))) {
						$buttons->start = true;
						$i = $pages - $maxdisplaypages + 1;
						$end = $pages;
					} else {
						$buttons->start = true;
						$i = $page - floor($maxdisplaypages/2);
						$end = $page + floor($maxdisplaypages/2);
						$buttons->end = true;
					}
				} else {
					$i = 1;
					$end = $maxdisplaypages;
					$buttons->end = true;
				}
			} else {
				//Alle Seitenzahlen anzeigen
				$i = 1;
				$end = $pages;
			}
			
			$par = new JObject();
			$par->type = $type;
			$par->id = $this->id;
			
			if($buttons->prev == true) {
				$par->prev = $this->getPageLink($page-1, $ajax, $page, $linkOJ, JText::_('Vor'));
			}
			$par->main = '';
			if($buttons->start == true) {
				$par->main .= $this->getPageLink(1, $ajax, $page, $linkOJ, JText::_('Start'));
				$par->main .= $this->getPageLink(($page > $maxdisplaypages ? ($page - $maxdisplaypages) : null), $ajax, $page, $linkOJ, '...');
			}
			for($i = $i; $i <= $end; $i++) {
				$par->main .= $this->getPageLink($i, $ajax, $page, $linkOJ);
			}
			if($buttons->end == true) {
				$par->main .= $this->getPageLink(($pages > ($page + $maxdisplaypages) ? ($page + $maxdisplaypages) : null), $ajax, $page, $linkOJ, '...');
				$par->main .= $this->getPageLink($pages, $ajax, $page, $linkOJ, JText::_('End'));
			}
			if($buttons->next == true) {
				$par->next = $this->getPageLink($page+1, $ajax, $page, $linkOJ, JText::_('Weiter'));
			}
			$navi = $this->template->loadTemplate("navi", $par);
		} else {
			$navi = '';
		}
		return $navi;
	}
	
	function getPolls($filter = array(), $start = 0, $limit = null) {
		$sql = "SELECT b.*, IFNULL(aStats.votes, 0) AS votes, IFNULL(cStats.comments,0) AS comments
				FROM `#__jvotesystem_boxes` AS b
				LEFT JOIN (
					SELECT a.`box_id`, SUM(v.`votes`) AS votes
					FROM `#__jvotesystem_answers` AS a
					LEFT JOIN `#__jvotesystem_votes` AS v ON (v.`answer_id`=a.`id`)
					GROUP BY a.`box_id`
				) AS aStats ON(aStats.box_id=b.`id`)
				LEFT JOIN (
					SELECT a.`box_id`, COUNT(c.`id`) AS comments
					FROM `#__jvotesystem_answers` AS a
					LEFT JOIN `#__jvotesystem_comments` AS c ON (c.`answer_id`=a.`id`)
					GROUP BY a.`box_id`
				) AS cStats ON(cStats.box_id=b.`id`),
				`#__jvotesystem_categories` AS c
				WHERE b.`published`=1 AND c.`id`=b.`catid` ";
		//Excludes
		if(!empty($filter["excludes"])) {
			$sql .= " AND (";
			foreach($filter["excludes"] AS $i => $exclude) {
				if($i != 0) $sql .= " AND ";
				$sql .= "b.`id` != '".$exclude."'";
			}
			$sql .= ") ";
		}
		//Keyword
		if(isset($filter["keyword"]))
			$sql .= " AND (b.`title` LIKE '%".$filter["keyword"]."%' OR b.`question` LIKE '%".$filter["keyword"]."%') ";
		//Time
		if(isset($filter["time"])) {
			switch(strtolower($filter["time"])) {
				case "today": 	$sql .= "AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) < b.`created` "; break;
				case "week": 	$sql .= "AND DATE_SUB(CURDATE(),INTERVAL 1 WEEK) < b.`created` "; break;
				case "month": 	$sql .= "AND DATE_SUB(CURDATE(),INTERVAL 1 MONTH) < b.`created` "; break;
			}
		}
		//Categories
		if(isset($filter["cid"]) AND @$filter["cid"] != 0) {
			$cats = array($filter["cid"]);
			if(!isset($filter["subcats"])) {
				//Wenn Subkategorien
				$params = $this->vbparams->getActiveMenuParams();
				
				$filter["subcats"] = $params->get("subcats", 1);
			}
			
			if($filter["subcats"]) {
				$cats = array_merge($cats, $this->category->getCategoryChilds($filter["cid"], 0));
			}
			//Alle Umfragen mit den IDS
			$sql .= " AND (";
			foreach($cats AS $i => $cat) {
				if($i != 0) $sql .= " OR ";
				$sql .= "b.`catid`='".$cat."'";
			}
			$sql .= ") ";
		}
		//Zugriff erlaubt
		$user = JFactory::getUser();
		if(version_compare( JVERSION, '1.6.0', 'lt' )) {
			$sql .= " AND c.`accesslevel` <= '".$user->gid."' ";
		} else {
			$levels = $user->getAuthorisedViewLevels();
			
			$sql .= " AND (";		
			foreach($levels AS $i => $level) {
				if($i != 0) $sql .= " OR ";
				$sql .= "c.`accesslevel`='".$level."'";
			}
			$sql .= ") ";
		}
		//Order
		switch(@$filter["order"]) {
			case "most-voted": $sql .= "ORDER BY aStats.votes DESC "; break;
			case "recent": $sql .= "ORDER BY b.`created` DESC "; break;
			case "most-discussed": $sql .= "ORDER BY cStats.comments DESC "; break;
			case "random": $sql .= "ORDER BY RAND() DESC "; break;
		} 
		//Limit
		if($limit == null) $limit = $this->vbparams->get("pollsPerPage");
		if(isset($filter["page"])) {
			$start = ($filter["page"]-1)*$this->vbparams->get("pollsPerPage");
			
			$this->db->setQuery($sql);
			$polls = $this->db->loadObjectList();
			$this->numRows = count($polls);
			
			while($start > $this->numRows) { $start -= $this->vbparams->get("pollsPerPage"); }
			return array_slice($polls, $start, $limit);
		} else {
			if($limit != -1) $sql .= " LIMIT $start, $limit";
			
			$this->db->setQuery($sql);
			$polls = $this->db->loadObjectList();
		}
		
		//echo str_replace("#_", "jos", $this->db->getQuery());
		
		return $polls;
	}
	
	function getNuwRows() {
		return $this->numRows;
	}
	
	function removePoll($id) {
		$sql = 'DELETE FROM `#__jvotesystem_boxes` '
		. ' WHERE `id` = '.$id
		. ' LIMIT 1';
		$this->db->setQuery($sql);
		$this->db->query();
		if($this->db->getErrorMsg()) return false;
		//Zugriffsrechte entfernen
		$this->access->removeAccessEntries($id, array("result_access", "admin_access", "access", "add_answer_access", "add_comment_access"));
		//Answers laden
		$sql = 'SELECT `id` '
		. ' FROM `#__jvotesystem_answers` '
		. ' WHERE `box_id` = '.$id;
		$this->db->setQuery($sql);
		$answers = $this->db->loadObjectList();
		//Answers und Votes löschen
		foreach($answers AS $answer) {
			$sql = 'DELETE FROM `#__jvotesystem_answers` '
			. ' WHERE `id` = '.$answer->id;
			$this->db->setQuery($sql);
			$this->db->query();
			if($this->db->getErrorMsg()) return false;
			//Votes löschen
			$sql = 'DELETE FROM `#__jvotesystem_votes` '
			. ' WHERE `answer_id`='.$answer->id;
			$this->db->setQuery($sql);
			$this->db->query();
			if($this->db->getErrorMsg()) return false;
			//Kommentare löschen
			$sql = 'DELETE FROM `#__jvotesystem_comments` '
			. ' WHERE `answer_id` = '.$answer->id;
			$this->db->setQuery($sql);
			$this->db->query();
			if($this->db->getErrorMsg()) return false;
			//Spam-Reports entfernen
			$sql = 'DELETE FROM `#__jvotesystem_spam_reports` '
			. ' WHERE `block_group`="answer" AND `block_id` = '.$answer->id;
			$this->db->setQuery($sql);
			$this->db->query();
			if($this->db->getErrorMsg()) return false;
		}
		return true;
	}
	
	function addDefaultSettingsBox($id) {
		$sql = "INSERT INTO `#__jvotesystem_boxes` (`id`, `catid`, `title`, `question`, `alias`, `access`, `result_access`, `admin_access`, `published`, `ordering`, `allowed_votes`, `add_answer`, `add_answer_access`, `add_comment`, `add_comment_access`, `created`, `autor_id`, `start_time`, `end_time`, `params`) VALUES
			(NULL, ".$id.", '', '', '', 0, 0, 25, -1, 0, 5, 1, 18, 1, 18, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '{\"max_votes_on_answer\":\"3\",\"show_thankyou_message\":\"1\",\"goto_chart\":\"1\",\"show_result\":\"always\",\"show_result_after_date\":\"0000-00-00 00:00:00\",\"activate_ranking\":\"1\",\"ranking_orderby\":\"votes\",\"ranking_orderby_direction\":\"DESC\",\"answers_orderby\":\"votes\",\"answers_orderby_direction\":\"DESC\",\"show_author\":\"1\",\"template\":\"default\",\"chart_type\":\"both\",\"send_mail_admin_answer\":\"1\",\"send_mail_user_answer_comments\":\"1\",\"send_mail_admin_comment\":\"1\",\"send_mail_user_comment_comments\":\"1\",\"activate_spam\":\"1\",\"spam_count\":\"5\",\"spam_mail_admin_report\":\"1\",\"spam_mail_admin_ban\":\"1\"}');";
		$this->db->setQuery($sql);
		if(!$this->db->query()) return false;
		
		if(!version_compare( JVERSION, '1.6.0', 'lt' )) {
			//Mit Rechten
			$insertId = $this->db->insertid();
			$sql = "INSERT INTO `#__jvotesystem_access` (`box_id`, `group_id`, `access`) VALUES
						($insertId, 1, 'access'),
						($insertId, 8, 'access'),
						($insertId, 7, 'access'),
						($insertId, 6, 'access'),
						($insertId, 5, 'access'),
						($insertId, 4, 'access'),
						($insertId, 3, 'access'),
						($insertId, 2, 'access'),
						($insertId, 3, 'add_answer_access'),
						($insertId, 2, 'add_answer_access'),
						($insertId, 5, 'add_answer_access'),
						($insertId, 6, 'add_answer_access'),
						($insertId, 7, 'add_answer_access'),
						($insertId, 8, 'add_answer_access'),
						($insertId, 4, 'add_answer_access'),
						($insertId, 2, 'add_comment_access'),
						($insertId, 3, 'add_comment_access'),
						($insertId, 4, 'add_comment_access'),
						($insertId, 6, 'add_comment_access'),
						($insertId, 7, 'add_comment_access'),
						($insertId, 5, 'add_comment_access'),
						($insertId, 8, 'add_comment_access'),
						($insertId, 8, 'admin_access'),
						($insertId, 7, 'admin_access'),
						($insertId, 6, 'admin_access'),
						($insertId, 8, 'result_access'),
						($insertId, 4, 'result_access'),
						($insertId, 7, 'result_access'),
						($insertId, 5, 'result_access'),
						($insertId, 3, 'result_access'),
						($insertId, 2, 'result_access'),
						($insertId, 1, 'result_access'),
						($insertId, 6, 'result_access');";
			$this->db->setQuery($sql);
			if(!$this->db->query()) return false;
		}
		return true;
	}
}
