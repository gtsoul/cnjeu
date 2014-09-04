<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.2.4 2013-10-28
 * @since       3.2.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');

/**
 * iCagenda Submit Event Model
 */
// import Joomla table library
jimport( 'joomla.form.form' );
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class iCagendaModelSubmit extends JModelItem
{
	/**
	 * @var msg
	 */
	protected $msg;

	function getForm()
	{
	    $form=JForm::getInstance('submit', JPATH_COMPONENT.'/models/forms/submit.xml');
	    return $form;
	}

	function test_input($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
//		$data = htmlspecialchars($data);
		return $data;
	}

	function getDb()
	{
		// Get Component Global Options
		$iCparams = JComponentHelper::getParams('com_icagenda');

		$accessDefault = '';
		$submitAccess = $iCparams->get('submitAccess', $accessDefault);
		$approvalGroups = $iCparams->get('approvalGroups', array("8"));

		// Get User
		$user = JFactory::getUser();
		// Get User Access Levels
//		$userLevels = $user->getAuthorisedViewLevels();

		// Get User Groups
		if(version_compare(JVERSION, '3.0', 'lt')) {
			$userGroups = $user->getAuthorisedGroups();
		} else {
			$userGroups = $user->groups;
		}

		$u_id=$user->get('id');
		$u_mail=$user->get('email');

		// logged-in Users: Name/User Name Option
		$nameJoomlaUser = $iCparams->get('nameJoomlaUser', 1);
		if ($nameJoomlaUser == 1) {
			$u_name=$user->get('name');
		} else {
			$u_name=$user->get('username');
		}

		$data						= new stdClass();
		$data->id					= null;
		$data->asset_id				= JRequest::getVar('asset_id', '', 'post');
		$data->ordering				= 0;
		$data->state				= 1;

		// Control: if Manager
		jimport( 'joomla.access.access' );
		$adminUsersArray = array();
		foreach ($approvalGroups AS $ag) {
			$adminUsers = JAccess::getUsersByGroup($ag, False);
			$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
		}
		if ( in_array($u_id, $adminUsersArray )) {
			$data->approval			= 0;
		} else {
			$data->approval			= 1;
		}

		$data->access				= 1 ;
		$data->language				= '*';
		$menuID 					= JRequest::getVar('menuID', '', 'post');

		$data->username 			= JRequest::getVar('username', '', 'post');

		$data->title 				= JRequest::getVar('title', '', 'post');
		$data->catid 				= JRequest::getVar('catid', '', 'post');
//		$data->image 				= JRequest::getVar('image', '', 'post');
		$image = JRequest::getVar('image', null, 'files', 'array');
		$data->image = $this->frontendImageUpload($image);

		$data->dates 				= JRequest::getVar('dates', '', 'post');



		$dates=$this->getDates($data->dates);
		rsort($dates);

		$datesall = $data->dates[0];

		if ($datesall != '0000-00-00 00:00') {
			$data->dates 			= serialize($dates);
		} else {
			$dates = array('0000-00-00 00:00:00');
			$data->dates 			= serialize($dates);
		}

		$datesget = unserialize($data->dates);
		$datesnext = $datesget[0];

		$data->startdate 			= JRequest::getVar('startdate', '', 'post');
		$data->enddate 				= JRequest::getVar('enddate', '', 'post');

		$nodate='0000-00-00 00:00:00';

		// Calcul des dates d'une période.
		$startdate= ($data->startdate);
		$enddate= ($data->enddate);

		if ($startdate == NULL) {
			$startdate = $nodate;
		}
		if ($enddate == NULL) {
			$enddate = $nodate;
		}
		if (($startdate == $nodate) && ($enddate != $nodate))  {
			$enddate = $nodate;
		}

		$startcontrol=$this->mkt($startdate);
		$endcontrol=$this->mkt($enddate);

		$errorperiod='';
		if ($startcontrol > $endcontrol) { $errorperiod='1'; }
		else {

			if (class_exists('DateInterval')) {

				// Create array with all dates of the period - PHP 5.3+
				$start = new DateTime($startdate);

				$interval = '+1 days';
				$date_interval = DateInterval::createFromDateString($interval);

				$timestartdate = date('H:i', strtotime($startdate));
				$timeenddate = date('H:i', strtotime($enddate));
				if ($timeenddate <= $timestartdate){
					$end = new DateTime("$enddate +1 days");
				} else {
					$end = new DateTime($enddate);
				}

				// Retourne toutes les dates.
				$perioddates = new DatePeriod($start, $date_interval, $end);
				$out = array();

			} else {

				// Create array with all dates of the period - PHP 5.2
				if (($startdate != $nodate) && ($enddate != $nodate)) {
					$start = new DateTime($startdate);

					$timestartdate = date('H:i', strtotime($startdate));
					$timeenddate = date('H:i', strtotime($enddate));
					if ($timeenddate <= $timestartdate){
						$end = new DateTime("$enddate +1 days");
					} else {
						$end = new DateTime($enddate);
					}
					while($start < $end) {
						$out[] = $start->format('Y-m-d H:i');
						$start->modify('+1 day');
					}
				}
			}

			// Prépare serialize.
			if (!empty($perioddates)) {

				foreach($perioddates as $dt) {
					$out[] = (
					$dt->format('Y-m-d H:i')
				);
				}
			}
		}

		// Serialize les dates de la période.
		if (($startdate != $nodate) && ($enddate != $nodate)) {
			if ($errorperiod != '1') {
				$data->period = serialize($out);
				$ctrl=unserialize($data->period);
				if(is_array($ctrl)){
					$period=unserialize($data->period);
				}else{
					$period=$this->getPeriod($data->period);
				}
				rsort($period);
				$data->period=serialize($period);
			} else {
				$data->period='';
			}
		} else {
			$data->period='';
		}

//		if($data->startdate){
			$periodnext = $data->startdate;
//		}
		$mktdatesnext = $this->mkt($datesnext);
		$mktperiodnext = $this->mkt($periodnext);
//		if(($data->startdate) AND ($data->dates)){
			if ($mktdatesnext < $mktperiodnext) {
				$data->next = $periodnext;
			} else {
				$data->next = $datesnext;
			}
//		}

		/**
		 * Set Week Days
		 */
		$data->weekdays 			= JRequest::getVar('weekdays', '', 'post');
		if (!isset($data->weekdays) && !is_array($data->weekdays)) {
			$data->weekdays = '';
		}
		if (isset($data->weekdays) && is_array($data->weekdays)) {
			$data->weekdays = implode(",", $data->weekdays);
		}

		if(version_compare(JVERSION, '3.0', 'lt')) {
			$data->desc 			= JRequest::getVar('desc', '', 'post', 'string', JREQUEST_ALLOWHTML);
		} else {
			$data->desc 			= JFactory::getApplication()->input->get('desc', '', 'RAW');
		}

//		$data->desc 				= JRequest::getVar('desc', '', 'post');
		$data->place 				= JRequest::getVar('place', '', 'post');
		$data->email 				= JRequest::getVar('email', '', 'post');
		$data->phone 				= JRequest::getVar('phone', '', 'post');
		$data->website 				= JRequest::getVar('website', '', 'post');

		//Retrieve file details from uploaded file, sent from upload form
		$file = JRequest::getVar('file', null, 'files', 'array');
		$data->file = $this->frontendFileUpload($file);

		$data->address 				= JRequest::getVar('address', '', 'post');
		$data->city 				= JRequest::getVar('city', '', 'post');
		$data->country 				= JRequest::getVar('country', '', 'post');
		$data->lat 					= JRequest::getVar('lat', '', 'post');
		$data->lng 					= JRequest::getVar('lng', '', 'post');


		$data->alias 				= JRequest::getVar('alias', '', 'post');
		// URL
		jimport( 'joomla.filter.output' );
		if(empty($data->alias)) {
			$data->alias = $data->title;
		}
		$data->alias = JFilterOutput::stringURLSafe($data->alias);

//		$data->id 					= JRequest::getVar('id', '', 'post');


		$data->created_by 			= $u_id;
		$data->created_by_alias 	= JRequest::getVar('created_by_alias', '', 'post');
		$data->created_by_email 	= JRequest::getVar('created_by_email', '', 'post');
		$data->created 				= JHTML::Date( 'now', 'Y-m-d H:i:s' );
		$data->checked_out 			= JRequest::getVar('checked_out', '', 'post');
		$data->checked_out_time 	= JRequest::getVar('checked_out_time', '', 'post');

		$data->params 	= JRequest::getVar('params', '', 'post');

		if (isset($data->params) && is_array($data->params)) {
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($data->params);
			$data->params = (string)$parameter;
		}


		$data->asset_id = null;

		$db = JFactory::getDbo();

		// insert Event in Database
		if (($data->username != NULL) && ($data->title != NULL) && ($data->created_by_email != NULL)) {
			$db->insertObject('#__icagenda_events', $data, id);
		} else {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}


		// Get the "event" URL
		$baseURL = JURI::base();
		$subpathURL = JURI::base(true);

		$baseURL = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		$urlsend = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=submit&layout=send'));

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL List filtering
		$urlsend = ltrim($urlsend, '/');
		if(substr($urlsend,0,strlen($subpathURL)+1) == "$subpathURL/") $urlsend = substr($urlsend,strlen($subpathURL)+1);
		$urlsend = rtrim($baseURL,'/').'/'.ltrim($urlsend,'/');

//		self::notificationUserEmail($data->created_by_email, $urlcheck);

		if ((isset($data->id)) AND ($data->id != '0') AND ($data->username != NULL) AND ($data->title != NULL)) {
			self::notificationManagerEmail($data->id, $data->title, $lien, $u_id);
		} else {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// get the application object
		$app = JFactory::getApplication();

		// redirect after successful submission
		$app->redirect(htmlspecialchars_decode($urlsend) , JText::_( 'COM_ICAGENDA_EVENT_SUBMISSION_CONFIRMATION' ), JText::_( 'COM_ICAGENDA_EVENT_SUBMISSION' ));


	}


	function notificationManagerEmail ($eventid, $title, $menuid, $u_id)
	{
		// Load iCagenda Global Options
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Load Joomla Config
		$config = JFactory::getConfig();

		// Get the site name
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$sitename = $config->get('sitename');
		} else {
			$sitename = $config->getValue('config.sitename');
		}

		// Get Global Joomla Contact Infos
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$mailfrom = $config->get('mailfrom');
			$fromname = $config->get('fromname');
		} else {
			$mailfrom = $config->getValue('config.mailfrom');
			$fromname = $config->getValue('config.fromname');
		}


		$siteURL = JURI::base();
		$siteURL = rtrim($siteURL,'/');

		//$iCmenuitem=$params->get('iCmenuitem');
		$iCmenuitem = false;

		// Itemid Request (automatic detection of the first iCagenda menu-link, by menuID, and depending of current language)
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$langdefault = $config->get('language');
		} else {
			$langdefault = $config->getValue('config.language');
		}
		$langFrontend = $langdefault;
		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('id AS idm')->from('#__menu')->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '$langFrontend')" );
		$db->setQuery($query);
		$idm=$db->loadResult();
		$mItemid=$idm;
		if ($mItemid == NULL) {
			$db = JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->select('id AS noidm')->from('#__menu')->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '*')" );
			$db->setQuery($query);
			$noidm=$db->loadResult();
		}
		$nolink = '';
		if ($noidm == NULL && $mItemid == NULL) {
			$nolink = 1;
		}
		if(is_numeric($iCmenuitem)) {
			$lien = $iCmenuitem;
		} else {
			if ($mItemid == NULL) {
				$lien = $noidm;
			}
			else {
				$lien = $mItemid;
			}
		}


		// Set Notification Email to each User groups allowed to approve event submitted
		$groupid = $iCparams->get('approvalGroups', array("8"));

		// Load Global Option for Autologin
		$autologin = $iCparams->get('auto_login', 1);

		jimport( 'joomla.access.access' );
		$adminUsersArray = array();
		foreach ($groupid AS $gp) {
			$adminUsers = JAccess::getUsersByGroup($gp, False);
//			if($adminUsers->block == '0' && empty($adminUsers->activation)){
//			if($adminUsers->block == '0'){
				$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
//			} else {
//				$adminUsersArray = JAccess::getUsersByGroup(8, False);
//			}
		}

        $db = JFactory::getDbo();
		$query	= $db->getQuery(true);

		if ($u_id == NULL) {
			$u_id = 0;
		}

		if (!in_array($u_id, $adminUsersArray)) {

			$matches = implode(',', $adminUsersArray);
			$query->select('ui.username AS username, ui.email AS email, ui.password AS passw, ui.block AS block, ui.activation AS activation')->from('#__users AS ui')->where( "ui.id IN ($matches) ");

		} else {

			$matches = $u_id;
			$query->select('ui.username AS username, ui.email AS email, ui.password AS passw, ui.block AS block, ui.activation AS activation')->from('#__users AS ui')->where( "ui.id = $matches ");

		}

		$db->setQuery($query);
        $managers = $db->loadObjectList();

        foreach ($managers AS $manager) {

			if (!in_array($u_id, $adminUsersArray)) {
				$type = 'approval';
			} else {
				$type = 'confirmation';
			}
			// Create Admin Mailer
			$adminmailer = JFactory::getMailer();

			// Set Sender of Notification Email
			$adminmailer->setSender(array( $mailfrom, $fromname ));

        	$username = $manager->username;
        	$passw = $manager->passw;
        	$email = $manager->email;

			// Set Recipient of Notification Email
			$adminrecipient = $email;
			$adminmailer->addRecipient($adminrecipient);

			// Set Subject of Admin Notification Email
			if (!in_array($u_id, $adminUsersArray)) {
				$adminsubject = JText::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_SUBJECT', $sitename);
			} else {
				$adminsubject = JText::sprintf('COM_ICAGENDA_LEGEND_NEW_EVENT').': '.$title;
			}
			$adminmailer->setSubject($adminsubject);



			// Set Url to preview and checking of event submitted
			$baseURL = JURI::base();
			$subpathURL = JURI::base(true);

			$baseURL = str_replace('/administrator', '', $baseURL);
			$subpathURL = str_replace('/administrator', '', $subpathURL);

			if ($autologin == 1) {

				$urlpreview = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=list&layout=event&id='.(int)$eventid.'&Itemid='.(int)$lien.'&icu='.$username.'&icp='.$passw));
				$urlcheck = str_replace('&amp;','&', JRoute::_('administrator/index.php?option=com_icagenda&view=events&Itemid='.(int)$lien).'&icu='.$username.'&icp='.$passw.'&filter_search='.$eventid);

			} else {

				$urlpreview = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=list&layout=event&id='.(int)$eventid.'&Itemid='.(int)$lien));
				$urlcheck = str_replace('&amp;','&', JRoute::_('administrator/index.php?option=com_icagenda&view=events&Itemid='.(int)$lien).'&filter_search='.$eventid);

			}

//			$urlpreview = str_replace('&amp;','&', $siteURL.'/index.php?option=com_icagenda&view=list&layout=event&id='.(int)$eventid.'&Itemid='.(int)$lien.'&icu='.$username.'&icp='.$passw);
			$urlpreviewshort = str_replace('&amp;','&', $siteURL.'/index.php?option=com_icagenda&view=list&layout=event&id='.(int)$eventid.'&Itemid='.(int)$lien);

			$urlcheckshort = str_replace('&amp;','&', $siteURL.'/administrator/index.php?option=com_icagenda&view=events');

			// Sub Path filtering
			$subpathURL = ltrim($subpathURL, '/');

			// URL Event Preview filtering
			$urlpreview = ltrim($urlpreview, '/');
			if(substr($urlpreview,0,strlen($subpathURL)+1) == "$subpathURL/") $urlpreview = substr($urlpreview,strlen($subpathURL)+1);
			$urlpreview = rtrim($baseURL,'/').'/'.ltrim($urlpreview,'/');

			// URL Event Check filtering
			$urlcheck = ltrim($urlcheck, '/');
			if(substr($urlcheck,0,strlen($subpathURL)+1) == "$subpathURL/") $urlcheck = substr($urlcheck,strlen($subpathURL)+1);
			$urlcheck = rtrim($baseURL,'/').'/'.ltrim($urlcheck,'/');

//			$sitename = '<i>'.$sitename.'</i>';

			// Set Body of User Notification Email

			$adminbodycontent = JText::sprintf( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_HELLO', $username).',<br /><br />';

			if ($type == 'approval') {
				$adminbodycontent.= JText::_( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_NEW_EVENT' ).'<br /><br />';
//			$adminbodycontent.= 'The following link allows you to preview the event.<br /><br />';
//			$adminbodycontent.= 'Preview link: <a href="'.$urlpreview.'">'.$urlpreviewshort.'</a><br /><br />';
//				$adminbodycontent.= '[ <a href="'.$urlpreview.'">'.JText::_( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_PREVIEW' ).'</a> ]<br /><br />';
				$adminbodycontent.= JText::sprintf( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_APPROVE_INFO', $sitename).'<br /><br />';
//				$adminbodycontent.= JText::_( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_APPROVE_LINK' ).': <a href="'.$urlcheck.'">'.$urlcheckshort.'</a><br /><br />';
				$adminbodycontent.= JText::_( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_APPROVE_LINK' ).': <a href="'.$urlpreview.'">'.$urlpreviewshort.'</a><br /><br />';
			}
			if ($type == 'confirmation') {
				$adminbodycontent.= JText::_( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_APPROVED_REVIEW' ).'<br /><br />';
				$adminbodycontent.= '<a href="'.$urlpreview.'">'.$urlpreviewshort.'</a><br /><br />';
			}
			if ($autologin == 1) {
				$adminbodycontent.= '<hr><small>'.JText::sprintf( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_FOOTER', $sitename).'<small>';
			} else {
				$adminbodycontent.= '<hr><small>'.JText::sprintf( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_FOOTER_NO_AUTOLOGIN', $sitename).'<small>';
			}

			$adminbody = rtrim($adminbodycontent);

			$adminmailer->isHTML(true);
			$adminmailer->Encoding = 'base64';

			$adminmailer->setBody($adminbody);

			// Send User Notification Email
			if (isset($email)) {
				if($manager->block == '0' && empty($manager->activation)){
					$send = $adminmailer->Send();
				}
			}
		}
	}


	function notificationUserEmail ($email)
	{
		// Load Joomla Config
		$config = JFactory::getConfig();

		// Create User Mailer
		$mailer = JFactory::getMailer();

		// Create Admin Mailer
		$adminmailer = JFactory::getMailer();

		// Get Global Joomla Contact Infos
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$mailfrom = $config->get('mailfrom');
			$fromname = $config->get('fromname');
		} else {
			$mailfrom = $config->getValue('config.mailfrom');
			$fromname = $config->getValue('config.fromname');
		}

		// Set Sender of Notification Email
		$mailer->setSender(array( $mailfrom, $fromname ));
		$adminmailer->setSender(array( $mailfrom, $fromname ));

		// Set Recipient of User Notification Email
		$userrecipient = $email;
		$mailer->addRecipient($userrecipient);

		// Set Subject of User Notification Email
		$subject = 'test';
		$mailer->setSubject($subject);

		// Set Body of User Notification Email
		$bodycontent = 'Staff will review your submission and will be in touch with you soon.
Thank you for submitting your event to labo 25 Test!';
		$body = $bodycontent;
		$mailer->setBody($body);

		// Send User Notification Email
		if (isset($email)) {
			$send = $mailer->Send();
		}
	}


	function getDates ($dates)
	{
		$dates=str_replace('d=', '', $dates);
		$dates=str_replace('+', ' ', $dates);
		$dates=str_replace('%3A', ':', $dates);
		$ex_dates=explode('&', $dates);
		return $ex_dates;
	}

	function getPeriod ($period){
		$period=str_replace('d=', '', $period);
		$period=str_replace('+', ' ', $period);
		$period=str_replace('%3A', ':', $period);
		$ex_period=explode('&', $period);
		return $ex_period;
	}

	function getNextDates ($dates)
	{
		$nodate='0000-00-00 00:00:00';
		$today=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$today=mktime(0,0,0,$m,$day,$y);
		$next=$this->mkt($dates[0]);

		if(count($dates)){

			while ($next <= $today) {
				$dd = $this->mkt($dates[0]);
				$nextDate=$dd;
				foreach($dates as $d){
					$d=$this->mkt($d);
					if ($d>=$today){
						$nextDate=$d;
					}
				}
//				echo ' today : '.$today;
//				echo ' next : '.$next;
//				echo ' date next : '.$d;

				return date('Y-m-d H:i', $nextDate);
			}

		}

	}

//	function getNext ($dates)
//	{
//		$dates=unserialize($dates);
//		$today=time();
//		if(count($dates)){
//			$next=$dates[0];
//			if ($this->mkt($next)<$today){
//				foreach($dates as $d){
//					$MktD=$this->mkt($d);
//					if ($MktD>=$today){
//						$next=$d;
//					}
//				}
//			}
//			return $next;
//		}
//	}

	function frontendImageUpload ($image){

		//Clean up filename to get rid of strange characters like spaces etc
		$imagename = JFile::makeSafe($image['name']);

		if($imagename!=''){
			//Set up the source and destination of the file
			$src = $image['tmp_name'];
			$dest =  JPATH_SITE.'/images/icagenda/frontend/images/'.$imagename;

			// Get Joomla Images PATH setting
			$params = JComponentHelper::getParams('com_media');
			$image_path = $params->get('image_path');

			// Create Folder iCagenda in ROOT/IMAGES_PATH/icagenda
			$folder[0][0]	=	'icagenda/frontend/' ;
			$folder[0][1]	= 	JPATH_ROOT.'/'.$image_path.'/'.$folder[0][0];
			$folder[1][0]	=	'icagenda/frontend/images/';
			$folder[1][1]	= 	JPATH_ROOT.'/'.$image_path.'/'.$folder[1][0];
			$error	 = array();
			foreach ($folder as $key => $value)
			{
				if (!JFolder::exists( $value[1]))
				{
					if (JFolder::create( $value[1], 0755 ))
					{
						$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						JFile::write($value[1]."/index.html", $data);
						$error[] = 0;
					}
					else
					{
						$error[] = 1;
					}
				}
				else//Folder exist
				{
					$error[] = 0;
				}
			}

			if ( JFile::upload($src, $dest, false) ){
				return 'images/icagenda/frontend/images/'.$imagename;
			}

			//First check if the file has the right extension, we need jpg only
//			if ( strtolower(JFile::getExt($filename) ) == 'jpg') {
//			   if ( JFile::upload($src, $dest) ) {
			      //Redirect to a page of your choice
//			   } else {
			      //Redirect and throw an error message
//			   }
//			} else {
			   //Redirect and notify user file is not right extension
//			}

		}
	}

	function frontendFileUpload ($file){

		//Clean up filename to get rid of strange characters like spaces etc
		$filename = JFile::makeSafe($file['name']);

		if($filename!=''){
			//Set up the source and destination of the file
			$src = $file['tmp_name'];
			$dest =  JPATH_SITE.'/images/icagenda/frontend/attachments/'.$filename;

			// Get Joomla Images PATH setting
			$params = JComponentHelper::getParams('com_media');
			$image_path = $params->get('image_path');

			// Create Folder iCagenda in ROOT/IMAGES_PATH/icagenda
			$folder[0][0]	=	'icagenda/frontend/' ;
			$folder[0][1]	= 	JPATH_ROOT.'/'.$image_path.'/'.$folder[0][0];
			$folder[1][0]	=	'icagenda/frontend/attachments/';
			$folder[1][1]	= 	JPATH_ROOT.'/'.$image_path.'/'.$folder[1][0];
			$error	 = array();
			foreach ($folder as $key => $value)
			{
				if (!JFolder::exists( $value[1]))
				{
					if (JFolder::create( $value[1], 0755 ))
					{
						$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						JFile::write($value[1]."/index.html", $data);
						$error[] = 0;
					}
					else
					{
						$error[] = 1;
					}
				}
				else//Folder exist
				{
					$error[] = 0;
				}
			}

			if ( JFile::upload($src, $dest, false) ){
				return 'images/icagenda/frontend/attachments/'.$filename;
			}

		}
	}

	function mkt($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		if (isset($ex_data['3']))$hour=$ex_data['3'];
		if (isset($ex_data['4']))$min=$ex_data['4'];
		if ((isset($hour)) && (isset($min)) && ($hour!='') && ($hour!=NULL) && ($min!='') && ($min!=NULL)) {
			$result=mktime($ex_data['3'], $ex_data['4'], '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		} else {
			$result=mktime('00', '00', '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		}
		return $result;
	}

	function mktdate($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		$result=mktime('00', '00', '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		return $result;
	}

	function mkttime($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		$result=mktime($ex_data['3'], $ex_data['4'], '00', '00', '00', '00');
		return $result;
	}

}
