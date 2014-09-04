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
 * @version     3.2.6 2013-11-21
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
jimport( 'joomla.html.parameter' );
jimport( 'joomla.registry.registry' );

jimport('joomla.user.helper');
jimport('joomla.access.access');

class iCModelItem extends JModelItem
{

	/**
	 * @var msg
	 */
	protected $msg;
	protected $filters;
	protected $options;
	protected $itObj;
	protected $where;


	/**
	 * Model Builder
	 */
	protected function startiCModel()
	{
		$this->filters = array();
		$this->options = array();
		$this->items = array();
		$this->itObj= new stdClass;

	}


	/**
	 * Table importation
	 */
	public function getTable($type = 'icagenda', $prefix = 'icagendaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	/**
	 * Get all data
	 */
	protected function getItems ($structure)
	{
		// renvoie les éléments

		// ADD is_array 1.2.9
		if (isset($this->items) && is_array($this->items)) { $this->items=$this->getDBitems(); }

		foreach ($structure as $k=>$v)
		{
			$this->itObj->$k=$this->$k($v);
		}
		return $this->itObj;
	}


	/**
	 * Add the filters to be used in queries
	 */
	protected function addFilter($name, $value)
	{
		$this->filters[$name]=$value;
	}


	/**
	 * Add the options you use to obtain the various data in the right setting
	 */
	protected function addOption($name, $value)
	{
		$this->options[$name]=$value;
	}


	function getDatesPeriod($start, $end)
	{
		if($start > $end)
		{
			return false;
		}

		$sdate    = strtotime($start);
//		$sdate    = strtotime("$start +1 day");
		$edate    = strtotime($end);

		$dates = array();

		for($i = $sdate; $i <= $edate; $i += strtotime('+1 day', 0))
		{
			$dates[] = date('Y-m-d H:i', $i);
		}

		return $dates;
	}


	/**
	 * Fetch data from DB
	 */
	protected function getDBitems()
	{
		// Preparing connection to db
		$db	= $this->getDbo();

		// Preparing the query
		$query = $db->getQuery(true);

		// Selectable items
		$query->select('next AS tNext, dates AS tDates, startdate AS tStartdate, enddate AS tEnddate, weekdays AS tWeekdays, id AS tId, state AS tState, access AS tAccess');
		$query->from('`#__icagenda_events` AS e');
		$query->where(' e.state = 1 OR e.state = 0 ');
		$db->setQuery($query);

		$allnext = $db->loadObjectList();

		foreach ($allnext as $an) {
			$tNext=$an->tNext;
			$tDates=$an->tDates;
			$tId=$an->tId;
			$tState=$an->tState;
			$tEnddate=$an->tEnddate;
			$tStartdate=$an->tStartdate;
			$tWeekdays=$an->tWeekdays;

			$dates=unserialize($tDates);

			$AllDates = array();
			if (isset($tWeekdays)) {$weekdays = $tWeekdays;} else {$weekdays = '';}

			$weekdays = explode (',', $weekdays);
			$weekdaysarray = array();

			foreach ($weekdays as $wed) {
				array_push($weekdaysarray, $wed);
			}

			if (in_array('', $weekdaysarray)) {
				$arrayWeekDays = array(0,1,2,3,4,5,6);
			}
			elseif ($tWeekdays) {
				$arrayWeekDays = $weekdaysarray;
			}
			else {
				$arrayWeekDays = array(0,1,2,3,4,5,6);
				//print_r($arrayWeekDays);
			}
			$WeeksDays = $arrayWeekDays;

			// If Single Dates, added to all dates for this event (ADD FIX 3.1.11 !)
			$singledates = unserialize($tDates);
			if ((isset ($dates)) AND ($dates!=NULL) AND (!in_array('0000-00-00 00:00:00', $singledates)) AND (!in_array('', $singledates))) {
				$AllDates = array_merge($AllDates, $dates);
			}
			elseif (in_array('', $singledates)) {
				$datesarray= array();
				$nodate = array('0000-00-00 00:00');
				$datesmerger = array_push($datesarray, $nodate);
				$DatesUpdate = serialize($nodate);

				$db		= JFactory::getDBO();
				$query	= $db->getQuery(true);
				$query->update('#__icagenda_events');
				$query->set("`dates`='".(string)$DatesUpdate."'");
				$query->where('`id`='.(int)$tId);
				$db->setQuery($query);
				$db->query($query);
				$nosingledates=unserialize($DatesUpdate);
				$AllDates = array_merge($AllDates, $nosingledates);
			}

			$StDate = date('Y-m-d H:i', $this->mkt($tStartdate));
			$EnDate = date('Y-m-d H:i', $this->mkt($tEnddate));
			$perioddates = $this->getDatesPeriod($StDate, $EnDate);

			$onlyStDate='';
			if (isset($this->onlyStDate)) $onlyStDate=$this->onlyStDate;

			if ((isset ($perioddates)) AND ($perioddates!=NULL)) {
				if ($onlyStDate==1) {
					array_push($AllDates, $StDate);
				} else {
					foreach ($perioddates as $Dat) {
						if (in_array(date('w', strtotime($Dat)), $WeeksDays)) {
							$SingleDate = date('Y-m-d H:i', $this->mkt($Dat));
							array_push($AllDates, $SingleDate);
						}
					}
				}
			}
//			print_r($AllDates);
			rsort($AllDates);

			if ($AllDates==NULL) {
				$next ='0000-00-00 00:00:00';
			} else {
				rsort($AllDates);

				$day= date('d');
				$m= date('m');
				$y= date('y');
				$hour= date('H');
				$min= date('i');
				$today=mktime(0,0,0,$m,$day,$y);

				$tsdate_Next = $this->mktshort($tNext);
				$ts_lastdate = $this->mkt($AllDates[0]);
				$tsdate_lastdate = $this->mktshort($AllDates[0]);
				$ts_enddate = $this->mkt($tEnddate);
				$tsdate_enddate = $this->mktshort($tEnddate);
				$ts_startdate = $this->mkt($tStartdate);

				$TimeEnd=date('H:i', strtotime($tEnddate));
				$TimeDate=date('H:i', $ts_lastdate);

				$returnNext = $tNext;

//				if ($tsdate_Next <= $today) {
					foreach($AllDates as $a){
						$d=$this->mkt($a);
						$ddate=$this->mktshort($a);
						if ($tsdate_lastdate < $today){
							$returnNext=date('Y-m-d H:i:s', $ts_lastdate);
						}
						elseif ($ddate>=$today){
							$returnNext=date('Y-m-d H:i:s', $d);
						}

					}
//				}

				// Test End Date if Next Date or Last Date (3.1.5)
				$NowTime=JHtml::date(time() , 'H:i');
				$NowDate=JHtml::date(time() , 'Y-m-d');

				$NowTimeTest = date('H:i', strtotime($NowTime));

				$EndDay = date('Y-m-d', strtotime($tEnddate));
				$LastDay = date('Y-m-d', strtotime($returnNext));

				$StartTimeReturn = date('H:i', strtotime($ts_startdate));
				$EndTimeReturn = date('H:i', strtotime($tEnddate));
				$LastTimeReturn = date('H:i', strtotime($returnNext));
//				if (($LastTimeReturn > $TimeEnd) AND ($EndDay == $LastDay)) {
				if ($EndDay == $LastDay) {
					$LastTime = $TimeEnd;
				} else {
					$LastTime = $LastTimeReturn;
				}

				$LastTimeTest2 = strtotime($LastTime);
				$NowTimeTest2 = strtotime($NowTime);

				// Fix 3.1.12 (removed isset($tPeriod))
				if (($tEnddate != '0000-00-00 00:00:00') AND ($ts_startdate < $today)) {
					if (($tsdate_enddate == $today) AND ($LastTime >= $NowTime)) {
						$returnNextPediod=date('Y-m-d H:i', $ts_enddate);
					} else {
						$returnNextPediod=$returnNext;
					}
				} else {
					$returnNextPediod=$returnNext;
				}

				// Set next var
				if (($LastDay == $EndDay) AND ($EndDay == $NowDate)) {
					$next=$returnNextPediod;
				}
				elseif (($ts_startdate < $today) AND ($EndDay > $NowDate) AND ($EndTimeReturn != $LastTimeReturn) AND (!$tDates)) {
					$next = date('Y-m-d', strtotime($LastDay)).' 23:59:59';
				}
				elseif (($ts_startdate < $today) AND ($EndDay > $NowDate) AND ($EndTimeReturn != $LastTimeReturn) AND ($tDates) AND ($LastTimeTest2 <= $NowTimeTest2)) {
					$next = date('Y-m-d', strtotime($LastDay)).' 23:59:59';
				}
				elseif (($ts_startdate < $today) AND ($EndDay > $NowDate) AND ($EndTimeReturn != $LastTimeReturn) AND ($tDates) AND ($LastTimeTest2 > $NowTimeTest2)) {
					$next = date('Y-m-d', strtotime($LastDay)).' '.date('H:i:s', strtotime($LastTime));
				}
				else {
					$next=$returnNext;
				}
			}

			// 3.1.12 Fixed and update events with bug
			if (($tNext == '0000-00-00 00:00:00') AND ($tState == 0)) {
				if (($tStartdate != '0000-00-00 00:00:00') AND ($tEnddate != '0000-00-00 00:00:00') AND ($tEnddate >=$tStartdate)) {
					$next=$returnNext;
					$db		= JFactory::getDBO();
					$query	= $db->getQuery(true);
					$query->update('#__icagenda_events');
					$query->set('`state`=1');
					$query->where('`id`='.(int)$tId);
					$db->setQuery($query);
					$db->query($query);
				}
			}

//echo '['.$next.' '.$tNext.']<br />';
			if ($next != $tNext) {
				$db		= JFactory::getDBO();
				$query	= $db->getQuery(true);
				$query->update('#__icagenda_events');
				$query->set("`next`='".$next."'");
				$query->where('`id`='.(int)$tId);
				$db->setQuery($query);
				$db->query($query);
			}
		}

		// Preparing connection to db
		$db	= $this->getDbo();

		// Preparing the query
		$query = $db->getQuery(true);

		// Selectable items
		$query->select('e.id, e.title, e.alias, e.state, e.approval, e.created_by_email, e.username, e.access, e.language, e.params, e.catid, e.image, e.file, e.displaytime, e.dates, e.period, e.startdate, e.enddate, e.weekdays, e.time, e.desc, e.next, e.address, e.city, e.country, e.phone, e.email, e.website, e.place as place_name, e.coordinate as coordinate, e.lat as lat, e.lng as lng, c.id as cat_id, c.title as cat_title, c.color as cat_color, c.desc as cat_desc, c.alias as cat_alias');
//		$query->select(' l.desc as location_desc');
//		$query->select(' c.title as cat_title, c.color as cat_color, c.desc as cat_desc, c.alias as cat_alias');
		//$query->select(' r.id as regId, r.eventid as event_id, r.name as reg_username');

		// join
		$query->from('`#__icagenda_events` AS e');
		$query->leftJoin('`#__icagenda_category` AS c ON c.id = e.catid');
//		$query->leftJoin('`#__icagenda_events` AS l ON l.id = e.place');
		//$query->leftJoin('`#__icagenda_location` AS l ON l.id = e.location');
		//$query->leftJoin('`#__icagenda_registration` AS r ON r.eventid = e.id');

		// Where (filters)
		$filters=$this->filters;
		$where='e.state ='.$filters['state'];


		$user = JFactory::getUser();
		$userID = $user->id;
		$userLevels = $user->getAuthorisedViewLevels();
		if(version_compare(JVERSION, '3.0', 'lt')) {
			$userGroups = $user->getAuthorisedGroups();
		} else {
			$userGroups = $user->groups;
		}
		$groupid = JComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));

		jimport( 'joomla.access.access' );
		$adminUsersArray = array();
		foreach ($groupid AS $gp) {
			$adminUsers = JAccess::getUsersByGroup($gp, False);
			$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
		}

		// Test if user login have Approval Rights
		if (!in_array($userID, $adminUsersArray) AND !in_array('8', $userGroups)) {
			$where.=' AND e.approval <> 1';
		} else {
			$where.=' AND e.approval < 2';
		}

		$where.=' AND e.next <> \'%0000-00-00 00:00:00%\'';
		unset($filters['state']);
		foreach($filters as $k=>$v){

			// in case of state - test
//			if ($k=='state'){
//				if(is_numeric($v)){
//					$where.='e.state='.$v;
//				}else{
//					$this->error404();
//				}
//			}

			// Access Control
//			$public = '1';
//			$notset = '0';

			if ( !in_array('8', $userGroups) )  {

				$useraccess = implode(', ', $userLevels);
				$where.=' AND e.access IN ('.$useraccess.')';



// START OLD
//				$where.=' AND (e.access LIKE \'%'.$public.'%\' ';
//				$where.=' AND e.approval <> 0';
//
//				foreach ($allnext as $an) {
//					$tAccess=$an->tAccess;
//					$control='';
//print_r($userLevels);
//					foreach ($userLevels as $level){
//						if ($level == $tAccess) {
//							$control=$tAccess;
//							// in the case of access
//							$where.=' OR e.access LIKE \'%'.$control.'%\' ';
//							$where.=' OR e.access LIKE \'%'.$notset.'%\' ';
//						}
//					}
//				}
//
//				$where.=' ) ';
// END OLD
			}



			// Language Control
			$lang = JFactory::getLanguage();
			$eventLang = '';
			$langTag = '';
			$langTag = $lang->getTag();
			$eventLang=$langTag;
			$langall="*";

			// in the case of language
			$where.=' AND (e.language LIKE "%'.$eventLang.'%" OR ';
			$where.=' e.language LIKE \'%'.$langall.'%\')';

			// normal cases
			if ($k!='key' && $k!='next' && $k!='e.catid' && $k!='id'){
				$where.=' AND '.$k.' LIKE "%'.$v.'%"';
			}

			// in case of search
			if ($k=='key'){
				$keys=explode(' ', $v);
				foreach ($keys as $ke){
					$where.=' AND (e.title LIKE \'%'.$ke.'%\' OR ';
					$where.=' e.desc LIKE \'%'.$ke.'%\' OR ';
					$where.=' e.address LIKE \'%'.$ke.'%\' OR ';
					$where.=' e.place LIKE \'%'.$ke.'%\' OR ';
					$where.=' c.title LIKE \'%'.$ke.'%\')';
				}
			}

			// in the case of time
			if ($k=='next'){
				if($v==1) $where.=' AND e.next >= (CURDATE())';
				if($v==2) $where.=' AND e.next < (CURDATE())';
				if($v==3) $where.=' AND e.next >= (NOW())';
				if($v==4) $where.=' AND (e.next >= (CURDATE()) AND (e.next < (CURDATE() + INTERVAL 1 DAY)))';
			}


			// in the case of category
			$mcatidtrue = array('0');
			if (isset($this->options['mcatid'])) $mcatidtrue = $this->options['mcatid'];
			$catold = '0';
			if(!is_array($mcatidtrue)) {
				$catold=$mcatidtrue;
				$mcatid = array($mcatidtrue);
			} else {
				$mcatid = $mcatidtrue;
			}
			if ( ((!in_array('0', $mcatid)) OR ($catold != 0)) ) {
				if ($k=='e.catid'){
					if(!is_array($v)) $v=array(''.$v.'');
					$v = implode(', ', $v);
					$where.=' AND '.$k.' IN ('.$v.')';
				}
			}

			// in case of id
			if ($k=='id'){
				//check if ID is a number
				if(is_numeric($v)){
					$where.=' AND e.id='.$v;
				}else{
					//ERROR Message
				}
			}
		}

		$this->where=$where;
		$query->where($where);

		// order and limit the query
		$orderdate = '0';
		if (isset($this->options['orderby'])) {$orderdate = $this->options['orderby'];}

		// in the case of order
		if ($orderdate==0){
			$query->order('e.next');
		}
		if ($orderdate==1){
			$query->order('e.next DESC');
		}
		if ($orderdate==2){
			$query->order('e.next ASC');
		}

		$getpage=JRequest::getVar('page');
		$numberperpage = '5';
		if (isset($this->options['number'])) {$numberperpage = $this->options['number'];}
		if (!$getpage){
			$start='0';
		}else{
			$start = $numberperpage*($getpage-1);
		}
		$query.=' LIMIT '.$start.', '.$numberperpage;

		$db->setQuery($query);
		$loaddb = $db->loadObjectList();

		return $loaddb;
	}


	/**
	 * Event list
	 */

	// CONTAINER
	protected function container ($atr){
		$contDef=new stdClass;
		foreach ($atr as $k=>$v){
			$contDef->$k=$this->$k();
		}
		return $contDef;
	}


	// Header Events List
	protected function header ()
	{
		// Preparing connection to db
		$db	= $this->getDbo();

		// Preparing the query
		$query = $db->getQuery(true);

		// Selectable items
		$query->select('next AS tNext, dates AS tDates, period AS tPeriod, id AS tId, state AS tState, access AS tAccess');
		$query->from('`#__icagenda_events` AS e');
		$query->where(' e.state = 1 ');
		$db->setQuery($query);

		$allnext = $db->loadObjectList();

		// declare the controls
		$ctrlNext=NULL;
		$ctrlBack=NULL;
		$getpage=JRequest::getVar('page');

		// check if there are other events after those selected
		$db	= $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('count(e.id) AS max')->from('#__icagenda_events AS e');


		$filters=$this->filters;
		$where='e.state ='.$filters['state'];
		unset($filters['state']);
		foreach($filters as $k=>$v){

			// normal cases
			if ($k!='key' && $k!='next' && $k!='e.catid' && $k!='e.id'){
				$where.=' AND '.$k.' LIKE "%'.$v.'%"';
			}

			// in the case of time
			if ($k=='next'){
				if($v==1) $where.=' AND e.next >= (CURDATE())';
				if($v==2) $where.=' AND e.next < (CURDATE())';
				if($v==3) $where.=' AND e.next >= (NOW())';
				if($v==4) $where.=' AND (e.next >= (CURDATE()) AND (e.next < (CURDATE() + INTERVAL 1 DAY)))';
			}

		}
		$numberperpage = '5';
		if (isset($this->options['number'])) {$numberperpage = $this->options['number'];}
		if (!$getpage){
			$start='0';
		}else{
			$start = $numberperpage*($getpage-1);
		}
		$query.=' LIMIT '.$start.', '.$numberperpage;

		$db->setQuery($query);
		$dbmax=$db->loadObject();
		$max='';
		if(isset($dbmax->max))$max=$dbmax->max;
		if ($max > $numberperpage)$ctrlNext=1;

		// first check whether there are elements of those selected
		if($getpage && $getpage>1)$ctrlBack=1;

		// Variables declaration
		$nbevents='';$total='';$timetitle='';$timetext='';$timetext1='';$noevt='';


		// Preparing connection to db
		$db	= $this->getDbo();

		// Preparing the query
		$query = $db->getQuery(true);

		// Adding Filter per Category in Navigation
		if (isset($this->options['mcatid'])) {
			$selcat = array();
			$selcat = $this->options['mcatid'];
			if(!is_array($selcat)) $selcat=array(''.$selcat.'');
			if (in_array('0', $selcat)){
				$selcat=array('e.catid');
			}
//			else {
//				$selcat=unserialize($selcat);
				$selcat=implode(', ', $selcat);
//			}
		}

		// Language Control
 		$lang = JFactory::getLanguage();
		$langcur = $lang->getTag();
		$langcurrent = $langcur;

		// Access Control
		$user = JFactory::getUser();
		$userID = $user->id;
		$userLevels = $user->getAuthorisedViewLevels();
		if(version_compare(JVERSION, '3.0', 'lt')) {
			$userGroups = $user->getAuthorisedGroups();
		} else {
			$userGroups = $user->groups;
		}
		$groupid = JComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));

		jimport( 'joomla.access.access' );
		$adminUsersArray = array();
		foreach ($groupid AS $gp) {
			$adminUsers = JAccess::getUsersByGroup($gp, False);
			$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
		}


		$nodate = '0000-00-00 00:00:00';

		$query->select('count(id) AS nbevents')->from('#__icagenda_events AS e');

		if ($v==0){
			$timetitle=''.JText::_( 'COM_ICAGENDA_ALL_TITLE' ).'';
			$timetext=''.JText::_( 'COM_ICAGENDA_ALL_TEXT' ).'';
			$timetext1=''.JText::_( 'COM_ICAGENDA_ALL_TEXT_ONE' ).'';
			$noevt=''.JText::_( 'COM_ICAGENDA_ALL_NO_EVENT_TEXT' ).'';
			$where= "(e.state = 1) AND (e.next != '$nodate')";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==1){
			$timetitle=''.JText::_( 'COM_ICAGENDA_FUTURE_TITLE' ).'';
			$timetext=''.JText::_( 'COM_ICAGENDA_FUTURE_TEXT' ).'';
			$timetext1=''.JText::_( 'COM_ICAGENDA_FUTURE_TEXT_ONE' ).'';
			$noevt=''.JText::_( 'COM_ICAGENDA_FUTURE_NO_EVENT_TEXT' ).'';
			$where= "(e.state = 1) AND (e.next >= (CURDATE())) AND (e.next != '$nodate')";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==2){
			$timetitle=''.JText::_( 'COM_ICAGENDA_PAST_TITLE' ).'';
			$timetext=''.JText::_( 'COM_ICAGENDA_PAST_TEXT' ).'';
			$timetext1=''.JText::_( 'COM_ICAGENDA_PAST_TEXT_ONE' ).'';
			$noevt=''.JText::_( 'COM_ICAGENDA_PAST_NO_EVENT_TEXT' ).'';
			$where= "(e.state = 1) AND (e.next < (CURDATE())) AND (e.next != '$nodate')";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==3){
			$where= "(e.next >= (NOW())) AND (e.next != '$nodate') AND (e.state = 1)";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==4){
			$where= "(e.next >= (CURDATE())) AND (e.next < (CURDATE() + INTERVAL 1 DAY)) AND (e.next != '$nodate') AND (e.state = 1)";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}



		// Test if user has Access Permissions
		if ( !in_array('8', $userGroups) )  {
			$useraccess = implode(', ', $userLevels);
			$where.=' AND e.access IN ('.$useraccess.')';
		}

		// Test if user logged-in has Approval Rights
		if (!in_array($userID, $adminUsersArray) AND !in_array('8', $userGroups)) {
			$where.=' AND e.approval <> 1';
		} else {
			$where.=' AND e.approval < 2';
		}

		$query->where($where);
		$db->setQuery($query);
		$nbevents=$db->loadObject()->nbevents;

		$total=$nbevents;

		if ($v==3){
			$header_title	= JText::_( 'COM_ICAGENDA_HEADER_UPCOMING_TITLE' );
			$header_many	= JText::sprintf( 'COM_ICAGENDA_HEADER_UPCOMING_MANY_EVENTS', $nbevents );
			$header_one		= JText::sprintf( 'COM_ICAGENDA_HEADER_UPCOMING_ONE_EVENT', $nbevents );
			$header_noevt	= JText::_( 'COM_ICAGENDA_HEADER_UPCOMING_NO_EVENT' );
		}

		if ($v==4){
			$header_title	= JText::_( 'COM_ICAGENDA_HEADER_TODAY_TITLE' );
			$header_many	= JText::sprintf( 'COM_ICAGENDA_HEADER_TODAY_MANY_EVENTS', $nbevents );
			$header_one		= JText::sprintf( 'COM_ICAGENDA_HEADER_TODAY_ONE_EVENT', $nbevents );
			$header_noevt	= JText::_( 'COM_ICAGENDA_HEADER_TODAY_NO_EVENT' );
		}

		$report='';$report2='';$report3='';$report4='';


		if (($v==3) OR ($v==4)){
			$timetitle = $header_title;
			if ($nbevents==1) {
				$report.= '<small> '.$header_one.' </small>';
			}
			if ($nbevents==0) {
				$report.= '<small> '.$header_noevt.' </small>';
			}
			if ($nbevents>1) {
				$report.= '<small> '.$header_many.' </small>';
			}
		} else {
			if ($nbevents==1) {
				$report.= '<small> '.JText::_( 'COM_ICAGENDA_ONE_EVENT_TEXT' ).' '.$total.' '.$timetext1.' </small>';
			}
			if ($nbevents==0) {
				$report.= '<small> '.$noevt.' </small>';
			}
			if ($nbevents>1) {
				$report.= '<small> '.JText::_( 'COM_ICAGENDA_EVENTS_TEXT' ).' '.$total.' '.$timetext.' </small>';
			}
		}

		$num=$numberperpage;

		// No display if number not exist (checked in 1.2.8)
		if ($num == NULL) {
			$pages= 1;
		}
		else {
			$pages=ceil($total/$num);
		}

			$pon=$getpage;
		if (JRequest::getVar('page')==NULL){
			$pon=1;
		}
		if ($pages<=1) {
			$report2.= '<br/><br/>';
		}

		else {
			$report2.= ' <small> - '.JText::_( 'COM_ICAGENDA_EVENTS_PAGE' ).' '.$pon.' / '.$pages.'</small><br/><br/>';
		}

		$report3.= ' <small> '.JText::_( 'COM_ICAGENDA_EVENTS_PAGE' ).' '.$pon.' / '.$pages.'</small><br/><br/>';

		$report4.= '<br> '.JText::_( 'COM_ICAGENDA_SEARCH_RESULTS' ).' &nbsp; ';


		// create the navigator

		$allcat = '';
		if (isset($this->filters['mcatid'])) {$allcat = $this->filters['mcatid'];}

		if ($k==0  &&  $k!='key') {

		$headerList = '1';
		if (isset($this->options['headerList'])) {$headerList = $this->options['headerList'];}


		$app = JFactory::getApplication();

		$menuItem = $app->getMenu()->getActive();

    	if ($menuItem->params->get('show_page_heading', 1)) {
			$tag = 'h2';
		} else {
			$tag = 'h1';
		}
		if ($headerList == 1) $header='<div><'.$tag.'> '.$timetitle.' <br/></'.$tag.'> '.$report.' '.$report2.'';
		if ($headerList == 2) $header='<div><'.$tag.'> '.$timetitle.' </'.$tag.'><br/>';
		if ($headerList == 3) $header='<div><br/>'.$report.' '.$report2.'';
		if ($headerList == 4) $header='<div><br/>';

		}
		elseif ($k=='e.catid'  &&  $k!='key') {
			$header='<div>'.$report3;
		}

		else {
			$header='<div>'.$report4;
		}



		$header.='</div>';
		return $header;
	}

	// Navigator Events list
	protected function navigator ()
	{
		// Preparing connection to db
		$db	= $this->getDbo();

		// Preparing the query
		$query = $db->getQuery(true);

		// Selectable items
		$query->select('next AS tNext, dates AS tDates, period AS tPeriod, id AS tId, state AS tState, access AS tAccess');
		$query->from('`#__icagenda_events` AS e');
		$query->where(' e.state = 1 ');
		$db->setQuery($query);

		$allnext = $db->loadObjectList();


		// declare the controls
		$ctrlNext=NULL;
		$ctrlBack=NULL;
		$getpage=JRequest::getVar('page');

		// check if there are other events after those selected
		$db	= $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('count(e.id) AS max')->from('#__icagenda_events AS e');

		$filters=$this->filters;
		$where='e.state ='.$filters['state'];
		unset($filters['state']);
		foreach($filters as $k=>$v){



			// normal cases
			if ($k!='key' && $k!='next' && $k!='e.catid' && $k!='e.id'){
				$where.=' AND '.$k.' LIKE "%'.$v.'%"';
			}

			// in the case of time
			if ($k=='next'){
				if($v==1) $where.=' AND e.next >= (CURDATE())';
				if($v==2) $where.=' AND e.next < (CURDATE())';
				if($v==3) $where.=' AND e.next >= (NOW())';
				if($v==4) $where.=' AND (e.next >= (CURDATE()) AND (e.next < (CURDATE() + INTERVAL 1 DAY)))';
			}

		}
		$numberperpage = '5';
		if (isset($this->options['number'])) {$numberperpage = $this->options['number'];}
		if (!$getpage){
			$start='0';
		}else{
			$start = $numberperpage*($getpage-1);
		}
		$query.=' LIMIT '.$start.', '.$numberperpage;
		$db->setQuery($query);
		$dbmax=$db->loadObject();
		$max='';
		if(isset($dbmax->max))$max=$dbmax->max;
		if ($max > $numberperpage)$ctrlNext=1;

		// first check whether there are elements of those selected
		if($getpage && $getpage>1)$ctrlBack=1;

		$nbevents='';$total='';$timetitle='';$timetext='';$timetext1='';$noevt='';
		$db	= $this->getDbo();
		$query = $db->getQuery(true);


		// Adding Filter per Category in Navigation
		if (isset($this->options['mcatid'])) {
			$selcat = array();
			if (isset($this->options['mcatid'])) {$selcat = $this->options['mcatid'];}
			if(!is_array($selcat)) $selcat=array(''.$selcat.'');
			if (in_array('0', $selcat)){
				$selcat=array('e.catid');
			}
//			else {
//				$selcat=unserialize($selcat);
				$selcat=implode(', ', $selcat);
//			}
		}

		// Language Control
 		$lang = JFactory::getLanguage();
		$langcur = $lang->getTag();
		$langcurrent = $langcur;

		// Access Control
		$user = JFactory::getUser();
		$userID = $user->id;
		$userLevels = $user->getAuthorisedViewLevels();
		if(version_compare(JVERSION, '3.0', 'lt')) {
			$userGroups = $user->getAuthorisedGroups();
		} else {
			$userGroups = $user->groups;
		}
		$groupid = JComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));

		jimport( 'joomla.access.access' );
		$adminUsersArray = array();
		foreach ($groupid AS $gp) {
			$adminUsers = JAccess::getUsersByGroup($gp, False);
			$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
		}

		$query->select('count(id) AS nbevents')->from('#__icagenda_events AS e');

		if ($v==0){
			$where= "(e.state = 1)";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==1){
			$where= "(e.next >= (CURDATE())) AND (e.state = 1)";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==2){
			$where= "(e.next < (CURDATE())) AND (e.state = 1)";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==3){
			$where= "(e.next >= (NOW())) AND (e.state = 1)";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		if ($v==4){
			$where= "(e.next >= (CURDATE())) AND (e.next < (CURDATE() + INTERVAL 1 DAY)) AND (e.state = 1)";
			if (isset($this->options['mcatid'])) {
				$where.= " AND (e.catid IN ($selcat))";
			}
			$where.= " AND ((e.language = '$langcurrent') OR (e.language = '*') OR (e.language = NULL) OR (e.language = ''))" ;
		}

		// Test if user has Access Permissions
		if ( !in_array('8', $userGroups) )  {
			$useraccess = implode(', ', $userLevels);
			$where.=' AND e.access IN ('.$useraccess.')';
		}

		// Test if user logged-in has Approval Rights
		if (!in_array($userID, $adminUsersArray) AND !in_array('8', $userGroups)) {
			$where.=' AND e.approval <> 1';
		} else {
			$where.=' AND e.approval < 2';
		}

		$query->where($where);
		$db->setQuery($query);
		$nbevents=$db->loadObject()->nbevents;
		$total=$nbevents;

		$num=$numberperpage;

		// No display if number not exist (checked in 1.2.8)
		if ($num == NULL) {
			$pages= 1;
		}
		else {
			$pages=ceil($total/$num);
		}

			$pon=$getpage;
		if (JRequest::getVar('page')==NULL){
			$pon=1;
		}


		// create the navigator

		$allcat = '';
		if (isset($this->filters['mcatid'])) {$allcat = $this->filters['mcatid'];}

		if ($k==0  &&  $k!='key') {
			$nav='<div class="navigator">';
		}
		elseif ($k=='e.catid'  &&  $k!='key') {
			$nav='<div class="navigator">';
		}

		else {
			$nav='<div class="navigator">';
		}



		// idea by L.V. : insert text with forward/back arrows.
		$arrowtext = NULL;
		if(isset($this->options['arrowtext']))$arrowtext = $this->options['arrowtext'];
		// in the case of text next/prev
				if ($arrowtext==1){
					$textnext=JText::_( 'JNEXT' );
					$textback=JText::_( 'JPREV' );
				}

		$parentnav = $this->options['Itemid'];


		$mainframe = JFactory::getApplication();
		$isSef = $mainframe->getCfg( 'sef' );

		if ($isSef == '1') {
			$urlback=JRoute::_(JURI::current().'?');
			$urlnext=JRoute::_(JURI::current().'?');
		}
		if ($isSef == '0') {
			$urlback='index.php?option=com_icagenda&view=list&Itemid='.(int)$parentnav.'&';
			$urlnext='index.php?option=com_icagenda&view=list&Itemid='.(int)$parentnav.'&';
		}

		if ($pages>=2) {

		if ($ctrlBack!=NULL){
			if ($getpage && $getpage<$pages) {
				$pageBack=$getpage-1;
				$pageNext=$getpage+1;
				$nav.='<a class="icagenda_back" href="'.$urlback.'page='.$pageBack.'"><span aria-hidden="true" class="iCicon-backic"></span> '.$textback.'&nbsp;</a>';
				$nav.='<a class="icagenda_next" href="'.$urlnext.'page='.$pageNext.'">&nbsp;'.$textnext.' <span aria-hidden="true" class="iCicon-nextic"></span></a>';
			}
			else {
				$pageBack=$getpage-1;
				$nav.='<a class="icagenda_back" href="'.$urlback.'page='.$pageBack.'"><span aria-hidden="true" class="iCicon-backic"></span> '.$textback.'&nbsp;</a>';
			}
		}
		if ($ctrlNext!=NULL){
			if(!$getpage){
				$pageNext=2;
			}
			else{
				$pageNext=$getpage+1;
				$pageBack=$getpage-1;
			}
			$nav.='<a class="icagenda_next" href="'.$urlnext.'page='.$pageNext.'">&nbsp;'.$textnext.' <span aria-hidden="true" class="iCicon-nextic"></span></a>';
		}

		}

		$nav.='</div>';


		return $nav;
	}




	/**
	 *
	 * ALL DATES
	 *
	 */

	protected function AllDates ($i){

		// Get Data
		$tNext=$i->next;
		$tDates=$i->dates;
		$tId=$i->id;
		$tState=$i->state;
		$tEnddate=$i->enddate;
		$tStartdate=$i->startdate;
		$tWeekdays=$i->weekdays;

		// Declare AllDates array
		$AllDates = array();

		// Get WeekDays setting
		if (isset($tWeekdays)) {$weekdays = $tWeekdays;} else {$weekdays = '';}

		$weekdays = explode (',', $weekdays);
		$weekdaysarray = array();

		foreach ($weekdays as $wed) {
			array_push($weekdaysarray, $wed);
		}

		if (in_array('', $weekdaysarray)) {
			$arrayWeekDays = array(0,1,2,3,4,5,6);
		}
		elseif ($tWeekdays) {
			$arrayWeekDays = $weekdaysarray;
		}
			elseif (in_array('0', $weekdaysarray)) {
				$arrayWeekDays = $weekdaysarray;
			}
		else {
			$arrayWeekDays = array(0,1,2,3,4,5,6);
		}
		$WeeksDays = $arrayWeekDays;

		// If Single Dates, added each one to All Dates for this event
		$singledates = unserialize($tDates);

		foreach($singledates as $sd) {
			if(strtotime($sd)) {
				array_push($AllDates, $sd);
			}
		}

		// If Period Dates, added each one to All Dates for this event (filter week Days, and if date not null)
		$perioddates = '';
		$StDate = date('Y-m-d H:i', $this->mkt($tStartdate));
		$EnDate = date('Y-m-d H:i', $this->mkt($tEnddate));
		if (strtotime($tStartdate) AND strtotime($tEnddate)) $perioddates = $this->getDatesPeriod($StDate, $EnDate);

		$onlyStDate='';
		if (isset($this->onlyStDate)) $onlyStDate=$this->onlyStDate;

		if ((isset ($perioddates)) AND ($perioddates!=NULL)) {
//			if ($onlyStDate==1) {
//				array_push($AllDates, $StDate);
//			} else {
				foreach ($perioddates as $Dat) {
					if (in_array(date('w', strtotime($Dat)), $WeeksDays)) {
						// May not work in php < 5.2.3 (should return false if date null since 5.2.4)
						$datevalid = strtotime($Dat);
						if ($datevalid) {
							$SingleDate = date('Y-m-d H:i', $this->mkt($Dat));
							array_push($AllDates, $SingleDate);
						}
					}
				}
//			}

		}
		return $AllDates;
	}

	/**
	 *
	 * EVENT DETAILS
	 *
	 */

	protected function iCparams (){
		$iCparams = JComponentHelper::getParams('com_icagenda');
		return $iCparams;
	}
	protected function template (){return $this->options['template'];}
	protected function displaytime ($i){return $i->displaytime;}
	protected function cat_desc ($i){return $i->cat_desc;}
	protected function access ($i){return $i->access;}
	protected function CurrentItemid ($i){
		// Get Current Itemid
		$this_itemid = JRequest::getInt('Itemid');
		return $this_itemid;
	}

	protected function BackURL ($i){
		// Get Current Itemid
		//$this_itemid = JRequest::getInt('Itemid');

		//$BackURL = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=list&Itemid='.$this_itemid));
		$BackURL = 'javascript:history.go(-1)';

		return $BackURL;
	}

	protected function BackArrow ($i){
		// Get Current Itemid
		$this_itemid = JRequest::getInt('Itemid');

		$layout = '';
		$layout = JRequest::getVar('layout');
		$manageraction = '';
		$manageraction = JRequest::getVar('manageraction');
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		if ($layout != '' AND !$manageraction) {
			if ( $referer != "") {
				$BackArrow = '<a href="'. str_replace(array('"', '<', '>', "'"), '', $referer) .'" title="'. JText::_( 'COM_ICAGENDA_BACK' ) .'"><span aria-hidden="true" class="iCicon-backic"></span> <span class="small">'. JText::_( 'COM_ICAGENDA_BACK' ) .'</span></a>';
			} else {
				return false;
			}
		} elseif ($manageraction) {
			$BackArrow = '<a href="'. str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&Itemid='.$this_itemid)) .'" title="'. JText::_( 'COM_ICAGENDA_BACK' ) .'"><span aria-hidden="true" class="iCicon-backic"></span> <span class="small">'. JText::_( 'COM_ICAGENDA_BACK' ) .'</span></a>';
		} else {
			return false;
		}

		return $BackArrow;
	}
	protected function ApprovedNotification ($creatorEmail, $eventUsername, $eventTitle, $eventLink){

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

		// Create User Mailer
		$approvedmailer = JFactory::getMailer();

		// Set Sender of Notification Email
		$approvedmailer->setSender(array( $mailfrom, $fromname ));

		// Set Recipient of Notification Email
		$approvedmailer->addRecipient($creatorEmail);

		// Set Subject of Notification Email
		$approvedsubject = JText::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_SUBJECT', $eventTitle);
		$approvedmailer->setSubject($approvedsubject);

		// Set Body of Notification Email
		$approvedbodycontent = JText::sprintf( 'COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_HELLO', $eventUsername).',<br /><br />';
		$approvedbodycontent.= JText::sprintf( 'COM_ICAGENDA_APPROVED_USEREMAIL_BODY_INTRO', $sitename).'<br /><br />';
		$approvedbodycontent.= JText::_( 'COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK' ).'<br />';
		$approvedbodycontent.= '<a href="'.$eventLink.'">'.$eventLink.'</a><br /><br />';
		$approvedbodycontent.= '<hr><small>'.JText::_( 'COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK_INFO' ).'</small><br /><br />';

		$approvedbody = rtrim($approvedbodycontent);

		$approvedmailer->isHTML(true);
		$approvedmailer->Encoding = 'base64';

		$approvedmailer->setBody($approvedbody);

		// Send User Notification Email
		if (isset($creatorEmail)) {
			$send = $approvedmailer->Send();
		}


	}
	protected function ManagerIcons ($i){

		$app = JFactory::getApplication();

		// Get Current Itemid
		$this_itemid = JRequest::getInt('Itemid');

		// Get Current Url
		$returnURL = base64_encode(JURI::getInstance()->toString());

		// Set Manager Actions Url
		$managerActionsURL = 'index.php?option=com_icagenda&view=list&layout=event&id='.$i->id.'&Itemid='.$this_itemid;

		// Set Email Notification Url to event
		$linkEmailUrl = JURI::base().'index.php?option=com_icagenda&view=list&layout=event&id='.$i->id.'&Itemid='.$this_itemid;

		// Get Approval Status
		$approved=$i->approval;

		// Get User groups allowed to approve event submitted
		$groupid = $this->iCparams()->get('approvalGroups', array("8"));

		jimport( 'joomla.access.access' );
		$adminUsersArray = array();
		foreach ($groupid AS $gp) {
			$adminUsers = JAccess::getUsersByGroup($gp, False);
			$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
		}

		// Get User Infos
		$user	= JFactory::getUser();

		$icid	= $user->get('id');
		$icu	= $user->get('username');
		$icp	= $user->get('password');

		// Get User groups of the user logged-in
		if(version_compare(JVERSION, '3.0', 'lt')) {
			$userGroups = $user->getAuthorisedGroups();
		} else {
			$userGroups = $user->groups;
		}


		$baseURL = JURI::base();
		$subpathURL = JURI::base(true);

		$baseURL = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		$urlcheck = str_replace('&amp;','&', JRoute::_('administrator/index.php?option=com_icagenda&view=events').'&icu='.$icu.'&icp='.$icp.'&filter_search='.$i->id);

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL Event Check filtering
		$urlcheck = ltrim($urlcheck, '/');
		if(substr($urlcheck,0,strlen($subpathURL)+1) == "$subpathURL/") $urlcheck = substr($urlcheck,strlen($subpathURL)+1);
		$urlcheck = rtrim($baseURL,'/').'/'.ltrim($urlcheck,'/');

		$icu='';
		if(JRequest::getVar('icu'))$icu = JRequest::getVar('icu');

		$icu_approve = '';
		if(JRequest::getVar('manageraction'))$icu_approve = JRequest::getVar('manageraction');

		$icu_layout = '';
		if(JRequest::getVar('layout'))$icu_layout = JRequest::getVar('layout');

		if (in_array($icid, $adminUsersArray) OR in_array('8', $userGroups)) {
			if($approved == 1) {

				$unapproved = '';
				if(version_compare(JVERSION, '3.0', 'lt')) {
					$unapproved.= '<a class="icTip" href="'.JRoute::_($managerActionsURL.'&manageraction=approve').'" title="'.JText::_( 'COM_ICAGENDA_APPROVE_AN_EVENT_LBL' ).'"><div class="icicon approval"></div></a>';
					$approveIcon = '<span class="icicon approval"></span>';
 				} else {
					$unapproved.= '<a class="icTip" href="'.JRoute::_($managerActionsURL.'&manageraction=approve').'" title="'.JText::_( 'COM_ICAGENDA_APPROVE_AN_EVENT_LBL' ).'"><button type="button" class="btn btn-micro btn-warning btn-xs"><i class="icon-checkmark"></i></button></a>';
					$approveIcon = '<button class="btn btn-micro btn-warning btn-xs "><i class="icon-checkmark"></i></button>';
				}

				if (($icu_layout == 'event') AND ($icu_approve != 'approve')) {
					$alertmsg = JText::sprintf('COM_ICAGENDA_APPROVE_AN_EVENT_NOTICE', $approveIcon);
					$alerttitle = JText::_( 'COM_ICAGENDA_APPROVE_AN_EVENT_LBL' );
					$alerttype = 'notice';
				}

				$approval = $unapproved;
				if ($icu_approve == 'approve') {
        			$db		= Jfactory::getDbo();
					$query	= $db->getQuery(true);
        			$query->clear();
					$query->update(' #__icagenda_events ');
					$query->set(' approval = 0 ' );
					$query->where(' id = ' . (int) $i->id );
					$db->setQuery((string)$query);
					$db->query($query);
					$approveSuccess = '"'.$i->title.'"';
					$alertmsg = JText::sprintf('COM_ICAGENDA_APPROVED_SUCCESS', $approveSuccess);
					$alerttitle = JText::_( 'COM_ICAGENDA_APPROVED' );
					$alerttype = 'success';
					$approvedLink = JRoute::_($managerActionsURL);

					self::ApprovedNotification($i->created_by_email, $i->username, $i->title, $linkEmailUrl);
					$app->enqueueMessage($alertmsg, $alerttitle, $alerttype);

				} else {
					if (!empty($alertmsg)) {
						$app->enqueueMessage($alertmsg, $alerttitle, $alerttype);
					}

					return $approval;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	protected function send_email ($email){
		$send = $email;
		return $send;
	}
	protected function evtID ($i){return $i->id;}
	protected function place_name ($i){return $i->place_name;}
	protected function address ($i){return $i->address;}
	protected function phone ($i){return $i->phone;}
	protected function email ($i){return $i->email;}
	protected function emailLink ($i){$emailcloack=''; $email=$i->email; if ($email != NULL) { $emailcloack= JHtml::_('email.cloak', $email); return $emailcloack; } }
	protected function website ($i){return $i->website;}
	protected function city ($i){return $i->city;}
	protected function country ($i){return $i->country;}
	protected function image ($i){
		$ic_image = JURI::base().$i->image;
		if($i->image) {
			return $ic_image;
		} else {
			return false;
		}
	}
	protected function file ($i){return $i->file;}


	// Get Items
	protected function items ($atr){

		// Initialize controls
		$access='0';
		$control='';

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.title, a.published, a.id')
			->from('`#__menu` AS a')
			->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0)" );
		$db->setQuery($query);
		$link = $db->loadObjectList();
		$itemid = JRequest::getVar('Itemid');

		$parentnav = $itemid;

		foreach ($link as $l){
			if (($l->published == '1') AND ($l->id == $parentnav)) {
				$linkexist = '1';
			}
		}

		if (is_numeric($parentnav) && !is_array($parentnav) && !$parentnav==0 && $linkexist==1) {

		$atr=$atr['item'];
		$items=$this->items;
		$itDef=new stdClass;


		if ($this->items == NULL) { return NULL; }
		else {
//$allevents = array();

//foreach ($items AS $event) {
//	$allDates = $this->AllDates($event);
//	$evtID = $this->evtID($event);
//	$allevents = array_merge($allevents, $allDates);
//}
			foreach($items as $i){


				// Access Control
//				$user = JFactory::getUser();
//				$userLevels = $user->getAuthorisedViewLevels();
//				$access=$i->access;
//				if ($access == '0') { $access='1'; }

//				foreach ($userLevels as $level){
//					if ($level == $access) {
//						$control=$access;
//					}
//				}

				// Language Control
				$lang = JFactory::getLanguage();
				$eventLang = '';
				$langTag = '';
				$langTag = $lang->getTag();

				if(isset($i->language)) $eventLang=$i->language;
				if($eventLang=='') $eventLang=$langTag;
				if($eventLang=='*') {
					$eventLang=$langTag;
				}

				if ($i->next != '0000-00-00 00:00:00') {
//				if ($control == $access) {
//					if ($eventLang == $langTag) {
						$i=$this->ctrlNext($i);
						$it=new stdClass;
						$id=$i->id;
						foreach($atr as $k=>$v){
							// Corrige Notice : Undefined property: stdClass::
							if(!empty($i->$k)){
								// functions
								$it->$k=$i->$k;
							}else{
								// data
								if(method_exists($this, $k)){$it->$k=$this->$k($i);}
							}
						}
//					foreach ($allevents AS $evt) {
						$itDef->$id=$it;
//					}
//					}
//				}
				}

			}
		}
		return $itDef;
		}
		else {
			$this->error404();
		}

	}

	public static function error404() {
		JError::raiseError('404', '<div>&nbsp;</div><div class="hero-unit center"><h1>' . JTEXT::_('COM_ICAGENDA_PAGE_NOT_FOUND') . ' <small><font face="Tahoma" color="red">' . JTEXT::_('JERROR_ERROR') . ' 404</font></small></h1><br /><p>' . JTEXT::_('COM_ICAGENDA_REQUESTED_PAGE_NOT_FOUND') . ', ' . JTEXT::_('COM_ICAGENDA_CONTACT_THE_WEBMASTER_OR_TRY_AGAIN') . '. ' . JTEXT::_('COM_ICAGENDA_USE_YOUR_BROWSERS_BACK_BUTTON') . '</p><p><b>' . JTEXT::_('COM_ICAGENDA_OR_JUST_PRESS_BUTTON') . '</b></p><a href="index.php" class="btn btn-large btn-info button"><i class="icon-home icon-white"></i>&nbsp;' . JTEXT::_('JERROR_LAYOUT_HOME_PAGE') . '</a></div><div align="center"><img src="media/com_icagenda/images/iconicagenda48.png"></div>', '404');
		return false;
	}



	// Get event Url
	protected function url ($i){

		$lien = $this->options['Itemid'];
		$eventnumber = $i->id;

		$urlevent = JRoute::_('index.php?option=com_icagenda&view=list&layout=event&id='.(int)$eventnumber.'&Itemid='.(int)$lien);
		$url=$urlevent;
		if (is_numeric($lien) && is_numeric($eventnumber) && !is_array($lien) && !is_array($eventnumber)) {
			return $url;
		}
		else {
			$url = JRoute::_('index.php');
			return $url;
		}

	}

	// Title with link to details
	protected function titleLink ($i){
		$title=$i->title;
		$url=$this->url($i);
		return '<a href="'.$url.'">'.$title.'</a>';
	}

	// Title + Manager Icons
	protected function titlebar ($i){
		$title=$i->title;
		$approval=$i->approval;

		// Get Current Itemid
		$this_itemid = JRequest::getInt('Itemid');

		// Set Manager Actions Url
		$managerActionsURL = 'index.php?option=com_icagenda&view=list&layout=event&id='.$i->id.'&Itemid='.$this_itemid;
		$unapproved='';
		if(version_compare(JVERSION, '3.0', 'lt')) {
			$unapproved.= '<a class="icTip" href="'.JRoute::_($managerActionsURL).'" title="'.JText::_( 'COM_ICAGENDA_APPROVE_AN_EVENT_LBL' ).'"><small><span class="iCicon-open-details"></span></small></a>';
 		} else {
			$unapproved.= '<a class="icTip" href="'.JRoute::_($managerActionsURL).'" title="'.JText::_( 'COM_ICAGENDA_APPROVE_AN_EVENT_LBL' ).'"><small><span class="iCicon-open-details"></span></small></a>';
		}
		if (($title != NULL) AND ($approval == 1)) {
			return $title.' '.$unapproved;
		} elseif (($title != NULL) AND ($approval != 1)) {
			return $title;
		} else {
			return NULL;
		}
	}

	// Title
	protected function title ($i){
		$title=$i->title;
		if ($title != NULL) {
			return $title;
		} else {
			return NULL;
		}
	}

	// Description
	protected function desc ($i){
		$desc=$i->desc;
		if ($desc != NULL) {
			return $desc;
		} else {
			return NULL;
		}
	}

	protected function description ($i){
		$description=$i->desc;
		if ($description != NULL) {
			$text = JHTML::_('content.prepare', $description);
			return $text;
		} else {
			return NULL;
		}
	}

	// Short Description
	protected function descShort ($i){
		$limit = '100';
		$limitGlobal = '0';
		if (isset($this->options['limitGlobal'])) $limitGlobal=$this->options['limitGlobal'];
		if ($limitGlobal == 1) {
			$limit = JComponentHelper::getParams('com_icagenda')->get('ShortDescLimit');
		}
		if ($limitGlobal == 0) {
			$customlimit=$this->options['limit'];
			if (is_numeric($customlimit)){
				$limit=$this->options['limit'];
			} else {
				$limit = JComponentHelper::getParams('com_icagenda')->get('ShortDescLimit');
			}
		}
		if (is_numeric($limit)) {
			$limit = $limit;
		} else {
			$limit = '1';
		}
		$readmore='';
		if ($limit <= 1) {
			$readmore='';
		} else {
			$readmore='[&#46;&#46;&#46;]';
		}
		$text=preg_replace('/<img[^>]*>/Ui', '', $i->desc);
		if(strlen($text)>$limit){
			$string_cut=substr($text, 0,$limit);
			$last_space=strrpos($string_cut,' ');
			$string_ok=substr($string_cut, 0,$last_space);
			$text=$string_ok.' ';
			$url=$this->url($i);
			$text=strip_tags($text).'<a href="'.$url.'" class="more">'.$readmore.'</a>';
		}else{
			$text=$text;
		}
		return $text;
	}

	// Image
	protected function imageTag ($i){
		//$noimg= '';
		if (!$i->image == NULL) {
			return '<img src="'.$i->image.'"/>';
		}
		//else {
		//	return $noimg;
		//}
	}

	// File
	protected function fileTag ($i){
		return '<a class="icDownload" href="'.$i->file.'" target="_blanck">'.JText::_( 'COM_ICAGENDA_EVENT_DOWNLOAD' ).'</a>';
	}

	// Link Website
	protected function websiteLink ($i){
		$gettarget="_blanck";
		if (isset($this->options['targetLink'])) {$gettarget=$this->options['targetLink'];}
		if ($gettarget == 0){
			$target="_parent";
		} else {
			$target="_blanck";
		}
		$wsLink = $i->website;
		if (filter_var($wsLink, FILTER_VALIDATE_URL)) {
			$link = $wsLink;
		} else {
			$link = 'http://'.$wsLink;
		}

		return '<a href="'.$link.'" target="'.$target.'">'.$wsLink.'</a>';
	}





	protected function nextMkt ($i){
		return $this->mkt($i->next);
	}

	protected function pastDates ($i)
	{
		$dates=unserialize($i->dates);
		$period=unserialize($i->period);
		$toDay=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$hour= date('H');
		$min= date('i');

		$toDay=mktime($hour,$min,0,$m,$day,$y);

		if($period != NULL) {$alldates = array_merge($dates, $period);} else {$alldates = $dates;}
		rsort($alldates);
		$lastDate = $this->mkt($alldates[0]);

		if (($lastDate < $toDay)) {
			$pastDates = '0';
		}
		else {
			$pastDates = '1';
		}
			return $pastDates;
	}



	/**
	 * TIME
	 */

	// Format Time (eg. 00:00)
	protected function evenTime ($i){
		$date_time = $this->mkt($i->next);
 		$time_format = 'H:i';
 		$t_time=date($time_format, $date_time);
		$timeformat='1';
		if (isset($this->options['timeformat'])) {$timeformat=$this->options['timeformat'];}

		if ($timeformat == 1) {
			$lang_time = strftime("%H:%M", strtotime("$t_time"));
		} else {
			$lang_time = strftime("%I:%M %p", strtotime("$t_time"));
		}

		$oldtime = $i->time;
		if ($oldtime != NULL){
			$time = $i->time;
		} else {
			$time = JText::_($lang_time);
		}
		$displayTime=$i->displaytime;
		if (($displayTime == 1) AND($time != '23:59')) {
			return $time;
		} else {
			return NULL;
		}
	}


	/**
	 * DAY
	 */

	// Day
	protected function day ($i){
		return date('d', $this->mkt($i->next));
	}

	// Day of the week, Full - From Joomla language file xx-XX.ini (eg. Saturday)
	protected function weekday ($i){
		$mkt_date=$this->mkt($i->next);
		$l_full_weekday = date("l", $mkt_date);
		$weekday = JText::_($l_full_weekday);
		return $weekday;
	}

	// Day of the week, Short - From Joomla language file xx-XX.ini (eg. Sat)
	protected function weekdayShort ($i){
		$mkt_date=$this->mkt($i->next);
		$l_short_weekday = date("D", $mkt_date);
		$weekdayShort = JText::_($l_short_weekday);
		return $weekdayShort;
	}


	/**
	 * MONTHS
	 */

	// Function used for special characters
	function substr_unicode($str, $s, $l = null) {
    	return join("", array_slice(
		preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}

	// Format Month (eg. December)
	protected function month ($i){
		$mkt_date = $this->mkt($i->next);
//		$dateFormat=date('Y-m-d', $mkt_date);
//		$l_full_month = date("F", strtotime("$dateFormat"));
		$l_full_month = date("F", $mkt_date);
		$lang_month = JText::_($l_full_month);
		$month = $lang_month;
		return $month;
	}

	// Format Month Short - 3 first characters (eg. Dec.) OR Joomla Translation core
	protected function monthShort ($i){
		$Jcore = '1';
		$mkt_date=$this->mkt($i->next);
		$l_full_month = date("F", $mkt_date);
		if ($Jcore == '1') {
			return $this->monthShortJoomla($i);
		}
		else {
			$lang_month = JText::_($l_full_month);
			$monthShort = $this->substr_unicode($lang_month,0,3);
			$space='';
			$point='';
			if(strlen($l_full_month)>3){
				$space='&nbsp;';
				$point='.';
			}
			return $space.$monthShort.$point;
		}
	}

	// Format Month Short Core - From Joomla language file xx-XX.ini (eg. Dec)
	protected function monthShortJoomla ($i){
		$mkt_date=$this->mkt($i->next);
		$l_full_month = date("F", $mkt_date);
		$monthShortJoomla = JText::_($l_full_month.'_SHORT');
		return $monthShortJoomla;
	}

	// Format Month Numeric - (eg. 07)
	protected function monthNum ($i){
		return $this->formatDate($i->next, 'm');
	}


	/**
	 * YEAR
	 */

	// Format Year Numeric - (eg. 2013)
	protected function year ($i){
		return date('Y', $this->mkt($i->next));
	}

	// Format Year Short Numeric - (eg. 13)
	protected function yearShort ($i){
		return date('y', $this->mkt($i->next));
	}


	/**
	 * DATES
	 */

	// Next Date Text
	protected function dateText ($i)
	{
		$dates=unserialize($i->dates);
		$period=unserialize($i->period);
		$toDay=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$toDay=mktime(0,0,0,$m,$day,$y);

		if($period != NULL) {$alldates = array_merge($dates, $period);} else {$alldates = $dates;}

			$nextDate = $i->next;
			$next = $this->mktshort($nextDate);

				$totDates = count($alldates);

		if (($next > $toDay) AND ($totDates > 1)) {
			rsort($alldates);
			$lastdate = '';
			$lastdate = $this->mkt($alldates[0]);
			if ($lastdate == $next) {
				$dateText = ''.JText::_( 'COM_ICAGENDA_EVENT_DATE_LAST' ).'';
			} else {
				$dateText = ''.JText::_( 'COM_ICAGENDA_EVENT_DATE_FUTUR' ).'';
			}

		}
		if (($next > $toDay) AND ($totDates <= 1)) {
			$dateText = ''.JText::_( 'COM_ICAGENDA_EVENT_DATE' ).'';
		}
		if (($next < $toDay) AND ($totDates > 1)) {
			$dateText = ''.JText::_( 'COM_ICAGENDA_EVENT_DATE_PAST' ).'';
		}
		if (($next < $toDay) AND ($totDates <= 1)) {
			$dateText = ''.JText::_( 'COM_ICAGENDA_EVENT_DATE' ).'';
		}
		if ($next == $toDay) {
			$dateText = ''.JText::_( 'COM_ICAGENDA_EVENT_DATE_TODAY' ).'';
		}
			return $dateText;
	}

	// Get Next Date (or Last Date)
	protected function nextDate ($i){

		$today=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$hour= date('H');
		$min= date('i');
		$today=mktime(0,0,0,$m,$day,$y);
		$testDate = $this->mkt($i->next);

		$testDateNext = $this->mktshort($i->next);
		$testPdatestart = $this->mktshort($i->startdate);
		$testPdateend = $this->mktshort($i->enddate);

		if ($testDate == $today) {
			if (($testDateNext == $testPdatestart) AND ($testPdateend == $testPdatestart)) {
				$nextDate = '<b>'.$this->nextPeriod($i).'</b>';
			} else {
				$nextDate = '<b>'.$this->formatDate($i->next).'<span class="evttime"> '.$this->evenTime($i).'</span></b>';
			}
		}
		else {
			if (($testDateNext == $testPdatestart) AND ($testPdateend == $testPdatestart)) {
				$nextDate = $this->nextPeriod($i);
			} else {
				$nextDate = $this->formatDate($i->next).'<span class="evttime"> '.$this->evenTime($i).'</span>';
			}
		}
			return $nextDate;
	}


	// Display next period date if is next date, in nextDate tag
	protected function nextPeriod ($i){

		$startDate=$this->formatDate($i->startdate);
		$endDate=$this->formatDate($i->enddate);
		if ($startDate == $endDate) {
			$start = $this->startDate($i);
			$end = '';
			$timeOneDay = '<span class="evttime">'.$this->startTime($i).' - '.$this->endTime($i).'</span>';
		} else {
			$start = JText::_( 'COM_ICAGENDA_PERIOD_FROM' ).' '.$this->startDate($i).' <span class="evttime">'.$this->startTime($i).'</span>';
			$end = JText::_( 'COM_ICAGENDA_PERIOD_TO' ).' '.$this->endDate($i).' <span class="evttime">'.$this->endTime($i).'</span>';
			$timeOneDay = '';
		}

		$period='<div class="alldates">'.$start.' '.$end.' '.$timeOneDay.'</div>';
		if ($this->periodTest($i) == '1') {
			if (($i->startdate!='0000-00-00 00:00:00') AND ($i->enddate!='0000-00-00 00:00:00')) {
				return $period;
			}
		} else {
			return false;
		}

	}


	// Control Upcoming dates Period
	protected function periodControl ($i){
		$upPeriod = '1';
		$endDate = $i->enddate;
		$mktendDate=$this->mkt($endDate);
		$today=time();
		if ($mktendDate > $today) {
			return $upPeriod;
		}

	}

	// Dates Drop list Registration
	protected function datelistMkt ($i){
		$daysd=unserialize($i->dates);
		if(isset($i->period)) {$daysp=unserialize($i->period);}
		$allDates = $this->AllDates($i);
		$today=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$hour= date('H');
		$min= date('i');
		$toDay=mktime($hour,$min,0,$m,$day,$y);
		$daysptest = '';
		sort($daysd);
		if($i->period!='') {sort($daysp);}
		$daysdtest = $this->mkt($daysd[0]);

		if (($this->periodTest($i) == '1') && (isset($i->period))) {
			$daysptest = $this->mkt($daysp[0]);
		} else {
			$daysptest == '943916400';
		}

//		if (($daysdtest == '943916400') AND ($daysptest != NULL)) {
//			$Testdays=$daysp;
//			foreach ($Testdays as $d){
//				$Testday=$this->mkt($d);
//			}
//		}
//		if (($daysdtest != '943916400') AND ($daysptest != NULL)) {
//			$Testdays=array_merge($daysd, $daysp);
//			foreach ($Testdays as $d){
//				$Testday=$this->mkt($d);
//			}
//		}
//		if (($daysdtest != '943916400') AND ($daysptest == NULL)) {
//			$Testdays=$daysd;
//			foreach ($Testdays as $d){
//				$Testday=$this->mkt($d);
//			}
//		}

		$timeformat='1';
		if (isset($this->options['timeformat'])) {$timeformat=$this->options['timeformat'];}
		if ($timeformat == 1) {
			$lang_time = 'H:i';
		} else {
			$lang_time = 'h:i A';
		}

		$displaytime = $this->displaytime($i);
		$upDays='';
//		if ($Testday > $toDay) {
			if (($daysdtest == '943916400') AND ($daysptest != NULL)) {
				$days=$allDates;
				foreach ($days as $k=>$d){
					$dd=$this->mkt($d);
				if ($dd > $today) {
					$date=$this->formatDate($d);
					if ($displaytime == 1) {
						$upDays[$k]=$date.' - '.date($lang_time, $dd);
					} else {
						$upDays[$k]=$date;
					}
				}
				}
				return $upDays;
			}
			if (($daysdtest != '943916400') AND ($daysptest != NULL)) {
				$days=$allDates;
				sort($days);
				foreach ($days as $k=>$d){
					$dd=$this->mkt($d);
				if ($dd > $today) {
					$date=$this->formatDate($d);
					if ($displaytime == 1) {
						$upDays[$k]=$date.' - '.date($lang_time, $dd);
					} else {
						$upDays[$k]=$date;
					}
				}
				}
				return $upDays;
			}
			if (($daysdtest != '943916400') AND ($daysptest == NULL)) {
				$days=$allDates;
				foreach ($days as $k=>$d){
					$dd=$this->mkt($d);
				if ($dd > $today) {
					$date=$this->formatDate($d);
					if ($displaytime == 1) {
						$upDays[$k]=$date.' - '.date($lang_time, $dd);
					} else {
						$upDays[$k]=$date;
					}
				}
				}
				return $upDays;
			}
//		}
	}

	// All Single Dates in Event Details Page
	protected function datelistUl ($i){

		// Hide/Show Option
		$SingleDates = JComponentHelper::getParams('com_icagenda')->get('SingleDates', 1);

		// Access Levels Option
		$accessSingleDates = JComponentHelper::getParams('com_icagenda')->get('accessSingleDates', 1);

		// Order by Dates
		$SingleDatesOrder = JComponentHelper::getParams('com_icagenda')->get('SingleDatesOrder', 1);

		// List Model
		$SingleDatesListModel = JComponentHelper::getParams('com_icagenda')->get('SingleDatesListModel', 1);

		if ( $SingleDates == 1 )  {
//			if ( $this->accessLevels($accessSingleDates) )  {
				$days=unserialize($i->dates);

				if ($SingleDatesOrder == 1) {
					rsort($days);
				} elseif  ($SingleDatesOrder == 2) {
					sort($days);
				}

				$totDates = count($days);

				$timeformat='1';
				if (isset($this->options['timeformat'])) {$timeformat=$this->options['timeformat'];}
				if ($timeformat == 1) {
					$lang_time = 'H:i';
				} else {
					$lang_time = 'h:i A';
				}

				$displaytime = $this->displaytime($i);

				// Detect if Singles Dates, and no single date with null value
				$displayDates = false;
				$nbDays = count($days);
				foreach ($days as $k=>$a){
					if (($a != '0000-00-00 00:00') AND ($nbDays != 1)){
						$displayDates = true;
					}
				}
				$daysUl = '';

				if ($displayDates) {

					if ($SingleDatesListModel == '2') {

						$n=0;
						$daysUl.='<div class="alldates"><i>'. JText::_( 'COM_ICAGENDA_LEGEND_DATES' ).': </i>';

						foreach ($days as $k=>$a){
							$n=$n+1;
							$fd=$this->formatDate($a);
 							$dd=$this->mkt($a);
							if ($a != '0000-00-00 00:00'){
								if ($displaytime == 1) {
									$timeDate=' <span class="evttime">'.date($lang_time, $dd).'</span>';
								} else {
									$timeDate='';
								}
								if ($n<=($totDates-1)) {
									$daysUl.='<span class="alldates">'.$fd.$timeDate.'</span> - ';
								}
								if ($n==$totDates) {
	   								$daysUl.='<span class="alldates">'.$fd.$timeDate.'</span>';
								}
							}
						}

						$daysUl.='</div>';

					} else {

						$daysUl.='<ul class="alldates">';

						foreach ($days as $k=>$a){
							if ($a != '0000-00-00 00:00'){
								$fd=$this->formatDate($a);
								$dd=$this->mkt($a);

								if ($displaytime == 1) {
									$timeDate=' <span class="evttime">'.date($lang_time, $dd).'</span>';
								} else {
									$timeDate='';
								}
								$daysUl.='<li class="alldates">'.$fd.$timeDate.'</li>';
							}
						}

						$daysUl.='</ul>';

					}
				}

				if (($totDates > '0') && ($a!='0000-00-00 00:00:00')) {
					return $daysUl;
				} else {
					return false;
				}

//			} else {
//				return false;
//			}
		} else {
			return false;
		}
	}

	// Function Period Display in Registration
	protected function periodDisplay ($i){
		if ($this->periodTest($i) == '1') {
			if (($i->startdate!='0000-00-00 00:00:00') OR ($i->enddate!='0000-00-00 00:00:00')) {
				$show = '1';
				return $show;
			}
		}
	}

	// Format Start Date of a period
	protected function startDate ($i){
		$start=$this->formatDate($i->startdate);
		return $start;
	}

	// Format End Date of a period
	protected function endDate ($i){
		$end=$this->formatDate($i->enddate);
		return $end;
	}

	// Start Day of a period (numeric 1)
	protected function startDay ($i){
		$start_day='';
		$start_day = $this->mkt($i->startdate);
 		$day_format = 'd-m-Y';
 		$d_day=date($day_format, $start_day);
		$format = '%e';
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
    		$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
		}
		$startDay = strftime($format, strtotime("$d_day"));
		$day = JText::_($startDay);
		if ($i->startdate!='0000-00-00 00:00:00') {
			return $startDay;
		} else {
			return '&nbsp;&nbsp;';
		}
	}

	// End Day of a period (numeric 1)
	protected function endDay ($i){
		$end_day = '';
		$end_day = $this->mkt($i->enddate);
 		$day_format = 'd-m-Y';
 		$d_day=date($day_format, $end_day);
		$format = '%e';
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
    		$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
		}
		$endDay = strftime($format, strtotime("$d_day"));
		$day = JText::_($endDay);
		if ($i->enddate!='0000-00-00 00:00:00') {
			return $endDay;
		} else {
			return '&nbsp;&nbsp;';
		}
	}

	// End Month of a period (numeric 01)
	protected function endMonthNum ($i){
		$end_month = $this->mkt($i->enddate);
		$endMonth = date("m", $end_month);
		$month = JText::_($endMonth);
		return $month;
	}

	// End Month of a period (text January)
	protected function endMonth ($i){
		$end_month = $this->mkt($i->enddate);
		$endMonth = date("F", $end_month);
		$month = JText::_($endMonth);
		return $month;
	}

	// End Year of a period (numeric 2001)
	protected function endYear ($i){
		$end_year = $this->mkt($i->enddate);
		$endYear = date("Y", $end_year);
		$year = JText::_($endYear);
		return $year;
	}

	// Format Start Time of a period
	protected function startTime ($i){
		$start_time = $this->mkt($i->startdate);

		$timeformat='1';
		if (isset($this->options['timeformat'])) {$timeformat=$this->options['timeformat'];}
		if ($timeformat == 1) {
			$lang_time = 'H:i';
		} else {
			$lang_time = 'h:i A';
		}

 		$time_format = $lang_time;
 		$time=date($time_format, $start_time);

		$displaytime = $this->displaytime($i);
		if ($displaytime == 1) {
			return $time;
		} else {
			return NULL;
		}
	}

	// Format End Time of a period
	protected function endTime ($i){
		$end_time = $this->mkt($i->enddate);

		$timeformat='1';
		if (isset($this->options['timeformat'])) {$timeformat=$this->options['timeformat'];}
		if ($timeformat == 1) {
			$lang_time = 'H:i';
		} else {
			$lang_time = 'h:i A';
		}

 		$time_format = $lang_time;
 		$time=date($time_format, $end_time);

		$displaytime = $this->displaytime($i);
		if ($displaytime == 1) {
			return $time;
		} else {
			return NULL;
		}
	}


	// Display period text width Format Date (eg. from 00-00-0000 to 00-00-0000)
	protected function periodDates ($i){

		// Hide/Show Option
		$PeriodDates = JComponentHelper::getParams('com_icagenda')->get('PeriodDates', 1);

		// Access Levels Option
		$accessPeriodDates = JComponentHelper::getParams('com_icagenda')->get('accessPeriodDates', 1);

		// List Model
		$SingleDatesListModel = JComponentHelper::getParams('com_icagenda')->get('SingleDatesListModel', 1);

		// WeekDays
		$weekdays = $i->weekdays;
		$weekdaysall = false;
		if($weekdays == '') $weekdaysall = true;
		if (!$weekdaysall) {
			$weekdays = explode (',', $weekdays);
			$wdaysArray = array();
			foreach ($weekdays AS $wd) {
				if ($wd == 0) $wdaysArray[] = JText::_( 'SUNDAY' );
				if ($wd == 1) $wdaysArray[] = JText::_( 'MONDAY' );
				if ($wd == 2) $wdaysArray[] = JText::_( 'TUESDAY' );
				if ($wd == 3) $wdaysArray[] = JText::_( 'WEDNESDAY' );
				if ($wd == 4) $wdaysArray[] = JText::_( 'THURSDAY' );
				if ($wd == 5) $wdaysArray[] = JText::_( 'FRIDAY' );
				if ($wd == 6) $wdaysArray[] = JText::_( 'SATURDAY' );
			}
			$last  = array_slice($wdaysArray, -1);
			$first = join(', ', array_slice($wdaysArray, 0, -1));
			$both  = array_filter(array_merge(array($first), $last));
			$wdays = '&#8627; <small><i>'.join(' & ', $both).'</i></small>';
		} else {
			$wdays = '';
		}
		$showDays ='';

		if ( $PeriodDates == 1 )  {
//			if ( $this->accessLevels($accessPeriodDates) )  {

				$startDate=$this->formatDate($i->startdate);
				$endDate=$this->formatDate($i->enddate);
				if ($startDate == $endDate) {
					$start = $this->startDate($i);
					$end = '';
					$timeOneDay = '<span class="evttime">'.$this->startTime($i).' - '.$this->endTime($i).'</span>';
				} else {
					$start = JText::_( 'COM_ICAGENDA_PERIOD_FROM' ).' '.$this->startDate($i).' <span class="evttime">'.$this->startTime($i).'</span>';
					$end = JText::_( 'COM_ICAGENDA_PERIOD_TO' ).' '.$this->endDate($i).' <span class="evttime">'.$this->endTime($i).'</span>';
					$showDays = $wdays;
					$timeOneDay = '';
				}

				if ($SingleDatesListModel == 2) {
					$period='<div class="alldates"><i>'. JText::_( 'COM_ICAGENDA_EVENT_PERIOD' ).': </i>'.$start.' '.$end.' '.$timeOneDay.'<br /><span style="margin-left:30px">'.$showDays.'</span></div>';
//					$period='<div class="alldates">'.$start.' '.$end.' '.$timeOneDay.'</div>';
				} else {
					$period='<ul class="alldates"><li>'.$start.' '.$end.' '.$timeOneDay.'<br />'.$showDays.'</li></ul>';
				}
				if ($this->periodTest($i) == '1') {
					if (($i->startdate!='0000-00-00 00:00:00') AND ($i->enddate!='0000-00-00 00:00:00')) {
						return $period;
					}
				} else {
					return false;
				}

//			} else {
//				return false;
//			}
		} else {
			return false;
		}
	}


	// Function to get Format Date (using option format, and translation)
	protected function formatDate ($d){
		$mkt_date=$this->mkt($d);

		// get Format
		$for = '0';
		if(isset($this->options['format'])) $for=$this->options['format'];

		// default
		if (($for == NULL) OR ($for == '0')) {$for = 'd * m * Y';}

		// update format values, from 2.0.x to 2.1
		if ($for == 'l, d Fnosep Y') {$for = 'l, _ d _ Fnosep _ Y';}
		if ($for == 'D d Mnosep Y') {$for = 'D _ d _ Mnosep _ Y';}
		if ($for == 'l, Fnosep d, Y') {$for = 'l, _ Fnosep _ d, _ Y';}
		if ($for == 'D, Mnosep d, Y') {$for = 'D, _ Mnosep _ d, _ Y';}

		// update format values, from release 2.1.6 and before, to 2.1.7 (using globalization)
		if ($for == 'd m Y') {$for = 'd * m * Y';}
		if ($for == 'd m y') {$for = 'd * m * y';}
		if ($for == 'Y m d') {$for = 'Y * m * d';}
		if ($for == 'Y M d') {$for = 'Y * M * d';}
		if ($for == 'd F Y') {$for = 'd * F * Y';}
		if ($for == 'd M Y') {$for = 'd * M * Y';}
		if ($for == 'd msepb') {$for = 'd * m';}
		if ($for == 'msepa d') {$for = 'm * d';}
		if ($for == 'Fnosep _ d, _ Y') {$for = 'F _ d , _ Y';}
		if ($for == 'Mnosep _ d, _ Y') {$for = 'M _ d , _ Y';}
		if ($for == 'l, _ d _ Fnosep _ Y') {$for = 'l , _ d _ F _ Y';}
		if ($for == 'D _ d _ Mnosep _ Y') {$for = 'D _ d _ M _ Y';}
		if ($for == 'l, _ Fnosep _ d, _ Y') {$for = 'l , _ F _ d, _ Y';}
		if ($for == 'D, _ Mnosep _ d, _ Y') {$for = 'D , _ M _ d, _ Y';}
		if ($for == 'd _ Fnosep') {$for = 'd _ F';}
		if ($for == 'Fnosep _ d') {$for = 'F _ d';}
		if ($for == 'd _ Mnosep') {$for = 'd _ M';}
		if ($for == 'Mnosep _ d') {$for = 'M _ d';}
		if ($for == 'Y. F d.') {$for = 'Y . F d .';}
		if ($for == 'Y. M. d.') {$for = 'Y . M . d .';}
		if ($for == 'Y. F d., l') {$for = 'Y . F d . , l';}
		if ($for == 'F d., l') {$for = 'F d . , l';}


		// NEW DATE FORMAT GLOBALIZED 2.1.7

		$lang = JFactory::getLanguage();
		$langTag = $lang->getTag();
		$langName = $lang->getName();
		if(!file_exists(JPATH_ADMINISTRATOR.'/components/com_icagenda/globalization/'.$langTag.'.php')){

			$langTag='en-GB';
		}

		$globalize = JPATH_ADMINISTRATOR.'/components/com_icagenda/globalization/'.$langTag.'.php';
		$iso = JPATH_ADMINISTRATOR.'/components/com_icagenda/globalization/iso.php';

		if (is_numeric($for)) {
			require $globalize;
		} else {
			require $iso;
		}

		// Load Globalization Date Format if selected
		if ($for == '1') {$for = $datevalue_1;}
		if ($for == '2') {$for = $datevalue_2;}
		if ($for == '3') {$for = $datevalue_3;}
		if ($for == '4') {$for = $datevalue_4;}
		if ($for == '5') {
			if (($langTag == 'en-GB') OR ($langTag == 'en-US')) {
				$for = $datevalue_5;
			} else {
				$for = $datevalue_4;
			}
		}
		if ($for == '6') {$for = $datevalue_6;}
		if ($for == '7') {$for = $datevalue_7;}
		if ($for == '8') {$for = $datevalue_8;}
		if ($for == '9') {
			if ($langTag == 'en-GB') {
				$for = $datevalue_9;
			} else {
				$for = $datevalue_7;
			}
		}
		if ($for == '10') {
			if ($langTag == 'en-GB') {
				$for = $datevalue_10;
			} else {
				$for = $datevalue_8;
			}
		}
		if ($for == '11') {$for = $datevalue_11;}
		if ($for == '12') {$for = $datevalue_12;}

		// Explode components of the date
		$exformat = explode (' ', $for);
		$format='';
		$separator = ' ';

		// Day with no 0 (test if Windows server)
		$dayj = '%e';
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
    		$dayj = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $dayj);
		}

		// Date Formatting using strings of Joomla Core Translations (update 3.1.4)
		$dateFormat=date('d-M-Y', $mkt_date);
		if (isset($this->date_separator)) $separator = $this->date_separator;
		foreach($exformat as $k=>$val){
			switch($val){

				// day (v3)
				case 'd': $val=date("d", strtotime("$dateFormat")); break;
				case 'j': $val=strftime("$dayj", strtotime("$dateFormat")); break;
				case 'D': $val=JText::_(date("D", strtotime("$dateFormat"))); break;
				case 'l': $val=JText::_(date("l", strtotime("$dateFormat"))); break;
				case 'dS': $val=strftime("%d", strtotime("$dateFormat")).'<sup>'.date("S", strtotime("$dateFormat")).'</sup>'; break;
				case 'jS': $val=strftime("$dayj", strtotime("$dateFormat")).'<sup>'.date("S", strtotime("$dateFormat")).'</sup>'; break;

				// month (v3)
				case 'm': $val=date("m", strtotime("$dateFormat")); break;
				case 'F': $val=JText::_(date("F", strtotime("$dateFormat"))); break;
				case 'M': $val=JText::_(date("F", strtotime("$dateFormat")).'_SHORT'); break;
				case 'n': $val=date("n", strtotime("$dateFormat")); break;

				// year (v3)
				case 'Y': $val=date("Y", strtotime("$dateFormat")); break;
				case 'y': $val=date("y", strtotime("$dateFormat")); break;

				// separators of the components (v2)
				case '*': $val=$separator; break;
				case '_': $val=' '; break;
				case '/': $val='/'; break;
				case '.': $val='.'; break;
				case '-': $val='-'; break;
				case ',': $val=','; break;
				case 'the': $val='the'; break;
				case 'gada': $val='gada'; break;
				case 'de': $val='de'; break;
				case 'г.': $val='г.'; break;
				case 'den': $val='den'; break;



				// day
				case 'N': $val=strftime("%u", strtotime("$dateFormat")); break;
				case 'w': $val=strftime("%w", strtotime("$dateFormat")); break;
				case 'z': $val=strftime("%j", strtotime("$dateFormat")); break;

				// week
				case 'W': $val=date("W", strtotime("$dateFormat")); break;

				// month
				case 'n': $val=$separator.date("n", strtotime("$dateFormat")).$separator; break;

				// time
				case 'H': $val=date("H", strtotime("$dateFormat")); break;
				case 'i': $val=date("i", strtotime("$dateFormat")); break;


				default: $val=''; break;
			}
			if($k!=0)$format.=''.$val;
			if($k==0)$format.=$val;
		}
		return $format;
	}


	/**
	 * GOOGLE MAPS
	 */

	// Latitude
	protected function lat ($i){
		if (($i->coordinate != NULL) AND ($i->lat == '0.0000000000000000')) {
			$ex=explode(', ', $i->coordinate);
			$latresult = $ex[0];
		} elseif ($i->lat != '0.0000000000000000') {
			$latresult = $i->lat;
		} else {
			$latresult = NULL;
		}
		return $latresult;
	}

	// Longitude
	protected function lng ($i){
		if (($i->coordinate != NULL) AND ($i->lng == '0.0000000000000000')) {
			$ex=explode(', ', $i->coordinate);
			$lngresult = $ex[1];
		} elseif ($i->lng != '0.0000000000000000') {
			$lngresult = $i->lng;
		} else {
			$lngresult = NULL;
		}
		return $lngresult;
	}

	// Function Map
	protected function map ($i){
		$maplat=$this->lat($i);
		$maplng=$this->lng($i);
		$mapid=$i->id;

	//<script type="text/javascript"> var lat = '.$maplat.'; var lng = '.$maplng.'; var id = '.$mapid.';</script>
		return '<script type="text/javascript">
window.onload = function() {
   initialize('.$maplat.', '.$maplng.', '.(int)$mapid.');
}
</script><div class="icagenda_map" id="map_canvas'.(int)$mapid.'" style="width:'.$this->options['m_width'].'; height:'.$this->options['m_height'].'"></div>';
	}

	// Function Map
	protected function coordinate ($i){

		// Hide/Show Option
		$GoogleMaps = JComponentHelper::getParams('com_icagenda')->get('GoogleMaps', 1);

		// Access Levels Option
		$accessGoogleMaps = JComponentHelper::getParams('com_icagenda')->get('accessGoogleMaps', 1);

		if ( $GoogleMaps == 1 )  {
			if ( $this->accessLevels($accessGoogleMaps) )  {
				$maplat=$this->lat($i);
				$maplng=$this->lng($i);
				if (($maplat != NULL) && ($maplng != NULL)) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	/**
	 * Registered Users List
	 */

	// Participant List Display
	protected function participantList ($i){

		// Hide/Show Option
		$participantList = JComponentHelper::getParams('com_icagenda')->get('participantList', 1);

		// Access Levels Option
		$accessParticipantList = JComponentHelper::getParams('com_icagenda')->get('accessParticipantList', 1);

		if ( $participantList == 1 )  {
			if ( $this->accessLevels($accessParticipantList) )  {
				return $participantList;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	// Display Title List of Participants (if no slide effect)
	protected function participantListTitle ($i){
		$participantList='';
		$participantSlide='';
		if (isset($this->options['participantList'])) $participantList=$this->options['participantList'];
		if (isset($this->options['participantSlide'])) $participantSlide=$this->options['participantSlide'];
		$registration = '';
		$registration = $this->statutReg($i);
		if (($participantSlide == 0 ) && ($registration == 1) && ($participantList == 1)) {
			return JText::_( 'COM_ICAGENDA_EVENT_LIST_OF_PARTICIPANTS');
		}
	}

	// Display Registered Users
	protected function registeredUsers ($i){
		$userReg='';

		// Preparing connection to db
		$db	= $this->getDbo();
		// Preparing the query
		$query = $db->getQuery(true);
		$query->select(' r.userid AS userid, r.name AS registeredUsers, r.date as regDate, r.people as regPeople, r.email as regEmail ')->from('#__icagenda_registration AS r')->where('(r.eventId='.(int)$i->id.') AND (r.state > 0)');
		$db->setQuery($query);
		$registeredUsers = $db->loadObjectList();
		$nbusers=count($registeredUsers);
		$nbmax=$nbusers-1;
		$registration = '';
		$registration = $this->statutReg($i);
		$n='0';


		$participantList='1';
		$participantSlide='1';
		$participantDisplay='1';
		$fullListColumns='tiers';
		$iCagendaParams = JComponentHelper::getParams('com_icagenda');
		if (isset($this->options['participantList'])) $participantList=$this->options['participantList'];

		$participantSlide=$iCagendaParams->get('participantSlide');
		if (isset($this->options['participantDisplay'])) $participantDisplay=$this->options['participantDisplay'];
		if (isset($this->options['fullListColumns'])) $fullListColumns=$this->options['fullListColumns'];

		// logged-in Users: Name/User Name Option
		$nameJoomlaUser = JComponentHelper::getParams('com_icagenda')->get('nameJoomlaUser', 1);

		jimport( 'joomla.html.html.sliders' );
		$slider_c='';
		if (($participantList == 1) && ($registration == 1)) {
			$n_list='names_noslide';
			if ($participantSlide == 1) {
				$n_list='names_slide';
				$slider_c='class="pane-slider content"';
				$userReg.=JHtml::_('sliders.start', 'icagenda', array('useCookie'=>0, 'startOffset'=>-1, 'startTransition'=>1));
				$userReg.=JHtml::_('sliders.panel', JText::_('COM_ICAGENDA_EVENT_LIST_OF_PARTICIPANTS'), 'slide1');
			}

//			$userReg.='<div style="display:block; float:left; width:100%; height:5px;">&nbsp;</div>';
//			$userReg.='<div style="width:100%; text-align:justify;">';
			if ($nbusers==NULL) {
				$userReg.='<div '.$slider_c.'>';
				$userReg.='&nbsp;'.JText::_( 'COM_ICAGENDA_NO_REGISTRATION').'&nbsp;';
				$userReg.='</div>';
			} else {


			if ($participantDisplay == 1) {

				$column='tiers';
				if(isset($fullListColumns)) {$column=$fullListColumns;}
				$userReg.='<div '.$slider_c.'>';

				foreach ($registeredUsers as $reguser){
					$avatar=md5( strtolower( trim( $reguser->regEmail ) ) );
					$n=$n+1;


					// Get Username and name
					if (($reguser->userid) AND ($reguser->userid != 0)) {
						$db = JFactory::getDBO();
						$db->setQuery(
							'SELECT `name`, `username`' .
							' FROM `#__users`' .
							' WHERE `id` = '. (int) $reguser->userid
						);
						$data_name=$db->loadObject()->name;
						$data_username=$db->loadObject()->username;
						if ($nameJoomlaUser == 1) {
							$reguser->registeredUsers = $data_name;
						} else {
							$reguser->registeredUsers = $data_username;
						}
					}


					if ($n<=$nbmax) {
						$userReg.='<table class="list_table '.$column.'" cellpadding="0" cellspacing="0"><tbody><tr><td class="imgbox"><img alt="'.$reguser->registeredUsers.'"  src="http://www.gravatar.com/avatar/'.$avatar.'?s=36&d=mm"/></td><td valign="middle"><span class="list_name">'.$reguser->registeredUsers.'</span><span class="list_places"> ('.$reguser->regPeople.')</span><br /><span class="list_date">'.$reguser->regDate.'</span></td></tr></tbody></table>';
					}
					if ($n==$nbusers) {
	   					$userReg.='<table class="list_table '.$column.'" cellpadding="0" cellspacing="0"><tbody><tr><td class="imgbox"><img alt="'.$reguser->registeredUsers.'"  src="http://www.gravatar.com/avatar/'.$avatar.'?s=36&d=mm"/></td><td valign="middle"><span class="list_name">'.$reguser->registeredUsers.'</span><span class="list_places"> ('.$reguser->regPeople.')</span><br /><span class="list_date">'.$reguser->regDate.'</span></td></tr></tbody></table>';
					}
				}

				$userReg.='</div>';

			}
			if ($participantDisplay == 2) {

				$userReg.='<div '.$slider_c.'>';

				foreach ($registeredUsers as $reguser){
					$avatar=md5( strtolower( trim( $reguser->regEmail ) ) );
					$n=$n+1;


					// Get Username and name
					if (($reguser->userid) AND ($reguser->userid != 0)) {
						$db = JFactory::getDBO();
						$db->setQuery(
							'SELECT `name`, `username`' .
							' FROM `#__users`' .
							' WHERE `id` = '. (int) $reguser->userid
						);
						$data_name=$db->loadObject()->name;
						$data_username=$db->loadObject()->username;
						if ($nameJoomlaUser == 1) {
							$reguser->registeredUsers = $data_name;
						} else {
							$reguser->registeredUsers = $data_username;
						}
					}


					if ($n<=$nbmax) {
						$userReg.='<div style="width: 76px; height: 80px; float:left; margin:2px; text-align:center;"><img style="border-radius: 3px 3px 3px 3px; margin:2px 0px;" alt="'.$reguser->registeredUsers.'"  src="http://www.gravatar.com/avatar/'.$avatar.'?s=48&d=mm"/><br/><strong style="text-align:center; font-size:9px;">'.$reguser->registeredUsers.'</strong></div>';
					}
					if ($n==$nbusers) {
	   					$userReg.='<div style="width: 76px; height: 80px; float:left; margin:2px; text-align:center;"><img style="border-radius: 3px 3px 3px 3px; margin:2px 0px;" alt="'.$reguser->registeredUsers.'"  src="http://www.gravatar.com/avatar/'.$avatar.'?s=48&d=mm"/><br/><strong style="text-align:center; font-size:9px;">'.$reguser->registeredUsers.'</strong></div>';
					}
				}

				$userReg.='</div>';

			}
			if ($participantDisplay == 3) {

				$userReg.='<div '.$slider_c.'>';

				$userReg.='<div class="'.$n_list.'">';
				foreach ($registeredUsers as $reguser){
					$n=$n+1;


					// Get Username and name
					if (($reguser->userid) AND ($reguser->userid != 0)) {
						$db = JFactory::getDBO();
						$db->setQuery(
							'SELECT `name`, `username`' .
							' FROM `#__users`' .
							' WHERE `id` = '. (int) $reguser->userid
						);
						$data_name=$db->loadObject()->name;
						$data_username=$db->loadObject()->username;
						if ($nameJoomlaUser == 1) {
							$reguser->registeredUsers = $data_name;
						} else {
							$reguser->registeredUsers = $data_username;
						}
					}


					if ($n<=$nbmax) {
						$userReg.=''.$reguser->registeredUsers.', ';
					}
					if ($n==$nbusers) {
	   					$userReg.=''.$reguser->registeredUsers.'';
					}
				}

				$userReg.='</div>';
				$userReg.='</div>';

			}

			}
//			$userReg.='</div>';
			if ($participantSlide == 1 ) {
//		   		$userReg.='<div class="icpanel-bottom">&nbsp;</div>';
				$userReg.=JHtml::_('sliders.end');
			}
		} else {
			$userReg.='';
		}
		return $userReg;
	}


	/**
	 * SPECIAL FUNCTIONS
	 */

	// function Event Options
	protected function evtParams ($i){
		$evtParams = '';
		$evtParams = new JRegistry($i->params);
		return $evtParams;
	}

	// Function if Period Dates exist
	protected function periodTest ($i){
		$daysp=unserialize($i->period);
		if ($daysp!=NULL) {
			$test = '1';
			return $test;
		}
	}

	// Function if Period Dates exist
	protected function accessLevels ($accessSet){

		// Get User Access Levels
		$user = JFactory::getUser();
		$userLevels = $user->getAuthorisedViewLevels();
		if(version_compare(JVERSION, '3.0', 'lt')) {
			$userGroups = $user->getAuthorisedGroups();
		} else {
			$userGroups = $user->groups;
		}

		// Control: if access level, or Super User
		if ( in_array($accessSet, $userLevels) OR in_array('8', $userGroups) )  {
			return true;
		} else {
			return false;
		}
	}

	// function to detect if info details exist in an event, and to hide or show it depending of Options (display and access levels)
	protected function infoDetails ($i){

		// Hide/Show Option
		$infoDetails = JComponentHelper::getParams('com_icagenda')->get('infoDetails', 1);
		//if (!isset($infoDetails)) $infoDetails='1';

		// Access Levels Option
		$accessInfoDetails = JComponentHelper::getParams('com_icagenda')->get('accessInfoDetails', 1);

		if ($infoDetails == 1) {
			if ( $this->accessLevels($accessInfoDetails) )  {
				if ((!$this->placeLeft($i)) && (!$i->phone) && (!$i->email) && (!$i->website) && (!$i->address) && (!$i->file)) {
					return false;
				} else {
					return true;
				}
			}
		} else {
			return false;
		}
	}

//	protected function idMenu ($i){
//		$idmenu = $this->options['Itemid'];
//		return $idmenu;
//	}


	/**
	 * ADDTHIS - Social Networks
	 */

	// function to override general options display of AddThis in event details view
	protected function ateventshow ($i){
		$atevent = '';
		if (isset($this->options['atevent'])) {$atevent = $this->options['atevent'];}

//		$eventparam = new JRegistry($i->params);
//		$eventatvent =$eventparam->get('atevent');
		$param = $this->evtParams($i);
		$eventatvent = $param->get('atevent');

		//echo $atevent.'|'.$eventatvent;
		if ($eventatvent==0) {
			$show=0 ;
		}
		if (($eventatvent==1) && ($atevent==NULL)) {
			$show=1;
		}
		if ($eventatvent==1) {
			$show=1;
		}
		if ($eventatvent==NULL) {
			$show = $atevent;
		}
		return $show;
	}

	// function option display AddThis social networks sharing
	protected function share_event ($i){

		$at = $this->ateventshow($i);
		if ($at == 1) {
			$share=$this->share($i);
		} else {
			$share=NULL;
		}
		return $share;
	}

	// function AddThis social networks sharing
	protected function share ($i){

		$float='';
		if(isset($this->options['atfloat'])) { $float = $this->options['atfloat']; }
		$icon = $this->options['aticon'];
		$iconaddthis='';
		if ($float==0) {
			$floataddthis='default';
			$posfloat='right';
		}
		if ($float==1) {
			$floataddthis='floating';
			$posfloat='left';
		}
		if ($float==2) {
			$floataddthis='floating';
			$posfloat='right';
		}
		if ($icon==1) {
			$iconaddthis='16x16';
		}
		if ($icon==2) {
			$iconaddthis='32x32';
		}
		$text='<div class="share">';
		$text.='<!-- AddThis Button BEGIN -->';
		$text.='<div class="addthis_toolbox addthis_'.$floataddthis.'_style addthis_'.$iconaddthis.'_style" style="'.$posfloat.':50px;top:40%;">';
		$text.='<a class="addthis_button_preferred_1"></a>';
		$text.='<a class="addthis_button_preferred_2"></a>';
		$text.='<a class="addthis_button_preferred_3"></a>';
		$text.='<a class="addthis_button_preferred_4"></a>';
		$text.='<a class="addthis_button_compact"></a>';
		$text.='<a class="addthis_counter addthis_bubble_style"></a>';
		$text.='</div>';

		$addthis = '';
		if (isset($this->options['addthis'])) {$addthis = $this->options['addthis'];}
		if ($addthis) {
			$text.='<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>';
			$text.='<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid='.$this->options['addthis'].'"></script>';
		}
		else {
			$text.='<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>';
			$text.='<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-5024db5322322e8b"></script>';
		}
		$text.='<!-- AddThis Button END -->';
		$text.='</div>';

		return $text;
	}


	/**
	 * REGISTRATIONS
	 */

	// function link to registration page
	protected function regUrl ($i){
		return JROUTE::_('index.php?option=com_icagenda&view=list&layout=registration&Itemid='.(int)$this->options['Itemid'].'&id='.(int)$i->id);
	}

	// function Registration statut
	protected function statutReg ($i){
		$statutReg = '';
		$gstatutReg = '';
		if (isset($this->options['statutReg'])) {$gstatutReg = $this->options['statutReg'];}

		$param = $this->evtParams($i);
		$evtstatutReg = $param->get('statutReg');

// Control and edit param values to iCagenda v3
		if ($evtstatutReg == '2') {
			$evtstatutReg = '0';
		}


		if ($evtstatutReg != '') {
			$statutReg = $evtstatutReg;
		} else {
			$statutReg = $gstatutReg;
		}
		return $statutReg;
	}

	// function Registration Access
	protected function accessReg ($i){
		$accessReg = '';

		$param = $this->evtParams($i);
		$accessReg = $param->get('accessReg');

		return $accessReg;
	}

	// function Registration Type
	protected function typeReg ($i){
		$typeReg = '';
//		if (isset($this->options['typeReg'])) {$maxRlist = $this->options['typeReg'];}

		$param = $this->evtParams($i);
		$typeReg = $param->get('typeReg');

		return $typeReg;
	}

	// function Max places per registration
	protected function maxRlist ($i){
		$maxRlist = '';
		$gmaxRlist = '';
		if (isset($this->options['maxRlist'])) {$gmaxRlist = $this->options['maxRlist'];}

		$param = $this->evtParams($i);
		$evtmaxRlistGlobal = $param->get('maxRlistGlobal');
		$evtmaxRlist = $param->get('maxRlist');

// Control and edit param values to iCagenda v3
		if ($evtmaxRlistGlobal == '1') {
			$evtmaxRlistGlobal = '';
		}
		if ($evtmaxRlistGlobal == '0') {
			$evtmaxRlistGlobal = '2';
		}

		if ($evtmaxRlistGlobal == '2') {
			$maxRlist = $evtmaxRlist;
		} else {
			$maxRlist = $gmaxRlist;
		}
		return $maxRlist;
	}

	// function Max Registrations per event
	protected function maxReg ($i){
		$maxReg = '1000000';
//		if (isset($this->options['maxReg'])) {$maxRlist = $this->options['maxReg'];}

		$param = $this->evtParams($i);
		if ($param->get('maxReg')) {$maxReg = $param->get('maxReg');}

		return $maxReg;
	}

	// function Max Nb Tickets (Control if set)
	protected function maxNbTickets ($i){
		$maxNbTickets = '1000000';
//		if (isset($this->options['maxReg'])) {$maxRlist = $this->options['maxReg'];}

		$param = $this->evtParams($i);
		if ($param->get('maxReg')) {$maxNbTickets = $param->get('maxReg');}
		if (($maxNbTickets != '1000000') && ($this->statutReg($i) == '1')) {
			return $maxNbTickets;
		}
	}

	// function Number of places left
	protected function placeLeft ($i){
		$placeLeft = '';
		$maxReg = $this->maxReg($i);
		$registered = $this->registered($i);
		$placeLeft = ($maxReg - $registered);
		if (($maxReg != '1000000') && ($this->statutReg($i) == '1')) {
			return $placeLeft;
		}
	}

	// function Email Required
	protected function emailRequired ($i){
		$emailRequired = '';
		if (isset($this->options['emailRequired'])) { $emailRequired=$this->options['emailRequired']; } else { $emailRequired = '0'; }
		return $emailRequired;
	}

	// function Phone Required
	protected function phoneRequired ($i){
		$phoneRequired = '';
		if (isset($this->options['phoneRequired'])) { $phoneRequired=$this->options['phoneRequired']; } else { $phoneRequired = '0'; }
		return $phoneRequired;
	}

	// function pre-formated to display Register button and registered bubble
	protected function reg ($i){
		$toDay=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$h= date('H');
		$min= date('i');
		$toDay=mktime($h,$min,0,$m,$day,$y);
		$testDate = $this->mkt($i->next);

		$reg = $this->statutReg($i);
		$accessreg = $this->accessReg($i);
		$nbreg = $this->registered($i);
		$maxreg = $this->maxReg($i);
		$pastDates = $this->pastDates($i);

		// Initialize controls
		$access='0';
		$control='';

		// Access Control
		$user = JFactory::getUser();
		$userLevels = $user->getAuthorisedViewLevels();

// Removed in v3.0 and replaced with in_array

//		$access=$accessreg;
//		if ($access == '0') { $access='1'; }
//		foreach ($userLevels as $level){
//			if ($level == $access) {
//				$control=$access;
//			}
//		}

//echo $accessreg;
//print_r($userLevels);

//		if ($control == $access) {

		$TextRegBt = '';
		$param = $this->evtParams($i);

		if ($param->get('RegButtonText')) {
			$TextRegBt = $param->get('RegButtonText');
		} elseif (isset($this->options['RegButtonText'])) {
			$TextRegBt = $this->options['RegButtonText'];
		} else {
			$TextRegBt = JText::_( 'COM_ICAGENDA_REGISTRATION_REGISTER');
		}


		if ( in_array($accessreg, $userLevels) )  {
			if (($reg == 1) && ($pastDates == 1) && ($nbreg < $maxreg)) {
				return '<div class="regisBox"><a href="'.$this->regUrl($i).'"><div class="regis_button">'.$TextRegBt.'</div></a><a href="'.$this->regUrl($i).'"><div class="regis_imgbutton"></div></a><div class="icRegistered" >'.$this->registered($i).'</div></div>';
			}
			if (($reg==1) && ($pastDates == 1) && ($nbreg >= $maxreg)) {
				return '<div class="regisBox"><a><div class="regis_button">'.JText::_( 'COM_ICAGENDA_REGISTRATION_EVENT_FULL').'</div></a><div class="icRegistered" >'.$this->registered($i).'</div></div>';
			}
			if (($reg==1) && ($pastDates == 0)) {
				return '<div class="regisBox"><a><div class="event_finished">'.JText::_( 'COM_ICAGENDA_REGISTRATION_EVENT_FINISHED').'</div></a><div class="icRegistered" >'.$this->registered($i).'</div></div>';
			}
		} else {
			if (($reg == 1) && ($pastDates == 1) && ($nbreg < $maxreg)) {
				$accessInfo = 'alert( \''.JText::_( 'JERROR_ALERTNOAUTHOR' ).' \n\n '.JText::_( 'JGLOBAL_YOU_MUST_LOGIN_FIRST' ).'\' )';
				return '<div class="regisBox"><a href="#" onClick="'.$accessInfo.'"><div class="regis_button">'.$TextRegBt.'</div></a><a href="#" onClick="'.$accessInfo.'"><div class="regis_imgbutton"></div></a><div class="icRegistered" >'.$this->registered($i).'</div></div>';
			} else {
				return '';
			}
		}
	}


	// function to get number of registered people to an event
	protected function registered ($i){

		// Preparing connection to db
		$db	= $this->getDbo();
		// Preparing the query
		$query = $db->getQuery(true);
		$query->select(' sum(r.people) AS registered')->from('#__icagenda_registration AS r')->where('(r.eventId='.(int)$i->id.') AND (r.state > 0)');
		$db->setQuery($query);
		$people = $db->loadObjectList();

		$reg = $this->statutReg($i);
		$nbreg = $people[0]->registered;
		if ($reg==1) {

			if ($nbreg == NULL) {
				$nbreg = '0';
				return $nbreg;
			} else {
				return $nbreg;
			}
		}

	}

	// url to return event details after registration (changed in 2.1.14 not in use; see $urlist)
	protected function urlList ($i){
		// Preparing connection to db
		$db	= $this->getDbo();
		// Preparing the query
		$query = $db->getQuery(true);
		$query->select(' r.eventId AS idevt')->from('#__icagenda_registration AS r');
		$db->setQuery($query);
		$idevt = $db->loadObjectList();

		$link = $this->options['Itemid'];
		$urllist = JROUTE::_('index.php?option=com_icagenda&view=list&layout=event&id='.(int)$idevt.'&Itemid='.(int)$link);
		$url=$urllist;
		if (is_numeric($link) && !is_array($link)) {
			return $url;
		}
		else {
			$url = JROUTE::_('index.php');
			return $url;
		}
	}

	// Save of a registration, and automatic email
	public function registration ($array){

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.title, a.published, a.id')
			->from('`#__menu` AS a')
			->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0)" );
		$db->setQuery($query);
		$link = $db->loadObjectList();
		$itemid = JRequest::getVar('Itemid');

		$parentnav = $itemid;

		foreach ($link as $l){
			if (($l->published == '1') AND ($l->id == $parentnav)) {
				$linkexist = '1';
			}
		}

		if (is_numeric($parentnav) && !is_array($parentnav) && !$parentnav==0 && $linkexist==1) {


		$data =new stdClass();
		$idevent = $data->id;
		$data->id = null;
		$data->eventid = '0';
		if(isset($array['uid'])) $data->userid = $array['uid'];
//		if(isset($array['name'])) $data->name = htmlentities(strip_tags($array['name']));
		if(isset($array['name'])) $data->name = $array['name'];
//		if(isset($array['email'])) $data->email = htmlentities(strip_tags($array['email']));
		if(isset($array['email'])) $data->email = $array['email'];
		if(isset($array['phone'])) $data->phone = $array['phone'];
		if(isset($array['date'])) $data->date = $array['date'];
		if(isset($array['period'])) $data->period = $array['period'];
		if(isset($array['people'])) $data->people = $array['people'];
		if(isset($array['notes'])) $data->notes = htmlentities(strip_tags($array['notes']));
		if(isset($array['event'])) $data->eventid = $array['event'];
		if(isset($array['menuID'])) $data->itemid = $array['menuID'];
		$data->checked_out_time = date('Y-m-d H:i:s');


		// Get the "event" URL
		$baseURL = JURI::base();
		$subpathURL = JURI::base(true);

// To be tested
//			$temp = str_replace('http://', '', $baseURL);
//			$temp = str_replace('https://', '', $temp);
//			$parts = explode($temp, '/', 2);
//			$subpathURL = count($parts) > 1 ? $parts[1] : '';


		$baseURL = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

//		if(JFactory::getApplication()->isAdmin()) {
//			$urlevent = 'index.php?option=com_icagenda&view=list&layout=event&Itemid='.(int)$data->itemid.'&id='.(int)$data->eventid;
//		} else {
			$urlevent = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=list&layout=event&Itemid='.(int)$data->itemid.'&id='.(int)$data->eventid));
			$urllist = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=list&Itemid='.(int)$data->itemid));
			$urlregistration = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=list&layout=registration&Itemid='.(int)$data->itemid.'&id='.(int)$data->eventid));
//		}


		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL Event Details filtering
		$urlevent = ltrim($urlevent, '/');
		if(substr($urlevent,0,strlen($subpathURL)+1) == "$subpathURL/") $urlevent = substr($urlevent,strlen($subpathURL)+1);
		$urlevent = rtrim($baseURL,'/').'/'.ltrim($urlevent,'/');

		// URL List filtering
		$urllist = ltrim($urllist, '/');
		if(substr($urllist,0,strlen($subpathURL)+1) == "$subpathURL/") $urllist = substr($urllist,strlen($subpathURL)+1);
		$urllist = rtrim($baseURL,'/').'/'.ltrim($urllist,'/');

		// URL Registration filtering
		$urlregistration = ltrim($urlregistration, '/');
		if(substr($urlregistration,0,strlen($subpathURL)+1) == "$subpathURL/") $urlregistration = substr($urlregistration,strlen($subpathURL)+1);
		$urlregistration = rtrim($baseURL,'/').'/'.ltrim($urlregistration,'/');


		$name_isValid = '1';

		$pattern = "#[/\\\\/\<>/\"%;=\[\]\+()&]|^[0-9]#i";

        if (isset($array['name'])){
			// get the application object
			$app = JFactory::getApplication();

			$nbMatches = preg_match($pattern, $array['name']);
			if ($nbMatches && $nbMatches==1){

				// message if invalid characters
				$app->redirect(htmlspecialchars_decode($urlregistration), JText::sprintf( 'COM_ICAGENDA_REGISTRATION_NAME_NOT_VALID' , '<b>'.htmlentities($array['name'], ENT_COMPAT, 'UTF-8').'</b>'), JText::_( 'JGLOBAL_VALIDATION_FORM_FAILED' ));
				$name_isValid = '0';

				return false;
			}
			if(strlen(utf8_decode($array['name']))<2){

				// message if less than 2 characters in the name
				$app->redirect(htmlspecialchars_decode($urlregistration), JText::_( 'COM_ICAGENDA_REGISTRATION_NAME_MINIMUM_CHARACTERS'), JText::_( 'JGLOBAL_VALIDATION_FORM_FAILED' ));
				$name_isValid = '0';

				return false;
			}
        }



		$data->name = filter_var($data->name, FILTER_SANITIZE_STRING);

		$emailCheckdnsrr='0';
		$emailCheckdnsrr = JComponentHelper::getParams('com_icagenda')->get('emailCheckdnsrr');

		if(!empty($data->email)) {
			$validEmail = true;
			$checkdnsrr = true;
			if (($emailCheckdnsrr == 1) AND (function_exists('checkdnsrr'))) {
				$provider = explode('@', $data->email);
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					if (version_compare(phpversion(), '5.3.0', '<')) {
						$checkdnsrr = true;
					}
				} else {
					$checkdnsrr = checkdnsrr($provider[1]);
				}
			} else {
				$checkdnsrr = true;
			}
		} else {
			$checkdnsrr = true;
		}

		if($validEmail) $validEmail = $this->validEmail($data->email);


		if (((($validEmail) AND ($checkdnsrr)) OR ($data->email==NULL)) AND ($name_isValid == '1')) {

		$eventid = $data->eventid;
		if ($period != NULL) {$period = $data->period;} else {$period = '0';}
		$people = $data->people;
		$name = $data->name;
		$email = $data->email;
		$phone = $data->phone;
		$notes = html_entity_decode($data->notes);
		$dateReg = $data->date;



		// Import params - Limit Options for User Registration
		$app = JFactory::getApplication();
		$icpar = $app->getParams();

		$limitRegEmail='1';
		$limitRegDate = '1';
		$emailRequired = '1';
		$limitRegEmail=$icpar->get('limitRegEmail');
		$limitRegDate=$icpar->get('limitRegDate');
		$emailRequired=$icpar->get('emailRequired');

		$alreadyexist='no';
		if (($limitRegEmail == 1) OR ($limitRegDate == 1)) {
			$db = JFactory::getDBO();
			$cf = JRequest::getString('email', '', 'post');

			if ($limitRegDate == 0) {
				$query = "
				  SELECT COUNT(*)
				    FROM `#__icagenda_registration`
				    WHERE `email` = '$cf' AND `eventid`='$eventid' AND `state`='1'
				";
			}
			if ($limitRegDate == 1) {
				$query = "
				  SELECT COUNT(*)
				    FROM `#__icagenda_registration`
				    WHERE `email` = '$cf' AND `eventid`='$eventid' AND `date`='$dateReg' AND `state`='1'
				";
			}

			$db->setQuery($query);

//			if (($emailRequired != '0') AND ($email!=NULL)) {
			if ($email!=NULL) {
				if ( $db->loadResult() ) {
					$alreadyexist='yes';
					JError::raiseWarning( 100, JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ALERT' ).' '.$email.'<br>' );

					//get the application object
					$app = JFactory::getApplication();
					//redirect to the event page
					$app->redirect(htmlspecialchars_decode($urlregistration));
					return false;
				} else {
					$alreadyexist='no';
				}
			}
		}


		if ($email == '') {
			$email=JText::_( 'COM_ICAGENDA_NOT_SPECIFIED' );
		}
		if ($phone == '') {
			$phone=JText::_( 'COM_ICAGENDA_NOT_SPECIFIED' );
		}
		// Insert data of the registered user (Option Email required)
		if ($emailRequired == '1') {
			if (is_numeric($eventid) && is_numeric($period) && is_numeric($people) && ($name != NULL) && ($email != NULL)) {
				$db = JFactory::getDBO();
				$db->insertObject( '#__icagenda_registration', $data, id );
			}
		} else {
			if (is_numeric($eventid) && is_numeric($period) && is_numeric($people) && ($name != NULL)) {
				$db = JFactory::getDBO();
				$db->insertObject( '#__icagenda_registration', $data, id );
			}
		}

//		if (is_numeric($eventid) && is_numeric($period) && is_numeric($people) && ($name != NULL) && ($email != NULL)) {
//			$db = JFactory::getDBO();
//			$db->insertObject( '#__icagenda_registration', $data, id );
//		}

		$author= '0';
		// Preparing connection to db
		$db	= $this->getDbo();

		// Preparing the query
		$query = $db->getQuery(true);
		$query->select('e.title AS title, e.startdate AS startdate, e.enddate AS enddate, e.created_by AS authorID, e.email AS contactemail')->from('#__icagenda_events AS e')->where("(e.id=$data->eventid)");
		$db->setQuery($query);
		$title=$db->loadObject()->title;
		$startdate=$db->loadObject()->startdate;
		$enddate=$db->loadObject()->enddate;
		$authorID=$db->loadObject()->authorID;
		$contactemail=$db->loadObject()->contactemail;

		$starDate = strftime("%d/%m/%Y", strtotime("$startdate"));
		$startD = JText::_($starDate);

		$endDate = strftime("%d/%m/%Y", strtotime("$enddate"));
		$endD = JText::_($endDate);

		$starTime = strftime("%H:%M", strtotime("$startdate"));
		$startT = JText::_($starTime);

		$endTime = strftime("%H:%M", strtotime("$enddate"));
		$endT = JText::_($endTime);

		$periodreg = $data->period;

		// Import params
		$app = JFactory::getApplication();
		$icpar = $app->getParams();

		$defaultemail='';
		$emailUserSubjectPeriod='';
		$emailUserBodyPeriod='';
		$emailUserSubjectDate='';
		$emailUserBodyDate='';
		$defaultemail=$icpar->get('regEmailUser');
		$emailUserSubjectPeriod=$icpar->get('emailUserSubjectPeriod');
		$emailUserBodyPeriod=$icpar->get('emailUserBodyPeriod');
		$emailUserSubjectDate=$icpar->get('emailUserSubjectDate');
		$emailUserBodyDate=$icpar->get('emailUserBodyDate');



		if (isset($emailUserSubjectPeriod)){
			$eUSP=$emailUserSubjectPeriod;
		} else {
			$eUSP=JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_SUBJECT' );
		}

		if (isset($emailUserBodyPeriod)){
			$eUBP=$emailUserBodyPeriod;
		} else {
			$eUBP=JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_BODY' );
		}

		if (isset($emailUserSubjectDate)){
			$eUSD=$emailUserSubjectDate;
		} else {
			$eUSD=JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_SUBJECT' );
		}

		if (isset($emailUserBodyDate)){
			$eUBD=$emailUserBodyDate;
		} else {
			$eUBD=JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_BODY' );
		}

		if ($periodreg == 1) {
			$periodd = JText::sprintf( 'COM_ICAGENDA_REGISTERED_EVENT_PERIOD', $startD, $startT, $endD, $endT );
			$adminsubject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DEFAULT_SUBJECT' );
			$adminbody = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_PERIOD_DEFAULT_BODY' );
			if ($defaultemail == 0) {
				$subject = $eUSP;
				$body = $eUBP;
			} else {
				$subject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_SUBJECT' );
				$body = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_BODY' );
			}
		} else {
			$periodd = JText::sprintf( 'COM_ICAGENDA_REGISTERED_EVENT_DATE', $array['date'], '' );
			$adminsubject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DEFAULT_SUBJECT' );
			$adminbody = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DATE_DEFAULT_BODY' );
			if ($defaultemail == 0) {
				$subject = $eUSD;
				$body = $eUBD;
			} else {
				$subject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_SUBJECT' );
				$body = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_BODY' );
			}
		}

		// Get the site name
		$config = JFactory::getConfig();
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$sitename = $config->get('sitename');
		} else {
			$sitename = $config->getValue('config.sitename');
		}



		$siteURL = JURI::base();
		$siteURL = rtrim($siteURL,'/');
		// ROUTE
//		$urlevent = str_replace('&amp;','&', JROUTE::_('index.php?option=com_icagenda&view=list&layout=event&id='.(int)$data->eventid));

		// Preparing connection to db
if ($authorID!= NULL) {
		$db	= $this->getDbo();
		// Preparing the query
		$query = $db->getQuery(true);
		$query->select('email AS authormail, name AS authorname')->from('#__users AS u')->where("(u.id=$authorID)");
//		$query->select('email AS authormail, name AS author')->from('#__users AS u');
		$db->setQuery($query);
		$authormail=$db->loadObject()->authormail;
		$authorname=$db->loadObject()->authorname;

		if ($authormail == NULL) {
			if(version_compare(JVERSION, '3.0', 'ge')) {
				$authormail = $config->get('mailfrom');
			} else {
				$authormail = $config->getValue('config.mailfrom');
			}
		}
} else {
				$authormail = '';
}

		// MAIL
		$replacements = array(
			"\\n"				=> "\n",
			'[SITENAME]'		=> $sitename,
			'[SITEURL]'			=> $siteURL,
			'[AUTHOR]'			=> $authorname,
			'[AUTHOREMAIL]'		=> $authormail,
			'[CONTACTEMAIL]'	=> $contactemail,
			'[TITLE]'			=> $title,
//			'[EVENTURL]'		=> $siteURL.$urlevent,
			'[EVENTURL]'		=> $urlevent,
			'[NAME]'			=> $name,
			'[EMAIL]'			=> $email,
			'[PHONE]'			=> $phone,
			'[PLACES]'			=> $people,
			'[NOTES]'			=> $notes,
			'[DATETIME]'		=> $data->date,
			'[STARTDATE]'		=> $startD,
			'[ENDDATE]'			=> $endD,
			'[STARTDATETIME]'	=> $startD.' - '.$startT,
			'[ENDDATETIME]'		=> $endD.' - '.$endT,
		);


		foreach($replacements as $key => $value) {
			$subject = str_replace($key, $value, $subject);
			$body = str_replace($key, $value, $body);
			$adminsubject = str_replace($key, $value, $adminsubject);
			$adminbody = str_replace($key, $value, $adminbody);
		}

		$mailer = JFactory::getMailer();
		$adminmailer = JFactory::getMailer();
//		$config = JFactory::getConfig();
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$mailfrom = $config->get('mailfrom');
			$fromname = $config->get('fromname');
		} else {
			$mailfrom = $config->getValue('config.mailfrom');
			$fromname = $config->getValue('config.fromname');
		}
		$mailer->setSender(array( $mailfrom, $fromname ));
		$adminmailer->setSender(array( $mailfrom, $fromname ));

		$user = JFactory::getUser();
		if (!isset($data->email)) {
			$recipient = $user->email;
		} else {
			$recipient = $data->email;
		}
//		$adminrecipient = $mailfrom;

		$mailer->addRecipient($recipient);

		$adminrecipient = array($mailfrom, $authormail);
		$adminmailer->addRecipient($adminrecipient);

		$mailer->setSubject($subject);
		$adminmailer->setSubject($adminsubject);
		$mailer->setBody($body);
		$adminmailer->setBody($adminbody);
		// Optional file attached
//		$mailer->addAttachment(JPATH_COMPONENT.DS.'assets'.DS.'document.pdf');

		if ((isset($data->email)) AND ($emailRequired == '1')) {
			$send = $mailer->Send();
		}
/* Removed in 3.0
		if ( $send !== true ) {
		    echo 'Error sending email: ' . $send->message;
		} else {
		    echo 'Mail sent';
		}
*/

		if ((isset($data->eventid)) AND ($data->eventid != '0') AND ($data->name != NULL)) {
			$sendadmin = $adminmailer->Send();
		}
/* Removed in 3.0
		if ( $sendadmin !== true ) {
		    echo 'Error sending email: ' . $sendadmin->message;
		} else {
		    echo 'Mail sent';
		}
*/


		if ($alreadyexist == 'no') {
			// get the application object
			$app = JFactory::getApplication();

			// redirect after successful registration
			$app->redirect(htmlspecialchars_decode($urllist) , ''. JText::_( 'COM_ICAGENDA_REGISTRATION_TY' ).' '.$data->name.', '.JText::sprintf( 'COM_ICAGENDA_REGISTRATION', $title ).'<br>'.$periodd.' (<a href="'.$urlevent.'">'. JText::_( 'COM_ICAGENDA_REGISTRATION_EVENT_LINK' ).'</a>)');
		}

		} else {
			// get the application object
			$app = JFactory::getApplication();

			// redirect after successful registration
			$app->redirect(htmlspecialchars_decode($urlregistration) , JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_NOT_VALID' ));

			return false;
}


		} else {
			$this->error404();
		}

	}


	/**
	 * ESSENTIAL FUNCTIONS
	 */

	// FUNCTION TO CHECK IF NEXT MOVE
	protected function ctrlNext ($i){

/* Remove in 2.0
		$toDay=time();
		$nextMkt=$this->mkt($i->next);
		$exora=explode(':', $i->time);
		$next= JRequest::getVar('next');
		if ($next>$toDay){
			$ctrl=0;
		}elseif($next==$toDay){
			$ctrl=0;
		}else{
			$ctrl=1;
		}
		if($ctrl==0){
			return $i;
		}else{
			$days=unserialize($i->dates);
			foreach($days as $d){
				$dMkt=$this->mkt($d);
				// si date passée + date aujourd'hui
				if ($dMkt<$toDay){
					$i->next=$d;
					return $i;
				}
				//si date passée + date future
				else{
					return $i;
				}
			}
			$this->writeNext($i);
			return $i;
		}
end remove */

		return $i;

	}

	// mktime with control
	private function mkt($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		if (isset($ex_data['3']))$hour=$ex_data['3'];
		if (isset($ex_data['4']))$min=$ex_data['4'];
		if ((isset($hour)) && (isset($min)) && ($hour!='') && ($hour!=NULL) && ($min!='') && ($min!=NULL)) {
			$ris=mktime($ex_data['3'], $ex_data['4'], '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		} else {
			$ris=mktime('00', '00', '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		}
		return strftime($ris);
	}

	// mktime with time
	private function mkttime($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		$ris=mktime($ex_data['3'], $ex_data['4'], '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		return strftime($ris);
	}

	// mktime with only date
	private function mktshort($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		$ris=mktime('00', '00', '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		return strftime($ris);
	}

	// Function to convert font color, depending on category color
	function fontColor($i){
		$RGB='$RGB';
		$RGBa=$RGB[0];
		$RGBb=$RGB[1];
		$RGBc=$RGB[2];

		// Preparing connection to db
		$db	= $this->getDbo();
		// Preparing the query
		$query = $db->getQuery(true);
		$query->select('c.color AS cat_color')->from('#__icagenda_category AS c')->where("(c.id=$i->catid)");
		$db->setQuery($query);
		$col=$db->loadObject()->cat_color;

		$color = '';
		if (isset($col)) {$color = $col;}

//		if(!is_array($color) && preg_match("/^[#]([0-9a-fA-F]{6})$/",$color)){

			$hex_R = substr($color,1,2);
			$hex_G = substr($color,3,2);
			$hex_B = substr($color,5,2);
			$RGBhex = hexdec($hex_R).",".hexdec($hex_G).",".hexdec($hex_B);
//		}
		$RGB = explode(",",$RGBhex);
		$RGBa=$RGB[0];
		$RGBb=$RGB[1];
		$RGBc=$RGB[2];
//		$a = array($RGBa, $RGBb, $RGBc);
//		$somme = array_sum($a);
		$somme = ($RGBa + $RGBb + $RGBc);
		if ($somme > '600') {
			$fcolor = 'fontColor';
		} else {
			$fcolor = '';
		}
		return $fcolor;
	}

	private function validEmail($email)
	{
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		} else {
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			} else if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
				// local part starts or ends with '.'
				$isValid = false;
			} else if (preg_match('/\\.\\./', $local)) {
				// local part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
				// character not valid in domain part
				$isValid = false;
			} else if (preg_match('/\\.\\./', $domain)) {
				// domain part has two consecutive dots
				$isValid = false;
			} else if
				(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
				str_replace("\\\\","",$local))) {
				// character not valid in local part unless
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/',
				str_replace("\\\\","",$local))) {
					$isValid = false;
				}
			}

			// Check the domain name
			if($isValid && !$this->is_valid_domain_name($domain)) {
				return false;
			}

			// Uncomment below to have PHP run a proper DNS check (risky on shared hosts!)
			/**
			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
				// domain not found in DNS
				$isValid = false;
			}
			/**/
		}
		return $isValid;
	}

	function is_valid_domain_name($domain_name)
	{
		$pieces = explode(".",$domain_name);
		foreach($pieces as $piece) {
			if (!preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $piece)
				|| preg_match('/-$/', $piece) ) {
				return false;
			}
		}
		return true;
	}






//	protected function gcalendarUrl ($i){
//		$ora=$i->time;
//		if($ora==0)$ora='00.00';
//		$text=$i->title.' ('.$i->cat_title.')';
//		$details=$this->descShort($i);
//		$place=$i->place.', '.$i->address;
//		$link=$this->url($i);
//
//		$date= JRequest::getVar('date');
//
//		$dates=str_replace('-', '', $date).'T'.str_replace('.', '', $ora).'00/'.str_replace('-', '', $date).'T'.str_replace('.', '', $ora).'00';
//		$href="http://www.google.com/calendar/event?action=TEMPLATE
//			&text=".urlencode($text)."
//			&dates=".$dates."
//			&place=".urlencode($place)."
//			&details=".urlencode($details)."
//			&sprop=sito web:".urlencode($link);
//
//		return $href;
//	}
//
//	protected function gcalendarLink ($i){
//		return '<a href="'.$this->gcalendarUrl($i).'">Gcalendar</a>';
//	}

}

?>
