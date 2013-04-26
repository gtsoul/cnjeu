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

jimport( 'joomla.methods' );
jimport( 'joomla.application.component.helper' );

class VBParams
{
	var $params, $view, $session;
	
	function __construct($v) { 
		$this->view = $v;
		$jP = JComponentHelper::getParams('com_jvotesystem');
		$this->params = array();
		$this->session = JSession::getInstance('none',array());
		//Default
		$this->params['autoPublish'] = $jP->get('autoPublish', 1);
		$this->params['autoPublishComment'] = $jP->get('autoPublishComment', 1);
		$this->params['voteOnOwn'] = $jP->get('voteOnOwn', 1);
		$this->params['deleteOwnAnswer'] = $jP->get('deleteOwnAnswer', 1);
		$this->params['shortCountAnswer'] = $jP->get('shortCountAnswer', 300);
		$this->params['facebookLike'] = $jP->get('facebookLike', 0);
		$this->params['resetOwnVotes'] = $jP->get('resetOwnVotes', 1);
		//Views
		$this->params['diagramm'] = $jP->get('diagramm', 1);
		$this->params['displayName'] = $jP->get('displayName', 'name');
		$this->params['voteAlign'] = $jP->get('voteAlign', 'left');
		$this->params['shortNavi'] = $jP->get('shortNavi', 7);
		$this->params['showToolbar'] = $jP->get('showToolbar', 1);
		switch($v) {
			case 'pollslist':
				//View: Polls-List
				$this->params['answersPerPage'] = $jP->get('pollslist_answersPerPage', 3);
				$this->params['commentsPerPage'] = $jP->get('pollslist_commentsPerPage', 3);
				$this->params['chart_barscount'] = $jP->get('pollslist_barscount', 5);
				break;
			case 'poll':
				//View: Poll
				$this->params['answersPerPage'] = $jP->get('poll_answersPerPage', 20);
				$this->params['commentsPerPage'] = $jP->get('poll_commentsPerPage', 10);
				$this->params['chart_barscount'] = $jP->get('poll_barscount', 10);
				break;
			case 'plugin':
				//View: Plugin
				$this->params['answersPerPage'] = $jP->get('plugin_answersPerPage', 5);
				$this->params['commentsPerPage'] = $jP->get('plugin_commentsPerPage', 3);
				$this->params['chart_barscount'] = $jP->get('plugin_barscount', 8);
				break;
			case 'module':
				//View: Plugin
				$this->params['answersPerPage'] = $jP->get('module_answersPerPage', 5);
				$this->params['commentsPerPage'] = $jP->get('module_commentsPerPage', 3);
				$this->params['chart_barscount'] = $jP->get('module_barscount', 5);
				break;
			default:
				$this->params['answersPerPage'] = $this->session->get('jVoteSystemView.'.$v.'.answersPerPage', 5);
				$this->params['commentsPerPage'] = $this->session->get('jVoteSystemView.'.$v.'.commentsPerPage', 3);
				$this->params['chart_barscount'] = $this->session->get('jVoteSystemView.'.$v.'.barscount', 5);
				break;
		}
		//Comments
		$this->params['orderComment'] = $jP->get('orderComment', 'DESC');
		$this->params['deleteOwnComment'] = $jP->get('deleteOwnComment', 1);
		$this->params['shortCountComment'] = $jP->get('shortCountComment', 500);
		//Security
		$this->params['recaptcha'] = $jP->get('recaptcha', 1);
		$this->params['recaptcha_publickey'] = $jP->get('recaptcha_publickey', '');
		$this->params['recaptcha_privatekey'] = $jP->get('recaptcha_privatekey', '');
		$this->params['checkIP'] = $jP->get('checkIP', 1);
		$this->params['checkCookies'] = $jP->get('checkCookies', 1);
		$this->params['logging'] = (int)$jP->get('loggingMode', 2);
		$this->params['showErrors'] = $jP->get('showErrors', 0);
		$this->params['captcha_vote'] = $jP->get('captcha_votes', 1);
		$this->params['captcha_addanswer'] = $jP->get('captcha_addanswer', 1);
		$this->params['captcha_addcomment'] = $jP->get('captcha_addcomment', 1);
		//BBCode
		$this->params['activate_bbcode'] = $jP->get('activate_bbcode', 1);
		$this->params['general_published_bbcode'] = 0;
		//Daten
		$this->params['load_jquery'] = $jP->get('load_jquery', 1);
		$this->params['load_domwrite'] = $jP->get('load_domwrite', 1);
		//Animation
		$this->params['activate_slide'] = $jP->get('activate_slide', 1);
		$this->params['activate_fade'] = $jP->get('activate_fade', 1);
		//E-Mail
		$this->params['quickModeration'] = $jP->get('quickModeration', 1);
		$this->params['validityPeriod'] = $jP->get('validityPeriod', 14);
		//Adsense
		$this->params['adsense'] = $jP->get('adsense', 1);
		$this->params['adsense_key'] = $jP->get('adsense_key', "");
		//Database-Plugin
		$this->params['sessionDataPeriod'] = $jP->get('sessionDataPeriod', 30);
		//Avatar & Profile
		$this->params['com_avatar'] = $jP->get('com_avatar', 'joomla');
		$this->params['com_profile'] = $jP->get('com_profile', 'joomla');
		
		$this->params['single_radiobutton'] = $jP->get('single_radiobutton', 1);
		$this->params['pollsPerPage'] = $jP->get('pollsPerPage', 10);
		//Min-& Max-Werte der Parameter überprüfen
		//$this->vbparams->checkParams(); 
	}
	
	function &getInstance($v = 'pollslist', $reload = false) {
		static $instance;
		if(empty($instance) OR $reload) {
			$instance = new VBParams($v);
		}
		return $instance;
	}
	
	function getView() {
		return $this->view;
	}
	
	function get($param) {
		//echo $this->view;
		return $this->params[$param];
	}
	
	function checkParams($param = null) {
		//@TODO: a lot..
		//Tabelle
			//Min
			$min = array();
			$min['answersPerPage'] = 1;
			$min['commentsPerPage'] = 1;
			$min['validityPeriod'] = 1;
			$min['shortCountAnswer'] = 30;
			$min['shortCountComment'] = 50;
			//Max
			$max = array();
		
		//Überprüfen
		if($param == null) {
			//Komplette Tabelle überprüfen
			foreach($this->params AS $p) {
				if(isset($min[key($this->params)])) {
					if($min[key($this->params)] < $p) {
						$this->params[key($this->params)] = $min[key($this->params)];
					}
				}
				next($this->params);
			}
		} else {
		
		}
	}
	
	function set($view, $param, $value) {
		if(isset($param) AND isset($value)) {
			$this->params[$param] = $value;
			if($view != "global") $this->session->set('jVoteSystemView.'.$view.'.'.$param, $value);
			//Min-& Max-Werte der Parameter überprüfen
			$this->checkParams($param);
		}
	}
	
	function convertBoxParams($box) {
		if(!isset($box->params)) return $box;
		if(substr($box->params, 0, 1) == "{") {
			//Version 2.0
			$params = json_decode($box->params);
			foreach($params AS $key => $param) {
				$box->$key = $param;
			}
		} else {
			//Version 1.13.2-
			$params = explode("\n", $box->params);
			foreach($params AS $param) {
				$pSplit = explode("=", $param);
				
				switch($pSplit[0]) {
					case "send_mail_admin_answer" : $box->send_mail_admin_answer = $pSplit[1]; break;
					case "send_mail_user_answer_comments" : $box->send_mail_user_answer_comments = $pSplit[1]; break;
					case "send_mail_admin_comment" : $box->send_mail_admin_comment = $pSplit[1]; break;
					case "send_mail_user_comment_comments" : $box->send_mail_user_comment_comments = $pSplit[1]; break;
					case "activate_spam" : $box->activate_spam = $pSplit[1]; break;
					case "spam_count" : $box->spam_count = $pSplit[1]; break;
					case "spam_mail_admin_report" : $box->spam_mail_admin_report = $pSplit[1]; break;
					case "spam_mail_admin_ban" : $box->spam_mail_admin_ban = $pSplit[1]; break;
					case "activate_ranking" : $box->activate_ranking = $pSplit[1]; break;
					case "answers_orderby" : $box->answers_orderby = $pSplit[1]; break;
					case "answers_orderby_direction" : $box->answers_orderby_direction = $pSplit[1]; break;
					case "max_votes_on_answer" : $box->max_votes_on_answer = $pSplit[1]; break;
					case "ranking_orderby" : $box->ranking_orderby = $pSplit[1]; break;
					case "ranking_orderby_direction" : $box->ranking_orderby_direction = $pSplit[1]; break;
					case "show_author" : $box->show_author = $pSplit[1]; break;
					case "template" : $box->template = $pSplit[1]; break;
					case "show_result" : $box->show_result = $pSplit[1]; break;
					case "show_result_after_date" : $box->show_result_after_date = $pSplit[1]; break;
					case "show_thankyou_message" : $box->show_thankyou_message = $pSplit[1]; break;
					case "goto_chart" : $box->goto_chart = $pSplit[1]; break;
				}
			}
		}
		
		if(!isset($box->answers_orderby)) $box->answers_orderby = "votes";
		if(!isset($box->answers_orderby_direction)) $box->answers_orderby_direction = "DESC";
		if(!isset($box->max_votes_on_answer)) $box->max_votes_on_answer = 3;
		if(!isset($box->ranking_orderby)) $box->ranking_orderby = "votes";
		if(!isset($box->ranking_orderby_direction)) $box->ranking_orderby_direction = "DESC";
		if(!isset($box->show_author)) $box->show_author = 1;
		if(!isset($box->template)) $box->template = "default";
		if(!isset($box->show_result)) $box->show_result = "always";
		if(!isset($box->show_result_after_date)) $box->show_result_after_date = "0000-00-00 00:00:00";
		if(!isset($box->show_thankyou_message)) $box->show_thankyou_message = 0;
		if(!isset($box->goto_chart)) $box->goto_chart = 1;
		if(!isset($box->chart_type)) $box->chart_type = "both";
		if(!isset($box->activate_spam)) $box->activate_spam = 0;
		
		return $box;
	}
	
	function getActiveMenu() {
		if(version_compare( JVERSION, '1.6.0', 'lt' )) {
			$menus = &JSite::getMenu();
			$active  = $menus->getActive();
			if($active) $active->title = $active->name;
		} else {
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
		}
		return $active;
	}
	
	function getActiveMenuParams() {
		$activeMenu = $this->getActiveMenu();
		if(version_compare( JVERSION, '1.6.0', 'lt' )) {
			$params = new JParameter( @$activeMenu->params );
			$params->set("show_page_heading", $params->get("show_page_title", 1));
			$params->set("page_heading", $params->get("page_title", ""));
		} else {
			$params = new JRegistry();
			if ($activeMenu) $params->loadString($activeMenu->params);
		}
		return $params;
	}
}//class
