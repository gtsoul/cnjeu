<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * AlfContact View
 */
class AlfContactViewAlfContact extends JView
{
        /**
         * display method of AlfContact view
         * @return void
         */
        public function display($tpl = null) 
        {
                // get the Data from the model and assign to the view
                $this->form = $this->get('Form');
                $this->item = $this->get('Item');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
 
                // Set the toolbar
                $this->addToolBar();
 
                // Display the template
                parent::display($tpl);
				
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
            $isNew = $this->item->id == 0;
            JToolBarHelper::title($isNew ? JText::_('COM_ALFCONTACT_MANAGER_ALFCONTACT_NEW') : JText::_('COM_ALFCONTACT_MANAGER_ALFCONTACT_EDIT'),'alfcontact');
            JHtml::stylesheet('com_alfcontact/admin.stylesheet.css', array(), true, false, false);
			
			JToolBarHelper::apply('alfcontact.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('alfcontact.save', 'JTOOLBAR_SAVE');
            
			If (JFactory::getUser()->authorise('core.create', 'com_alfcontact')){
				JToolBarHelper::custom('alfcontact.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}
						
			JToolBarHelper::cancel('alfcontact.cancel', 'JTOOLBAR_CANCEL');
             
        }
}
