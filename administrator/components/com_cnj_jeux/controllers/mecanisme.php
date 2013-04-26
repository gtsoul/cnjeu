<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Distinction controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 * @since       1.6
 */
class Cnj_jeuxControllermecanisme extends JControllerForm
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_CNJ_JEUX_mecanisme';
        
        protected $view_list = 'mecanismes';
        
}
