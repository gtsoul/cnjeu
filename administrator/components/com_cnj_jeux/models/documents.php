<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of documents records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxModelDocuments extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'd.nom', 
				'd.type', 
				'd.path_hd', 
				'd.old_id_document', 
				'd.id', 
			);
		}
                
		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
        protected function getListQuery()
	{
            $db = $this->getDbo();
            $query = $db->getQuery(true);

            $query->select($this->getState('list.select', 'd.*'));
            $query->from('cnj_document AS d');  
            
            // Filter by search in title
            $titre = $this->getState('filter.titre');
            if (!empty($titre)) {
                    if (stripos($titre, 'id:') === 0) {
                            $query->where('d.id = '.(int) substr($titre, 3));
                    } elseif (stripos($titre, 'oldid:') === 0) {
                            $query->where('d.old_id_document = '.(int) substr($titre, 6));
                    } else {
                            $titre = $db->Quote('%'.$db->escape($titre, true).'%');
                            $query->where('(d.nom LIKE '.$titre.')');
                    }
            }
            
            // Add the list ordering clause.
            $orderCol	= $this->state->get('list.ordering', 'd.nom');
            $orderDirn	= $this->state->get('list.direction', 'ASC');
            /*if($orderCol == 'd.ordre') {
                $query->order($db->escape('d.old_id_document '.$orderDirn . ', d.ordre '.$orderDirn));
            }
            else */$query->order($db->escape($orderCol.' '.$orderDirn));
            
            return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		return parent::getStoreId($id);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Cnj_document', $prefix = 'Cnj_jeuxTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
        protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$titre = $this->getUserStateFromRequest($this->context.'.filter.titre', 'filter_titre');
		$this->setState('filter.titre', $titre);
                
		// Load the parameters.
		$params = JComponentHelper::getParams('com_cnj_jeux');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('d.nom', 'asc');
	}
        
        public function delete($ids)
	{
            $db = $this->getDbo();
            
            foreach ($ids as $id) { 

                $db = $this->getDbo();
                $db->setQuery(
                        'DELETE FROM cnj_document' .
                        ' WHERE id = ' . (int)$id
                );
                $db->query();
                
                
                $id_document = $this->getIdDocument((int)$id);
   
                if($id_document) 
                {
                    $db->setQuery(
                            'DELETE FROM cnj_auteur_to_document' .
                            ' WHERE id_document = ' . (int)$id_document
                    );
                    $db->query();

                    $db->setQuery(
                            'DELETE FROM cnj_catalogue_to_document' .
                            ' WHERE id_document = ' . (int)$id_document
                    );
                    $db->query();

                    $db->setQuery(
                            'DELETE FROM cnj_jeu_to_document' .
                            ' WHERE id_document = ' . (int)$id_document
                    );
                    $db->query();

                    $db->setQuery(
                            'DELETE FROM cnj_ouvrage_to_document' .
                            ' WHERE id_document = ' . (int)$id_document
                    );
                    $db->query();

                    $db->setQuery(
                            'DELETE FROM cnj_reference_to_document' .
                            ' WHERE id_document = ' . (int)$id_document
                    );
                    $db->query();
                }
            }

            return true;
        }
        
        public function getIdDocument($id) 
        {
            $db = $this->getDbo();
            $db->setQuery(
                    'SELECT old_id_document FROM cnj_document' .
                    ' WHERE id = ' . (int)$id
            );
            return $db->loadResult();
        }

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 *
	 * @return  mixed
	 *
	 * @since   11.1
	 */
	/*public function saveorder($pks = null, $order = null)
	{
		// Initialise variables.
		$table = $this->getTable();
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
		}

		// update ordering values
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			// Access checks.
			/*if (!$this->canEditState($table))
			{
				// Prune items that you can't change.
				unset($pks[$i]);
				JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
			else*//*if ($table->ordre != $order[$i])
			{
				$table->ordre = $order[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}

				// Remember to reorder within position and client_id
				$condition = $this->getReorderConditions($table);
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}*/

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table  A JTable object.
	 *
	 * @return  array  An array of conditions to add to ordering queries.
	 *
	 * @since   11.1
	 */
	/*protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'old_id_document = '.(int) $table->old_id_document;

		return $condition;
	}*/

	/**
	 * Method to adjust the ordering of a row.
	 *
	 * Returns NULL if the user did not have edit
	 * privileges for any of the selected primary keys.
	 *
	 * @param   integer  $pks    The ID of the primary key to move.
	 * @param   integer  $delta  Increment, usually +1 or -1
	 *
	 * @return  mixed  False on failure or error, true on success, null if the $pk is empty (no items selected).
	 *
	 * @since   11.1
	 */
	/*public function reorder($pks, $delta = 0)
	{
		// Initialise variables.
		$table = $this->getTable();
		$pks = (array) $pks;
		$result = true;

		$allowed = true;

		foreach ($pks as $i => $pk)
		{
			$table->reset();

			if ($table->load($pk)/* && $this->checkout($pk)*//*)
			{
				// Access checks.
				/*if (!$this->canEditState($table))
				{
					// Prune items that you can't change.
					unset($pks[$i]);
					$this->checkin($pk);
					JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
					$allowed = false;
					continue;
				}*/

				/*$where = array();
				$where = $this->getReorderConditions($table);

				if (!$table->move($delta, $where))
				{
					$this->setError($table->getError());
					unset($pks[$i]);
					$result = false;
				}

				//$this->checkin($pk);
			}
			else
			{
				$this->setError($table->getError());
				unset($pks[$i]);
				$result = false;
			}
		}

		if ($allowed === false && empty($pks))
		{
			$result = null;
		}

		// Clear the component's cache
		if ($result == true)
		{
			$this->cleanCache();
		}

		return $result;
	}*/
}
