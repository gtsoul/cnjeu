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
 * @version     3.0 2013-06-30
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();


class iCagendaUpdateLogsColoriser
{
	public static function colorise($file, $onlyLast = false)
	{
		$ret = '';

		$lines = @file($file);
		if(empty($lines)) return $ret;

		array_shift($lines);

		foreach($lines as $line) {
			$line = trim($line);
			if(empty($line)) continue;
			$type = substr($line,0,1);
			switch($type) {
				case '=':
					continue;
					break;

				case ':':
					$ret .= "\t".'<li style="font-size:8pt;">legend'.$line."</li>\n";
					break;

				case '+':
					$ret .= "\t".'<li class="icagenda-updatelogs-added"><span></span>+ '.htmlentities(trim(substr($line,2)))."</li>\n";
					break;

				case '-':
					$ret .= "\t".'<li class="icagenda-updatelogs-removed"><span></span>- '.htmlentities(trim(substr($line,2)))."</li>\n";
					break;

				case '~':
					$ret .= "\t".'<li class="icagenda-updatelogs-changed"><span></span>~ '.htmlentities(trim(substr($line,2)))."</li>\n";
					break;

				case '!':
					$ret .= "\t".'<li class="icagenda-updatelogs-important"><b><span></span>! '.htmlentities(trim(substr($line,2)))."</b></li>\n";
					break;

				case '#':
					$ret .= "\t".'<li class="icagenda-updatelogs-fixed"><span></span># '.htmlentities(trim(substr($line,2)))."</li>\n";
					break;

				default:
					if(!empty($ret)) {
						$ret .= "</ul>";
						if($onlyLast) return $ret;
					}
					if(!$onlyLast) $ret .= "<h3 class=\"icagenda-updatelogs\">$line</h3>\n";
					$ret .= "<ul class=\"icagenda-updatelogs\">\n";
					break;
			}
		}

		return $ret;
	}
}
