<?php

/**
 * First file that gets called for accessing jfusion in the administrator panel
 *
 * PHP version 5
 *
 * @category  JFusion
 * @package   ControllerFront
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Require the base controller
 */
require_once JPATH_COMPONENT . DS . 'controllers' . DS . 'controller.jfusion.php';
// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path)) {
        include_once $path;
    } else {
        $controller = '';
    }
}
// Create the controller
$classname = 'JFusionControllerFrontEnd' . $controller;
$controller = new $classname();
//load the views
$controller->addViewPath(JPATH_COMPONENT . DS . 'view');
// Perform the Request task
$controller->execute('displayplugin');
// Redirect if set by the controller
$controller->redirect();
