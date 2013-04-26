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

class VBLog extends JObject
{
	//Construct
	private $db, $doc, $user, $logs, $date;
	
	function __construct() {
		parent::__construct();
	
		$this->doc = & JFactory::getDocument();
		$this->db =& JFactory::getDBO();
		$this->user =& JFactory::getUser();
		$this->date =& JFactory::getDate();
		$this->logs = array();
		
		$this->vsuser =& VBUser::getInstance();
		$this->vbparams =& VBParams::getInstance();
	}
	
	function &getInstance() {
		static $instance;
		if(empty($instance)) {
			$instance = new VBLog();
		}
		return $instance;
	}
	//Destruct
	function __destruct() {
		$dir = JPATH_SITE.DS."administrator".DS."components".DS."com_jvotesystem".DS."logs";
		//Save logs
		foreach($this->logs AS $type => $logs) {
			$path = $dir.DS.$this->date->toFormat("%m.%Y").".php";
			//Check file
			if(JFile::exists($path)) $content = JFile::read($path);
			else $content = '#<?php die("Forbidden."); ?>' . "\n"
						  . '#Date: '.$this->date->toMySQL().' UTC' . "\n"
						  . '#Software: jVoteSystem for Joomla by www.joomess.de' . "\n"
						  . "\n"
						  . '#Fields: type	date-time	uid	jvsuid		message						parameters';
			//Add Logs
			foreach($logs AS $log) {
				$content .= "\n[".$type."]\t".$this->date->toMySQL()."\t".$log["user"]."\t".$this->vsuser->id."\t".$log["msg"]."\t".$log["pars"];
			}
			//Write File
			JFile::write($path, $content);			
		}
	}
	
	function add($type, 	$message,	$pars = array(),	$user = null) {
		if($this->vbparams->get("logging") == 0) return;
		if($this->vbparams->get("logging") == 2 && $type != "ERROR") return;
		if(empty($this->logs[$type])) $this->logs[$type] = array();
		$this->logs[strtoupper($type)][] = array(	"msg"	=>	JText::_($message),
													"pars"	=>	json_encode($pars),
													"user"	=>	($user == null) ? $this->user->id : $user->id);
	}
	
}//class
