<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class AlfContactViewCustomCSS extends JView
{
	function display($tpl = null) {
        ob_end_clean();
        header('Content-Type: text/css; charset=UTF-8');
        $params = JComponentHelper::getParams('com_alfcontact');
        echo $params->get('customCSS');
        exit;
    }
}