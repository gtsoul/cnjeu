<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5 - 2.5
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

class jVoteSystemViewBox extends JView
{
    
	private $access;
    function display($tpl = null)
    {        
		global $mainframe;
		
		VBParams::load();
		$this->general->load();
		VBTemplate::load();
		$this->access =& VBAccess::getInstance();
		
		//Variablen laden
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		
		//Daten laden
		$item = $this->get('Data');
		
		$isNew = (!isset($item));
        $text = $isNew ? JText::_('New') : JText::_('Edit');
		
		//Toolbar
		JToolBarHelper::title(JText::_('Box').': <small><small>[ '.$text.' ]</small></small>');
		JToolBarHelper::custom('goToAnswers', 'answers', 'forward', 'SAVE_AND_ANSWERS', false);
		JToolBarHelper::divider();
		JToolBarHelper::save();
		JToolBarHelper::apply();
        if($isNew) JToolBarHelper::cancel();
        else JToolBarHelper::cancel('cancel', JText::_('Close'));
		
		//Neue Datenstruktur erstellen
		if($isNew) {
			$iN = new JObject();
			$iN->id = null;
			$iN->title = '';
			$iN->question = '';
			$iN->object_group = 'com_jvotesystem';
			$iN->object_id = null;
			$iN->allowed_votes = 5;
			$iN->published = 1;
			$iN->access = 0;
			$iN->admin_access = 22;
			$iN->add_answer = 1;
			$iN->add_answer_access = 18;
			$iN->add_comment = 1;
			$iN->add_comment_access = 18;
			$date=JFactory::getDate();
			$iN->start_time = $date->toMySQL();
			$iN->end_time = '0000-00-00 00:00:00';
			$iN->send_mail_admin_answer = 1;
			$iN->send_mail_user_answer_comments = 1;
			$iN->send_mail_admin_comment = 1;
			$iN->send_mail_user_comment_comments = 1;
			$iN->activate_spam = 1;
			$iN->spam_count = 5;
			$iN->spam_mail_admin_report = 1;
			$iN->spam_mail_admin_ban = 1;
			$iN->activate_ranking = 0;
			$iN->answers_orderby = "votes";
			$iN->answers_orderby_direction = "DESC";
			$iN->max_votes_on_answer = 3;
			$iN->ranking_orderby = "votes";
			$iN->ranking_orderby_direction = "DESC";
			$iN->show_author = 1;
			$iN->template = "default";
			$iN->show_result = "always";
			$iN->show_result_after_date = "0000-00-00 00:00:00";
			$iN->show_thankyou_message = 0;
			$iN->goto_chart = 1;
			$iN->poll_access = 0;
			$iN->result_access = 0;
			
			$row = $iN;
		} else {
			$row = $item;
		}
		
		//Access
		$actions = array();
			//Show Poll
			$action = array();
			$action["title"] = JText::_('Show_poll');
			$action["name"] = "poll_access";
		$actions[] = $action;
			//Vote
			$action = array();
			$action["title"] = JText::_('Vote');
			$action["name"] = "access";
		$actions[] = $action;
			//Show Result
			$action = array();
			$action["title"] = JText::_('show_result');
			$action["name"] = "result_access";
		$actions[] = $action;
			//add_answer
			$action = array();
			$action["title"] = JText::_('add_answer');
			$action["name"] = "add_answer_access";
		$actions[] = $action;
			//add_comment
			$action = array();
			$action["title"] = JText::_('add_comment');
			$action["name"] = "add_comment_access";
		$actions[] = $action;
			//Vote
			$action = array();
			$action["title"] = JText::_('admin');
			$action["name"] = "admin_access";
		$actions[] = $action;
		
		$lists = new JObject();
		$lists->access = $this->access->getHtmlAccessLists($actions, $row);
		/*$lists->access = $this->access->getHtmlList('access', $row->access, $row->id); 
		$lists->add_answer = $this->access->getHtmlList('add_answer_access', $row->add_answer_access, $row->id); 
		$lists->add_comment = $this->access->getHtmlList('add_comment_access', $row->add_comment_access, $row->id); 
		$lists->admin_access = $this->access->getHtmlList('admin_access', $row->admin_access, $row->id); */

        //Daten weitergeben
		$this->assignRef('editor'      	, $editor);
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('item', $row);

        parent::display($tpl);
    }//function

}//class
