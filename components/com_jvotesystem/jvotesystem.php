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

/**
 *  Require the base controller.
 */
require_once JPATH_COMPONENT.DS.'controller.php';

// Require specific controller if requested
if($controller = JRequest::getCmd('controller'))
{
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

    if(file_exists($path))
    {
        require_once $path;
    } else
    {
        $controller = '';
    }
}

//-- Create the controller
$classname = 'jVoteSystemController'.$controller;
$controller = new $classname();

//-- Load language files
$jlang =& JFactory::getLanguage();
$jlang->load('com_jvotesystem', JPATH_SITE, 'en-GB', true);
$jlang->load('com_jvotesystem', JPATH_SITE, null, true);
$jlang->load('com_jvotesystem', JPATH_COMPONENT, null, true);

//-- Perform the Request task
$controller->execute(JRequest::getCmd('task'));

//-- Redirect if set by the controller
$controller->redirect();
