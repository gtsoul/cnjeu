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

class VBGeneral
{
	static $db, $count, $spacer;

	function __construct($load) { if(!$load) return false;
		$this->db =& JFactory::getDBO();
		$this->count = 0;
		$this->spacer = ' ';
		$this->document = & JFactory::getDocument();
		$this->vbparams =& VBParams::getInstance();
		$this->BBCodeToolbar = '';
	}
	
	function &getInstance($load = true) {
		static $instance;
		if(empty($instance)) {
			$instance = new VBGeneral($load);
		}
		return $instance;
	}

	function shortText($s, $max, $onclick = true) {
		$this->spacer = '[(LZ)]';
		$this->count = $max;
		$s = $this->BBCode($s);
	
		$short = (strlen($s) > ($this->count+1));
	
		$whitespaceposition = @strpos($s," ",$this->count)-1;
		if($short AND $whitespaceposition > 0) {
			$newS = substr($s, 0, ($whitespaceposition+1));
		} else $newS = $s;
		
		//Leerzeichen von BBCode wieder ersetzen
		$s = str_ireplace($this->spacer, ' ', $s);
		$newS = str_ireplace($this->spacer, ' ', $newS);
		//->keine HTML-Tags zerstört...
		
		if($newS == '' OR trim($newS) == trim($s)) {
			$newS = $s;
			$short = false;
		}
		
		if($short) {
			if($onclick == true) {
				$s = substr($s, strlen($newS) + 1, strlen($s));
				$o = $newS;
				$o .= ' <a class="showMoreText">['.JText::_('More').'...]';
				$o .= '<span style="display:none;">'.urlencode(nl2br($s)).'</span>';
				$o .= '</a>';
				//$o .= '<noscript>'.$s.'</noscript>';
			} else {
				$o = $newS;
				$o .= '...';
			}
			return trim($o);
		}
		
		return trim($newS);
	}
	
	private $bbcodes;
	function BBCode($s, $newSpacer = null) {
		if(!isset($this->spacer)) $this->spacer = ' ';
		if($newSpacer != null) $this->spacer = $newSpacer;
		
		if(!isset($this->bbcodes)) {
			$sql = "SELECT * FROM `#__jvotesystem_bbcodes` ";
			$this->db->setQuery($sql);
			$this->bbcodes = $this->db->loadObjectList();
		}
		
		foreach($this->bbcodes AS $bc) {
			$s = $this->replaceBBCode($s, $bc->published, $bc->regex, $bc->replace, $bc->replaceNot);
		}
		
		return $s;
	}
	
	function getBBCodeToolbar($divID, $textfieldID, $hidden = true, $path = null) {
		if($hidden == false) $style = "display: block;"; else $style = 'display: none;';
		if($path == null) $path = JURI::root(true);
	
		$out = '<div id="'.$divID.'" class="bbcodeToolbar" style="width:100%;'.$style.'">';
		
		$sql = "SELECT * FROM `#__jvotesystem_bbcodes` WHERE `withButton`=1 AND `buttonImage`!=''";
		$this->db->setQuery($sql);
		$bbcodes = $this->db->loadObjectList();
		
		if($textfieldID != "this") $textfieldID = "'".$textfieldID."'";
		
		foreach($bbcodes AS $bc) {
			$testReplace = explode("$1", $bc->replace);
			if(count($testReplace) <= 1) {
				$js = "jVoteSystemInsertCode($textfieldID, '".$bc->editorCode."'); ";
			} else {
				$js = "jVoteSystemInsertBBCode($textfieldID,'".$bc->editorCode."', '".$bc->buttonInfo."');";
			}
			
			$out .= '<img onMouseDown="jVoteBoxStopReset = true;" src="'.$path.'/components/com_jvotesystem/assets/images/bbcode/'.$bc->buttonImage.'" class="bbcodeIcon" onclick="'.$js.'" alt="'.$bc->name.'" title="'.$bc->name.'" />';
		}
		
		$out .= '</div>';
		return $out;
	}
	var $BBCodeToolbar;
	function getBBCodeToolbar2($hidden = null) {
		$path = JURI::root(true);
		if ($hidden !== null) $hidden = "display:none;";
		if (empty($this->BBCodeToolbar)) {
			$sql = "SELECT * FROM `#__jvotesystem_bbcodes` WHERE `withButton`=1 AND `buttonImage`!=''";
			$this->db->setQuery($sql);
			$bbcodes = $this->db->loadObjectList();
			
			foreach($bbcodes AS $bc) {
				$testReplace = explode("$1", $bc->replace);
				if(count($testReplace) <= 1) {
					$js = 'data-insert="'.$bc->editorCode.'"';
				} else {
					$js = 'data-bbcode='.$bc->editorCode.' data-bbinfo="'.$bc->buttonInfo.'"';
				}
				
				$this->BBCodeToolbar .= '<img src="'.$path.'/components/com_jvotesystem/assets/images/bbcode/'.$bc->buttonImage.'" title="'.$bc->name.'" '.$js.' />';
			}
		}
		
		return '<div class="bbcodeToolbar" style="'.$hidden.'width:100%;">'. $this->BBCodeToolbar .'</div>';
	}
	
	private function replaceBBCode($s, $published, $regex, $replace, $replaceNot = "") {
		$replace = str_replace("{bbCodeImagePath}", JURI::base(true).'/components/com_jvotesystem/assets/images/bbcode', $replace);
	
		$testReplace = explode("$1", $replace);
		if(count($testReplace) <= 1) {
			if($published AND $this->vbparams->get('activate_bbcode')) {
				$replace = str_replace(' ', $this->spacer, $replace);
				
				$s = str_replace($regex, $replace, $s, $count);
				$this->count += strlen($replace)*$count;
			}
		} else {		
			preg_match_all($regex, $s, $matches);
			if(!$matches[0]) return $s;
			$mI = 0;
			
			$replacements = array();
			
			if($published AND $this->vbparams->get('activate_bbcode')) {
				foreach($matches AS $gap) {
					if($gap != $matches[0]) {
						$i = 0;
						foreach($gap AS $match) {
							$match = str_replace(' ', $this->spacer, $match);
							//$match = str_replace("\n", '', $match);
							if(!isset($replacements[$i])) $replacements[$i] = str_replace(' ', $this->spacer, $replace);
							$replacements[$i] = str_replace('$'.($mI), $match, $replacements[$i]);
							$i++;
						}
					}
					$mI++;
				}
			} elseif($published OR $this->vbparams->get('general_published_bbcode')) {
				$i = 0;
				foreach($matches[0] AS $match) {
					$replacements[$i] = ($replaceNot != "") ? $replaceNot : $matches[1][$i];
					$i++;
				}
			} else {
				$i = 0;
				foreach($matches[0] AS $match) {
					$replacements[$i] = $matches[1][$i];
					$i++;
				}
			}
			
			$i = 0;
			foreach($matches[0] AS $match) {
				$s = str_replace($match, $replacements[$i].' ', $s, $count);
				$this->count += strlen($replace)*$count;
				$i++;
			}
		}
		return $s;
	}
	
	function generateHash($task, $params, $uid) {
		$date = new JDate();
	
		$ins = new JObject();
		$ins->id = null;
		$ins->task = $task;
		$ins->params = $params;
		$ins->user_id = $uid;
		$ins->hash = md5(uniqid(rand()));
		$ins->created = $date->toMySQL();
		
		$this->db->insertObject('#__jvotesystem_tasks', $ins);
		
		return $ins->hash;
	}
	
	function getTask($hash) {
		$sql = 'SELECT * FROM `#__jvotesystem_tasks` WHERE `hash`="'.$hash.'" ORDER BY `created` DESC';
		$this->db->setQuery($sql);
		$task = $this->db->loadObject();
		
		if(!empty($task)) {
			$splits = explode("\n", $task->params);
			$task->params = array();
			foreach($splits AS $param) {
				$pSplits = explode('=', $param);
				$task->params[$pSplits[0]] = $pSplits[1];
			}
			
			return $task;
		}
		return null;
	}
	
	function unactivateTask($id) {
		$upd->id = $id;
		$upd->active = 0;
		
		$this->db->updateObject('#__jvotesystem_tasks', $upd, 'id');
	}
	
	function getAdminFooter() {
		?>
		<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 10px;"><tr><td>
			<table class="adminlist"><tr><td style="text-align: center;">
				<a href="http://joomess.de/projekte/18-jvotesystem">jVoteSystem</a> developed and designed by <a href="http://www.joomess.de">www.joomess.de</a>.
			</td></tr></table>
		</td></tr></table>
		<?php
	}
	
	/*DB - orderBy*/
	function getSqlOrderBy($set, $dir) {
		switch($set) {
			default:
			case "votes":
				$order_by = ' ORDER BY votes '.$dir.', lastvote ASC, created ASC'; 
				break;
			case "id":
				$order_by = ' ORDER BY `id` '.$dir.', lastvote ASC, created ASC'; 
				break;
			case "name":
				$order_by = ' ORDER BY `answer` '.$dir.', lastvote ASC, created ASC'; 
				break;
			case "created":
				$order_by = ' ORDER BY `created` '.$dir.', lastvote ASC'; 
				break;
		}
		return $order_by;
	}

	function VDTS ($var)
	{
		ob_start();
		var_dump($var);
		$result = ob_get_clean();
		error_log($result);
		return;
	}
	
	function flatten($array,$keys = null) {
		$out = array();
		if ( $keys == null ) {
			$keys = array('key','value');
		}
		foreach($array AS $a) {
			$out[$a[$keys[0]]]=$a[$keys[1]];
		}
		return $out;
	}
	
	private $menuIds;
	private function getMenuItem($link) {
		//if(empty($this->menuIds)) $this->menuIds = array();
		if(empty($this->menuIds)) {
			$lang =& JFactory::getLanguage();
			if(version_compare( JVERSION, '1.6.0', 'lt' )) {
				$sql = 'SELECT link,id FROM `#__menu` WHERE LEFT(link,length("index.php?option=com_jvotesystem")) = "index.php?option=com_jvotesystem" AND published = 1;';
				//TODO Joomla 1.5
			} else {
				$sql = 'SELECT link,id FROM `#__menu` WHERE LEFT(link,length("index.php?option=com_jvotesystem")) = "index.php?option=com_jvotesystem" AND published = 1 AND (language = "*" OR language = "'.$lang->getTag().'");';
				$this->db->setQuery($sql);
				//$this->menuIds = $this->db->loadAssocList('link');
			}
			
		}
		return isset($this->menuIds[$link]) ? $this->menuIds[$link]['id'] : null;
	}
	
	private $pollMenuData;
	private function getPollMenuData($id) {
		if(empty($this->pollMenuData)) $this->pollMenuData = array();
		if(empty($this->pollMenuData[$id])) {
			$sql = 'SELECT `catid`, `alias` FROM `#__jvotesystem_boxes` WHERE `id` = '.$id;
			$this->db->setQuery($sql); 
			$this->pollMenuData[$id] = $this->db->loadObject();
		}
		return $this->pollMenuData[$id];
	}
	
	function buildLink($view = "", $id = null, $task = "", $pars = array()) {
		$link = "index.php?option=com_jvotesystem";
		$category =& VBCategory::getInstance();
		$user =& VBUser::getInstance();
		
		switch($view) {
			case "list":
				$params = $this->vbparams->getActiveMenuParams();
				
				if($this->getMenuItem($link."&view=polls")) $link = "index.php?Itemid=".$this->getMenuItem($link."&view=polls");
				else $link .= "&view=polls";
				//Filter
				if(isset($pars["cat"]) AND @$pars["cat"] != $params->get("cat", "all"))$link .= "&cat=".$pars["cat"];
				elseif(isset($pars["cid"]) AND @$pars["cid"] != $category->getCatIdByAlias($params->get("cat", "all"))) $link .= "&cat=".$category->getCategory($pars["cid"])->alias;
				
				if(isset($pars["order"]) AND @$pars["order"] != $params->get("order", "most-voted")) $link .= "&order=".$pars["order"];
				if(isset($pars["time"]) AND @$pars["time"] != $params->get("time", "all-time")) $link .= "&time=".$pars["time"];
				if(isset($pars["keyword"]) AND @$pars["keyword"] != "") $link .= "&keyword=".$pars["keyword"];
				if(isset($pars["page"]) AND @$pars["page"] != 1) $link .= "&page=".$pars["page"];
				
				return JRoute::_($link)."#jvotesystem";
				break;
			case "category":
				$cat = $category->getCategory($id);
				
				if($this->getMenuItem($link."&view=polls")) $link = "index.php?Itemid=".$this->getMenuItem($link."&view=polls")."&cat=".$cat->alias;
				else $link .= "&view=polls&cat=".$cat->alias;
				
				break;
			case "poll":
				$poll = $this->getPollMenuData($id);
				$cat = $category->getCategory($poll->catid);
				switch($task) {
					//Links für No-JS Support
					case "vote": case "changePublishStateAnswer": case "reportAnswer": case "removeAnswer":
						$link .= "&view=ajax&task=".$task."&box=".$id;
						$link .= '&ref='.base64_encode(JRequest::getURI());
						
						$session =& JFactory::getSession();
						if(version_compare( JVERSION, '1.6.0', 'lt' ))
							$link .= "&".JUtility::getToken()."=1";
						else
							$link .= "&".$session->getFormToken()."=1";
						break;
					default:
						if($this->getMenuItem($link."&view=poll&id=".$id)) $link = "index.php?Itemid=".$this->getMenuItem($link."&view=poll&id=".$id);
						elseif($this->getMenuItem($link."&view=polls")) $link = "index.php?Itemid=".$this->getMenuItem($link."&view=polls")."&cat=".$cat->alias."&alias=".$poll->alias;
						else $link .= "&view=poll&alias=".$poll->alias;
						break;
				}
				break;
			case "user":
				$com = $this->vbparams->get("com_profile");
				$curuser = $user->getUserData($id);
				
				if($curuser->jid == 0) return "#";
				
				switch($com) {
					case "cb":
						$link = "index.php?option=com_comprofiler&task=userProfile&user=".$curuser->jid;
						break;
					case "jomsocial":
						$jspath = JPATH_ROOT.DS.'components'.DS.'com_community';
						@include_once($jspath.DS.'libraries'.DS.'core.php');
						// Get CUser object
						if(class_exists("CFactory"))
							return CRoute::_('index.php?option=com_community&view=profile&userid='.$curuser->jid);
						break;
					case "kunena":
						$link = 'index.php?option=com_kunena&view=profile&userid='.$curuser->jid;
						break;
					default:
						return "#";
					break;
				}
				break;
			case "task":
				$link .= "&view=tasks&hash=".$id;
				break;
		}
		
		foreach($pars AS $key => $par) {
			$link .= "&".$key."=".$par;	
		}
		return JRoute::_($link);
	}
	
	function convertTime($date) {
		$app =& JFactory::getApplication();
		$curDate = JFactory::getDate(null, $app->getCfg('offset'));
		$date = JFactory::getDate($date, $app->getCfg('offset'));
		
		$tsCur = $curDate->toUnix();
		$ts = $date->toUnix();
		
		$diff = $tsCur - $ts;
		
		if($diff < 60) {
			$count = $diff;
			$text = "Seconds";
		} elseif($diff < 60*60) {
			$count = round($diff/60);
			$text = "Minutes";
		} elseif($diff < 60*60*24) {
			$count = round($diff/60/60);
			$text = "Hours";
		} elseif($diff < 60*60*24*7) {
			$count = floor($diff/60/60/24);
			$text = "Days";
		} elseif($diff < 60*60*24*30) {
			$count = floor($diff/60/60/24/7);
			$text = "Weeks";
		} elseif($diff < 60*60*24*365) {
			$count = floor($diff/60/60/24/30);
			$text = "Months";
		} else {
			$count = floor($diff/60/60/24/365);
			$text = "Years";
		}
		
		return '<span title="'.$date->toFormat("%d.%B %Y, %H:%M").'" class="update_timestamp" data-time="'.$ts.'">'.$count." ".JText::_(($count == 1) ? substr($text, 0, strlen($text)-1) : $text).'</span>';
	}
	
	function cleanStr($str, $short = 25) {
		return substr(strtolower(str_replace("STRIPE", "-", preg_replace("/[^a-zA-Z0-9]/", "", str_replace(" ", "STRIPE",str_replace("-", "STRIPE",trim($str)))))), 0, $short);
	}
	
	private $aliases;
	function checkAlias($str, $type = "boxes") {
		//Aliase abrufen
		if(empty($this->aliases)) $this->aliases = array();
		if(empty($this->aliases[$type])) {
			$sql = "SELECT `alias` FROM `#__jvotesystem_$type` ";
			$this->db->setQuery($sql);
			$this->aliases[$type] = $this->db->loadObjectList("alias");
		}
		
		$newStr = $str;
		$i = 2;
		while(isset($this->aliases[$type][$newStr])) {
			$newStr = $str."-".$i;
			$i++;
		}
		return $newStr;
	}
}//class
