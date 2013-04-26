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
 * Jeu model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 * @since       1.6
 */
class Cnj_jeuxModelJeu extends JModelAdmin
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
	public function getTable($type = 'Cnj_jeu', $prefix = 'Cnj_jeuxTable', $config = array())
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
		$form = $this->loadForm('com_cnj_jeux.jeu', 'jeu', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_cnj_jeux.edit.jeu.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
                        
                        $data->auteurs = $this->_getAuteurs($data->id_jeu);
                        $data->references = $this->_getReferences($data->id_jeu);
                        $data->distinctions = $this->_getDistinctions($data->id_jeu);
                        $data->documents = $this->_getDocuments($data->id_jeu);
          		$data->motcles = $this->_getMotcles($data->id_jeu);
  			$data->mecanismes = $this->_getMecanismes($data->id_jeu);
		}

		return $data;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   JTable  $table  A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 *
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '. (int) $table->catid;
		$condition[] = 'state >= 0';
		return $condition;
	}

	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	private function _getReferences($id)
	{
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'r.id_reference, r.nom, jr.qualite'));
            $query->from('cnj_reference AS r');
            $query->join('INNER ', 'cnj_jeu_to_reference AS jr ON r.id_reference = jr.id_reference AND jr.id_jeu = ' . (int)$id);
            $query->order('r.nom');
            
            $data = $this->_getList($query);
 
            return $data;
	}

	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	private function _getAuteurs($id)
	{
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'a.id_auteur, a.nom, ja.qualite, ja.qualite_old'));
            $query->from('cnj_auteur AS a');
            $query->join('INNER ', 'cnj_jeu_to_auteur AS ja ON a.id_auteur = ja.id_auteur AND ja.id_jeu = ' . (int)$id);
            $query->order('a.nom');
            
            $data = $this->_getList($query);
 
            return $data;
	}


	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	private function _getMotcles($id)
	{
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'm.id_motcle, m.motcle'));
            $query->from('cnj_motcle AS m');
            $query->join('INNER ', 'cnj_jeu_to_motcle AS jm ON m.id_motcle = jm.id_motcle AND jm.id_jeu = ' . (int)$id);
            $query->order('m.motcle');
            
            $data = $this->_getList($query);

            return $data;
	}


	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	private function _getMecanismes($id)
	{
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'm.id_mecanisme, m.mecanisme'));
            $query->from('cnj_mecanisme AS m');
            $query->join('INNER ', 'cnj_jeu_to_mecanisme AS jm ON m.id_mecanisme = jm.id_mecanisme AND jm.id_jeu = ' . (int)$id);
            $query->order('m.mecanisme');
            
            $data = $this->_getList($query);

            return $data;
	}





	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	private function _getDistinctions($id)
	{
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'di.id_distinction, di.nom, jdi.date_distinction'));
            $query->from('cnj_distinction AS di');
            $query->join('INNER ', 'cnj_jeu_to_distinction AS jdi ON di.id_distinction = jdi.id_distinction AND jdi.id_jeu = ' . (int)$id);
            $query->order('di.nom');
            
            $data = $this->_getList($query);
 
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
            $query->select($this->getState('list.select', 'd.id, d.old_id_document, d.nom, jd.ordre'));
            $query->from('cnj_document AS d');
            $query->join('INNER ', 'cnj_jeu_to_document AS jd ON d.id = jd.id_document AND jd.id_jeu = ' . (int)$id);
            $query->order('jd.ordre asc, d.nom asc');

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
