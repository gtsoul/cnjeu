<?php
/**
 *	iCagenda Globalization - International Date Formats
 *----------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright	Copyright (C) 2013 JOOMLIC - All rights reserved.

 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      Jooml!C - http://www.joomlic.com
 *
 * @update		2013-05-12
 * @version		2.1.11
 *----------------------------------------------------------------------------
*/

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

		// International Date Format (ISO)
		$iso = 'Y - m - d';

		// DMY Little-endian (day, month, year), e.g. 22.04.96 or 22/04/96
		$dmy_1 = 'd * m * Y';
		$dmy_2 = 'd * m * y';
		$dmy_3 = 'd * m';
		$dmy_4 = 'm * y';
		$dmy_5 = 'd * F * Y';
		$dmy_6 = 'd * M * Y';

		// MDY Middle-endian (month, day, year), e.g. 04/22/96
		$mdy_1 = 'm * d * Y';
		$mdy_2 = 'm * d * y';
		$mdy_3 = 'm * d';
		$mdy_4 = 'm * y';
		$mdy_5 = 'F * d * Y';
		$mdy_6 = 'M * d * Y';

		// YMD Big-endian (year, month, day), e.g. 1996-04-22
		$ymd_1 = 'Y * m * d';
		$ymd_2 = 'y * m * d';
		$ymd_3 = 'm * d';
		$ymd_4 = 'y * m';
		$ymd_5 = 'Y * F * d';
		$ymd_6 = 'Y * M * d';

