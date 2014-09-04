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
 * @version     3.1.10 2013-09-10
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

/**
 * event Table class
 */
class iCagendaTableevent extends JTable
{

	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
// 2.5 :	public function __construct(&$db)
// 2.5 :	{
// 2.5 :		parent::__construct('#__icagenda_events', 'id', $db);
// 2.5 :	}

	public function __construct(&$_db)
	{
		parent::__construct('#__icagenda_events', 'id', $_db);
//		$date = JFactory::getDate();
//		$this->created = $date->toSql();
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param	array		Named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see		JTable:bind
	 * @since	1.3
	 */
	public function bind($array, $ignore = '')
	{

		if (isset($array['params']) && is_array($array['params'])) {
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}
		// return parent::bind($array, $ignore);


		/**
		 * Serialize Single Dates
		 */
		$ctrl=unserialize($array['dates']);
		if(is_array($ctrl)){
			$dates=unserialize($array['dates']);
		}else{
			$dates=$this->getDates($array['dates']);
		}
		rsort($dates);

		$array['dates']=serialize($dates);


		/**
		 * Serialize Period Dates
		 */
		$nodate='0000-00-00 00:00:00';

		// Calcul des dates d'une période.
		$startdate= ($array['startdate']);
		$enddate= ($array['enddate']);

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
				$array['period'] = serialize($out);
				$ctrl=unserialize($array['period']);
				if(is_array($ctrl)){
					$period=unserialize($array['period']);
				}else{
					$period=$this->getPeriod($array['period']);
				}
				rsort($period);
				$array['period']=serialize($period);
			} else {
				$array['period']='';
			}
		} else {
			$array['period']='';
		}


		/**
		 * Create Next Date
		 */
		$todaytime=time();

//		$period=JRequest::getVar('period');
		$NextDates=$this->getNextDates($dates);
		if (isset($period)) {
			$NextPeriod=$this->getNextPeriod($period);
		} else {
			$NextPeriod=$this->getNextDates($dates);
		}

		$today=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$hour= date('H');
		$min= date('i');
		$today=mktime(0,0,0,$m,$day,$y);

		$nextdmkt = strtotime($NextDates);

		$nextpmkt = strtotime($NextPeriod);


		$nextDmktdate = $this->mktdate($NextDates);

		$nextPmktdate = $this->mktdate($NextPeriod);

		$nextDmkttime = $this->mkttime($NextDates);

		$nextPmkttime = $this->mkttime($NextPeriod);


//		echo 'today : '.$todaytime.' | ';
//		echo 'NextDates : '.$nextDmktdate.' '.$nextDmkttime.' | ';
//		echo 'NextPeriod : '.$nextPmktdate.' '.$nextPmkttime.' || ';

		// Controle Date à venir
		if (($nextDmktdate >= $today) && ($nextPmktdate >= $today)) {
			if ($nextDmktdate < $nextPmktdate) {
			$array['next']=$this->getNextDates($dates);
			}
			if ($nextDmktdate > $nextPmktdate) {
			$array['next']=$this->getNextPeriod($period);
			}
			if ($nextDmktdate == $nextPmktdate) {
				if ($nextDmkttime >= $nextPmkttime) {
					if (isset($period)) {
						$array['next']=$this->getNextPeriod($period);
					} else {
						$array['next']=$this->getNextDates($dates);
					}
				} else {
					$array['next']=$this->getNextDates($dates);
				}
			}
		}
		if (($nextDmktdate < $today) && ($nextPmktdate >= $today)) {
			$array['next']=$this->getNextPeriod($period);
		}
		if (($nextDmktdate >= $today) && ($nextPmktdate < $today)) {
			$array['next']=$this->getNextDates($dates);
		}
		if (($nextDmktdate < $today) && ($nextPmktdate < $today)) {
			if ($nextDmktdate < $nextPmktdate) {
			$array['next']=$this->getNextPeriod($period);
			}
			if ($nextDmktdate >= $nextPmktdate) {
			$array['next']=$this->getNextDates($dates);
			}
		}

		// Control of dates if valid (EDIT SINCE VERSION 3.0 - update 3.1.4)
		if ((($nextdmkt>='943916400') AND ($nextdmkt<='944002800')) && ($errorperiod=='1')) {
			$array['next']= '-3600';
		}
		if ((($nextdmkt=='943916400') OR ($nextdmkt=='943920000')) && (($nextpmkt=='943916400') OR ($nextpmkt=='943920000'))) {
			$array['next']= '-3600';
		}


		if ($array['next']=='-3600') {
			$state = 0;
			$this->_db->setQuery(
			'UPDATE `#__icagenda_events`' .
			' SET `state` = '.(int) $state .
			' WHERE `id` = '. (int) $array['id']
			);
			if(version_compare(JVERSION, '3.0', 'lt')) {
				$this->_db->query();
			} else {
				$this->_db->execute();
			}
		}


		/**
		 * Set Creator infos
		 */
		$user = JFactory::getUser();
		$userId	= $user->get('id');
		if ($array['created_by']=='0') {
			$array['created_by']=$userId;
		}

		$username=$user->get('name');
		$array['username']=$username;


		/**
		 * Set File upload
		 */
		if (!isset($array['file'])) {
			$file = JRequest::getVar('jform', null, 'files', 'array');
			$fileUrl = $this->upload($file);
			$array['file'] = $fileUrl;
		}

		/*if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}*/



		/**
		 * Set Meta data
		 */
		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string)$registry;
		}


		/**
		 * Set Week Days
		 */
		if (!isset($array['weekdays']) && !is_array($array['weekdays'])) {
			$array['weekdays'] = '';
		}
		if (isset($array['weekdays']) && is_array($array['weekdays'])) {
			$array['weekdays'] = implode(",", $_REQUEST['jform']['weekdays']);
		}
//		if (!isset($array['weekdays'])) {
//			$array['weekdays'] = implode(",", "");
//		}

		return parent::bind($array, $ignore);
	}


	function getDates ($dates){
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
		$next=JRequest::getVar('next');

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


	function getNextPeriod ($period)
	{
		$today=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$today=mktime(0,0,0,$m,$day,$y);
		$next=JRequest::getVar('next');

		if(count($period)){

			while ($next <= $today) {
				$ee = $this->mkt($period[0]);
				$nextPeriod=$ee;
				foreach($period as $e){
					$e=$this->mkt($e);
					if ($e>=$today){
						$nextPeriod=$e;
					}
				}
//for test				echo ' today : '.$today;
//for test				echo ' next : '.$next;
//for test				echo ' date next : '.$d;

			return date('Y-m-d H:i', $nextPeriod);

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

	/**
	 * upload
	 */

	function upload ($file){
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

//	if(version_compare(JVERSION, '3.1.2', 'lt')) {
		$filename = JFile::makeSafe($file['name']['file']);
//	}

		if($filename!=''){

			$src = $file['tmp_name']['file'];
			$dest =  JPATH_SITE.'/images/icagenda/files/'.$filename;

			if(!is_dir($dest)){
				mkdir($intDir, 0755);
			}


			if ( JFile::upload($src, $dest, false) ){
				echo 'upload';
				return 'images/icagenda/files/'.$filename;
			}

			return 'images/icagenda/files/'.$filename;
		}
	}

	/**
	* Overloaded check function
	*/
	public function check() {

		//If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0) {
			$this->ordering = self::getNextOrder();
		}

		// URL
		jimport( 'joomla.filter.output' );
		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);


		return parent::check();
	}


	/**
	* Method to set the publishing state for a row or list of rows in the database
	* table.  The method respects checked out rows by other users and will attempt
	* to checkin rows that it can after adjustments are made.
	*
	* @param	mixed	An optional array of primary key values to update.  If not
	*					set the instance property value is used.
	* @param    integer The publishing state. eg. [0 = unpublished, 1 = published]
	* @param    integer The user id of the user performing the operation.
	* @return    boolean    True on success.
	* @since    1.0.4
	*/
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		}
		else {
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `'.$this->_tbl.'`' .
			' SET `state` = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);
// J2.5 :
		$this->_db->query();

// J3
//		$this->_db->setQuery($query);
//		$this->_db->execute();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}


		/**
		* Overloaded load function
		*
		* @param       int $pk primary key
		* @param       boolean $reset reset data
		* @return      boolean
		* @see JTable:load
		*/
		public function load($pk = null, $reset = true)
		{
			if (parent::load($pk, $reset))
			{
			// Convert the params field to a registry.
				$params = new JRegistry;
				// loadJSON is @deprecated    12.1  Use loadString passing JSON as the format instead.
				// $params->loadString($this->item->params, 'JSON');
				// "item" should not be present.
				if(version_compare(JVERSION, '3.0', 'lt')) {
					$params->loadJSON($this->params);
				} else {
					$params->loadString($this->params);
				}
				$this->params = $params;
				return true;
			}
			else
			{
				return false;
			}
		}

}
