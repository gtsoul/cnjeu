<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Reference model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 * @since       1.6
 */
class Cnj_jeuxModelReference extends JModelAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_CNJ_JEUX_JEU';

	/**
	 * Returns a JTable object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate. [optional]
	 * @param   string  $prefix  A prefix for the table class name. [optional]
	 * @param   array   $config  Configuration array for model. [optional]
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Cnj_reference', $prefix = 'Cnj_jeuxTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form. [optional]
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_cnj_jeux.reference', 'reference', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			//$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			//$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_cnj_jeux.edit.reference.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
                        
                        $data->documents = $this->_getDocuments($data->id_reference);
		}

		return $data;
	}

	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	private function _getDocuments($id)
	{
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'd.id, d.nom, rd.ordre'));
            $query->from('cnj_document AS d');
            $query->join('INNER ', 'cnj_reference_to_document AS rd ON d.id = rd.id_document AND rd.id_reference = ' . (int)$id);
            $query->order('rd.ordre asc, d.nom asc');
            
            $data = $this->_getList($query);
 
            return $data;
	}

protected function prepareTable(&$table)
{
    $jform = JRequest::getVar('jform'); // load all submitted data
    if (!isset($jform['tri_1'])) { // see if the checkbox has been submitted
        $table->tri_1 = 0; // if it has not been submitted, mark the field unchecked
    }
    if (!isset($jform['tri_2'])) { // see if the checkbox has been submitted
        $table->tri_2 = 0; // if it has not been submitted, mark the field unchecked
    }
    if (!isset($jform['tri_3'])) { // see if the checkbox has been submitted
        $table->tri_3 = 0; // if it has not been submitted, mark the field unchecked
    }
    if (!isset($jform['tri_4'])) { // see if the checkbox has been submitted
        $table->tri_4 = 0; // if it has not been submitted, mark the field unchecked
    }




}



}
