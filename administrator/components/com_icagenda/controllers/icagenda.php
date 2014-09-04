<?php
/** 
 *	iCagenda
 *----------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright	Copyright (C) 2012-2013 JOOMLIC - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jooml!C - http://www.joomlic.com
 * 
 * @update		2013-05-05
 * @version		3.0
 *----------------------------------------------------------------------------
*/

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controlleradmin');

/**
 * Categories list controller class.
 */
// J2.5 : class iCagendaControlleriCagenda extends JControllerAdmin
class iCagendaControlleriCagenda extends JControllerLegacyAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'icagenda', $prefix = 'iCagendaModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}