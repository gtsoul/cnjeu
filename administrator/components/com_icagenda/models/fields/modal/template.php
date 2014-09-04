<?php
/**
 *	iCagenda
 *----------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright	Copyright (C) 2012-2013 JOOMLIC - All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jooml!C - http://www.joomlic.com
 *
 * @update		2013-05-31
 * @version		3.0
 *----------------------------------------------------------------------------
*/

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class JFormFieldModal_Template extends JFormField
{
	protected $type='modal_template';

	protected function getInput()
	{
//		$url=JPATH_SITE.DS.'components'.DS.'com_icagenda'.DS.'themes'.DS.'packs';
		$url=JPATH_SITE.'/components/com_icagenda/themes/packs';
		$list=$this->getList($url);
		//echo '<pre>'.print_r($list, true).'</pre>';
		$class = JRequest::getVar('class');
		$html= '<select id="'.$this->id.'_id"'.$class.' name="'.$this->name.'">';
		foreach ($list as $l){
			$html.='<option value="'.$l.'"';
			if ($this->value == $l){
				$html.='selected="selected"';
			}
			$html.='>'.$l.'</option>';
		}
		$html.='</select>';

		return $html;
	}

	function getList($dirname){
		$arrayfiles=Array();
		if(file_exists($dirname)){
			$handle = opendir($dirname);
			while (false !== ($file = readdir($handle))) {
				if(!is_file($dirname.$file) && $file!= '.' && $file!='..' && $file!='index.php' && $file!='index.html' && $file!='.DS_Store' && $file!='.thumbs'){
					array_push($arrayfiles,$file);
				}
			}
			$handle = closedir($handle);
		}
		sort($arrayfiles);
		return $arrayfiles;
	}
}
