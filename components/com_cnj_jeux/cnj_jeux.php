<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

$controller = JController::getInstance('Cnj_jeux');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
