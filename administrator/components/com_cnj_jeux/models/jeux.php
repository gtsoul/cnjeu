<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of jeux records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxModelJeux extends JModelList
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
				'j.titre', 
				'j.date_parution_debut', 
				'j.pays_edition', 
				'j.id_jeu', 
			);
		}
                
		parent::__construct($config);
	}

	/**
	 * @since	1.6
	 */
	protected $basename;

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

            $query->select($this->getState('list.select', 'j.*'));
            $query->from('cnj_jeu AS j');  
            
            // Filter by search in title
            $titre = $this->getState('filter.titre');
            if (!empty($titre)) {
                    if (stripos($titre, 'id:') === 0) {
                            $query->where('j.id_jeu = '.(int) substr($titre, 3));
                    } else {
                            $titre = $db->Quote('%'.$db->escape($titre, true).'%');
                            $query->where('(j.titre LIKE '.$titre.')');
                    }
            }
            
            // Filter by date_parution_debut
            $date_parution_debut = $this->getState('filter.date_parution_debut');
            if (is_numeric($date_parution_debut)) {
        		$query->where('j.date_parution_debut <>"" and j.date_parution_debut >= ' . (int)$date_parution_debut);
            }
	   else
           if ($date_parution_debut == '[vide]') {
        		$query->where('j.date_parution_debut =""  ');
            }
	
            
            // Filter by date_parution_fin
            $date_parution_fin = $this->getState('filter.date_parution_fin');
            if (is_numeric($date_parution_fin)) {
		$query->where('j.date_parution_debut <>"" and j.date_parution_debut <= ' . (int)$date_parution_fin);
            }
 	   else
           if ($date_parution_fin == '[vide]') {
        		$query->where('j.date_parution_fin =""  ');
            }
	           
            // Filter by tri 
            $tri_1 =  $this->getState('filter.tri_1') ;
	    if ($tri_1!="")
	    	$query->where('j.tri_1 ='.$tri_1);

      	    $tri_2 = $this->getState('filter.tri_2');
	    if ($tri_2!="")
    	    	$query->where('j.tri_2 ='.$tri_2);

       	    $tri_3 = $this->getState('filter.tri_3');
	    if ($tri_3!="")
    		$query->where('j.tri_3 ='.$tri_3);

            $tri_4 = $this->getState('filter.tri_4');
        if ($tri_4!="")
    		$query->where('j.tri_4 ='.$tri_4);

            $motcle = $this->getState('filter.motcle');
            if (!empty($motcle)) {
             
		if ($motcle != '[vide]')
		{
			$motcle = $db->Quote('%'.$db->escape($motcle, true).'%');
			$query->where('  EXISTS (select jm.id_jeu from cnj_jeu_to_motcle jm, cnj_motcle m where jm.id_jeu = j.id_jeu and m.id_motcle = jm.id_motcle and m.motcle  LIKE ' . $motcle . ') or   EXISTS (select jm.id_jeu from cnj_jeu_to_mecanisme jm, cnj_mecanisme m where jm.id_jeu = j.id_jeu and m.id_mecanisme = jm.id_mecanisme and m.mecanisme  LIKE ' . $motcle . ')');



		}
		else
		{
			$query->where(' NOT EXISTS (select jm.id_jeu from cnj_jeu_to_motcle jm where jm.id_jeu = j.id_jeu) ');
		}
            }

            // Filter by type
            $type = $this->getState('filter.type');
            if (!empty($type)) {
                if($type == 'jeu_de_role')
                    $query->where('j.type = "jeu_de_role"');
                elseif($type == 'no_jeu_de_role')
                    $query->where('j.type != "jeu_de_role"');
            }
            
            // Filter by publication
            $publication = $this->getState('filter.publication');
            if (!empty($publication)) {
                if($publication == 'publie')
                    $query->where('j.publication = "publie"');
                elseif($publication == 'non_publie')
                    $query->where('j.publication = "non_publie"');
            }

            $auteur = $this->getState('filter.auteur');
            if (!empty($auteur)) {
             
		if ($auteur != '[vide]')
		{
			$auteur = $db->Quote('%'.$db->escape($auteur, true).'%');
                	$query->join('LEFT', 'cnj_jeu_to_auteur AS ja ON j.id_jeu = ja.id_jeu');
                	$query->join('LEFT', 'cnj_auteur AS a ON a.id_auteur = ja.id_auteur');
                	$query->where('(a.nom LIKE ' . $auteur . ' or a.alias LIKE ' . $auteur . ')');
		}
		else
		{
			$query->where(' NOT EXISTS (select ja.id_jeu from cnj_jeu_to_auteur ja where ja.id_jeu = j.id_jeu) ');
		}
            }
            
            $reference = $this->getState('filter.reference');
            if (!empty($reference)) {

		if ($reference != '[vide]')
		{
                	$reference = $db->Quote('%'.$db->escape($reference, true).'%');
                	$query->join('LEFT', 'cnj_jeu_to_reference AS jr ON j.id_jeu = jr.id_jeu');
                	$query->join('LEFT', 'cnj_reference AS r ON r.id_reference = jr.id_reference');
                	$query->where('(r.nom LIKE ' . $reference . ' or r.alias LIKE ' . $reference . ')');
		}
		else
		{
			$query->where(' NOT EXISTS (select jr.id_jeu from cnj_jeu_to_reference jr where jr.id_jeu = j.id_jeu) ');
		}
            }
            
            // Add the list ordering clause.
            $orderCol	= $this->state->get('list.ordering', 'j.titre');
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
                $item->auteurs = $this->_getAuteurs($item->id_jeu);
                $item->references = $this->_getReferences($item->id_jeu);
                $item->documents = $this->_getDocuments($item->id_jeu);
                $item->distinctions = $this->_getDistinctions($item->id_jeu);
                $item->motcles = $this->_getMotcles($item->id_jeu);
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
	public function getTable($type = 'Cnj_jeu', $prefix = 'Cnj_jeuxTable', $config = array())
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

		$auteur = $this->getUserStateFromRequest($this->context.'.filter.auteur', 'filter_auteur', '', 'string');
		$this->setState('filter.auteur', $auteur);

		$reference = $this->getUserStateFromRequest($this->context.'.filter.reference', 'filter_reference', '');
		$this->setState('filter.reference', $reference);

		$date_parution_debut = $this->getUserStateFromRequest($this->context.'.filter.date_parution_debut', 'filter_date_parution_debut', '');
		$this->setState('filter.date_parution_debut', $date_parution_debut);

		$date_parution_fin = $this->getUserStateFromRequest($this->context.'.filter.date_parution_fin', 'filter_date_parution_fin', '');
		$this->setState('filter.date_parution_fin', $date_parution_fin);
                
		$motcle = $this->getUserStateFromRequest($this->context.'.filter.motcle', 'filter_motcle');
		$this->setState('filter.motcle', $motcle);
                
		$type = $this->getUserStateFromRequest($this->context.'.filter.type', 'filter_type');
		$this->setState('filter.type', $type);
                
		$publication = $this->getUserStateFromRequest($this->context.'.filter.publication', 'filter_publication');
		$this->setState('filter.publication', $publication);

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
		parent::populateState('j.titre', 'asc');
	}
        
        public function delete($ids)
	{
            $db = $this->getDbo();
            
            foreach ($ids as $id) {
                $db = $this->getDbo();
                $db->setQuery(
                        'DELETE FROM cnj_jeu' .
                        ' WHERE id_jeu = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_jeu_to_auteur' .
                        ' WHERE id_jeu = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_jeu_to_distinction' .
                        ' WHERE id_jeu = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_jeu_to_document' .
                        ' WHERE id_jeu = ' . (int)$id
                );
                $db->query();
                
                $db->setQuery(
                        'DELETE FROM cnj_jeu_to_reference' .
                        ' WHERE id_jeu = ' . (int)$id
                );
                $db->query();
            }

            return true;
        }

	/**
	 * Get file name
	 *
	 * @return	string	The file name
	 * @since	1.6
	 */
	public function getBaseName()
	{
            if (!isset($this->basename)) {

                $app		= JFactory::getApplication();
                $basename	= $this->getState('basename');
                $basename	= str_replace('__SITE__', $app->getCfg('sitename'), $basename);

                $clientId = $this->getState('filter.client_id');
                if (is_numeric($clientId)) {

                        if ($clientId > 0) {
                                $basename = str_replace('__CLIENTID__', $clientId, $basename);
                        } else {
                                $basename = str_replace('__CLIENTID__', '', $basename);
                        }
                        $clientName = $this->getClientName();
                        $basename = str_replace('__CLIENTNAME__', $clientName, $basename);

                } else {

                        $basename = str_replace('__CLIENTID__', '', $basename);
                        $basename = str_replace('__CLIENTNAME__', '', $basename);
                }

                $type = $this->getState('filter.type');
                if ($type > 0) {

                        $basename = str_replace('__TYPE__', $type, $basename);
                        $typeName = JText::_('COM_BANNERS_TYPE'.$type);
                        $basename = str_replace('__TYPENAME__', $typeName, $basename);
                } else {
                        $basename = str_replace('__TYPE__', '', $basename);
                        $basename = str_replace('__TYPENAME__', '', $basename);
                }

                $begin = $this->getState('filter.begin');
                if (!empty($begin)) {
                        $basename = str_replace('__BEGIN__', $begin, $basename);
                } else {
                        $basename = str_replace('__BEGIN__', '', $basename);
                }

                $end = $this->getState('filter.end');
                if (!empty($end)) {
                        $basename = str_replace('__END__', $end, $basename);
                } else {
                        $basename = str_replace('__END__', '', $basename);
                }

                $this->basename = $basename;
            }

            return $this->basename;
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
            $query->select($this->getState('list.select', 'a.nom, ja.qualite'));
            $query->from('cnj_auteur AS a');
            $query->join('INNER ', 'cnj_jeu_to_auteur AS ja ON a.id_auteur = ja.id_auteur AND ja.id_jeu = ' . (int)$id);
            
            $data = $this->_getList($query);
 
            return $data;
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
            $query->select($this->getState('list.select', 'r.nom, jr.qualite'));
            $query->from('cnj_reference AS r');
            $query->join('INNER ', 'cnj_jeu_to_reference AS jr ON r.id_reference = jr.id_reference AND jr.id_jeu = ' . (int)$id);
            
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
            $query->select($this->getState('list.select', 'd.id, d.old_id_document, d.nom, d.type, d.path_hd, d.path_optimise'));
            $query->from('cnj_document AS d');
            $query->join('INNER ', 'cnj_jeu_to_document AS jd ON d.id = jd.id_document AND jd.id_jeu = ' . (int)$id);
            $query->order('jd.ordre asc, d.nom asc');

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
            $query->select($this->getState('list.select', 'd.nom'));
            $query->from('cnj_distinction AS d');
            $query->join('INNER ', 'cnj_jeu_to_distinction AS jd ON d.id_distinction = jd.id_distinction AND jd.id_jeu = ' . (int)$id);
            
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
            $query->select($this->getState('list.select', 'd.nom'));
            $query->from('cnj_motcle AS d');
            $query->join('INNER ', 'cnj_jeu_to_motcle AS jd ON d.id_motcle = jd.id_motcle AND jd.id_jeu = ' . (int)$id);
            
            $data = $this->_getList($query);

            return $data;
	}



}
