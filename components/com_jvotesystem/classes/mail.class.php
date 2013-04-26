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

jimport( 'joomla.application.component.helper' );
jimport( 'joomla.mail.mail' );
jimport( 'joomla.utilities.date' );

class VBMail
{
	//Variablen
	var $db, $user, $document, $jobs;
	
	function __construct() {
		$this->comment =& VBComment::getInstance();
		$this->answer =& VBAnswer::getInstance();
		$this->document = & JFactory::getDocument();
		$this->db =& JFactory::getDBO();
		$this->user =& VBUser::getInstance();
		$this->access =& VBAccess::getInstance();
		$this->vbparams =& VBParams::getInstance();
		$this->general =& VBGeneral::getInstance();
		
		$this->jobs = array();
	}
	
	function &getInstance() {
		static $instance;
		if(empty($instance)) {
			$instance = new VBMail();
		}
		return $instance;
	}
	
	
	function getJobs() {
		return $this->jobs;
	}
	
	function runJobs($jobs = null) {
		if(!empty($jobs)) {
			foreach($jobs AS $job) {
				switch($job[0]) {
					case 'addedAnswer':
						$this->addedAnswer($job[1][0], $job[1][1], $job[1][2], $job[1][3]);
						break;
					case 'addedComment':
						$this->addedComment($job[1][0], $job[1][1], $job[1][2], $job[1][3], $job[1][4]);
						break;
					case 'reportedObject':
						$this->reportedObject($job[1][0], $job[1][1], $job[1][2]);
						break;
					case 'bannedObject':
						$this->bannedObject($job[1][0], $job[1][1], $job[1][2]);
						break;
				}
			}
		}
	}
	
	function addJob($function, $arguments) {
		$this->jobs[] = array($function, $arguments);
	}

	function addedAnswer($id, $answer, $box, $user) {	
		if($box->send_mail_admin_answer) {
			$userData = $this->user->getUserData($user->id);
			$jUser = JFactory::getUser($userData->jid);
			if($this->access->checkAccessGroup('admin_access', $box, false, $jUser)) return true;
			
			if(version_compare( JVERSION, '1.6.0', 'lt' )) {
				$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id`"
					. " FROM `#__users` AS ju, `#__jvotesystem_users` AS u"
					. " WHERE u.`jid`=ju.`id` AND ju.`block`=0"
					. " AND ju.`gid`>='".$box->admin_access."'";
			} else {
				$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id` \n"
					. "FROM `#__users` AS ju, `#__jvotesystem_users` AS u, `#__user_usergroup_map` AS ugm, `#__jvotesystem_access` AS ac\n"
					. "WHERE ju.`id`=ugm.`user_id`\n"
					. "AND u.`jid`=ju.`id`\n"
					. "AND ac.`group_id`=ugm.`group_id`\n"
					. "AND ac.`box_id`='".$box->id."'\n"
					. "AND ac.`access`='admin_access'";
			}
			$this->db->setQuery($sql);
			$rows = $this->db->loadObjectList();
			if(!$rows) return true;
			
			$app =& JFactory::getApplication();
			$date = new JDate();
			$date->setOffset($app->getCfg('offset'));
			
			$betreff = JText::_('ANSWER_ADDED').': '.$box->title;
			$inhalt = JText::_('Poll').': <a href="'.JURI::root(false, "").$this->general->buildLink("poll", $box->id).'">'.$box->title.'</a><br /><br />';
			$inhalt .= '<span style="color:#3c452d;font:bold 1em Verdana, Arial, Sans-Serif">'.$userData->name.'</span> ( ';
			$email = $user->email;
			if(!$email) $email = $userData->email;
			if($email != '') $inhalt .= '<a target="_blank" href="mailto:+'.$email.'">'.$email.'</a>, ';
			$inhalt .= '<span style="font-size:11px">IP: '.$user->ip.'</span> ) &mdash; <span style="font-size:11px;color:#999">'.$date->toFormat('%d.%B %Y - %H:%M').'</span>';
			$inhalt .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
			$inhalt .= $this->general->BBCode(nl2br($answer)).'</div>';
			
			foreach($rows AS $row) {
				if($row->email) {
					$content = $inhalt;
					if($this->vbparams->get('autoPublish') == 0) {
						$content .= JText::_('ANSWER_NOT_PUBLISHED').'<br />';
						if($this->vbparams->get('quickModeration')) {
							//Hash erstellen für Publish
							$params = "state=1\n"."answer=".$id;
							$hash = $this->general->generateHash('changePublishStateAnswer', $params, $row->id);
							$content .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Publish').'</a>';
						}
					} else {
						if($this->vbparams->get('quickModeration')) {
							//Hash erstellen für unPublish
							$params = "state=0\n"."answer=".$id;
							$hash = $this->general->generateHash('changePublishStateAnswer', $params, $row->id);
							$content .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('UNPublish').'</a>';
						}
					}
					if($this->vbparams->get('quickModeration')) {
						//Hash erstellen für Delete
						$params = 'answer='.$id;
						$hash = $this->general->generateHash('removeAnswer', $params, $row->id);
						$content .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Delete').'</a>';
					}
					$this->sendMail($betreff, $row->email, $content, true);					
				}
			}
		}
	}
	
	function addedComment($id, $comment, $answer, $box, $user) {
		$userData = $this->user->getUserData($user->id);
		$jUser = JFactory::getUser($userData->jid);
		$app =& JFactory::getApplication();
		$date = new JDate();
		$date->setOffset($app->getCfg('offset'));
		
		$duplicateEmail = array();
		if(($box->send_mail_admin_comment AND !$this->access->checkAccessGroup('admin_access', $box, false, $jUser)) OR ($box->send_mail_user_answer_comments AND $user->id != $answer->autor_id)) {
			
	
			$betreff = JText::_('COMMENT_ADDED').': '.$box->title;
			$inhalt = JText::_('Poll').': <a href="'.JURI::root(false, "").$this->general->buildLink("poll", $box->id).'">'.$box->title.'</a> &mdash; '.JText::_('Answer').':';
			$inhalt .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
			$inhalt .= $this->general->BBCode(nl2br($answer->answer)).'</div>';
			$inhalt .= '<div style="padding-left:20px;"><span style="color:#3c452d;font:bold 1em Verdana, Arial, Sans-Serif">'.$userData->name.'</span>';
			
			if($box->send_mail_admin_comment AND !$this->access->checkAccessGroup('admin_access', $box, false, $jUser)) {
				$content = $inhalt;
				$content .= ' ( ';
				$email = false;
				if(isset($user->email)) $email = $user->email;
				if(!$email) $email = $userData->email;
				if($email != '') $content .= '<a target="_blank" href="mailto:+'.$email.'">'.$email.'</a>, ';
				$content .= '<span style="font-size:11px">IP: '.$user->ip.'</span> )';
				$content .= ' &mdash; <span style="font-size:11px;color:#999">'.$date->toFormat('%d.%B %Y - %H:%M').'</span>';
				$content .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
				$content .= $this->general->BBCode(nl2br($comment)).'</div></div>';
			
				if(version_compare( JVERSION, '1.6.0', 'lt' )) {
					$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id`"
						. " FROM `#__users` AS ju, `#__jvotesystem_users` AS u"
						. " WHERE u.`jid`=ju.`id` AND ju.`block`=0"
						. " AND ju.`gid`>=".$box->admin_access;
				} else {
					$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id` \n"
						. "FROM `#__users` AS ju, `#__jvotesystem_users` AS u, `#__user_usergroup_map` AS ugm, `#__jvotesystem_access` AS ac\n"
						. "WHERE ju.`id`=ugm.`user_id`\n"
						. "AND u.`jid`=ju.`id`\n"
						. "AND ac.`group_id`=ugm.`group_id`\n"
						. "AND ac.`box_id`='".$box->id."'\n"
						. "AND ac.`access`='admin_access'";
				}
				$this->db->setQuery($sql);
				$rows = $this->db->loadObjectList();
				
				foreach($rows AS $row) {
					if($row->email) {
						$text = $content;
						if($this->vbparams->get('autoPublishComment') == 0) {
							$text .= JText::_('COMMENT_NOT_PUBLISHED').'<br />';
							if($this->vbparams->get('quickModeration')) {
								//Hash erstellen für Publish
								$params = "state=1\n"."comment=".$id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('changePublishStateComment', $params, $row->id);
								$text .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Publish').'</a>';
								//Hash erstellen für Delete
								$params = 'comment='.$id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('removeComment', $params, $row->id);
								$text .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Delete').'</a>';
							}
						} else {
							if($this->vbparams->get('quickModeration')) {
								//Hash erstellen für unPublish
								$params = "state=0\n"."comment=".$id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('changePublishStateComment', $params, $row->id);
								$text .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('unPublish').'</a>';
							}
						}
						if($this->vbparams->get('quickModeration')) {
							//Hash erstellen für Delete
							$params = 'comment='.$id."\n"."box=".$box->id;
							$hash = $this->general->generateHash('removeComment', $params, $row->id);
							$text .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Delete').'</a>';
						}
						if(!isset($duplicateEmail[$row->email]) AND $row->email != '') $this->sendMail($betreff, $row->email, $text, true);	
						$duplicateEmail[$row->email] = true;
					}
				}
			}
			
			if($box->send_mail_user_answer_comments AND $user->id != $answer->autor_id) {
				$content = $inhalt;
				$content .= ' &mdash; <span style="font-size:11px;color:#999">'.$date->toFormat('%d.%B %Y - %H:%M').'</span>';
				$content .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
				$content .= $this->general->BBCode(nl2br($comment)).'</div></div>';
			
				$autorData = VBUser::getUserData($answer->autor_id);
				
				$AEmail = false;
				if(isset($autorData->email)) $AEmail = $autorData->email;
				if(!$AEmail AND isset($autorData->jemail)) $AEmail = $autorData->jemail; else $AEmail = '';
				if($AEmail != '' AND !isset($duplicateEmail[$AEmail])) {
					$this->sendMail($betreff, $AEmail, $content, true);	
					$duplicateEmail[$AEmail] = true;
				}
			}
		}
		if($box->send_mail_user_comment_comments) {
	
			$sql = "SELECT u.id, IF(ju.`email` != \"\", ju.`email`, u.`email`) AS email, c.`comment`\n"
				. "FROM `#__jvotesystem_users` AS u\n"
				. "LEFT JOIN `#__users` AS ju ON ju.`id`=u.`jid`\n"
				. "LEFT JOIN `#__jvotesystem_comments` AS c ON (c.`autor_id`=u.`id` AND c.`published`=1)\n"
				. "LEFT JOIN `#__jvotesystem_answers` AS a ON c.`answer_id`=a.`id`\n"
				. "WHERE a.`id` = ".$answer->id."\n"
				. "ORDER BY c.`created` DESC";
			$this->db->setQuery($sql);
			$rows = $this->db->loadAssocList();
			
			if($rows) {
				$betreff = JText::_('COMMENT_ADDED').': '.$box->title;
				$inhalt = JText::_('Poll').': <a href="'.JURI::root(false, "").$this->general->buildLink("poll", $box->id).'">'.$box->title.'</a> &mdash; '.JText::_('Answer').':';
				$inhalt .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
				$inhalt .= $this->general->BBCode(nl2br($answer->answer)).'</div>';
				$inhalt .= '<div style="padding-left:20px;">';
					
				$anzahl = count($rows);
				$i = 0;
				foreach($rows AS $row) {
					$content = $inhalt;
					if(isset($rows[$i + 1])) $content .= '<div style="border: 1px solid rgb(204, 204, 204); padding: 2px 5px; font: italic 1em Verdana,Arial,Sans-Serif; text-align: center; margin: 0pt 0pt 0pt 40%; width: 20%;">'.($anzahl - $i - 1).' '.JText::_('MORE_COMMENTS').'</div>';
					$content .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
					$content .= $this->general->BBCode(nl2br($row['comment'])).'</div>
					';
					if($i - 1 > 0) $content .= '<div style="border: 1px solid rgb(204, 204, 204); padding: 2px 5px; font: italic 1em Verdana,Arial,Sans-Serif; text-align: center; margin: 0pt 0pt 0pt 40%; width: 20%;">'.($i - 1).' '.JText::_('MORE_COMMENT').'</div>';
					$content .= '<span style="color:#3c452d;font:bold 1em Verdana, Arial, Sans-Serif">'.$userData->name.'</span>';
					$content .= ' &mdash; <span style="font-size:11px;color:#999">'.$date->toFormat('%d.%B %Y - %H:%M').'</span>
					';
					$content .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
					$content .= $this->general->BBCode(nl2br($comment)).'</div></div>';
					
					if($row['email'] != '' AND !isset($duplicateEmail[$row['email']]) AND $row['id'] != $user->id) {
						$this->sendMail($betreff, $row['email'], $content, true);	
						$duplicateEmail[$row['email']] = true;
					}
					
					$i++;
				}
			}
		}
	}
	
	function reportedObject($what, $row, $box) {
		if($box->spam_mail_admin_report) {
			$inhalt = JText::_('Poll').': <a href="'.JURI::root(false, "").$this->general->buildLink("poll", $box->id).'">'.$box->title.'</a> &mdash; '.JText::_($what).':';
			$inhalt .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
		
			switch($what) {
				case 'answer':
					$betreff = JText::_('ANSWER_REPORTED').': '.$box->title;
					$inhalt .= $this->general->BBCode(nl2br($row->answer)).'</div>';
					
					$inhalt .= '<p>'.JText::_('ANSWER_REPORTED_TIMES').'</p>';
					break;
				case 'comment':
					$betreff = JText::_('COMMENT_REPORTED').': '.$box->title;
					$inhalt .= $this->general->BBCode(nl2br($row->comment)).'</div>';
					
					$inhalt .= '<p>'.JText::_('COMMENT_REPORTED_TIMES').'</p>';
					break;
			}
			
			$inhalt = str_replace('%REPORTS%', $row->reports, $inhalt);
			$inhalt = str_replace('%SPAMCOUNT%', $box->spam_count, $inhalt);
			
			if(version_compare( JVERSION, '1.6.0', 'lt' )) {
				$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id`"
					. " FROM `#__users` AS ju, `#__jvotesystem_users` AS u"
					. " WHERE u.`jid`=ju.`id` AND ju.`block`=0"
					. " AND ju.`gid`>='".$box->admin_access."'";
			} else {
				$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id` \n"
					. "FROM `#__users` AS ju, `#__jvotesystem_users` AS u, `#__user_usergroup_map` AS ugm, `#__jvotesystem_access` AS ac\n"
					. "WHERE ju.`id`=ugm.`user_id`\n"
					. "AND u.`jid`=ju.`id`\n"
					. "AND ac.`group_id`=ugm.`group_id`\n"
					. "AND ac.`box_id`='".$box->id."'\n"
					. "AND ac.`access`='admin_access'";
			}
			$this->db->setQuery($sql);
			$rows = $this->db->loadObjectList();
					
			$duplicateEmail = array();		
			
			foreach($rows AS $user) {
				if($user->email) {
					$content = $inhalt;
					if($this->vbparams->get('quickModeration')) {
						$content .= '<br />';
						switch($what) {
							case 'answer':	
								//Hash erstellen für Protect
								$params = "state=1\n"."answer=".$row->id;
								$hash = $this->general->generateHash('changePublishStateAnswer', $params, $user->id);
								$content .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Protect').'</a> | ';
								//Hash erstellen für Publish
								$params = "state=0\n"."answer=".$row->id;
								$hash = $this->general->generateHash('changePublishStateAnswer', $params, $user->id);
								$content .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('UNPublish').'</a>';
								//Hash erstellen für Delete
								$params = 'answer='.$row->id;
								$hash = $this->general->generateHash('removeAnswer', $params, $user->id);
								$content .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Delete').'</a>';
								break;
							case 'comment':
								//Hash erstellen für Protect
								$params = "state=1\n"."comment=".$row->id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('changePublishStateComment', $params, $user->id);
								$content .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Protect').'</a>';
								//Hash erstellen für Publish
								$params = "state=0\n"."comment=".$row->id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('changePublishStateComment', $params, $user->id);
								$content .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('UNPublish').'</a>';
								//Hash erstellen für Delete
								$params = 'comment='.$row->id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('removeComment', $params, $user->id);
								$content .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Delete').'</a>';
								break;
						}
					}
					if(!isset($duplicateEmail[$user->email]) AND $user->email != '') 
						$this->sendMail($betreff, $user->email, $content, true);	
					$duplicateEmail[$user->email] = true;
				}
			}
		}
	}
	
	function bannedObject($what, $row, $box) {
		if($box->spam_mail_admin_ban) {
			$inhalt = JText::_('Poll').': <a href="'.JURI::root(false, "").$this->general->buildLink("poll", $box->id).'">'.$box->title.'</a> &mdash; '.JText::_($what).':';
			$inhalt .= '<div style="border:1px solid #ccc;padding:10px 5px;margin:5px 0;font:normal 1em Verdana, Arial, Sans-Serif">';
		
			switch($what) {
				case 'answer':
					$betreff = JText::_('ANSWER_BLOCKED').': '.$box->title;
					$inhalt .= $this->general->BBCode(nl2br($row->answer)).'</div>';
					
					$inhalt .= '<p>'.JText::_('ANSWER_BLOCKED_TIMES').'</p>';
					break;
				case 'comment':
					$betreff = JText::_('COMMENT_BLOCKED').': '.$box->title;
					$inhalt .= $this->general->BBCode(nl2br($row->comment)).'</div>';
					
					$inhalt .= '<p>'.JText::_('COMMENT_BLOCKED_TIMES').'</p>';
					break;
			}
			
			$inhalt = str_replace('%REPORTS%', $row->reports, $inhalt);
			
			if(version_compare( JVERSION, '1.6.0', 'lt' )) {
				$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id`"
					. " FROM `#__users` AS ju, `#__jvotesystem_users` AS u"
					. " WHERE u.`jid`=ju.`id` AND ju.`block`=0"
					. " AND ju.`gid`>=".$box->admin_access;
			} else {
				$sql = "SELECT ju.`id` AS jid, ju.`email` AS email, u.`id` \n"
					. "FROM `#__users` AS ju, `#__jvotesystem_users` AS u, `#__user_usergroup_map` AS ugm, `#__jvotesystem_access` AS ac\n"
					. "WHERE ju.`id`=ugm.`user_id`\n"
					. "AND u.`jid`=ju.`id`\n"
					. "AND ac.`group_id`=ugm.`group_id`\n"
					. "AND ac.`box_id`='".$box->id."'\n"
					. "AND ac.`access`='admin_access'";
			}
			$this->db->setQuery($sql);
			$rows = $this->db->loadObjectList();
					
			$duplicateEmail = array();		
			
			foreach($rows AS $user) {
				if($user->email) {
					$content = $inhalt;
					if($this->vbparams->get('quickModeration')) {
						$content .= '<br />';
						switch($what) {
							case 'answer':	
								//Hash erstellen für Publish
								$params = "state=1\n"."answer=".$row->id;
								$hash = $this->general->generateHash('changePublishStateAnswer', $params, $user->id);
								$content .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Publish').'</a>';
								//Hash erstellen für Delete
								$params = 'answer='.$row->id;
								$hash = $this->general->generateHash('removeAnswer', $params, $user->id);
								$content .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Delete').'</a>';
								break;
							case 'comment':
								//Hash erstellen für Publish
								$params = "state=1\n"."comment=".$row->id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('changePublishStateComment', $params, $user->id);
								$content .= '<a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Publish').'</a>';
								//Hash erstellen für Delete
								$params = 'comment='.$row->id."\n"."box=".$box->id;
								$hash = $this->general->generateHash('removeComment', $params, $user->id);
								$content .= ' | <a href="'.JURI::root(false, "").$this->general->buildLink("task", $hash).'" target="_blank">'.JText::_('Delete').'</a>';
								break;
						}
					}
					if(!isset($duplicateEmail[$user->email]) AND $user->email != '') 
						$this->sendMail($betreff, $user->email, $content, true);	
					$duplicateEmail[$user->email] = true;
				}
			}
		}
	}
	
	function sendMail($betreff, $email, $inhalt, $isHTML) {
		 $mainframe = JFactory::getApplication();
		
		if (!$inhalt || !$betreff || !$email) return false;
		
		if($isHTML)
			$inhalt = '
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			  <title>'.$betreff.'</title>
			</head>
			<body>
			  '.$inhalt.'
			</body>
			</html>
			';
		
		$mailer =& JFactory::getMailer();
		$mailer->setSender(array($mainframe->getCfg('mailfrom'), $mainframe->getCfg('fromname')));
		$mailer->setSubject($betreff);
		$mailer->setBody($inhalt);
		$mailer->IsHTML($isHTML);
		
		// Add recipients
		$mailer->addRecipient($email);
		
		// Send the Mail
		$rs	= @$mailer->Send();
		
		// Check for an error
		if ( JError::isError($rs) ) {
			return false;
		} else {
			return true;
		}
	}
}//class
