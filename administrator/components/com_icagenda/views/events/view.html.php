<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.2.5 2013-11-09
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

// iCagenda Class control (Joomla 2.5/3.x)
if(!class_exists('iCJView')) {
   if(version_compare(JVERSION,'3.0.0','ge')) {
      class iCJView extends JViewLegacy {
      };
   } else {
      jimport('joomla.application.component.view');
      class iCJView extends JView {};
   }
}

/**
 * View class Admin - List of Events - iCagenda.
 */
class iCagendaViewEvents extends iCJView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		if(version_compare(JVERSION, '3.0', 'lt')) {

			jimport( 'joomla.environment.request' );

			JHTML::_('stylesheet', 'icagenda.css', 'administrator/components/com_icagenda/add/css/');
			JHTML::_('stylesheet', 'icagenda.j25.css', 'administrator/components/com_icagenda/add/css/');

		} else {

			$document = JFactory::getDocument();
			$document->addStyleSheet( JURI::base().'components/com_icagenda/add/css/icagenda.css' );

		}

		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->authors		= $this->get('Authors');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			if(version_compare(JVERSION, '3.0', 'ge')) {
				$this->sidebar = JHtmlSidebar::render();
			}
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'icagenda.php';

		$state	= $this->get('State');
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
        $canDo		= iCagendaHelper::getActions();

		// Set Title
		if(version_compare(JVERSION, '3.0', 'lt')) {
			JToolBarHelper::title('iCagenda - ' . JText::_('COM_ICAGENDA_TITLE_EVENTS'), 'events.png');
		} else {
			JToolBarHelper::title('iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_TITLE_EVENTS') . '</span>', 'calendar');
		}

		$icTitle = JText::_('COM_ICAGENDA_TITLE_EVENTS');

		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$sitename = $app->getCfg('sitename');
		$title = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;
		$document->setTitle($title);

		//Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/event';
		if (file_exists($formPath)) {

			if ($canDo->get('core.create')) {
				JToolBarHelper::addNew('event.add','JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
				JToolBarHelper::editList('event.edit');
			}

		}

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('events.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('events.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'events.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('events.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('events.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'events.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('events.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_icagenda');
		}

		if(version_compare(JVERSION, '3.0', 'ge')) {
			JHtmlSidebar::setAction('index.php?option=com_icagenda&view=events');

			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
			);
		}


	}

	/**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return    void
     *
     * @since   3.0
     */

    public function saveOrderAjax()
    {
        // Get the input
        $input = JFactory::getApplication()->input;
        $pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

        // Sanitize the input
		JArrayHelper::toInteger($pks);
        JArrayHelper::toInteger($order);

        // Get the model
		$model = $this->getModel();

        // Save the ordering
        $return = $model->saveorder($pks, $order);

        if ($return)
        {
            echo "1";
        }

        // Close the application
        JFactory::getApplication()->close();
	}
}
