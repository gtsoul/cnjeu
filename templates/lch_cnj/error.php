<?php

/**
 * Template AUVENCE	 
 *
 * @author mploquin 
 */

// restricted access
/*defined( '_JEXEC' ) or die;
	
echo file_get_contents (JURI::base() . '404');*/

if (($this->error->getCode()) == '404' || ($this->error->getCode()) == '500') {
	header('Location: http://212.227.107.67/404/divers/404');
	exit;
}

/*defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
$controller = new JController();
$controller->setRedirect('index.php?option=com_content&view=article&id=197');
$controller->redirect();*/

?>