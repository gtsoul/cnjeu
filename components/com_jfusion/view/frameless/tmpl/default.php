<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsFront
 * @subpackage Frameless
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

//initialise some vars
$mainframe = JFactory::getApplication();
$uri = JURI::getInstance();
// declare Data object
$data = new stdClass();
$data->buffer = null;
$data->header = null;
$data->body = null;
$data->baseURL = null;
$data->fullURL = null;
$data->integratedURL = null;
$data->jPluginParam = $this->jPluginParam;
$data->Itemid = JRequest::getInt('Itemid');
//Get the base URL to the specific JFusion plugin
$data->baseURL = JFusionFunction::getPluginURL($data->Itemid);
//Get the full current URL
$query = $uri->getQuery();
$url = $uri->current();
$data->fullURL = $query ? $url . '?' . $query : $url;
$data->fullURL = str_replace('&', '&amp;', $data->fullURL);

//check for .html suffix mode
$sef_suffix = $mainframe->getCfg('sef_suffix');
$sef = $mainframe->getCfg('sef');
if($sef_suffix == 1 && $sef == 1 && !count(JRequest::get('POST'))){
    //redirect if url non_sef
    if (strrpos($data->fullURL, '?') !== false) {
        $u = JFactory::getURI();
        if ($u->getVar('Itemid') && $u->getVar('option')) {
            $u->delVar('Itemid');
            $u->delVar('option');
            $jfile = $u->getVar('jfile');
            if ($jfile) {
                $u->delVar('jfile');
            }
            $url = $u->getQuery();
            $url = JFusionFunction::routeURL($jfile.'?'.$url,$data->Itemid,'',true,false);
            $mainframe->redirect($url);
        }
    }
}

//Get the integrated URL
$JFusionParam = & JFusionFactory::getParams($this->jname);
$data->integratedURL = $JFusionParam->get('source_url');
//Load language files
$lang = JFactory::getLanguage();
$lang->load('com_jfusion.plg_' . $this->jname);
$lang->load('com_jfusion.plg_' . $this->jname, JPATH_ADMINISTRATOR);
// Get the output from the JFusion plugin
$JFusionPlugin = & JFusionFactory::getPublic($this->jname);
//backup Joomla's globals
$joomla_globals = $GLOBALS;
//get Joomla's session token so we can reset it afterward in case the software closes the session
$session = JFactory::getSession();
$token = $session->getToken();
/*
 * Caused issues with more people than it helped
//make sure that the software's database is selected in the case the mysql server and credentials are the same but a different database is used
$db_name = $JFusionParam->get('database_name');
if (!empty($db_name)) {
	$db =& JFusionFactory::getDatabase($this->jname);
    $query = "USE $db_name";
	$db->setQuery($query);
	$db->query();
}
*/
//get the buffer
$JFusionPlugin->getBuffer($data);
//restore Joomla's globals
$GLOBALS = $joomla_globals;
//restore session token
$session->set('session.token', $token);
//reset the global $Itemid so that modules are not repeated
global $Itemid;
$Itemid = $data->Itemid;
//reset Itemid so that it can be obtained via getVar
JRequest::setVar('Itemid', $data->Itemid);
//clear the page title
if (!empty($data->buffer)) {
    $document = JFactory::getDocument();	
    $document->setTitle('');
}
//check to see if the Joomla database is still connnected incase the plugin messed it up
JFusionFunction::reconnectJoomlaDb();
if ($data->buffer === 0) {
    JError::raiseWarning(500, JText::_('NO_FRAMELESS'));
    $result = false;
    return $result;
}
if (!$data->buffer) {
    JError::raiseWarning(500, JText::_('NO_BUFFER'));
    $result = false;
    return $result;
}
//we set the backtrack_limit to twice the buffer length just in case!
$backtrack_limit = ini_get('pcre.backtrack_limit');
ini_set('pcre.backtrack_limit', strlen($data->buffer) * 2);
$pattern = '#<head[^>]*>(.*?)<\/head>.*?<body[^>]*>(.*)<\/body>#si';
preg_match($pattern, $data->buffer, $temp);
if (count($temp) == 3) {
    $data->header = $temp[1];
    $data->body = $temp[2];
}
unset($temp);
// Check if we found something
if (!strlen($data->header) || !strlen($data->body)) {
    if (!empty($data->buffer)) {
        //non html output, return without parsing
        die($data->buffer);
    } else {
        unset($data->buffer);
        //no output returned
        JError::raiseWarning(500, JText::_('NO_HTML'));
    }
} else {
    unset($data->buffer);
    // Add the header information
    if (isset($data->header)) {
        $document = JFactory::getDocument();
        $regex_header = array();
        $replace_header = array();
        //change the page title
        $pattern = '#<title>(.*?)<\/title>#si';
        preg_match($pattern, $data->header, $page_title);
        $document->setTitle(html_entity_decode($page_title[1], ENT_QUOTES, "utf-8"));
        $regex_header[] = $pattern;
        $replace_header[] = '';
        //set meta data to that of softwares
        $meta = array('keywords', 'description', 'robots');
        foreach ($meta as $m) {
            $pattern = '#<meta name=["|\']' . $m . '["|\'](.*?)content=["|\'](.*?)["|\'](.*?)>#Si';
            if (preg_match($pattern, $data->header, $page_meta)) {
                if ($page_meta[2]) {
                    $document->setMetaData($m, $page_meta[2]);
                }
                $regex_header[] = $pattern;
                $replace_header[] = '';
            }
        }
        $pattern = '#<meta name=["|\']generator["|\'](.*?)content=["|\'](.*?)["|\'](.*?)>#Si';
        if (preg_match($pattern, $data->header, $page_generator)) {
            if ($page_generator[2]) {
                $document->setGenerator($document->getGenerator() . ', ' . $page_generator[2]);
            }
            $regex_header[] = $pattern;
            $replace_header[] = '';
        }
        //use Joomla's default
        $regex_header[] = '#<meta http-equiv=["|\']Content-Type["|\'](.*?)>#Si';
        $replace_header[] = '';
        //remove above set meta data from software's header
        $data->header = preg_replace($regex_header, $replace_header, $data->header);
        $JFusionPlugin->parseHeader($data);
        $document->addCustomTag($data->header);
        $pathway = $JFusionPlugin->getPathWay();
        if (is_array($pathway)) {
            $breadcrumbs = & $mainframe->getPathWay();
            foreach ($pathway as $path) {
                $breadcrumbs->addItem($path->title, JFusionFunction::routeURL($path->url, JRequest::getInt('Itemid')));
            }
        }
    }
    // Output the body
    if (isset($data->body)) {
        // parse the URL's'
        $JFusionPlugin->parseBody($data);
        echo $data->body;
    }
    //set the base href (commented out by mariusvr as this caused errors for people using IE)
    //$document->setBase($data->baseURL);
    //restore the backtrack_limit
    ini_set('pcre.backtrack_limit', $backtrack_limit);
}
