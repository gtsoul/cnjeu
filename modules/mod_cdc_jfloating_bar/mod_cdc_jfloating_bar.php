<?php
// CDC J FLOAT BAR 1.0
// Created Dec 01 2010
// @copyright http://www.citeducommerce.com
// @license http://www.gnu.org/copyleft/gpl.html GNU/GPL

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$params->def('greeting', 1);

$type 	= modcdcjfloatingbarHelper::getType();
$return	= modcdcjfloatingbarHelper::getReturnURL($params, $type);

$user =& JFactory::getUser();

require(JModuleHelper::getLayoutPath('mod_cdc_jfloating_bar'));