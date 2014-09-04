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
 * @version     3.2.2.1 2013-10-06
 * @since       3.2.2
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
jimport( 'joomla.html.parameter' );
jimport( 'joomla.registry.registry' );

jimport('joomla.user.helper');
jimport('joomla.access.access');

	function iCparam ($param){
		// Import params
		$app = JFactory::getApplication();
		$iCparams = $app->getParams();
		$iCparam = $iCparams->get($param);

		return $iCparam;
	}

	// Function to get Format Date (using option format, and translation)
	function formatDate ($d){
		$mkt_date= mkt($d);

		// get Format
		$for = '0';
		$for=iCparam('format');

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
		$separator = iCparam('date_separator');
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

	// mktime with control
	function mkt($data)
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

	// Get Next Date (or Last Date)
	function nextDate ($evt, $i){

		$today=time();
		$day= date('d');
		$m= date('m');
		$y= date('y');
		$hour= date('H');
		$min= date('i');
		$today=mktime(0,0,0,$m,$day,$y);
		$testDate = mkt($evt);

		if ($testDate == $today) {
			$nextDate = '<b>'.formatDate($evt).'<span class="evttime"> '.evenTime($evt, $i).'</span></b>';
		}
		else {
			$nextDate = formatDate($evt).'<span class="evttime"> '.evenTime($evt, $i).'</span>';
		}
			return $nextDate;
	}


	function evenTime ($d, $i){
		$date_time = mkt($d);
 		$time_format = 'H:i';
 		$t_time=date($time_format, $date_time);
		$timeformat='1';
		$timeformat=iCparam('timeformat');

		if ($timeformat == 1) {
			$lang_time = strftime("%H:%M", strtotime("$t_time"));
		} else {
			$lang_time = strftime("%I:%M %p", strtotime("$t_time"));
		}

		if (isset($i->time)) {
			$oldtime = $i->time;
		} else {
			$oldtime = NULL;
		}
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


?>
