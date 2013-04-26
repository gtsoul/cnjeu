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

class jVoteSystemViewComment extends JView
{
    /**
     * jVoteSystemList view display method
     * @return void
     **/
    function display($tpl = null)
    {        
		$aid = JRequest::getInt('aid', '');
		$bid = JRequest::getInt('bid', '');
		$section = JRequest::getString('object_group', '');
		//Variablen laden
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$this->general =& VBGeneral::getInstance();
		
		//Daten laden
		$item = $this->get('Data');
		
		$isNew = (!isset($item));
        $text = $isNew ? JText::_('New') : JText::_('Edit');
		
		//Toolbar
		JToolBarHelper::title(JText::_('Comment').': <small><small>[ '.$text.' ]</small></small>');
		JToolBarHelper::save();
		JToolBarHelper::apply();
        if($isNew) JToolBarHelper::cancel();
        else JToolBarHelper::cancel('cancel', JText::_('Close'));
		
		//Antworten holen
		$lists = new JObject();
		$lists->answers = $this->get('Answers');
		if($isNew) $lists->id = $aid;
		else $lists->id = $item->answer_id;
		
        //Daten übergeben
		$this->assignRef('editor'      	, $editor);
		$this->assignRef('bid', $bid);
		$this->assignRef('item'      	, $item);
		$this->assignRef('aid', $aid);
		$this->assignRef('lists', $lists);
		$this->assignRef('section', $section);

        parent::display($tpl);
    }//function

}//class
