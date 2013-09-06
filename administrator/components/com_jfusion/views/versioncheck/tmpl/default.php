<?php

/**
 * This is view file for versioncheck
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Versioncheck
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
//display the paypal donation button
JFusionFunction::displayDonate();
?>

<table><tr><td width="100px">
<img src="components/com_jfusion/images/jfusion_large.png" height="75px" width="75px">
</td><td width="100px">
<img src="components/com_jfusion/images/versioncheck.png" height="75px" width="75px">
<td><h2><?php echo JText::_('VERSION_CHECKER'); ?></h2></td>
</tr></table><br/>

<style type="text/css">
tr.good0 { background-color: #ecfbf0; }
tr.good1 { background-color: #d9f9e2; }
tr.bad0 { background-color: #f9ded9; }
tr.bad1 { background-color: #f9e5e2; }
table.adminform td {width: 33%;}
</style>

<table class="adminform" style="border-spacing:1px;"><thead><tr>
<th class="title" align="left"><?php echo JText::_('SERVER_SOFTWARE'); ?></th>
<th class="title" align="center"><?php echo JText::_('YOUR_VERSION'); ?></th>
<th class="title" align="center"><?php echo JText::_('MINIMUM_VERSION'); ?></th>
</tr></thead><tbody>

<?php
$server_compatible = true;
if (version_compare(phpversion(), $this->JFusionVersion->php[0]->data()) == - 1) {
    ?><tr class = "bad0"><?php
    $server_compatible = false;
} else {
    ?><tr class = "good0"><?php
} ?>


<td>PHP</td>
<td><?php echo phpversion(); ?></td>
<td><?php echo $this->JFusionVersion->php[0]->data(); ?></td></tr>

<?php
$version = new JVersion;
$joomla_version = $version->getShortVersion();
//remove any letters from the version
$joomla_versionclean = preg_replace("[A-Za-z !]", "", $joomla_version);
if (version_compare($joomla_versionclean, $this->JFusionVersion->joomla[0]->data()) == - 1) {
    ?><tr class = "bad1"><?php
    $server_compatible = false;
} else {
    ?><tr class = "good1"><?php
} ?>

<td>Joomla</td>
<td><?php echo $joomla_version; ?></td>
<td><?php echo $this->JFusionVersion->joomla[0]->data(); ?></td></tr>

<?php
$db = JFactory::getDBO();
$mysql_version = $db->getVersion();
if (version_compare($mysql_version, $this->JFusionVersion->mysql[0]->data()) == - 1) {
    ?><tr class = "bad0"><?php
    $server_compatible = false;
} else {
    ?><tr class = "good0"><?php
} ?>

<td>MySQL</td>
<td><?php echo $mysql_version; ?></td>
<td><?php echo $this->JFusionVersion->mysql[0]->data(); ?></td></tr>

</tbody></table>
<?php
if ($server_compatible) {
    //output the good news
    ?>
    <table style="background-color:#d9f9e2;width:100%;"><tr><td>
    <img src="components/com_jfusion/images/check_good.png" height="30px" width="30px">
    <td><h2><?php echo JText::_('SERVER_UP2DATE'); ?></h2></td><td></td></tr></table>

    <?php
} else {
    //output the bad news and automatic upgrade option
     ?>
    <table style="background-color:#f9ded9;"><tr><td width="50px"><td>
    <img src="components/com_jfusion/images/check_bad.png" height="30px" width="30px">
    <td><h2><?php echo JText::_('SERVER_OUTDATED'); ?></h2></td>

    <td></td></tr></table>

    <?php
}
?>

<br/><br/><table class="adminform" style="border-spacing:1px;"><thead><tr>
<th class="title" align="left"><?php echo JText::_('JFUSION_SOFTWARE'); ?></th>
<th class="title" align="center"><?php echo JText::_('YOUR_VERSION'); ?></th>
<th class="title" align="center"><?php echo JText::_('CURRENT_VERSION'); ?></th>
</tr></thead><tbody>

<?php
/**
 * This function allows the version number to be retrieved for JFusion plugins
 *
 * @param string $filename   filename
 * @param string $name       name
 * @param string $version    version
 * @param string &$row_count rowcount
 * @param string &$up2date   up2date
 *
 * @return string nothing
 *
 */
function getVersionNumber($filename, $name, $version, &$row_count, &$up2date)
{
    if (file_exists($filename) && is_readable($filename)) {
        //get the version number
        $parser = JFactory::getXMLParser('Simple');
        $parser->loadFile($filename);
        if (version_compare($parser->document->version[0]->data(), $version) == - 1) {
            echo '<tr class = "bad' . $row_count . '">';
            $up2date = false;
        } else {
            echo '<tr class = "good' . $row_count . '">';
        }
        echo '<td>' . JText::_('JFUSION') . ' ' . $name . ' ' . JText::_('VERSION') . '</td>';
        echo '<td>' . $parser->document->version[0]->data();
        if ($name == JText::_('COMPONENT')&& !empty($parser->document->revision[0])) {
            $rev = $parser->document->revision[0]->data();
            echo " (Rev $rev) ";
        }
        echo '</td>';
        echo '<td>' . $version . '</td></tr>';
    } else {
        JFusionFunction::raiseWarning(JText::_('ERROR'), JText::_('XML_FILE_MISSING') . ' '. JText::_('JFUSION') . ' ' . $name . ' ' . JText::_('PLUGIN'), 1);
        echo '<td>' . JText::_('JFUSION') . ' ' . $name . ' ' . JText::_('VERSION') . '</td>';
        echo '<td></td>';
        echo '<td>' . JText::_('UNKNOWN') . '</td></tr>';
    }
    //cleanup for the next function call
    unset($parser);
    if ($row_count == 1) {
        $row_count = 0;
    } else {
        $row_count = 1;
    }
}
//check if the JFusion component,plugins and modules
$row_count = 0;
$up2date = true;
//check the JFusion component,plugins and modules versions
getVersionNumber(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'jfusion.xml', JText::_('COMPONENT'), $this->JFusionVersion->component[0]->data(), $row_count, $up2date);
getVersionNumber(JPATH_SITE . DS . 'modules' . DS . 'mod_jfusion_activity' . DS . 'mod_jfusion_activity.xml', JText::_('ACTIVITY') . ' ' . JText::_('MODULE'), $this->JFusionVersion->activity[0]->data(), $row_count, $up2date);
getVersionNumber(JPATH_SITE . DS . 'modules' . DS . 'mod_jfusion_user_activity' . DS . 'mod_jfusion_user_activity.xml', JText::_('USER') . ' ' . JText::_('ACTIVITY') . ' ' . JText::_('MODULE'), $this->JFusionVersion->useractivity[0]->data(), $row_count, $up2date);
getVersionNumber(JPATH_SITE . DS . 'modules' . DS . 'mod_jfusion_whosonline' . DS . 'mod_jfusion_whosonline.xml', JText::_('WHOSONLINE') . ' ' . JText::_('MODULE'), $this->JFusionVersion->whosonline[0]->data(), $row_count, $up2date);
getVersionNumber(JPATH_SITE . DS . 'modules' . DS . 'mod_jfusion_login' . DS . 'mod_jfusion_login.xml', JText::_('LOGIN') . ' ' . JText::_('MODULE'), $this->JFusionVersion->login[0]->data(), $row_count, $up2date);
if(JFusionFunction::isJoomlaVersion('1.6')){  
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'authentication' . DS . 'jfusion'. DS . 'jfusion.xml', JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->auth[0]->data(), $row_count, $up2date);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'user' . DS .  'jfusion'. DS . 'jfusion.xml', JText::_('USER') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->user[0]->data(), $row_count, $up2date);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'search' . DS .  'jfusion'. DS . 'jfusion.xml', JText::_('SEARCH') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->search[0]->data(), $row_count, $up2date);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'content' . DS .  'jfusion'. DS . 'jfusion.xml', JText::_('DISCUSSION') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->discussion[0]->data(), $row_count, $up2date);
} else {
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'authentication' . DS . 'jfusion.xml', JText::_('AUTHENTICATION') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->auth[0]->data(), $row_count, $up2date);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'user' . DS . 'jfusion.xml', JText::_('USER') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->user[0]->data(), $row_count, $up2date);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'search' . DS . 'jfusion.xml', JText::_('SEARCH') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->search[0]->data(), $row_count, $up2date);
    getVersionNumber(JPATH_SITE . DS . 'plugins' . DS . 'content' . DS . 'jfusion.xml', JText::_('DISCUSSION') . ' ' . JText::_('PLUGIN'), $this->JFusionVersion->discussion[0]->data(), $row_count, $up2date);	
}
?>
</table><br/>

<table class="adminform" style="border-spacing:1px;"><thead><tr>
<th class="title" align="left"><?php echo JText::_('JFUSION_PLUGINS'); ?></th>
<th class="title" align="center"><?php echo JText::_('YOUR_VERSION'); ?></th>
<th class="title" align="center"><?php echo JText::_('CURRENT_VERSION'); ?></th>
</tr></thead><tbody>
<?php
$db = JFactory::getDBO();
$query = 'SELECT * from #__jfusion';
$db->setQuery($query);
$plugins = $db->loadObjectList();
foreach ($plugins as $plugin) {
    if (isset($this->JFusionVersion->{$plugin->name})) {
        $plugin_version = $this->JFusionVersion->{$plugin->name};
        if ($plugin_version[0]->data()) {
            $version = $plugin_version[0]->data();
        } else {
            $version = JText::_('UNKNOWN');
        }
    } else {
        $version = JText::_('UNKNOWN');
    }
    getVersionNumber(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'plugins' . DS . $plugin->name . DS . 'jfusion.xml', $plugin->name . ' ' . JText::_('PLUGIN'), $version, $row_count, $up2date);
}
?></table><?php
if ($up2date) {
    //output the good news

    ?>
    <table style="background-color:#d9f9e2;width:100%;"><tr><td>
    <img src="components/com_jfusion/images/check_good.png" height="30px" width="30px">
    <td><h2><?php echo JText::_('JFUSION_UP2DATE'); ?></h2></td><td></td></tr></table>

    <?php
} else {
    //output the bad news and automatic upgrade option
    ?>
    <table style="background-color:#f9ded9;"><tr><td width="50px"><td>
    <img src="components/com_jfusion/images/check_bad.png" height="30px" width="30px">
    <td><h2><?php echo JText::_('JFUSION_OUTDATED'); ?></h2></td>

    <td>
    <script type="text/javascript">
    <!--
    function confirmSubmit()
    {
    var agree=confirm("<?php echo JText::_('UPGRADE_CONFIRM_RELEASE') . ' ' . $this->JFusionVersion->component[0]->data(); ?>");
    if (agree)
    return true ;
    else
    return false ;
    }
    // -->
    </script>

    <form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm">
    <input type="submit" value="<?php echo JText::_('UPGRADE_JFUSION'); ?>" onCLick="return confirmSubmit();"/>
    <input type="hidden" name="install_url" value="http://jfusion.googlecode.com/svn/branches/jfusion_package.zip" />
    <input type="hidden" name="type" value="" />
    <input type="hidden" name="installtype" value="url" />
    <?php if(JFusionFunction::isJoomlaVersion('1.6')){ ?>
     <input type="hidden" name="task" value="install.install" />
    <?php } else { ?>
    <input type="hidden" name="task" value="doInstall" /> 
    <?php } ?>
    <input type="hidden" name="option" value="com_installer" />
    <?php echo JHTML::_('form.token'); ?>
    </form>
    </td></tr></table>
    <br/><br/>

    <?php
}
?>
<br/><br/><br/><table style="background-color:#ffffce;width:100%;"><tr><td width="50px"><td>
<img src="components/com_jfusion/images/advanced.png" height="75px" width="75px">
<td><h3><?php echo JText::_('ADVANCED') . ' ' . JText::_('VERSION') . ' ' . JText::_('MANAGEMENT'); ?></h3>
<script type="text/javascript">
<!--
function confirmSubmit2(action)
{
if (action == 'build')
{
    var confirm_text = '<?php echo JText::_('UPGRADE_CONFIRM_BUILD'); ?>';
    var install_url = 'http://jfusion.googlecode.com/svn/branches/1.7.x/jfusion_package.zip';
} else if (action == 'release')
{
    var confirm_text = '<?php echo JText::_('UPGRADE_CONFIRM_RELEASE') . ' ' . $this->JFusionVersion->component[0]->data(); ?>';
    var install_url = 'http://jfusion.googlecode.com/svn/branches/jfusion_package.zip';
} else if (action == 'svn')
{
    var confirm_text = '<?php echo JText::_('UPGRADE_CONFIRM_SVN'); ?> ' + document.adminForm2.svn_build.value;
    var install_url = 'http://jfusion.googlecode.com/svn-history/r' + document.adminForm2.svn_build.value + '/branches/1.7.x/jfusion_package.zip';
}

var agree=confirm(confirm_text);
if (agree)
{
    document.adminForm2.install_url.value = install_url;
    return true ;
} else {
    return false ;
}
}
// -->
</script>


<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm2">
    <input type="hidden" name="install_url" value="" />
    <input type="hidden" name="type" value="" />
    <input type="hidden" name="installtype" value="url" />
    <?php if(JFusionFunction::isJoomlaVersion('1.6')){ ?>
     <input type="hidden" name="task" value="install.install" />
    <?php } else { ?>
    <input type="hidden" name="task" value="doInstall" /> 
    <?php } ?>
    <input type="hidden" name="option" value="com_installer" />
    <?php echo JHTML::_('form.token'); ?>
<b><?php echo JText::_('ADVANCED_WARNING'); ?></b><br/>

<input type="submit" value="<?php echo JText::_('INSTALL') . ' ' . JText::_('LATEST') . ' ' . JText::_('RELEASE'); ?>" onCLick="return confirmSubmit2('release');"/><br/>
<input type="submit" value="<?php echo JText::_('INSTALL') . ' ' . JText::_('LATEST') . ' SVN Build'; ?>" onCLick="return confirmSubmit2('build');"/><br/>
SVN build:<input type="text" name="svn_build" size="4"/> <input type="submit" value="<?php echo JText::_('INSTALL') . ' ' . JText::_('SPECIFIC') . ' SVN Build'; ?>" onCLick="return confirmSubmit2('svn');"/><br/>
</form></td></tr></table>
<br/><br/>
