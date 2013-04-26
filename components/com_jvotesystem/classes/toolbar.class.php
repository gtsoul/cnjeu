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

class VBToolbar
{
	//Construct
	private $db, $doc, $user, $access, $cat, $poll, $addInfo, $objects;
	
	function __construct($cat, $poll = null) {
		$this->doc = & JFactory::getDocument();
		$this->db =& JFactory::getDBO();
		$this->access =& VBAccess::getInstance();
		$this->general =& VBGeneral::getInstance();
		$this->user =& VBUser::getInstance();
		
		$this->cat = $cat;
		$this->poll = $poll;
		
		$this->addInfo = false;
		$this->objects = array();
	}
	
	function add() {
		if($this->access->add($this->cat)) {
			$this->objects[] = array(
											"button"=>	"add",
											"title"	=>	JText::_("ADD_NEW_POLL"),
											"type"	=>	"assistant",
											"par"	=>	"&catid=".$this->cat->id,
											"class"	=>	"addpoll"
			);
		}
	}
	
	function edit() {
		if($this->access->edit($this->cat, $this->poll)) {
			$this->objects[] = array(
												"button"=>	"edit",
												"title"	=>	JText::_("EDIT_POLL"),
												"type"	=>	"assistant",
												"par"	=>	"&id=".$this->poll->id,
												"class"	=>	"editpoll"
			);
		}
	}
	
	function remove() {
		if($this->access->remove($this->cat, $this->poll)) {
			$this->objects[] = array(
												"button"=>	"remove",
												"title"	=>	JText::_("REMOVE_POLL"),
												"js"	=>	"jVS.removePoll(".$this->poll->id.");",
												"par"	=>	"&id=".$this->poll->id,
												"class"	=>	"removepoll"
			);
		}
	}
	
	function info() {
		if($this->poll != null && VBParams::getInstance()->get("showToolbar")) $this->addInfo = true;
	}
	
	function out($style = "overflow: auto; margin: 0;") { 
		$html = array();
		if(!empty($this->objects) || $this->addInfo) {
			$this->doc->addStyleSheet(JURI::base(true).'/components/com_jvotesystem/assets/css/toolbar.css');
			$html[] = '<div id="jvotesystem" style="'.$style.'" data-linkedto="'.@$this->poll->id.'">';
				//Infobar
				if($this->addInfo) {
					$html[] = '<div class="infobar">';
						//Category
						$html[] = '<a class="toolbarlink category-icon" data-cid="'.$this->cat->id.'" href="'.$this->general->buildLink("category", $this->cat->id).'">'.$this->cat->title.'</a>';
						//Author
						$html[] = '<a class="toolbarlink author-icon" data-u="'.$this->poll->autor_id.'" href="'.$this->general->buildLink("user", $this->poll->autor_id).'">'.$this->user->getUserData($this->poll->autor_id)->name.'</a>';
						//Author
						$html[] = '<span class="toolbarlink clock-icon">'.sprintf(JText::_("TIME_AGO"), $this->general->convertTime($this->poll->created)).'</span>';
					$html[] = '</div>';
				}
				//Smalltoolbar
				$html[] = '<div class="smalltoolbar">';
				foreach($this->objects AS $object) {
					if(!isset($this->blocked[$object["button"]])) {
						if(@$object["type"] == "assistant") {
							JHTML::_( 'behavior.modal' );
							$this->doc->addScript(JURI::base(true).'/components/com_jvotesystem/assets/js/jvotesystem.min.js');
							$link = JUri::root(true)."/components/com_jvotesystem/assistant/index.php?interface=site&view=poll".@$object["par"];
							$onclick = "jVS.loadSqueezebox(this, '$link');return false;";
						} elseif(isset($object["link"])) {
							$link = $object["link"];
							$onclick = "";
						} else {
							$link = "#";
							$onclick = $object["js"].'return false;';
						}
						$html[] = '<a class="toolbarbutton '.$object["class"].'" href="'.$link.'" onclick="'.$onclick.'">'.$object["title"].'</a>';
					}
				}
				$html[] = '</div>';
			$html[] = '</div>';
		}
	
		return implode("\n", $html);
	}
}//class
