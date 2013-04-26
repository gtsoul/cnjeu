<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of references records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxModelReferences extends JModelList
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
				'r.nom', 
				'r.alias', 
				'r.pays', 
				'r.adresse', 
				'r.id_reference', 
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

            $query->select($this->getState('list.select', 'r.*'));
            $query->from('cnj_reference AS r');  
            
            // Filter by search in title
            $titre = $this->getState('filter.titre');
            if (!empty($titre)) {
                    if (stripos($titre, 'id:') === 0) {
                            $query->where('r.id_reference = '.(int) substr($titre, 3));
                    } else {
                            $titre = $db->Quote('%'.$db->escape($titre, true).'%');
                            $query->where('(r.nom LIKE '.$titre.' OR r.alias LIKE '.$titre.')');
                    }
            }

            // Filter by tri 
            $tri_1 =  $this->getState('filter.tri_1') ;
	    if ($tri_1!="")
	    	$query->where('r.tri_1 ='.$tri_1);

      	    $tri_2 = $this->getState('filter.tri_2');
	    if ($tri_2!="")
    	    	$query->where('r.tri_2 ='.$tri_2);

       	    $tri_3 = $this->getState('filter.tri_3');
	    if ($tri_3!="")
    		$query->where('r.tri_3 ='.$tri_3);

            $tri_4 = $this->getState('filter.tri_4');
        if ($tri_4!="")
    		$query->where('r.tri_4 ='.$tri_4);

            
            // Add the list ordering clause.
            $orderCol	= $this->state->get('list.ordering', 'r.nom');
            $orderDirn	= $this->state->get('list.direction', 'ASC');
            $query->order($db->escape($orderCol.' '.$orderDirn));
                
            return $query;
	}

	/**
	 * Get the articles in the category
	 *
	 * @return	mixed	An array of articles or false if an error occurs.
	 * @since	1.5
	 */
	function getItems()
	{
            $items	= parent::getItems();
            
            // recuperation des auteurs et references pour chaque item
            foreach ($items as &$item)
            {
                $item->documents = $this->_getDocuments($item->id_reference);
            }

            return $items;
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
	public function getTable($type = 'Cnj_reference', $prefix = 'Cnj_jeuxTable', $config = array())
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

		$tri_1 = $this->getUserStateFromRequest($this->context.'.filter.tri_1', 'filter_tri_1');
		$this->setState('filter.tri_1', $tri_1);

		$tri_2 = $this->getUserStateFromRequest($this->context.'.filter.tri_2', 'filter_tri_2');
		$this->setState('filter.tri_2', $tri_2);

		$tri_3 = $this->getUserStateFromRequest($this->context.'.filter.tri_3', 'filter_tri_3');
		$this->setState('filter.tri_3', $tri_3);

		$tri_4 = $this->getUserStateFromRequest($this->context.'.filter.tri_4', 'filter_tri_4');
		$this->setState('filter.tri_4', $tri_4);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_cnj_jeux');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('r.nom', 'asc');
	}
        
        public function delete($ids)
	{
            $db = $this->getDbo();
            
            foreach ($ids as $id) {
                $db = $this->getDbo();
                $db->setQuery(
                        'DELETE FROM cnj_reference' .
                        ' WHERE id_reference = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_reference_to_document' .
                        ' WHERE id_reference = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_reference_associee' .
                        ' WHERE id_reference = ' . (int)$id . ' OR id_reference_associee = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_jeu_to_reference' .
                        ' WHERE id_reference = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_ouvrage_to_reference' .
                        ' WHERE id_reference = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_catalogue_to_reference' .
                        ' WHERE id_reference = ' . (int)$id
                );
                $db->query();
            }

            return true;
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
            $query->select($this->getState('list.select', 'd.id, d.nom'));
            $query->from('cnj_document AS d');
            $query->join('INNER ', 'cnj_reference_to_document AS rd ON d.id = rd.id_document AND rd.id_reference = ' . (int)$id);
            $query->order('rd.ordre asc, d.nom asc');

            $data = $this->_getList($query);
 
            return $data;
	}
}
