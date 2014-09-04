<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda - mod_iccalendar
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.2.7 2013-11-23
 * @since       3.1.9 (1.0)
 *------------------------------------------------------------------------------
*/

/**
 *	iCagenda - iC calendar
 */


// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.methods' );
jimport( 'joomla.environment.request' );
jimport('joomla.application.component.helper');

// classe du Module
class modiCcalendarHelper
{

	private function construct($params)
	{
		$this->catid = $params->get('mcatid');
		$this->time = $params->get('time');
		$this->number = $params->get('number');
		$this->onlyStDate = $params->get('onlyStDate');
		$page = JRequest::getVar('page', '0', 'get', 'int');

		$number=$this->number;

		if (!$page){
			$this->start='0';
		}
		if ($page){
			$this->start = $number*($page-1);
		}
		$this->template=$params->get('template');
		$this->format= $params->get('format');
		$this->date_separator= $params->get('date_separator');

		//Add Security

		$mItemid = JRequest::getInt('Itemid');
//		if($this->itemid===NULL) $this->itemid = $mItemid;
		$this->itemid = $mItemid;

		$this->date_start=date('Y-m-d');
		$dateget=JRequest::getVar('date');
		if(isset($dateget)&&(!empty($dateget))) $this->date_start=$dateget;

		if($this->time==0) $this->addFilter('e.next', $this->date_start,'>=');
		if($this->catid!=0) $this->addFilter('e.catid', $this->catid);
	}


	function start($params)
	{
		$this->construct($params);
	}


	function addFilter($key, $var, $for=NULL){
		if($for==NULL) $for='=';
		$this->filter[$key]=' AND '.$key.$for.$var;
	}


	function getDatesPeriod($startdate, $enddate)
	{
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
		$dates = $out;

//		if($start > $end)
//		{
//			return false;
//		}

//		$sdate    = strtotime($start);
//		//$sdate    = strtotime("$start +1 day");
//		$edate    = strtotime($end);

//		$dates = array();

//		for($i = $sdate; $i <= $edate; $i += strtotime('+1 day', 0))
//		{
//			//$dates[] = date('Y-m-d H:i', $i);
//			$dates[] = date('Y-m-d', $i);
//		}

		return $dates;
	}


	// function to get number of registered people to an event
	public function registered ($eventID){

		// Preparing connection to db
		$db = JFactory::getDBO();
		// Preparing the query
		$query = $db->getQuery(true);
		$query->select(' sum(r.people) AS registered')->from('#__icagenda_registration AS r')->where('(r.eventId='.(int)$eventID.') AND (r.state > 0)');
		$db->setQuery($query);
		$people = $db->loadObjectList();

		$nbreg = $people[0]->registered;

		return $nbreg;

	}


	// Class Method
	function getStamp($params)
	{
		// iCthumb generator pre-settings
		include_once JPATH_ROOT.'/media/com_icagenda/scripts/icthumb.php';

		// Check if GD is enabled
		if (extension_loaded('gd') && function_exists('gd_info')) {
			$ic_params = JComponentHelper::getParams('com_icagenda');
			$thumb_generator = $ic_params->get('thumb_generator', 1);
		} else {
			$thumb_generator = 0;
			//JError::raiseWarning('101', JText::_('COM_ICAGENDA_PHP_ERROR_GD'));
		}

		// Check if fopen is allowed
		$fopen = true;
		$result = ini_get('allow_url_fopen');
		if (empty($result))
		{
			//JError::raiseWarning('101', JText::_('COM_ICAGENDA_PHP_ERROR_FOPEN'));
			$fopen = false;
		}

		$this->start($params);
		// Prépare la requête
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		// Sélection de la requête
		$query->select(
			'e.id, e.title, e.alias, e.access, e.approval, e.language, e.params, e.catid, e.image, e.file, e.next, e.dates, e.period, e.startdate, e.enddate, e.weekdays, e.time, e.desc, e.address as address, e.place as place_name, e.city as city, e.country as country, e.coordinate as coordinate, c.title as cat_title, c.alias as cat_alias, c.color as cat_color'
		);
		$query->from('`#__icagenda_events` AS e');
		$query->leftJoin('`#__icagenda_category` AS c ON c.id = e.catid');
		$query->leftJoin('`#__icagenda_events` AS l ON l.id = e.place');
		$query->order('e.next DESC');

		// where
		$where='e.state = 1';
		$where.=' AND e.approval = 0';

		// ajout des filtres
		if(isset($this->filter)){
			foreach ($this->filter as $filter){
				$where.=$filter;
			}
		}

		$query->where($where);
//		$query->order('e.next');
		$query.=' LIMIT 0, 1000';
		// requête
		$db->setQuery($query);
		$res = $db->loadObjectList();

//		$days=$this->getDays($this->date_start, $this->format);
		$days=$this->getDays($this->date_start, 'Y-m-d H:i');

		foreach ($res as $r) {
			// liste dates calendrier
			if (isset($next)) {$next=$next;} else {$next='';}

			$datemultiplelist=$this->getDatelist($r->dates, $next);
//			$dateperiodlist=$this->getPeriodlist($r->period, $next);
//			if ($dateperiodlist) {
//				$datelist=array_merge($datemultiplelist, $dateperiodlist);
//			} else {
				$datelist=$datemultiplelist;
//			}



			$AllDates = array();
			if (isset($r->weekdays)) {$weekdays = $r->weekdays;} else {$weekdays = '';}

			$weekdays = explode (',', $weekdays);
			$weekdaysarray = array();

			foreach ($weekdays as $wed) {
				array_push($weekdaysarray, $wed);
			}

			if (in_array('', $weekdaysarray)) {
				$arrayWeekDays = array(0,1,2,3,4,5,6);
			}
			elseif ($r->weekdays) {
				$arrayWeekDays = $weekdaysarray;
			}
			elseif (in_array('0', $weekdaysarray)) {
				$arrayWeekDays = $weekdaysarray;
			}
			else {
				$arrayWeekDays = array(0,1,2,3,4,5,6);
				//print_r($arrayWeekDays);
			}
			$WeeksDays = $arrayWeekDays;

			// If Single Dates, added to all dates for this event
			$singledates = unserialize($r->dates);
			if ((isset ($datemultiplelist)) AND ($datemultiplelist!=NULL) AND (!in_array('0000-00-00 00:00:00', $singledates))) {
				$AllDates = array_merge($AllDates, $datemultiplelist);
			}

			$StDate = date('Y-m-d H:i', $this->mkttime($r->startdate));
			$EnDate = date('Y-m-d H:i', $this->mkttime($r->enddate));
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
//					array_push($AllDates, $StDate);
//					array_push($AllDates, $EnDate);
				}
			}
//			print_r($AllDates);
			rsort($AllDates);


			//liste dates next
			$datemlist=$this->getmlist($r->dates, $next);
			$dateplist=$this->getplist($r->period, $next);
			if ($dateplist) {
				$datelistcal=array_merge($datemlist, $dateplist);
			} else {
				$datelistcal=$datemlist;
			}

			$todaytime=time();

			rsort($datelist);
			rsort($datelistcal);
// Retrait 3.1.7
//			if ($datelistcal > $todaytime) {
//				$alldates=serialize($datelistcal);
//			}




			// requête Itemid
 			$lang = JFactory::getLanguage();
			$langcur = $lang->getTag();
			$langcurrent = $langcur;
			$noidm='';

			$db = JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->select('id AS idm')->from('#__menu')->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '$langcurrent')" );
			$db->setQuery($query);
			$idm=$db->loadResult();
			$mItemid=$idm;

			if ($mItemid == NULL) {
			$db = JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->select('id AS noidm')->from('#__menu')->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '*')" );
			$db->setQuery($query);
			$noidm=$db->loadResult();
			$noidm=$noidm;
			}
			$nolink = '';
			if ($noidm == NULL && $mItemid == NULL) {
				$nolink = 1;
			}

			$iCmenuitem='';
			$iCmenuitem=$params->get('iCmenuitem');

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

			$eventnumber = NULL;
			$eventnumber = $r->id;

			if ($nolink == 1) {
				$urlevent = '#';
			}else {
				$urlevent = JRoute::_('index.php?option=com_icagenda&amp;view=list&amp;layout=event&amp;id='.(int)$eventnumber.'&amp;Itemid='.(int)$lien);
			}


			$limit = JComponentHelper::getParams('com_icagenda')->get('ShortDescLimit');
//			$limit=$this->options['limit'];
			$text=strip_tags($r->desc, '<br><br/>');
			if(strlen($text)>$limit){
				$string_cut=substr($text, 0,$limit);
				$last_space=strrpos($string_cut," ");
				$string_ok=substr($string_cut, 0,$last_space);
				$text=$string_ok." ";
				$descShort=$text.'[&#46;&#46;&#46;]';
			}else{
				$descShort=$text;
			}

//			$dates=unserialize($r->dates);
//			$period=unserialize($r->period);
//			if($period != NULL) {$alldates = array_merge($dates, $period);} else {$alldates = $dates;}

//			$today=time();
//			$day= date('d');
//			$m= date('m');
//			$y= date('y');
//			$toDay=mktime(0,0,0,$m,$day,$y);
//			$time='';

//			foreach($alldates as $a){
//					foreach ($datelist as $d){
//						foreach ($days as $k=>$dy){
//							if($d==$dy['date']){
//								$dd=$this->mkttime($a);
//								$time=date('H:i', $dd);
//							}
//						}
//					}
//			}

						// START iCthumb

						// Initialize Vars
						$Image_Link 			= '';
						$Thumb_Link 			= '';
						$Display_Thumb 			= false;
						$No_Thumb_Option		= false;
						$Default_Thumb			= false;
						$MimeTypeOK 			= true;
						$MimeTypeERROR			= false;
						$Invalid_Link 			= false;
						$Invalid_Img_Format		= false;
						$fopen_bmp_error_msg	= false;

						// SETTINGS ICTHUMB
						$FixedImageVar 			= $r->image;

						// Set if run iCthumb
						if (($FixedImageVar) AND ($thumb_generator == 1)) {

							$params_media = JComponentHelper::getParams('com_media');
							$image_path = $params_media->get('image_path', 'images');

							// Set folder vars
							$fld_icagenda 		= 'icagenda';
							$fld_thumbs 		= 'thumbs';
							$fld_copy	 		= 'copy';

							// SETTINGS ICTHUMB
							$thumb_width		= '100';
							$thumb_height		= '200';
							$thumb_quality		= '100';
							$thumb_destination	= 'themes/w'.$thumb_width.'h'.$thumb_height.'q'.$thumb_quality.'_';

							// Get Image File Infos
							$url = $FixedImageVar;
							$decomposition = explode( '/' , $url );
							// in each parent
							$i = 0;
							while ( isset($decomposition[$i]) )
								$i++;
							$i--;
							$imgname = $decomposition[$i];
							$fichier = explode( '.', $decomposition[$i] );
							$imgtitle = $fichier[0];
							$imgextension = strtolower($fichier[1]); // fixed 3.1.10


//							$GblParams = JComponentHelper::getParams('com_icagenda');
//							$MaxWidth = $GblParams->get('Event_MaxWidth');
//							$MaxHeight = $GblParams->get('Event_MaxHeight');
//							$Quality = $GblParams->get('Event_Quality');

							// Get Image File Infos (minimum php 5.2) - Remove in 3.1.9
//							$path = pathinfo($item->image);
//							$imgname = $path['basename'];			// Get file base name
//							$imgdirname = $path['dirname'];		// Get image dirname
//							$imgextension = strtolower($path['extension']);	// Get image extension
//							$imgtitle = $path['filename'];			// Get image name

							// Clean file name
							jimport( 'joomla.filter.output' );
							$cleanFileName = JFilterOutput::stringURLSafe($imgtitle) . '.' . $imgextension;
							$cleanTitle = JFilterOutput::stringURLSafe($imgtitle);

//							$cleanFileName2 = cleanString($imgtitle) . '.' . $imgextension;
//							$cleanTitle2 = cleanString($imgtitle);
//							echo $cleanFileName.'<br />'.$cleanFileName2;

							// Paths to thumbs and copy folders
							$thumbsPath 			= $image_path.'/'.$fld_icagenda.'/'.$fld_thumbs.'/';
							$copyPath	 			= $image_path.'/'.$fld_icagenda.'/'.$fld_thumbs.'/'.$fld_copy.'/';

							// Image pre-settings
							$imageValue 			= $FixedImageVar;
							$Image_Link 			= $FixedImageVar;
							$Invalid_LinkMsg		= '<i class="icon-warning"></i><br /><span style="color:red;"><strong>' . JText::_('COM_ICAGENDA_INVALID_PICTURE_LINK') . '</strong></span>';
							$Wrong_img_format		= '<i class="icon-warning"></i><br/><span style="color:red;"><strong>' . JText::_('COM_ICAGENDA_NOT_AUTHORIZED_IMAGE_TYPE') . '</strong><br/>' . JText::_('COM_ICAGENDA_NOT_AUTHORIZED_IMAGE_TYPE_INFO') . '</span>';
							$fopen_bmp_error		= '<i class="icon-warning"></i><br/><span style="color:red;"><strong>' . JText::_('COM_ICAGENDA_PHP_ERROR_FOPEN_COPY_BMP') . '</strong><br/>' . JText::_('COM_ICAGENDA_PHP_ERROR_FOPEN_COPY_BMP_INFO') . '</span>';

							// Mime-Type pre-settings
							$errorMimeTypeMsg 		= '<i class="icon-warning"></i><br /><span style="color:red;"><strong>' . JText::_('COM_ICAGENDA_ERROR_MIME_TYPE') . '</strong><br/>' . JText::_('COM_ICAGENDA_ERROR_MIME_TYPE_NO_THUMBNAIL');

							// url to thumbnails already created
							$Thumb_Link 			= $image_path.'/'.$fld_icagenda.'/'.$fld_thumbs.'/'.$thumb_destination . $cleanFileName;
							$Thumb_aftercopy_Link 	= $image_path.'/'.$fld_icagenda.'/'.$fld_thumbs.'/'.$thumb_destination . $cleanTitle . '.jpg';

							// Check if thumbnails already created
							if ((file_exists(JPATH_ROOT . '/' . $Thumb_Link)) AND (!file_exists(JPATH_ROOT . '/' . $Thumb_aftercopy_Link))) {
								$Thumb_Link = $Thumb_Link;
								$Display_Thumb = true;
							}
							elseif (file_exists(JPATH_ROOT . '/' . $Thumb_aftercopy_Link)) {
								$Thumb_Link = $Thumb_aftercopy_Link;
								$Display_Thumb = true;
							}
							// if thumbnails not already created, create thumbnails
							else {

								if (filter_var($imageValue, FILTER_VALIDATE_URL)) {
									$linkToImage = $imageValue;
								} else {
									$linkToImage = JPATH_ROOT . '/' . $imageValue;
								}

								if (file_exists($linkToImage)) {

//									if ($imgextension == "png") {
//										if (mime_content_type($linkToImage) != 'image/png') { echo 'Yes'; } else { }
//									}

									// Test Mime-Type
									$fileinfos = getimagesize($linkToImage);
									$mimeType = $fileinfos['mime'];
									$extensionType = 'image/'.$imgextension;
//									echo $mimeType.'<br/>'.$extensionType.'<br/>';

									// Message Error Mime-Type info
									$errorMimeTypeInfo = '<span style="color:black;"><br/>' . JText::sprintf('COM_ICAGENDA_ERROR_MIME_TYPE_INFO', $imgextension, $mimeType);

									// Error message if Mime-Type is not the same as extension
									if (($imgextension == 'jpeg') OR ($imgextension == 'jpg')) {
										if (($mimeType != 'image/jpeg') AND ($mimeType != 'image/jpg')) {
											$MimeTypeOK 	= false;
											$MimeTypeERROR 	= true;
										}
									}
									elseif ($imgextension == 'bmp') {
										if (($mimeType != 'image/bmp') AND ($mimeType != 'image/x-ms-bmp')) {
											$MimeTypeOK 	= false;
											$MimeTypeERROR 	= true;
										}
									}
									else {
										if ($mimeType != $extensionType) {
											$MimeTypeOK 	= false;
											$MimeTypeERROR 	= true;
										}
									}
								}
//								switch ($size['mime']) {
//									case "image/gif":
//										echo "Image is a gif";
//										break;
//									case "image/jpeg":
//										echo "Image is a jpeg";
//										break;
//									case "image/jpg":
//										echo "Image is a jpg";
//										break;
//									case "image/png":
//										echo "Image is a png";
//										break;
//									case "image/bmp":
//										echo "Image is a bmp";
//										break;
//									case "image/x-ms-bmp":
//										echo "Image is a bmp";
//										break;
//								}

								// If Error mime-type, no thumbnail creation
								if ($MimeTypeOK) {


									// Call function and create image thumbnail for events list in admin

									// If Image JPG, JPEG, PNG or GIF
									if (($imgextension == "jpg") OR ($imgextension == "jpeg") OR ($imgextension == "png") OR ($imgextension == "gif")) {

										$Thumb_Link = $Thumb_Link;

										if (!file_exists(JPATH_ROOT . '/' . $Thumb_Link)) {

											if (filter_var($imageValue, FILTER_VALIDATE_URL)) {

												if ((url_exists($imageValue)) AND ($fopen)) {

													$testFile = JPATH_ROOT . '/' . $copyPath . $cleanFileName;
													if (!file_exists($testFile)) {
														//Get the file
														$content = file_get_contents($imageValue);
														//Store in the filesystem.
														$fp = fopen(JPATH_ROOT . '/' . $copyPath . $cleanFileName, "w");
														fwrite($fp, $content);
														fclose($fp);
													}

													$linkToImage = JPATH_ROOT . '/' . $copyPath . $cleanFileName;
													$imageValue = $copyPath . $cleanFileName;

												} else {
													$linkToImage = $imageValue;
												}

											} else {
												$linkToImage = JPATH_ROOT . '/' . $imageValue;
											}

											if ((url_exists($linkToImage)) OR (file_exists($linkToImage))) {
												createthumb($linkToImage, JPATH_ROOT . '/' . $Thumb_Link, $thumb_width, $thumb_height, $thumb_quality);

											} else {
												$Invalid_Link = true;

											}
										}
									}

									// If Image BMP
									elseif ($imgextension == "bmp") {

										$Image_Link = $copyPath . $cleanTitle . '.jpg';

										$Thumb_Link = $Thumb_aftercopy_Link;

										if (!file_exists(JPATH_ROOT . '/' . $Thumb_Link)) {

											if (filter_var($imageValue, FILTER_VALIDATE_URL)) {

												if ((url_exists($imageValue)) AND ($fopen)) {

													$testFile = JPATH_ROOT . '/' . $copyPath . $cleanTitle . '.jpg';
													if (!file_exists($testFile)) {
														//Get the file
														$content = file_get_contents($imageValue);
														//Store in the filesystem.
														$fp = fopen(JPATH_ROOT . '/' . $copyPath . $cleanFileName, "w");
														fwrite($fp, $content);
														fclose($fp);
														$imageNewValue = JPATH_ROOT . '/' . $copyPath . $cleanFileName;
														imagejpeg(ImageCreateFromBMP($imageNewValue), JPATH_ROOT . '/' . $copyPath . $cleanTitle . '.jpg', 100);
														unlink($imageNewValue);
													}

												} else {
													$linkToImage = $imageValue;
												}

											} else {
												imagejpeg(imagecreatefrombmptest(JPATH_ROOT . '/' . $imageValue), JPATH_ROOT . '/' . $copyPath . $cleanTitle . '.jpg', 100);
											}

											$imageValue = $copyPath . $cleanTitle . '.jpg';
											$linkToImage = JPATH_ROOT . '/' . $imageValue;

											if (!$fopen) {
												$fopen_bmp_error_msg = true;
											}
											elseif ((url_exists($linkToImage)) OR (file_exists($linkToImage))) {
												createthumb($linkToImage, JPATH_ROOT . '/' . $Thumb_Link, $thumb_width, $thumb_height, $thumb_quality);
											}
											else {
												$Invalid_Link = true;
											}
										}
									}

									// If Not authorized Image Format
									else {
										if ((url_exists($linkToImage)) OR (file_exists($linkToImage))) {
											$Invalid_Img_Format = true;
										} else {
											$Invalid_Link = true;
										}
									}

									if (!$Invalid_Link) {
										$Display_Thumb = true;
									}
								}
								// If error Mime-Type
								else {
									if (($imgextension == "jpg") OR ($imgextension == "jpeg") OR ($imgextension == "png") OR ($imgextension == "gif") OR ($imgextension == "bmp")) {
										$MimeTypeERROR = true;
									} else {
										$Invalid_Img_Format = true;
										$MimeTypeERROR = false;
									}
								}
							}

						}
						elseif (($FixedImageVar) AND ($thumb_generator == 0)) {
							$No_Thumb_Option = true;
						}
						else {
							$Default_Thumb = true;
						}

						// END iCthumb



						// Set Thumbnail
						$default_thumbnail = 'media/com_icagenda/images/nophoto.jpg';
						if ($Invalid_Img_Format) {
							$thumb_img = $default_thumbnail;
						}

						if ($Invalid_Link) {
							$thumb_img = $default_thumbnail;
						}

						if ($MimeTypeERROR) {
							$thumb_img = $default_thumbnail;
						}

						if ($fopen_bmp_error_msg) {
							$thumb_img = $default_thumbnail;
						}

						if ($Display_Thumb) {
							$thumb_img = $Thumb_Link;
						}

						if ($No_Thumb_Option) {
							$thumb_img = $FixedImageVar;
						}

						if ($Default_Thumb) {
							if ($r->image) {
								$thumb_img = $default_thumbnail;
							} else {
								$thumb_img = '';
							}
						}

						if ((!file_exists(JPATH_ROOT . '/' . $Thumb_Link)) AND ($r->image)) {
							$thumb_img = $default_thumbnail;
						}

			$evtParams = '';
			$evtParams = new JRegistry($r->params);

			// Display City
			$dp_city = $params->get('dp_city', 1);
			if ($dp_city == 1) {
				$r_city = $r->city;
			} else {
				$r_city = false;
			}

			// Display Venue Name
			$dp_venuename = $params->get('dp_venuename', 1);
			if ($dp_venuename == 1) {
				$r_place = $r->place_name;
			} else {
				$r_place = false;
			}

			// Display Short Description
			$dp_shortDesc = $params->get('dp_shortDesc', 1);
			if ($dp_shortDesc != 1) {
				$descShort = false;
			}

			// Display Registration Infos
			$dp_regInfos = $params->get('dp_regInfos', 1);
			if ($dp_regInfos == 1) {
				$registered = $this->registered($r->id);
				$maxTickets = $evtParams->get('maxReg');
				$TicketsLeft = ($maxTickets - $registered);
			} else {
				$registered = false;
				$maxTickets = false;
				$TicketsLeft = false;
			}

			$event=array(
				'id' => (int)$r->id,
				'registered' => (int)$registered,
				'maxTickets' => (int)$maxTickets,
				'TicketsLeft' => (int)$TicketsLeft,
				'Itemid' => (int)$mItemid,
				'url'=> $urlevent,
				'title' => $r->title,
				'next' => $this->formatDate($r->next),
//				'image' => $r->image,
				'image' => $thumb_img,
// Time under test developpement
//				'time' => $time,
				'address' => $r->address,
				'city' => $r_city,
				'country' => $r->country,
				'place' => $r_place,
				'description' => $r->desc,
				'descShort' => $descShort,
				'cat_title' => $r->cat_title,
				'cat_color' => $r->cat_color,
				'nb_events' => count($r->id),
				'no_image' => JTEXT::_('MOD_ICCALENDAR_NO_IMAGE'),
			);

			$user = JFactory::getUser();
			$userID = $user->id;
			$userLevels = $user->getAuthorisedViewLevels();
			if(version_compare(JVERSION, '3.0', 'lt')) {
				$userGroups = $user->getAuthorisedGroups();
			} else {
				$userGroups = $user->groups;
			}

			// Initialize
			$access='0';
			$control='';

			// Access Control
			$user = JFactory::getUser();
			$userLevels = $user->getAuthorisedViewLevels();
			$access=$r->access;
			if ($access == '0') { $access='1'; }

//			foreach ($userLevels as $level){
//				if ($level == $access) {
//					$control=$access;
//				}
//			}

			if ( in_array($access, $userLevels) OR in_array('8', $userGroups) )  {
				$control=$access;
			}

			// Language Control
			$lang = JFactory::getLanguage();
			$eventLang = '';
			$langTag = '';
			$langTag = $lang->getTag();

			if(isset($r->language)) $eventLang=$r->language;
			if($eventLang=='') $eventLang=$langTag;
			if($eventLang=='*') {
				$eventLang=$langTag;
			}

			// Get List of Dates
			if ($control == $access) {
				if ($eventLang == $langTag) {

					if (is_numeric($lien) && is_numeric($eventnumber) && !is_array($lien) && !is_array($eventnumber)) {
						if (is_array($event)) {
							foreach ($AllDates as $d){
								foreach ($days as $k=>$dy){
									if($d==$dy['date']){
										array_push ($days[$k]['events'], $event);
									}
								}
							}
						}
					}
				}
			}

		}

		$i='';

 		$lang = JFactory::getLanguage();
		$langcur = $lang->getTag();
		$langcurrent = $langcur;

		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('id AS idm')->from('#__menu')->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '$langcurrent')" );
		$db->setQuery($query);
		$idm=$db->loadResult();
		$mItemid=$idm;

		if ($mItemid == NULL) {
		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('id AS noidm')->from('#__menu')->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '*')" );
		$db->setQuery($query);
		$noidm=$db->loadResult();
		$noidm=$noidm;
		}
		$nolink = '';
		if ($noidm == NULL && $mItemid == NULL) {
			$nolink = 1;
		}
		if ($nolink == 1) {
			do {
				echo '<div style="color:#a40505; text-align: center;"><b>info :</b></div><div style="color:#a40505; font-size: 0.8em; text-align: center;">'.JText::_( 'MOD_ICCALENDAR_COM_ICAGENDA_MENULINK_UNPUBLISHED_MESSAGE' ).'</div>';
			} while ($i > 0);
  		}

		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('id AS nbevt')->from('`#__icagenda_events` AS e')->where('e.state > 0');
		$db->setQuery($query);
		$nbevt=$db->loadResult();
		$nbevt=count($nbevt);
		if ($nbevt == NULL) {
//			do {
				echo '<div style="font-size: 0.8em; text-align: center;">'.JText::_( 'MOD_ICCALENDAR_NO_EVENT' ).'</div>';
//			} while ($i = 0);
  		}

		return $days;

	}


	// test
	function clickDate ($eventdate, $d)
	{
		$eventdate = $d;
		return $eventdate;
	}


	/***/

	// Function to get Format Date (using option format, and translation)
	protected function formatDate ($d){
		$mkt_date=$this->mkt($d);

		// get Format
		$for = '0';
		if(isset($this->format)) $for=$this->format;

		// default
		if (($for == NULL) OR ($for == '0')) {$for = 'd * m * Y';}

		//update default value, from 2.0.x to 2.1
		if ($for == '%d.%m.%Y') {$for = 'd m Y'; $separator = '.';}
		if ($for == '%d.%m.%y') {$for = 'd m y'; $separator = '.';}
		if ($for == '%Y.%m.%d') {$for = 'Y m d'; $separator = '.';}
		if ($for == '%Y.%b.%d') {$for = 'Y M d'; $separator = '.';}

		if ($for == '%d-%m-%Y') {$for = 'd m Y'; $separator = '-';}
		if ($for == '%d-%m-%y') {$for = 'd m y'; $separator = '-';}
		if ($for == '%Y-%m-%d') {$for = 'Y m d'; $separator = '-';}
		if ($for == '%Y-%b-%d') {$for = 'Y M d'; $separator = '-';}

		if ($for == '%d/%m/%Y') {$for = 'd m Y'; $separator = '/';}
		if ($for == '%d/%m/%y') {$for = 'd m y'; $separator = '/';}
		if ($for == '%Y/%m/%d') {$for = 'Y m d'; $separator = '/';}
		if ($for == '%Y/%b/%d') {$for = 'Y M d'; $separator = '/';}

		if ($for == '%d %B %Y') {$for = 'd F Y';}
		if ($for == '%d %b %Y') {$for = 'd M Y';}

		if ($for == '%A, %d %B %Y') {$for = 'l, _ d _ Fnosep _ Y';}
		if ($for == '%a %d %b %Y') {$for = 'D _ d _ Mnosep _ Y';}
		if ($for == '%A, %B %d, %Y') {$for = 'l, _ Fnosep _ d, _ Y';}
		if ($for == '%a, %b %d, %Y') {$for = 'D, _ Mnosep _ d, _ Y';}


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
		$dateFormat=date('Y-m-d H:i', $mkt_date);
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



	// Génération des jours du mois
	function getDays ($d, $f)
	{

	// Set Locale
//	$codeset = "UTF8";  // warning ! not UTF-8 with dash '-'
//	$lang = JFactory::getLanguage();
//	$tag = $lang->getTag();
//	$tag=str_replace('-', '_', $tag);
//	$locale = $lang->getlocale() ;
//	setlocale( LC_ALL, $tag.'.'.$codeset, $locale, $locale[0] ) ;

		//update default value, from 1.2.2 to 1.2.3
		if ($f == 'd-m-Y') {
			$f = '%d-%m-%Y';
		}


		// détermine le mois et l'année

//		$d=str_replace(' ', '-', $d);
//		$d=str_replace(':', '-', $d);
		$ex_data=explode('-', $d);
		$month=$ex_data[1];
		$year=$ex_data[0];
		$jour=$ex_data[2];

		// Génération du Calendrier
		$days = date("d", mktime(0, 0, 0, $month+1, 0, $year));
		$list = array();


		$today=time();
//		$todayt = date($today, mktime(0, 0, 0, $month+1, 0, $year));
		// New 3.2.5 local detection of date and time
		$visitorDate = JHtml::_('date', $today, 'Y-n-d H:i:s');

		$v_date=str_replace(' ', '-', $visitorDate);
		$v_date=str_replace(':', '-', $v_date);
		$v_date=explode('-', $v_date);

		$v_year=$v_date[0];
		$v_month=$v_date[1];
		$v_day=$v_date[2];


//		$day= date('d');
//		$m= date('n');
//		$y= date('Y');
//		$H= date('H', $today);
//		$i= date('i');
//		$e= date('Z', $today);

//echo '<b>'.$visitorDate.'</b><br />';

		for($a=1; $a<=$days; $a++)
		{
         if (($a == $v_day) && ($month == $v_month) && ($year == $v_year)) {
         	$classDay = 'style_Today';
         } else {
         	$classDay = 'style_Day';
         }

			$datejour=date('Y-m-d H:i', mktime(0, 0, 0, $month, $a, $year));

			$list[$a]['date'] = date('Y-m-d H:i', mktime(0, 0, 0, $month, $a, $year));
			$list[$a]['dateFormat'] = strftime($f, mktime(0, 0, 0, $month, $a, $year));
//			$list[$a]['dateTitle'] =  JText::_(strftime($f, mktime(0, 0, 0, $month, $a, $year)));
//			$list[$a]['dateFormat'] = $this->formatDate($d);
			$list[$a]['dateTitle'] =  $this->formatDate($datejour);
			$list[$a]['week'] = date('N', mktime(0, 0, 0, $month, $a, $year));
			// Removed 'day' in 2.1.2 theme pack, NAME_day.php
			$list[$a]['day'] = "<div class='".$classDay."'>".$a."</div>";
			// Added in 2.1.2 (change in NAME_day.php)
			$list[$a]['ifToday'] = $classDay;
			$list[$a]['Days'] = $a;
			//
			$list[$a]['month'] = $month;
			$list[$a]['year'] = $year;
			$list[$a]['events']=array();
//			$list[$a]['plus'] = "$stamp->events[1]['cat_color']";

		}
		return $list;
	}
	/***/

	/**
	 * liste des dates pour un évènement
	 */
	private function getDatelist($dates, $next)
	{

		$dates=unserialize($dates);
		$da=array();
		foreach($dates as $d){
			$d=$this->mkt($d);
			if($d>=$next){
				array_push($da, date('Y-m-d H:i', $d));
			}
		}
		return $da;
	}

	private function getPeriodlist($period, $next)
	{

		if ($period) {
			$period=unserialize($period);
			$da=array();
			foreach($period as $d){
				$d=$this->mkttime($d);
				if($d>=$next){
					array_push($da, date('Y-m-d H:i', $d));
				}
			}
		} else {
			$da = NULL;
		}
		return $da;
	}

	private function getmlist($dates, $next)
	{

		$dates=unserialize($dates);
		$da=array();
		foreach($dates as $d){
			$d=$this->mkttime($d);
			if($d>=$next){
				array_push($da, date('Y-m-d H:i', $d));
			}
		}
		return $da;
	}

	private function getplist($period, $next)
	{

		if ($period) {
			$period=unserialize($period);
			$da=array();
			foreach($period as $d){
				$d=$this->mkttime($d);
				if($d>=$next){
					array_push($da, date('Y-m-d H:i', $d));
				}
			}
		} else {
			$da = NULL;
		}
		return $da;
	}

	// Format Date
	private function mkt($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		$ris=mktime('0', '0', '0', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		return strftime($ris);
	}

	private function mkttime($data)
	{
		$data=str_replace(' ', '-', $data);
		$data=str_replace(':', '-', $data);
		$ex_data=explode('-', $data);
		if (isset($ex_data['3'])) {
			$ris=mktime($ex_data['3'], $ex_data['4'], '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		} else {
			$ris=mktime('00', '00', '00', $ex_data['1'], $ex_data['2'], $ex_data['0']);
		}
		return strftime($ris);
	}

	//
	private function addDay ($mkt)
	{
		return $mkt+82800;
	}


	/***/

	/** Systeme de navigation **/
	function getNav($date_start, $modid)
	{
		// Return Current URL
		$view='';
		$layout='';
		$Itemid='';
		$id='';
		$option = 'index.php?option='.JRequest::getVar('option');
		if(JRequest::getVar('view'))$view = '&view='.JRequest::getVar('view');
		if(JRequest::getVar('layout'))$layout = '&layout='.JRequest::getVar('layout');
		if(JRequest::getInt('Itemid'))$Itemid = '&Itemid='.JRequest::getInt('Itemid');
		if(JRequest::getInt('id'))$id = '&id='.JRequest::getVar('id');
				$Aurl = JURI::base().$option.$view.$layout.$Itemid.$id;

		$ex_date=explode('-', $date_start);
		$mkt_date=$this->mkt($date_start);
		$year=$ex_date[0];
		$month=$ex_date[1];
		$day=1;

		if($month!=1){$backMonth=$month-1; $backYear=$year;}
		if($month==1){$backMonth=12; $backYear=$year-1;}
		if($month!=12){$nextMonth=$month+1; $nextYear=$year;}
		if($month==12){$nextMonth=1; $nextYear=$year+1;}
		$backYYear = $year-1;
		$nextYYear = $year+1;


		$backY='<div class="backicY icagendabtn_'.$modid.'" href="'.JRoute::_($Aurl.'&date='.$backYYear.'-'.$month.'-'.$day).'"><span aria-hidden="true" class="iCicon-backicY"></span></div>';
		$back='<div class="backic icagendabtn_'.$modid.'" href="'.JRoute::_($Aurl.'&date='.$backYear.'-'.$backMonth.'-'.$day).'"><span aria-hidden="true" class="iCicon-backic"></span></div>';

		$next='<div class="nextic icagendabtn_'.$modid.'" href="'.JRoute::_($Aurl.'&date='.$nextYear.'-'.$nextMonth.'-'.$day).'"><span aria-hidden="true" class="iCicon-nextic"></span></div>';
		$nextY='<div class="nexticY icagendabtn_'.$modid.'" href="'.JRoute::_($Aurl.'&date='.$nextYYear.'-'.$month.'-'.$day).'"><span aria-hidden="true" class="iCicon-nexticY"></span></div>';


	/** translate the month in the calendar module -- Leland Vandervort **/
		$dateFormat=date('d-M-Y', $mkt_date);

		// split out the month and year to obtain translation key for JText using joomla core translation
		$t_day = strftime("%d", strtotime("$dateFormat"));
		$t_month = date("F", strtotime("$dateFormat"));
		$t_year = strftime("%Y", strtotime("$dateFormat"));

		// now resplice the title back together and call JText::
		$title = JText::_(''.strtoupper($t_month).'') . ' ' . $t_year;
		$mois = JText::_($t_month);
		$annee = $t_year;

	/***/

		$html='<div class="icnav">'.$backY.$back.$nextY.$next.'<div class="titleic">'.$title.'</div></div>';
		$html.='<div class="clr"></div>';
//		$html.='<div class="nav">'.$backY.$nextY.'<div class="title" style="text-transform: uppercase;">'.$annee.'</div></div>';
//		$html.='<div class="nav">'.$back.$next.'<div class="title" style="text-transform: uppercase;">'.$mois.'</div></div>';

		return $html;
	}
	/***/



}

function activeColor($color){
	#convert hexadecimal to RGB
	if(!is_array($color) && preg_match("/^[#]([0-9a-fA-F]{6})$/",$color)){
		$hex_R = substr($color,1,2);
		$hex_G = substr($color,3,2);
		$hex_B = substr($color,5,2);
		$RGB = hexdec($hex_R).",".hexdec($hex_G).",".hexdec($hex_B);
		return $RGB;
	}
}



class cal
{

	public $data;
	public $template;
	public $t_calendar;
	public $t_day;
	public $nav;
	public $fontcolor;

	function __construct ($data, $t_calendar, $t_day, $nav, $mon, $tue, $wed, $thu, $fri, $sat, $sun, $firstday, $bgcolor, $bgimage, $bgimagerepeat, $na,$nb,$nc,$nd,$ne,$nf,$ng,$moduleclass_sfx,$modid,$template)
	{
		$this->data=$data;
		$this->t_calendar=$t_calendar;
		$this->t_day=$t_day;
		$this->nav=$nav;
		$this->mon=$mon;
		$this->tue=$tue;
		$this->wed=$wed;
		$this->thu=$thu;
		$this->fri=$fri;
		$this->sat=$sat;
		$this->sun=$sun;
		$this->na=$na;
		$this->nb=$nb;
		$this->nc=$nc;
		$this->nd=$nd;
		$this->ne=$ne;
		$this->nf=$nf;
		$this->ng=$ng;
		$this->firstday=$firstday;
		$this->bgcolor=$bgcolor;
		$this->bgimage=$bgimage;
		$this->bgimagerepeat=$bgimagerepeat;
		$this->moduleclass_sfx=$moduleclass_sfx;
		$this->modid=$modid;
		$this->template=$template;


	}

	function days ()
	{

	echo '<div class="'.$this->template.' iccalendar '.$this->moduleclass_sfx.'" style="background-color: '.$this->bgcolor.'; background-image: url(\''.$this->bgimage.'\'); background-repeat: '.$this->bgimagerepeat.'" id="'.$this->modid.'">';


		if ($this->firstday=='0') {
			echo '<div id="mod_iccalendar_'.$this->modid.'">'.$this->nav.'
			<table id="icagenda_calendar" width="100%" cellspacing="0">
				<thead>
					<th width="15%" style="background:'.$this->sun.';">'.JText::_('SUN').'</th>
					<th width="14%" style="background:'.$this->mon.';">'.JText::_('MON').'</th>
					<th width="14%" style="background:'.$this->tue.';">'.JText::_('TUE').'</th>
					<th width="14%" style="background:'.$this->wed.';">'.JText::_('WED').'</th>
					<th width="14%" style="background:'.$this->thu.';">'.JText::_('THU').'</th>
					<th width="14%" style="background:'.$this->fri.';">'.JText::_('FRI').'</th>
					<th width="15%" style="background:'.$this->sat.';">'.JText::_('SAT').'</th>
				</thead>
		';
		}
		if ($this->firstday=='1') {
			echo '<div id="mod_iccalendar_'.$this->modid.'">'.$this->nav.'
			<table id="icagenda_calendar" width="100%" cellspacing="0">
				<thead>
					<th width="15%" style="background:'.$this->mon.';">'.JText::_('MON').'</th>
					<th width="14%" style="background:'.$this->tue.';">'.JText::_('TUE').'</th>
					<th width="14%" style="background:'.$this->wed.';">'.JText::_('WED').'</th>
					<th width="14%" style="background:'.$this->thu.';">'.JText::_('THU').'</th>
					<th width="14%" style="background:'.$this->fri.';">'.JText::_('FRI').'</th>
					<th width="14%" style="background:'.$this->sat.';">'.JText::_('SAT').'</th>
					<th width="15%" style="background:'.$this->sun.';">'.JText::_('SUN').'</th>
				</thead>
		';
		}

		switch ($this->data[1]['week']){
			case $this->na:
				break;
			default:
				echo '<tr><td colspan="'.($this->data[1]['week']-$this->firstday).'"></td>';
				break;
		}

		foreach ($this->data as $d){
			$stamp= new day($d);

		if ($this->firstday=='0') {
			switch($stamp->week){
				case $this->na:
					echo '<tr><td style="background:'.$this->sun.';">';
					break;
				case $this->nb:
					echo '<td style="background:'.$this->mon.';">';
					break;
				case $this->nc:
					echo '<td style="background:'.$this->tue.';">';
					break;
				case $this->nd:
					echo '<td style="background:'.$this->wed.';">';
					break;
				case $this->ne:
					echo '<td style="background:'.$this->thu.';">';
					break;
				case $this->nf:
					echo '<td style="background:'.$this->fri.';">';
					break;
				case $this->ng:
					echo '<td style="background:'.$this->sat.';">';
					break;
				default:
					echo '<td>';
					break;
			}
		}

		if ($this->firstday=='1') {
			switch($stamp->week){
				case $this->na:
					echo '<tr><td style="background:'.$this->mon.';">';
					break;
				case $this->nb:
					echo '<td style="background:'.$this->tue.';">';
					break;
				case $this->nc:
					echo '<td style="background:'.$this->wed.';">';
					break;
				case $this->nd:
					echo '<td style="background:'.$this->thu.';">';
					break;
				case $this->ne:
					echo '<td style="background:'.$this->fri.';">';
					break;
				case $this->nf:
					echo '<td style="background:'.$this->sat.';">';
					break;
				case $this->ng:
					echo '<td style="background:'.$this->sun.';">';
					break;
				default:
					echo '<td>';
					break;
			}
		}
//			rsort($stamp->events);
//			$RGB = '$RGB';
			$bgcolor ='';
			if (isset($stamp->events[0]['cat_color'])) { $RGB = explode(",",activeColor($stamp->events[0]['cat_color']));
			$c = array($RGB[0], $RGB[1], $RGB[2]);
			$bgcolor = array_sum($c);
			 }
			if ($bgcolor > '600') {
				$bgcolor='bright';
			} else {
				$bgcolor='';
			}
			$order='first';
			if (isset($stamp->events[0]['cat_color'])) { $bg_catcolor = $stamp->events[0]['cat_color']; $bg_day=$bg_catcolor; }
			if (!isset($stamp->events[0]['cat_color'])) { $bg_day=''; }

			if (isset($stamp->events[1]['cat_color'])) { $multi_events = 'icmulti'; } else { $multi_events = ''; }

			require $this->t_day;

			switch('week'){
				case $this->ng:
					echo '</td></tr>';
					break;
				default:
					echo '</td>';
					break;
			}
		}

		switch ($stamp->week){
			case $this->ng:
				break;
			default:
				echo '<td colspan="'.(7-$stamp->week).'"></td></tr>';
				break;
		}

		echo '</table></div>';

	echo '</div>';

	}

}

class day
{
	public $date;
	public $week;
	public $day;
	public $month;
	public $year;
	public $events;
	public $fontcolor;

	function __construct ($day)
	{
		foreach ($day as $k=>$v){
			$this->$k=$v;
		}
	}
}
?>
