<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * AlfContacts Controller
 */
class AlfContactControllerAlfContacts extends JControllerAdmin
{
    /**
    * Proxy for getModel.
    * @since       1.6
    */
    public function getModel($name = 'AlfContact', $prefix = 'AlfContactModel', $config = array('ignore_request' => true)) 
    {
        $model = parent::getModel($name, $prefix, $config);
		
        return $model;
    }
	
}
