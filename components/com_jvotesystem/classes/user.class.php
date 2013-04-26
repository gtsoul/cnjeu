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

class VBUser
{
	//Variablen
	private $db, $loaded, $document, $try, $userData;
	
	public $name, $id, $gid, $jid, $rights, $blocked, $email;
	
	function __construct() {
		//Feste Variablen laden
		//$this->option = 'com_jvotesystem'; - Joomla 1.6 entfernt
		$this->document = & JFactory::getDocument();
		$this->db =& JFactory::getDBO();
		$this->vbparams =& VBParams::getInstance();
		$this->loaded = false;
		
		$this->name = JText::_('Anonym');
		$this->id = 0;
		$this->gid = 0;
		$this->jid = 0;
		$this->rights = 1;
		$this->blocked = 0;
		$this->email = "";
	}
	
	function &getInstance($create = false) {
		static $instance;
		if(empty($instance)) {
			$instance = new VBUser();
			$instance->loadUser($create);
		}
		if($create) $instance->loadUser(true);
		return $instance;
	}

    function loadUser($create = false) {
		if(!isset($this->try)) $this->try = false;
		if((!$this->loaded AND $this->try == false) OR (!$this->loaded AND $create == true)) {
			if(!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['REMOTE_ADDR']; else $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			$user = null;
			
			$date = new JDate();
			$jUser = JFactory::getUser();
			//Cookie laden, wenn vorhanden
			$cookie = $this->getCookie();
			//Session laden
			$jsession =& JFactory::getSession();
			$sessionid = $jsession->getId();
			//User nach jVS ID laden
			if($this->id != 0) {
				$sql = 'SELECT u.*, s.`id` AS session_id, s.`jsession_id`, s.`cookie`, s.`lastVisitDate`, s.`rights` '
				. ' FROM `#__jvotesystem_users` AS u LEFT JOIN  `#__jvotesystem_sessions` AS s ON( u.`id`=s.`user_id`)'
				. ' WHERE u.`id` = "'.$this->id.'" ORDER BY s.`lastVisitDate` DESC' ;
				$this->db->setQuery($sql);
				$user = $this->db->loadObject();
			}
			//User nach ID laden
			if(!isset($user) and $jUser->id != 0) {
				$sql = 'SELECT u.*, s.`id` AS session_id, s.`jsession_id`, s.`cookie`, s.`lastVisitDate`, s.`rights` '
					. ' FROM `#__jvotesystem_users` AS u LEFT JOIN  `#__jvotesystem_sessions` AS s ON( u.`id`=s.`user_id`)'
					. ' WHERE `jid` = "'.$jUser->id.'" ORDER BY s.`lastVisitDate` DESC' ;
				$this->db->setQuery($sql);
				$user = $this->db->loadObject();
			}
			//User hat Cookie
			if(!isset($user) and $cookie AND $this->vbparams->get("checkCookies")) {
				$sql = 'SELECT u.*, s.`id` AS session_id, s.`jsession_id`, s.`cookie`, s.`lastVisitDate`, s.`rights` '
					. ' FROM `#__jvotesystem_users` AS u LEFT JOIN  `#__jvotesystem_sessions` AS s ON( u.`id`=s.`user_id`)'
					. ' WHERE s.`cookie`='.$this->db->quote($cookie)
					. ' ORDER BY s.`lastVisitDate` DESC';
				$this->db->setQuery($sql);
				$user = $this->db->loadObject();
			}
			//User nach Session laden
			if(!isset($user)) {
				$sql = 'SELECT u.*, s.`id` AS session_id, s.`jsession_id`, s.`cookie`, s.`lastVisitDate`, s.`rights` '
				. ' FROM `#__jvotesystem_users` AS u LEFT JOIN  `#__jvotesystem_sessions` AS s ON( u.`id`=s.`user_id`)'
				. ' WHERE s.`jsession_id`='.$this->db->quote($sessionid)
				. ' ORDER BY s.`lastVisitDate` DESC';
				$this->db->setQuery($sql);
				$user = $this->db->loadObject();
			}
			//User hat IP
			if(!isset($user) and $ip AND $this->vbparams->get("checkIP")) {
				$sql = 'SELECT u.*, s.`id` AS session_id, s.`jsession_id`, s.`cookie`, s.`lastVisitDate`, s.`rights` '
					. ' FROM `#__jvotesystem_users` AS u LEFT JOIN  `#__jvotesystem_sessions` AS s ON( u.`id`=s.`user_id`)'
					. ' WHERE `ip`="'.$ip.'"'
					. ' ORDER BY s.`lastVisitDate` DESC';
				$this->db->setQuery($sql);
				$user = $this->db->loadObject();
			}
			if($jUser->id != 0) {
				if(isset($user->jid)) {
					if($user->jid != $jUser->id AND $user->jid != 0) unset($user);
				}
			}
			//Wenn nich vorhanden, neuen Nutzer erstellen
			if(!isset($user) and $create == true) {
				$user = new JObject();
				$user->id = null;
				if($jUser->id != 0) $user->jid = $jUser->id;
				$user->ip = $ip;
				$user->registered_time = $date->toMySQL();
				
				$this->db->insertObject('#__jvotesystem_users', $user);
				
				$id = $this->db->insertid();
				
				//Session für User generieren
				$session = new JObject();
				$session->cookie = $this->setNewCookie();
				$session->user_id = $id;
				$session->rights = 1;
				//$session->jsession_id = $sessionid;
				$session->lastVisitDate = $date->toMySQL();
				$this->db->insertObject('#__jvotesystem_sessions', $session);
				
				//Vorhandene Daten zurücksetzen
				unset($this->userData[$id]);
				$vote =& VBVote::getInstance();
				unset($vote->votesByUser);
				unset($vote->votesByAnswer);
				
				$this->try = false; 
				
				$this->id = $id;
				return $this->loadUser(false);
			} elseif(isset($user)) {
				//Wenn Cookie von anderem User & Session anders (sonst Ajax)
				if(($cookie != $user->cookie AND $user->jsession_id != $sessionid) || $cookie == null) {
					//Neue Session für User generieren
					$session = new JObject();
					$session->cookie = $this->setNewCookie();
					$session->user_id = $user->id;
					$session->rights = 0;
					$session->lastVisitDate = $date->toMySQL();
					//$session->jsession_id = $sessionid;
					$this->db->insertObject('#__jvotesystem_sessions', $session);
					
					if($this->db->getErrorMsg()) { echo $this->db->getErrorMsg(); return false; }
					return $this->loadUser(false);
				}
				//Wenn User-IP nicht aktuell
				if($user->ip != $ip) {
					$ins = new JObject();
					$ins->id = $user->id;
					$ins->ip = $ip;
					$this->db->updateObject('#__jvotesystem_users', $ins, 'id');
					if($this->db->getErrorMsg()) { echo $this->db->getErrorMsg(); return false; }
					return $this->loadUser(false);
				}
				//Wenn anonymer User nun bekannt
				if($jUser->id != 0 and $user->jid == 0) {
					$ins = new JObject();
					$ins->id = $user->id;
					$ins->jid = $jUser->id;
					$this->db->updateObject('#__jvotesystem_users', $ins, 'id');
					if($this->db->getErrorMsg()) { echo $this->db->getErrorMsg(); return false; }
					return $this->loadUser(false);
				}
				//Wenn User eindeutig -> alle Rechte geben
				if($user->rights == 0 AND $jUser->id != 0) {
					$session = new JObject();
					$session->id = $user->session_id;
					$session->rights = 1;
						
					$this->db->updateObject('#__jvotesystem_sessions', $session, 'id');
					if($this->db->getErrorMsg()) { echo $this->db->getErrorMsg(); return false; }
					return $this->loadUser(false);
				}
				//Session neu setzen
				if($user->jsession_id != $sessionid) {
					$session = new JObject();
					$session->id = $user->session_id;
					$session->jsession_id = $sessionid;
					
					$this->db->updateObject('#__jvotesystem_sessions', $session, 'id');
					if($this->db->getErrorMsg()) { echo $this->db->getErrorMsg(); return false; }
				}
				//Wenn letzter Besuch mehr als 7 Tagen zurückliegt, Rechte nehmen
				$lastVisitDate = JFactory::getDate($user->lastVisitDate)->toUnix(); $curDate = JFactory::getDate()->toUnix();
				if(($lastVisitDate + 7*24*60*60) < $curDate AND $user->rights == 1) {
					$session = new JObject();
					$session->id = $user->session_id;
					$session->rights = 0;
					
					$this->db->updateObject('#__jvotesystem_sessions', $session, 'id');
					if($this->db->getErrorMsg()) { echo $this->db->getErrorMsg(); return false; }
					return $this->loadUser(false);
				}
				//LastVisitDate eintragen
				if($user->lastVisitDate != $date->toMySQL()) {
					$session = new JObject();
					$session->id = $user->session_id;
					$session->lastVisitDate = $date->toMySQL();
					$user->lastVisitDate = $session->lastVisitDate;
					
					$this->db->updateObject('#__jvotesystem_sessions', $session, 'id');
					if($this->db->getErrorMsg()) { echo $this->db->getErrorMsg(); return false; }
				}
			} else $this->try = true;
			if(isset($user)) {
				//Daten an Klassenobjekt anhängen
				foreach($user AS $key => $value) {
					$this->$key = $value;
				}
				$this->loaded = true;
			}
		}
	}
	
	function setNewCookie() {		
		$cookie = 'jVoteSystemUser';
		$value = md5(uniqid(rand(),true));
		@setcookie($cookie, $value, time() + 24*60*60*30);
		$_COOKIE[$cookie] = $value;
		return $value;
	}
	
	function getCookie() {
		$cookie = 'jVoteSystemUser';
		if(isset($_COOKIE[$cookie]))
			return $_COOKIE[$cookie];
		else null;
	}
	
	public function getUserData($id, $new = false) {
		if(empty($this->userData)) $this->userData = array();
		
		if(empty($this->userData[$id]) OR $new == true) {
			//User laden
			
				$sql = 'SELECT u.*, name, username, ju.email AS jemail, s.`rights`,';
				if(version_compare( JVERSION, '1.6.0', 'lt' )) $sql .= "gid, ";
				$sql .= 'block'
				. ' FROM `#__jvotesystem_users` AS u LEFT JOIN  `#__jvotesystem_sessions` AS s ON( u.`id`=s.`user_id`) '
				. ' LEFT JOIN `#__users` AS ju ON (ju.`id`=u.`jid`)'
				. ' WHERE u.`id`="'.$id.'" ORDER BY s.`lastVisitDate` DESC'; 
				$this->db->setQuery($sql);
				$user = $this->db->loadObject();
			if(!$user) {
				$user->name = JText::_('Anonym');
				$user->id = 0;
				$user->gid = 0;
				$user->jid = 0;
			} elseif($user->jid == 0) {
				$user->name = JText::_('Anonym');
				$user->gid = 0;
			} elseif($user) {
				if($this->vbparams->get('displayName') == 'username') {
					$user->name = $user->username;
				}
			}
			
			$this->userData[$id] = $user;
		}
		return $this->userData[$id];
	}
	
	private $userStats;
	function getUserStats($id) {
		if(empty($this->userStats)) $this->userStats = array();
		if(empty($this->userStats[$id])) {
			$sql = "SELECT votes, answers, polls, comments FROM
					(SELECT IFNULL(SUM(votes), 0) AS votes FROM `#__jvotesystem_votes` WHERE `user_id`=".$this->db->quote($id).") v,
					(SELECT COUNT(*) AS answers FROM `#__jvotesystem_answers` WHERE `published`=1 AND `autor_id`=".$this->db->quote($id).") a,
					(SELECT COUNT(*) AS polls FROM `#__jvotesystem_boxes` WHERE `published`=1 AND `autor_id`=".$this->db->quote($id).") b ,
					(SELECT COUNT(*) AS comments FROM `#__jvotesystem_comments` WHERE `published`=1 AND `autor_id`=".$this->db->quote($id).") c ";
			$this->db->setQuery($sql);
			$this->userStats[$id] = $this->db->loadObject();
		}
		return $this->userStats[$id];
	}

	function loadUserListVotedOnAnswer($answer) {
		$sql = "SELECT SUM(`votes`) AS voted, u.`id` AS vsid, ju.id, ju.name, ju.username, IF(ju.`id` IS NULL, 0, 1) AS userFirst, `voted_time`, COUNT(v.`user_id`) AS users\n"
			. "FROM `#__jvotesystem_votes` AS v\n"
			. "LEFT JOIN `#__jvotesystem_users` AS u ON (v.`user_id`=u.`id` AND u.`blocked`=0)\n"
			. "LEFT JOIN `#__users` AS ju ON (u.`jid`=ju.`id`)\n"
			. "WHERE v.`answer_id`='".$answer->id."'\n"
			. "AND v.`votes`>0\n"
			. "GROUP BY(ju.`id`)\n"
			. "ORDER BY userFirst DESC, voted DESC, voted_time ASC";
		$this->db->setQuery($sql);
		$data = $this->db->loadObjectList();
		
		$template =& VBTemplate::getInstance();
		$general =& VBGeneral::getInstance();
		//Tabelle ausgeben
		$out = '';
			foreach($data AS $row) {
				$par = new JObject();
					$par->count = $row->voted;
					if($row->id == null) {
						$par->user = sprintf(JText::_('%o unregistrierte(r) Benutzer'), $row->users);
					} elseif($this->vbparams->get('displayName') == 'username') {
						$par->user = $row->username;
					} else {
						$par->user = $row->name;
					}
					$par->date = JText::_('Last_vote').' '.sprintf(JText::_("TIME_AGO"), $general->convertTime($row->voted_time)).'.';
					$par->avatar = $this->getAvatar($row->vsid, 40, $row->id != null);
					
				$out .= $template->loadTemplate("votedlist", $par);
			}

		return $out;
	}
	
	function getAvatar($id, $height = 50, $tooltip = true) {
		$general =& VBGeneral::getInstance();
		
		$com = $this->vbparams->get("com_avatar");
		$found = true;
		$html = array();
		
		$html[] = '<div class="jvs_avatar thumb'.$height.'"';
		if($tooltip) $html[] =' data-u="'.$id.'"';
		$html[] ='>';
		
		$user = $this->getUserData($id);
		$baseurl=JURI::base();
		
		$jid = $user->jid;
		
		switch($com) {
			case "cb":
				$this->db->setQuery("SELECT avatar FROM #__comprofiler WHERE user_id = $jid AND avatarapproved = '1'");
				$avatar = $this->db->loadResult();
				
				if (!empty($avatar)) {
					if(is_array($avatar))
					$avatar=$avatar[0];
					// Replace username with profile pic in content with link to user profile. Alt=username, Title=username, float=left. Margins are left: 0px, top: 5px, bottom: 5px, right: 5px.
					$html[] = "<a href=\"".$general->buildLink("user", $id)."\"><img src=\"{$baseurl}images/comprofiler/tn$avatar\" / alt=\"$user->name\" title=\"$user->name\"></a>";
				} else $found = false;
				break;
			case "jomsocial":
				$jspath = JPATH_ROOT.DS.'components'.DS.'com_community';
				@include_once($jspath.DS.'libraries'.DS.'core.php');
				// Get CUser object
				if(class_exists("CFactory")) {
					$user = CFactory::getUser($jid);
					$avatarUrl = $user->getThumbAvatar();
					$html[] = "<a href=\"".$general->buildLink("user", $id)."\"><img src=\"{$avatarUrl}\" / alt=\"$user->name\" title=\"$user->name\"></a>";
				} else $found =false;
				break;
			case "kunena":
				$path = 'media/kunena/avatars/resized/size72/users/avatar'.$jid.'.jpg';
				if(JFile::exists(JPATH_SITE.DS.str_replace("/", DS,$path))) {
					$html[] = "<a href=\"".$general->buildLink("user", $id)."\"><img src=\"".$baseurl.$path."\" / alt=\"$user->name\" title=\"$user->name\"></a>";
				} else $found = false;
				break;
			default:
				$found = false;
				break;
		}
		
		//Standard-Bild
		if(!$found) {
			$html[] = "<img src=\"{$baseurl}components/com_jvotesystem/assets/images/standard-profile.png\" / alt=\"".$user->name."\" title=\"".$user->name."\">";
		}
		
		$html[] = '</div>';
		
		return implode("", $html);
	}
}//class
