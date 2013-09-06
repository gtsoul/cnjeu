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
//use an output buffer, in order for cookies to be passed onto the header
ob_start();
//please support JFusion
JFusionFunction::displayDonate();

global $jfusionDebug;
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
<td><h2><?php echo JText::_('LOGIN_CHECKER_RESULT');
?></h2></td>
</tr></table>

<div style="border: 0pt none ; margin: 0pt; padding: 0pt 5px; width: 800px; float: left;">

<?php
//get the submitted login details
$credentials['username'] = JRequest::getVar('check_username');
$credentials['password'] = JRequest::getVar('check_password', '', 'post', 'string', JREQUEST_ALLOWRAW);
//setup the options array
$options = array();
if (JRequest::getVar('remember') == 1) {
    $options['remember'] = 1;
}
if (JRequest::getVar('show_unsensored') == 1) {
    $options['show_unsensored'] = 1;
}
if (JRequest::getVar('skip_password_check') == 1) {
    $options['skip_password_check'] = 1;
}
if (JRequest::getVar('overwrite') == 1) {
    $options['overwrite'] = 1;
}
//prevent current jooomla session from being destroyed
global $JFusionActivePlugin, $JFusionLoginCheckActive;
$JFusionActivePlugin = 'joomla_int';
$JFusionLoginCheckActive = true;

$textOutput = array();

//get server specs
$version = new JVersion;
//put the relevant specs into an array
$server_info = array();
$server_info['Joomla Version'] = $version->getShortVersion();
$server_info['PHP Version'] = phpversion();
$db = JFactory::getDBO();
$mysql_version = $db->getVersion();
$server_info['MySQL Version'] = $mysql_version;
$server_info['System Information'] = php_uname();
$server_info['Browser Information'] = $_SERVER['HTTP_USER_AGENT'];
//display active plugins
if(JFusionFunction::isJoomlaVersion('1.6')){
    $query = 'SELECT folder, element, enabled as published from #__extensions WHERE (folder = \'authentication\' OR folder = \'user\') AND (element =\'jfusion\' OR enabled = 1)';
} else {
	$query = 'SELECT folder, element, published from #__plugins WHERE (folder = \'authentication\' OR folder = \'user\') AND (element =\'jfusion\' OR published = 1)';
}

$db->setQuery($query);
$system_plugins = $db->loadObjectList();
foreach ($system_plugins as $system_plugin) {
    if ($system_plugin->published == 1) {
        $server_info[$system_plugin->element . ' ' . $system_plugin->folder . ' Plugin'] = JText::_('ENABLED');
    } else {
        $server_info[$system_plugin->element . ' ' . $system_plugin->folder . ' Plugin'] = JText::_('DISABLED');
    }
}
//output the information to the user
debug::show($server_info, JText::_('SERVER') . ' ' . JText::_('CONFIGURATION'), 1);
$textOutput[JText::_('SERVER') . ' ' . JText::_('CONFIGURATION')] = $server_info;
echo '<br/>';

/**
 * retrieves version numbers
 *
 * @param strinf $filename         filename
 * @param string $name             name
 * @param string &$jfusion_version version number of the current jfusion
 *
 * @return unknown_type
 */
function getVersionNumber($filename, $name, &$jfusion_version)
{
    if (file_exists($filename)) {
        //get the version number
        $parser = JFactory::getXMLParser('Simple');
        $parser->loadFile($filename);
        $jfusion_version[JText::_('JFUSION') . ' ' . $name . ' ' . JText::_('VERSION') ] = ' ' . $parser->document->version[0]->data() . ' ';
        if ($name == JText::_('COMPONENT') && !empty($parser->document->revision[0])) {
            $rev = $parser->document->revision[0]->data();
            $jfusion_version[JText::_('JFUSION') . ' ' . $name . ' ' . JText::_('VERSION') ].= "(Rev $rev) ";
        }
        unset($parser);
    }
}
//get the JFusion version numbers
$jfusion_version = array();
getVersionNumber(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'jfusion.xml', JText::_('COMPONENT'), $jfusion_version);
getVersionNumber(JPATH_SITE . DS . 'modules' . DS . 'mod_jfusion_activity' . DS . 'mod_jfusion_activity.xml', JText::_('ACTIVITY') . ' ' . JText::_('MODULE'), $jfusion_version);
getVersionNumber(JPATH_SITE . DS . 'modules' . DS . 'mod_jfusion_login' . DS . 'mod_jfusion_login.xml', JText::_('LOGIN') . ' ' . JText::_('MODULE'), $jfusion_version);

if(JFusionFunction::isJoomlaVersion('1.6')){
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'authentication' . DS . 'jfusion' . DS . 'jfusion.xml', JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN'), $jfusion_version);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'user' . DS . 'jfusion' . DS .'jfusion.xml', JText::_('USER') . ' ' . JText::_('PLUGIN'), $jfusion_version);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'search' . DS . 'jfusion' . DS .'jfusion.xml', JText::_('SEARCH') . ' ' . JText::_('PLUGIN'), $jfusion_version);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'content' . DS . 'jfusion' . DS .'jfusion.xml', JText::_('DISCUSSION') . ' ' . JText::_('PLUGIN'), $jfusion_version);
} else {
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'authentication' . DS . 'jfusion.xml', JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN'), $jfusion_version);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'user' . DS . 'jfusion.xml', JText::_('USER') . ' ' . JText::_('PLUGIN'), $jfusion_version);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'search' . DS . 'jfusion.xml', JText::_('SEARCH') . ' ' . JText::_('PLUGIN'), $jfusion_version);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'content' . DS . 'jfusion.xml', JText::_('DISCUSSION') . ' ' . JText::_('PLUGIN'), $jfusion_version);
}
//output the information to the user
debug::show($jfusion_version, JText::_('JFUSION') . ' ' . JText::_('VERSIONS'), 1);
$textOutput[JText::_('JFUSION') . ' ' . JText::_('VERSIONS')] = $jfusion_version;
echo '<br/>';
//output the current configuration
$db = JFactory::getDBO();
$query = 'SELECT * from #__jfusion WHERE master = 1 OR slave = 1 or check_encryption = 1 ORDER BY master DESC;';
$db->setQuery($query);
$plugin_list = $db->loadObjectList();
foreach ($plugin_list as $plugin_details) {
    $plugin = new stdClass;
    $plugin->configuration = new stdClass;
    $plugin->configuration->master = $plugin_details->master;
    $plugin->configuration->slave = $plugin_details->slave;
    $plugin->configuration->dual_login = $plugin_details->dual_login;
    $plugin->configuration->check_encryption = $plugin_details->check_encryption;
    debug::show($plugin, JText::_('JFUSION') . ' ' . $plugin_details->name . ' ' . JText::_('PLUGIN'), 1);
    $textOutput[JText::_('JFUSION') . ' ' . $plugin_details->name . ' ' . JText::_('PLUGIN')] = $plugin;
}
echo '<br/><br/>';
/**
 * Launch Authentication Plugin Code
 */
// Initialize variables
jimport('joomla.user.authentication');
$authenticate = & JAuthentication::getInstance();
$auth = false;
// Get plugins
$plugins = JPluginHelper::getPlugin('authentication');
//add Jfusion plugin
$jfusion_auth = array('type' => 'authentication', 'name' => 'jfusion', 'params' => '');
$plugins[] = (object)$jfusion_auth;
//remove joomla plugin and load model
foreach ($plugins as $key => $value) {
    if ($value->name == 'joomla') {
        unset($plugins[$key]);
    } else {
        if(JFusionFunction::isJoomlaVersion('1.6')){
            include_once JPATH_SITE . DS . 'plugins' . DS . 'authentication' . DS . $value->name. DS .$value->name . '.php';
        } else {
            include_once JPATH_SITE . DS . 'plugins' . DS . 'authentication' . DS . $value->name . '.php';
        }
    }
}
// Create authencication response
$response = new JAuthenticationResponse();
foreach ($plugins as $plugin) {
    $className = 'plg' . $plugin->type . $plugin->name;
    if (class_exists($className)) {
        $plugin = new $className($this, (array)$plugin);
    }
    // Try to authenticate
    if(JFusionFunction::isJoomlaVersion('1.6')){
        $plugin->onUserAuthenticate($credentials, $options, $response);
    } else {
        $plugin->onAuthenticate($credentials, $options, $response);
    }
    // If authentication is successfull break out of the loop
    if ($response->status === JAUTHENTICATE_STATUS_SUCCESS) {
        if (empty($response->type)) {
            $response->type = isset($plugin->_name) ? $plugin->_name : $plugin->name;
        }
        if (empty($response->username)) {
            $response->username = $credentials['username'];
        }
        if (empty($response->fullname)) {
            $response->fullname = $credentials['username'];
        }
        if (empty($response->password)) {
            $response->password = $credentials['password'];
        }
        break;
    }
}
//check to see if JFusion auth plugin was used
if (isset($response->userinfo)) {
    //hide sensitive information
    $auth_userinfo = clone ($response->userinfo);
} else {
    //non jfusion auth plugin was used
    $auth_userinfo = clone ($response);
}

if (empty($options['show_unsensored'])) {
    //hide sensitive data
    $auth_userinfo = JFusionFunction::anonymizeUserinfo($auth_userinfo);

}
if (!empty($response->error_message)) {
    //clean up empty params for easier reading
    unset($auth_userinfo->fullname, $auth_userinfo->birthdate, $auth_userinfo->gender, $auth_userinfo->postcode, $auth_userinfo->country, $auth_userinfo->language, $auth_userinfo->timezone, $auth_userinfo->type);
}
//output from auth plugin results
debug::show($auth_userinfo, JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN'), 1);
$textOutput[JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN')] = $auth_userinfo;

//check to see if plugins returned true
if ($response->status === JAUTHENTICATE_STATUS_SUCCESS) { ?>
    <table style="background-color:#d9f9e2;width:100%;"><tr style="height:30px"><td width="50px">
    <img src="components/com_jfusion/images/check_good.png" height="20px" width="20px"></td>
    <td><font size="2"><b><?php echo JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('SUCCESS'); ?></b></font></td></tr></table>
    
    <?php
	$textOutput[JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('SUCCESS')] = "";    
    if (!empty($response->debug)) {
        debug::show($response->debug, JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('DEBUG'), 1);
        $textOutput[JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('DEBUG')] = $response->debug; 
    }
} else { ?>
    <table style="background-color:#f9ded9;width:100%;"><tr style="height:30px"><td width="50px">
    <img src="components/com_jfusion/images/check_bad.png" height="20px" width="20px"></td>
    <td><font size="2"><b><?php echo JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('ERROR'); ?></b></font></td></tr></table>
    <?php
	$textOutput[JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('ERROR')] = "";    
    if (!empty($response->debug)) {
        debug::show($response->debug, JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('DEBUG'), 1);
		$textOutput[JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('DEBUG')] = $response->debug;        
    }
	$debug=null;
	foreach ($textOutput as $key => $value) {
		if ($debug) {
			 $debug .= "\n\n".debug::getText($value, $key, 1);
		} else {
			$debug = debug::getText($value, $key, 1);
		}
	}
	echo '<textarea rows="10" cols="110">'.$debug.'</textarea>';
    //no password found: abort the login checker
    echo '</div>';
    ob_end_flush();
    $result = false;
    return $result;
}
/**
 * Launch User Plugin Code
 */
// Get plugins
$plugins = JPluginHelper::getPlugin('user');
$jfusion_user_plugin = 0;
//remove joomla plugin and load model
foreach ($plugins as $key => $value) {
    if ($value->name == 'joomla') {
        unset($plugins[$key]);
    }
    if ($value->name == 'jfusion') {
        $jfusion_user_plugin = 1;
    }
}
if ($jfusion_user_plugin == 0) {
    //add Jfusion plugin
    $jfusion_user = array('type' => 'user', 'name' => 'jfusion', 'params' => '');
    $plugins[] = (object)$jfusion_user;
}
foreach ($plugins as $plugin) {
    if(JFusionFunction::isJoomlaVersion('1.6')){
        include_once JPATH_SITE . DS . 'plugins' . DS . 'user' . DS . $plugin->name .  DS . $plugin->name . '.php';
    } else {
        include_once JPATH_SITE . DS . 'plugins' . DS . 'user' . DS . $plugin->name . '.php';
    }
    $className = 'plg' . ucfirst($plugin->type) . ucfirst($plugin->name);
	$plugin_name = $plugin->name;
    if (class_exists($className)) {
        $plugin = new $className($this, (array)$plugin);
    }

    $method_name = (JFusionFunction::isJoomlaVersion('1.6')) ? 'onUserLogin' : 'onLoginUser';
    if (method_exists($plugin, $method_name)) {
        // Try to authenticate
        $user_results = (array)$response;
        $results = $plugin->$method_name($user_results, $options);

        ?>
        <br/><br/><table style="width:100%"><tr style="height:30px"><td ALIGN="center" colspan="2" bgcolor="#D6F2FF"><b><?php echo $plugin_name . ' ' . JText::_('USER') . ' ' . JText::_('PLUGIN') ?></b></td></tr>
        <?php
        if ($results == true) { ?>
            <tr style="height:30px"><td width="50px" style="background-color:#d9f9e2;">
            <img src="components/com_jfusion/images/check_good.png" height="20px" width="20px"></td>
            <td style="background-color:#d9f9e2;"><font size="2"><b><?php echo JText::_('USER') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('SUCCESS'); ?></b></font></td></tr>
            <?php
			$textOutput[JText::_('USER') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('SUCCESS')] = "";            
        } else { ?>
            <tr style="height:30px"><td width="50px" style="background-color:#f9ded9;">
            <img src="components/com_jfusion/images/check_bad.png" height="20px" width="20px"></td>
            <td style="background-color:#f9ded9;"><font size="2"><b><?php echo JText::_('USER') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('ERROR'); ?></b></font></td></tr>
            <?php
            $textOutput[JText::_('USER') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('ERROR')] = "";
        }
        ?></table><?php

        if (!empty($jfusionDebug)) {
            debug::show($jfusionDebug, JText::_('USER') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('DEBUG'), 1);
            $textOutput[JText::_('USER') . ' ' . JText::_('PLUGIN') . ' ' . JText::_('DEBUG')] = $jfusionDebug;
        }
    }
}
//create a link to test out the logout function
?>
<br/><br/>
<form method="post" action="index.php" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_jfusion" />
<input type="hidden" name="show_unsensored" value="<?php echo $options['show_unsensored']; ?>" />
<input type="hidden" name="task" value="logoutcheckerresult" />
<?php if (!empty($jfusionDebug['joomlaid'])) : ?>
<input type="hidden" name="joomlaid" value="<?php echo $jfusionDebug['joomlaid']; ?>"/>
<?php endif; ?>
<input type="submit" value="Debug the Logout Function"/>
</form>
<br/>
<?php
$debug=null;
foreach ($textOutput as $key => $value) {
	if ($debug) {
		 $debug .= "\n\n".debug::getText($value, $key, 1);
	} else {
		$debug = debug::getText($value, $key, 1);
	}
}
echo '<textarea rows="10" cols="110">'.$debug.'</textarea>';
?>
</div>
<?php
ob_end_flush();
return;
