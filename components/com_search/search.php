<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if($_SERVER['REMOTE_ADDR'] == '82.239.221.134') {
	echo('search.php<br/>');
		//die('<br/>plop2');
}
// Create the controller
try {
$controller = JControllerLegacy::getInstance('Search');
} catch (Exception $e) {
if($_SERVER['REMOTE_ADDR'] == '82.239.221.134') {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
	//die('<br/>Erreur');
}
}
if($_SERVER['REMOTE_ADDR'] == '82.239.221.134') {
	echo('$controller='.$controller);
	//die('<br/>plop');
}
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
