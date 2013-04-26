<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of motcles records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxModelMotcles extends JModelList
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
				'd.motcle', 
				'd.id_motcle', 
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
            $query->from('cnj_motcle AS d');  
            
            // Filter by search in title
            $titre = $this->getState('filter.titre');
            if (!empty($titre)) {
                    if (stripos($titre, 'id:') === 0) {
                            $query->where('d.id_motcle = '.(int) substr($titre, 3));
                    } else {
                            $titre = $db->Quote('%'.$db->escape($titre, true).'%');
                            $query->where('(d.motcle LIKE '.$titre.')');
                    }
            }
            
            // Add the list ordering clause.
            $orderCol	= $this->state->get('list.ordering', 'd.motcle');
            $orderDirn	= $this->state->get('list.direction', 'ASC');
            $query->order($db->escape($orderCol.' '.$orderDirn));
                
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
	public function getTable($type = 'Cnj_motcle', $prefix = 'Cnj_jeuxTable', $config = array())
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
		parent::populateState('d.motcle', 'asc');
	}
        
        public function delete($ids)
	{
            $db = $this->getDbo();
            
            foreach ($ids as $id) {
                $db = $this->getDbo();
                $db->setQuery(
                        'DELETE FROM cnj_motcle' .
                        ' WHERE id_motcle = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_jeu_to_motcle' .
                        ' WHERE id_motcle = ' . (int)$id
                );
                $db->query();
            }

            return true;
        }
}
