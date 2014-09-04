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


class JFormFieldiCmap_city extends JFormField
{
	protected $type='icmap_city';

	protected function getInput()
	{
		$def=JRequest::getVar('def');
		if ($def=='')$def=$this->value;

		$html= '<div class="clr"></div>';
		$html.= '<label class="icmap-label">'.JText::_('COM_ICAGENDA_FORM_LBL_EVENT_CITY').'</label> <input name="'.$this->name.'" id="locality" type="text" size="41" style="color:#777777;" value="'.$def.'"/>';

		return $html;
	}
}
