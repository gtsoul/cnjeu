<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsFront
 * @subpackage Wrapper
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * load the framework
 */
jimport('joomla.application.component.view');
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'models' . DS . 'model.factory.php';
/**
 * Renders the main admin screen that shows the configuration overview of all integrations
 * 
 * @category   JFusion
 * @package    ViewsFront
 * @subpackage Wrapper
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
class jfusionViewWrapper extends JView
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
        parent::display($tpl);
    }
}
