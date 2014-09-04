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

jimport( 'joomla.filesystem.path' );

// Load file helpers

if(!class_exists('iCModelItem'))require(JPATH_COMPONENT.DS.'helpers'.DS.'icmodel.php');


/**
 * icagenda Model
 */
class icagendaModelList extends iCModelItem
{

	/**
	 * Get Params
	 */
	public function getData()
	{
		$this->startiCModel();
		$user = JFactory::getUser();
		$userid=$user->get('id');

		$regpost = JRequest::get( 'post' );
		if (JRequest::getVar('event')){
			$this->registration($regpost);
		}

		// Import params
		$app = JFactory::getApplication();
		$icpar = $app->getParams();


		// filters
		$this->addFilter('state', 1);
		//Add Security
		$id = JRequest::getInt('id');
		if ((!$id) || (!preg_match("/^[0-9]+$/", $id))) {};

		$Itemid = JRequest::getInt('Itemid');
		//UPDATED in 1.2.8
		if ((!$Itemid) || (!preg_match("/^[0-9]+$/", $Itemid)));

		if($id){

			$this->addFilter('id', $id);

		} else {

			if($icpar->get('mcatid')) $this->addFilter('e.catid', $icpar->get('mcatid'));
			if($icpar->get('place')!='NULL') $this->addFilter('e.place', $icpar->get('place'));
			if($icpar->get('address')) $this->addFilter('e.address', $icpar->get('address'));
			if(JRequest::getVar('key','' ,'post')) $this->addFilter('key', JRequest::getVar('key','' ,'post'));
			if($icpar->get('time')) $this->addFilter('next', $icpar->get('time'));
		}

		// Options
		if($icpar->get('orderby')) $this->addOption('orderby', $icpar->get('orderby'));
		// added in 1.2.6
		if($icpar->get('mcatid')) $this->addOption('mcatid', $icpar->get('mcatid'));

		if($icpar->get('addthis')) $this->addOption('addthis', $icpar->get('addthis'));
		if($icpar->get('atevent')) $this->addOption('atevent', $icpar->get('atevent'));
		if($icpar->get('atfloat')) $this->addOption('atfloat', $icpar->get('atfloat'));
		if($icpar->get('aticon')) $this->addOption('aticon', $icpar->get('aticon'));
		if($icpar->get('timeformat')) $this->addOption('timeformat', $icpar->get('timeformat'));
		if($icpar->get('participantList')) $this->addOption('participantList', $icpar->get('participantList'));
		if($icpar->get('participantSlide')) $this->addOption('participantSlide', $icpar->get('participantSlide'));
		if($icpar->get('participantDisplay')) $this->addOption('participantDisplay', $icpar->get('participantDisplay'));
		if($icpar->get('fullListColumns')) $this->addOption('fullListColumns', $icpar->get('fullListColumns'));
		if($icpar->get('targetLink')) $this->addOption('targetLink', $icpar->get('targetLink'));
		if($icpar->get('arrowtext')) $this->addOption('arrowtext', $icpar->get('arrowtext'));
		if($icpar->get('statutReg')) $this->addOption('statutReg', $icpar->get('statutReg'));
		if($icpar->get('accessReg')) $this->addOption('accessReg', $icpar->get('accessReg'));
		if($icpar->get('limitRegEmail')) $this->addOption('limitRegEmail', $icpar->get('limitRegEmail'));
		if($icpar->get('limitRegDate')) $this->addOption('limitRegDate', $icpar->get('limitRegDate'));
		if($icpar->get('maxReg')) $this->addOption('maxReg', $icpar->get('maxReg'));
		if($icpar->get('maxRlist')) $this->addOption('maxRlist', $icpar->get('maxRlist'));
		if($icpar->get('emailRequired')) $this->addOption('emailRequired', $icpar->get('emailRequired'));
		if($icpar->get('phoneRequired')) $this->addOption('phoneRequired', $icpar->get('phoneRequired'));
		if($icpar->get('regEmailUser')) $this->addOption('regEmailUser', $icpar->get('regEmailUser'));
		if($icpar->get('emailUserSubjectPeriod')) $this->addOption('emailUserSubjectPeriod', $icpar->get('emailUserSubjectPeriod'));
		if($icpar->get('emailUserBodyPeriod')) $this->addOption('emailUserBodyPeriod', $icpar->get('emailUserBodyPeriod'));
		if($icpar->get('emailUserSubjectDate')) $this->addOption('emailUserSubjectDate', $icpar->get('emailUserSubjectDate'));
		if($icpar->get('emailUserBodyDate')) $this->addOption('emailUserBodyDate', $icpar->get('emailUserBodyDate'));
		if($icpar->get('headerList')) $this->addOption('headerList', $icpar->get('headerList'));
		if($icpar->get('RegButtonText')) $this->addOption('RegButtonText', $icpar->get('RegButtonText'));


		if($icpar->get('number')) $this->addOption('number', $icpar->get('number'));
		if($icpar->get('template')) $this->addOption('template', $icpar->get('template'));
		if($icpar->get('limitGlobal')) $this->addOption('limitGlobal', $icpar->get('limitGlobal'));
		if($icpar->get('limit')) $this->addOption('limit', $icpar->get('limit'));
		if($icpar->get('format')) $this->addOption('format', $icpar->get('format'));
		if($icpar->get('date_format')) $this->addOption('date_format', $icpar->get('date_format'));
		if($icpar->get('date_separator')) $this->addOption('date_separator', $icpar->get('date_separator'));
		if($icpar->get('m_width')) $this->addOption('m_width', $icpar->get('m_width'));
		if($icpar->get('m_height')) $this->addOption('m_height', $icpar->get('m_height'));
		if($icpar->get('itemid')) {$this->addOption('Itemid', $icpar->get('itemid'));} else {$this->addOption('Itemid', $Itemid);}


		// Struture
		$structure=array(
			'container'=>array(
				'template'=>'',
				'header'=>'',
				'navigator'=>''
			),
			'items'=>array(
				'item'=>array(
					'BackArrow'=>'',
					'AllDates'=>'',
					'weekday'=>'',
					'weekdayShort'=>'',
					'id'=>'',
					'Itemid'=>'',
					'CurrentItemid'=>'',
					'BackURL'=>'',
					'timeformat'=>'',
					'participantList'=>'',
					'participantSlide'=>'',
					'participantDisplay'=>'',
					'fullListColumns'=>'',
					'participantListTitle'=>'',
					'arrowtext'=>'',
					'navposition'=>'',
					'headerList'=>'',
					'title'=>'',
					'titlebar'=>'',
					'ManagerIcons'=>'',
					'url'=>NULL,
					'titleLink'=>'',
					'cat_id'=>'',
					'cat_title'=>'',
					'cat_color'=>'',
					'fontColor'=>'',
					'cat_desc'=>'',
					'desc'=>'',
					'description'=>'',
					'descShort'=>'',
					'image'=>'',
					'imageTag'=>'',
					'infoDetails'=>'',
					'file'=>'',
					'fileTag'=>'',

					'displaytime'=>'',
					'nextMkt'=>'',
					'next'=>'',
					'nextDate'=>'',
					'nextPeriod'=>'',
					'nextControl'=>'',

					'startDate'=>'',
					'startDay'=>'',

					'endDate'=>'',
					'endDay'=>'',
					'endMonth'=>'',
					'endMonthNum'=>'',
					'endYear'=>'',

					'startTime'=>'',
					'endTime'=>'',

					'periodDates'=>'',
					'dateText'=>'',
					'periodDisplay'=>'',
					'periodControl'=>'',
					'day'=>'',
					'maxNbTickets'=>'',
					'maxReg'=>'',
					'maxRlist'=>'',
					'emailRequired'=>'',
					'phoneRequired'=>'',

					'month'=>'',
					'monthShort'=>'',
					'monthShortJoomla'=>'',
					'monthNum'=>'',

					'year'=>'',
					'yearShort'=>'',
					'evenTime'=>'',
					'dateFormat'=>'',
					'datelistMkt'=>'',
					'datelist'=>'',
					'datelistUl'=>'',
					'time'=>'',
					'address'=>'',
					'name'=>'',
					'email'=>'',
					'emailLink'=>'',
					'phone'=>'',
					'website'=>'',
					'websiteLink'=>'',
					'targetLink'=>'',
					'place_name'=>'',
					'place_desc'=>'',
					'placeLeft'=>'',
					'city'=>'',
					'country'=>'',
					'coordinate'=>'',
					'lat'=>'',
					'lng'=>'',
					'map'=>'',
					'share'=>'',
					'share_event'=>'',
					'statutReg'=>'',
					'accessReg'=>'',
					'limitRegEmail'=>'',
					'limitRegDate'=>'',
//					'gcalendarUrl'=>'',
//					'gcalendarLink'=>'',
					'registered'=>'',
					'registeredUsers'=>'',
					'reg'=>'',
					'regUrl'=>'',
					'typeReg'=>'',
					'regEmailUser'=>'',
					'emailUserSubjectPeriod'=>'',
					'emailUserBodyPeriod'=>'',
					'emailUserSubjectDate'=>'',
					'emailUserBodyDate'=>'',
					'language'=>'',
					'params'=>''
				)
			)
		);
		return $this->getItems($structure);
	}

   /**

    * Get the return URL.

    *

    * @return  string   The return URL.

    * @since   1.6

    */

   public function getReturnPage()

   {

      return base64_encode($this->getState('return_page'));

   }




}
