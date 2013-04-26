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

jimport( 'joomla.plugin.plugin' );

require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'vote.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'user.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'answer.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'access.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'params.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'comment.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'general.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'mail.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'spam.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'charts.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'template.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'category.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'toolbar.class.php';
require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'log.class.php';

class VBLoader
{
	//Variablen
	private $db, $user, $document;
	
	function __construct($load) { if(!$load) return;
		$this->user =& VBUser::getInstance();
		$this->template =& VBTemplate::getInstance();
		$this->general =& VBGeneral::getInstance();
		
		$this->document = & JFactory::getDocument();
		$this->db = JFactory::getDBO();		
		//Sprache
		$this->loadLanguageFiles(true);
	}
	
	function &getInstance($load = true) {
		static $instance;
		if(empty($instance)) {
			$instance = new VBLoader($load);
		}
		return $instance;
	}
	
	function loadLanguageFiles($onlySite = false) {
		$jlang =& JFactory::getLanguage();
		//-- Load language files
		$jlang->load('com_jvotesystem', JPATH_SITE, 'en-GB', true);
		$jlang->load('com_jvotesystem', JPATH_SITE, null, true);
		$jlang->load('com_jvotesystem', JPATH_SITE.DS.'components'.DS.'com_jvotesystem', null, true);
		if($onlySite) return;
		//-- Load language files
		$jlang->load('com_jvotesystem', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_jvotesystem', JPATH_ADMINISTRATOR, null, true);
		$jlang->load('com_jvotesystem', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jvotesystem', null, true);
	}
	
	function getID($id, $view, $tmpl = null, $toolbar = true, $link = false) {
		//Laden..
		VBParams::getInstance($view, true);
		$this->vote =& VBVote::getInstance();
	
		$output = $this->vote->getVotebox($id, false, null, $link, $tmpl, $toolbar);
//		$output .= '<p style="text-align: center;font-size: 11pt; font-style: italic; text-align: center;"><a href="http://joomess.de/projects/jvotesystem">jVoteSystem</a> developed and designed by <a href="http://www.joomess.de">www.joomess.de</a>.</p>';
		/* The copyright information may not be removed or made invisible! To remove the code, please purchase a version on www.joomess.de. Thanks!*/
		return $output;
	}
	
}//class
