<?php
/**
 *	Plugin System - iCagenda :: Autologin
 *----------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2013 Cyril Rezé, Jooml!C - All rights reserved

 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @update      3.2.0 2013-09-18
 * @version		1.0
 *----------------------------------------------------------------------------
*/


// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

class PlgSystemic_autologin extends JPlugin
{
	protected $_icu;
	protected $_icp;

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onAfterInitialise()
	{
		$this->_icu = JRequest::getVar('icu', null);
		$this->_icp = JRequest::getVar('icp', null);

		if (!empty($this->_icu) && !empty($this->_icp)) {

			$result = $this->icLogin();

			$urllink = JURI::getInstance()->toString();
			$cleanurl = preg_replace('/&icu=[^&]*/', '', $urllink);
			$cleanurl = preg_replace('/&icp=[^&]*/', '', $cleanurl);

			// Cleaned URL
			if (!JError::isError($result)) {
				$app = JFactory::getApplication();
				$app->redirect($cleanurl);
			}
		}

		return true;

	}


	/**
	 * LOGIN with ENCRYPT PASSWORD
	 */

	function icLogin() {

		// Get the application object.
		$app = JFactory::getApplication();

		$db =& JFactory::getDBO();
		$query = 'SELECT `id`, `username`, `password`'
				. ' FROM `#__users`'
				. ' WHERE username=' . $db->Quote( $this->_icu )
				. '   AND password=' . $db->Quote( $this->_icp )
		;
		$db->setQuery( $query );
		$result = $db->loadObject();

		if($result) {
			JPluginHelper::importPlugin('user');

			$options = array();
			$options['action'] = 'core.login.site';

			$response['username'] = $result->username;
			$result = $app->triggerEvent('onUserLogin', array((array)$response, $options));
		}

	}

}
