<?php

/**
 * This is view file for logincheckerresult
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Logincheckerresults
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
 * @subpackage Logincheckerresults
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
class jfusionViewLoginCheckerResult extends JView
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
    
    /**
     * function to override the default attach function
     *
     * @param string $sample sample name
     * 
     * @return string nothing
     */      
    function attach($sample)
    {
    }
}
