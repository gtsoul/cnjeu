<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Banners list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class Cnj_jeuxControllerDistinctions extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_CNJ_JEUX_DISTINCTIONS';

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Distinctions', $prefix = 'Cnj_jeuxModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
        
        
	public function delete()
	{
            // Check for request forgeries
            JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

            // Get items to remove from the request.
            $cid = JRequest::getVar('cid', array(), '', 'array');

            if (!is_array($cid) || count($cid) < 1)
            {
                    JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
            }
            else
            {
                    // Get the model.
                    $model = $this->getModel();

                    // Make sure the item ids are integers
                    jimport('joomla.utilities.arrayhelper');
                    JArrayHelper::toInteger($cid);

                    // Remove the items.
                    if ($model->delete($cid))
                    {
                            $this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
                    }
                    else
                    {
                            $this->setMessage($model->getError());
                    }
            }

            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}
}
