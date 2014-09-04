<?php
/** 
 *	iCagenda
 *----------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright	Copyright (C) 2012 JOOMLIC - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jooml!C - http://www.joomlic.com
 * 
 * @since		1.0
 *----------------------------------------------------------------------------
*/

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controllerform');

/**
 * Event controller class.
 */
class iCagendaControllerMail extends JControllerForm
{

    function __construct() {
        $this->view_list = 'icagenda';
        parent::__construct();
    }

}