<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the ALFContact Component
 */
class AlfContactViewAlfContact extends JView
{
    protected $params;
	
	// Overwriting JView display method
    function display($tpl = null) 
    {
		// Assign data from the model to the view
        $this->items = $this->get('Items');
				        
		// Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
			
        //Check for variables in case of reload
        $this->assign('name', JRequest::getString('name',''));
        $this->assign('email', JRequest::getString('email', ''));
        $this->assign('emailto_id', JRequest::getInt('emailto_id', 0));
        $this->assign('subject', JRequest::getString('subject', ''));
        $this->assign('message', JRequest::getString('message', ''));
        $this->assign('copy', JRequest::getVar('copy', '0'));
        $this->assign('extravalue', JRequest::getString('extravalue', ''));
        $this->assign('extra2value', JRequest::getString('extra2value', ''));
        
		// Prepare the document
		$this->_prepareDocument();
		
		// Display the view
        parent::display($tpl);

    }
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app  = JFactory::getApplication();
		$menu = $app->getMenu()->getActive();
		$this->pagetitle = $menu->params->get('page_title');
		$title = null;

		$title = $this->pagetitle;

		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		
		$this->document->setTitle($title);
		
	}
	
}
