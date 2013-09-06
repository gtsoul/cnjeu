<?php

/**
 * This is view file for synchistory
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Synchistory
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
 * @subpackage Synchistory
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
class jfusionViewsynchistory extends JView
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
        $bar->appendButton('Standard', 'delete', 'Delete Record', 'deletehistory', false, false);
        $bar->appendButton('Standard', 'forward', 'Resolve Error', 'resolveerror', false, false);
        $toolbar = $bar->render();
        //get the all usersync data
        $db = JFactory::getDBO();
        $query = 'SELECT * from #__jfusion_sync ORDER BY time_end DESC, time_start DESC';
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $this->assignRef('rows', $rows);
        $this->assignRef('toolbar', $toolbar);
        parent::display($tpl);
    }
}
