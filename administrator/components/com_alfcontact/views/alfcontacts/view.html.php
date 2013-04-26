<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

/**
 * AlfContacts View
 */
class AlfContactViewAlfContacts extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
    * ALFContacts view display method
    * @return void
    */
    public function display($tpl = null) 
    {
		// Assign data from the model to the view
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		
		// Set the toolbar
        $this->addToolBar();
 
        // Display the template
        parent::display($tpl);
				
    }
		
	/**
	 * Setting the toolbar
	 */
	public function addToolBar() 
	{
		JToolBarHelper::title(JText::_('COM_ALFCONTACT_MANAGER_ALFCONTACTS'), 'alfcontact');
		JHtml::stylesheet('com_alfcontact/admin.stylesheet.css', array(), true, false, false);
		
		If (JFactory::getUser()->authorise('core.create', 'com_alfcontact')){
			JToolBarHelper::addNewX('alfcontact.add');
		}
		
		JToolBarHelper::editListX('alfcontact.edit');
		JToolBarHelper::divider();    
		JToolbarHelper::publishList('alfcontacts.publish');
		JToolbarHelper::unpublishList('alfcontacts.unpublish');
		JToolBarHelper::divider();
		
		if ($this->getModel()->getState('filter.published') == -2 && JFactory::getUser()->authorise('core.delete', 'com_alfcontact')) {
			JToolBarHelper::deleteList('COM_ALFCONTACT_REALLY_DELETE', 'alfcontacts.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else {
            JToolBarHelper::trash('alfcontacts.trash');
        }
		        
		JToolBarHelper::divider();
		
		// Options button.
		If (JFactory::getUser()->authorise('core.admin', 'com_alfcontact')) {
			JToolBarHelper::preferences('com_alfcontact', '575');
			JToolBarHelper::divider();
		}
				
		JToolBarHelper::help('Components_ALFContact', false);
	}
}
