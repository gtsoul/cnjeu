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
class jVoteSystemViewPolls extends JView
{
    /**
     * jVoteSystem view display method
     * @return void
     **/
    function display($tpl = null)
    {
		//Klassen laden
		$this->vbparams =& VBParams::getInstance('pollslist', true);
		$this->vote =& VBVote::getInstance();
		$this->user =& VBUser::getInstance();
		$this->category =& VBCategory::getInstance();
		$this->general =& VBGeneral::getInstance();
		
		//JS laden
		$template =& VBTemplate::getInstance();
		$template->setTemplate("default");
		
		$doc =& JFactory::getDocument();
		//Title setzen
		$doc->setTitle(JText::_('Polls'));
		//Css laden
		//$doc->addScript(JURI::base( true ).'/components/com_jvotesystem/assets/js/ajax_new.js');
		$doc->addStyleSheet(JUri::root()."/components/com_jvotesystem/assets/css/general.css");
		$doc->addStyleSheet(JUri::root()."/components/com_jvotesystem/assets/css/list.css");
		
		//Daten holen
		$this->filter = $this->get("Filter");
		$this->polls = $this->get("Polls");
		$this->count = $this->vote->getNuwRows();
		$this->cats = $this->category->getCategories();
		
		//Toolbar
		$this->toolbar = new VBToolbar($this->category->getCategory($this->filter["cid"]));
		$this->toolbar->add();
		
		$this->params = $this->vbparams->getActiveMenuParams();
		$this->menu = $this->vbparams->getActiveMenu();
		
		$this->layout = $this->params->get("list_layout", "table");
		
		parent::display($tpl);
    }//function

}//class

