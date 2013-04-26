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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the jVoteSystem Component
 *
 * @package    jVoteSystem
 * @subpackage Views
 */
class jVoteSystemViewPoll extends JView
{
    /**
     * jVoteSystem view display method
     * @return void
     **/
    function display($tpl = null)
    {
		//Klassen laden
		$params =& VBParams::getInstance('poll', true);
		$general =& VBGeneral::getInstance();
		$mainframe = &JFactory::getApplication();
		$pathway   =& $mainframe->getPathway();
		
		$id = JRequest::getInt('bid', null);
		if($id == null) $id = JRequest::getInt('id', null);
		$alias = JRequest::getString("alias", "");
		
		$vote =& VBVote::getInstance();
		
		//Box laden
		$box = $vote->getBox($id, $alias);
		$page = null;
		//Falsche ID
		if(!$box) {
			JController::setRedirect($general->buildLink("list"), JText::_('NOBOXFOUNDORPUBLISHED'), 'error');
			JController::redirect();
			return false;
		}
		
		//Title setzen
		$doc =& JFactory::getDocument();
		$doc->setTitle($box->title);
		
		$pathway->addItem($box->title, $general->buildLink("poll", $box->id));
		
		//Metadaten
		$doc =& JFactory::getDocument();
		$headData = $doc->getHeadData();
		$headData["custom"][] = '<meta property="og:title" content="'.JText::_('Poll').': '.$box->title.'" />';
		$headData["custom"][] = '<meta property="og:url" content="'.JURI::base(false).'index.php?option=com_jvotesystem&view=box&bid='.$box->id.'" />';
		$headData["custom"][] = '<meta property="og:type" content="website" />';
		$headData["custom"][] = '<meta property="og:image" content="'.JURI::base(false).'components/com_jvotesystem/assets/images/icons/icon-100-jvotesystem.png'.'" />';
		$headData["custom"][] = '<meta property="og:description" content="'.$box->question.'" />';		
		$doc->setHeadData($headData);
		
		//Wenn Variable aid gesetzt, wird zu die angegebene Antwort geladen
		$aid = JRequest::getInt('aid', null);
		if($aid != null) {
			$vbanswer =& VBAnswer::getInstance();
			$page = $vbanswer->getAnswersPageCount($box, $aid);
			JUri::setFragment("vb".$box->id."answer".$aid);
		}
		
		$out = $vote->getVoteBox($box->id, false, $page, false);
//		$out .= '<p style="text-align: center;font-size: 11pt; font-style: italic; text-align: center;"><a href="http://joomess.de/projects/jvotesystem">jVoteSystem</a> developed and designed by <a href="http://www.joomess.de">www.joomess.de</a>.</p>';
		/* The copyright information may not be removed or made invisible! To remove the code, please purchase a version on www.joomess.de. Thanks!*/
		
		$this->assignRef('out', $out);
		
        parent::display($tpl);
    }//function

}//class

