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
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.application.component.helper' );

class VBTemplate
{
	var $db, $document, $template, $root, $demo_mode;

	function __construct() {
		$this->db =& JFactory::getDBO();
		$this->document = & JFactory::getDocument();
		if(!isset($this->template)) $this->template = 'default'; 
		$this->root = JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'templates';
		$this->demo_mode = false;
		$this->vbparams =& VBParams::getInstance();
	}
	
	function &getInstance() {
		static $instance;
		if(empty($instance)) {
			$instance = new VBTemplate();
		}
		return $instance;
	}
	
	function setTemplate($newTemplate, $demo = false) {
		$this->template = $newTemplate;
		$this->demo_mode = $demo;
		
		//make sure jQuery is loaded first!
		if($this->vbparams->get('load_jquery')) {
			$this->document->addScript(JURI::base( true ).'/components/com_jvotesystem/assets/js/jquery-1.7.1.min.noconflict.js');
		}
		
		//Load Files
		if($this->vbparams->get("load_domwrite") && $this->vbparams->get("adsense")) $this->document->addScript(JURI::base( true ).'/components/com_jvotesystem/assets/js/domWrite.js');
		
		$this->document->addScript(JURI::base( true ).'/components/com_jvotesystem/assets/js/jvotesystem.min.js');
		
		$this->document->addStyleSheet(JURI::base( true )."/components/com_jvotesystem/templates/assets/css/".$this->template."/default.css");
		if(!JFile::exists($this->root.DS.'assets'.DS.'css'.DS.$this->template.DS.'default.css'))
			$this->document->addStyleSheet(JURI::base( true )."/components/com_jvotesystem/templates/assets/css/default/default.css");
		if(JFile::exists($this->root.DS.'assets'.DS.'js'.DS.$this->template.DS.'default.js'))
			$this->document->addScript(JURI::base( true )."/components/com_jvotesystem/templates/assets/js/".$this->template."/default.js");
	}

	function getTemplate() {
		return $this->template;
	}
	
	function getEngine() {//kleine Hilfsfunktion, um festzustellen, ob neues, oder altes JS/HTML verwendet werden soll. Vllt kann man das auch irgendwie besser lösen...
		if ($this->template == "modern" || $this->template == "module") return true;
		else return false;
	}
	
	var $templates;
	function getTemplates() {
		if(!isset($this->templates)) {
			$this->templates = array();
			
			//Stammordner auslesen
			$i = 0;
			foreach(JFolder::folders($this->root.DS.'elements'.DS.'main') AS $folder) { //Nur vorzeitig TopBox
				$this->templates[$i] = array();
				$this->templates[$i]["name"] = $folder;
				$this->templates[$i]["id"] = $folder;
				
				$i++;
			}
		}
		
		return $this->templates;
	}
	
	var $viewPaths;
	function loadTemplate($view, $par = null, $onlyLoad = false) {
		if(!isset($this->viewPaths)) $this->viewPaths = array();
		if(!isset($this->viewPaths[$this->template])) $this->viewPaths[$this->template] = array();
		if(!isset($this->viewPaths[$this->template][$view])) $this->viewPaths[$this->template][$view] = "";
		
		if($this->viewPaths[$this->template][$view] == "") {
			$pathSelectedTemplate = $this->root.DS.'elements'.DS.$view.DS.$this->template;
			
			//Templatedatei überprüfen
			$pathPHP = $pathSelectedTemplate;
			if(!JFile::exists($pathSelectedTemplate.DS.'default.php')) {
				//Standard-Template laden
				$pathPHP = $this->root.DS.'elements'.DS.$view.DS.'default';
			}
			
			//Path speichern
			$this->viewPaths[$this->template][$view] = $pathPHP;
		}
		
		if($onlyLoad == true) return null;
		
		//Puffer leeren und vorbereiten..
		$old = ob_get_contents();
		ob_clean();
		
		//Datei laden
		require $this->viewPaths[$this->template][$view].DS.'default.php';
		
		//Puffer zurückgeben
		$out = ob_get_contents();
		ob_clean();
		echo $old;
		return $out;
	}
	
	function getImageSrc($section, $name) {//deprecated
		//Bild-Datei überprüfen
		$path = $this->root.DS.'assets'.DS."images".DS.$this->template;
		if($section != "") $path .= DS.$section;
		if(!JFile::exists($path.DS.$name)) {
			//Standard-Bild laden
			$src = JUri::root(true).'/components/com_jvotesystem/templates/assets/images/default/';
		} else {
			$src = JUri::root(true).'/components/com_jvotesystem/templates/assets/images/'.$this->template.'/';
		}
		
		if($section != "") $src .= $section.'/';
		return $src.$name;
	}
}//class
