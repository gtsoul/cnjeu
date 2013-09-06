<?php

/**
 * This is view file for logoutcheckerresult
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Logoutcheckerresults
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
//use an output buffer, in order for cookies to be passed onto the header
ob_start();
JFusionFunction::displayDonate();
/**
 *     Load debug library
 */
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'models' . DS . 'model.debug.php';
/**
 * Output information about the server for future support queries
 */
?>
<table><tr><td width="100px">
<img src="components/com_jfusion/images/jfusion_large.png" height="75px" width="75px">
</td><td width="100px">
<img src="components/com_jfusion/images/login_checker2.png" height="75px" width="75px">
<td><h2><?php echo JText::_('LOGOUT_CHECKER_RESULT'); ?></h2></td>
</tr></table>

<div style="border: 0pt none ; margin: 0pt; padding: 0pt 5px; width: 800px; float: left;">

<?php
//get the joomla id
$joomlaid = JRequest::getVar('joomlaid');
$user = (array)JFactory::getUser($joomlaid);
$options = array();
$textOutput = array();
$options['group'] = 'USERS';
if (JRequest::getVar('show_unsensored') == 1) {
    $options['show_unsensored'] = 1;
}
//prevent current jooomla session from being destroyed
global $JFusionActivePlugin, $jfusionDebug;
$jfusionDebug = array();
$JFusionActivePlugin = 'joomla_int';
$jfusion_user = array('type' => 'user', 'name' => 'jfusion', 'params' => '');
$plugin = (object)$jfusion_user;
    if(JFusionFunction::isJoomlaVersion('1.6')){
        include_once JPATH_SITE . DS . 'plugins' . DS . 'user' . DS . $plugin->name .  DS . $plugin->name . '.php';
    } else {
        include_once JPATH_SITE . DS . 'plugins' . DS . 'user' . DS . $plugin->name . '.php';
    }
$className = 'plg' . $plugin->type . $plugin->name;
if (class_exists($className)) {
    $plugin = new $className($this, (array)$plugin);
}
$method_name = (JFusionFunction::isJoomlaVersion('1.6')) ? 'onUserLogout' : 'onLogoutUser';
if (method_exists($plugin, $method_name)) {
    $response = $plugin->$method_name($user, $options);
}

debug::show($jfusionDebug, JText::_('LOGOUT') . ' ' . JText::_('DEBUG'), 1);
$textOutput[JText::_('LOGOUT') . ' ' . JText::_('DEBUG')] = $jfusionDebug;
$debug=null;
foreach ($textOutput as $key => $value) {
	if ($debug) {
		 $debug .= "\n\n".debug::getText($value, $key, 1);
	} else {
		$debug = debug::getText($value, $key, 1);
	}
}
echo '<textarea rows="10" cols="110">'.$debug.'</textarea>';
?> </div> <?php
ob_end_flush();
return;
