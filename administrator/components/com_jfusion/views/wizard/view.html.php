<?php

/**
 * This is view file for wizard
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Wizard
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Renders the main admin screen that shows the configuration overview of all integrations
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Wizard
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
class jfusionViewwizard extends JView
{

     /**
     * displays the view
     *
     * @param string $tpl template name
     *
     * @return string html output of view
     */
    function display($tpl = null)
    {
        //prepare the toolbar
        $bar = new JToolBar('My Toolbar');
        $bar->appendButton('Standard', 'forward', JText::_('NEXT'), 'wizardresult', false, false);
        $bar->appendButton('Standard', 'cancel', JText::_('CANCEL'), 'plugindisplay', false, false);
        $toolbar = $bar->render();
        $jname = JRequest::getVar('jname');
        if ($jname) {
            //hides the main menu and disables the Joomla's navigation menu
            JRequest::setVar('hidemainmenu', 1);
            //print out results to user
            $this->assignRef('toolbar', $toolbar);
            $this->assignRef('jname', $jname);
            parent::display($tpl);
        } else {
            JError::raiseWarning(500, JText::_('NONE_SELECTED'));
        }
    }
}
