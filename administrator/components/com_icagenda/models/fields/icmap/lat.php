<?php
/**
 *	iCagenda
 *----------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright	Copyright (C) 2013 JOOMLIC - All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jooml!C - http://www.joomlic.com
 *
 * @update		2013-05-20
 * @version		3.0
 *----------------------------------------------------------------------------
*/

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');


class JFormFieldiCmap_lat extends JFormField
{
	protected $type='icmap_lat';

	protected function getInput()
	{
		$id=JRequest::getVar('id');
		if (isset($id)) {
			$db	= JFactory::getDBO();
			$db->setQuery(
				'SELECT a.coordinate' .
				' FROM #__icagenda_events AS a' .
				' WHERE a.id = '.(int) $id
			);
			$coords= $db->loadResult();
		} else {
			$coords=NULL;
		}

		$def=JRequest::getVar('def');
		if ($def=='')$def=$this->value;

		if (($coords != NULL) AND ($def == '0.0000000000000000')) {
			$ex=explode(', ', $coords);
			$def = $ex[0];
		} elseif ($def != '0.0000000000000000') {
			$def = $def;
		} else {
			$def = NULL;
		}


		$html= '<div class="clr"></div>';
		$html.= '<label class="icmap-label">'.JText::_('COM_ICAGENDA_GOOGLE_MAPS_LATITUDE_LBL').'</label> <input name="'.$this->name.'" id="lat" type="text" size="41" style="color:#777777;" value="'.$def.'"/>';

		return $html;
	}
}

