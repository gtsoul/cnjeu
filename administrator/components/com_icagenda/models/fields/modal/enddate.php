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
 * @version     3.1.2 2013-08-29
 * @since       2.0.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');


JText::script('COM_ICAGENDA_TP_CURRENT');
JText::script('COM_ICAGENDA_TP_CLOSE');
JText::script('COM_ICAGENDA_TP_TITLE');
JText::script('COM_ICAGENDA_TP_TIME');
JText::script('COM_ICAGENDA_TP_HOUR');
JText::script('COM_ICAGENDA_TP_MINUTE');


class JFormFieldModal_enddate extends JFormField
{
	protected $type='modal_enddate';

	protected function getInput()
	{
		$class = JRequest::getVar('class');
		$html ='<input type="text"  id="enddate" class="'.$class.'" name="'.$this->name.'" value="'.$this->value.'"/>';

		return $html;
	}
}
