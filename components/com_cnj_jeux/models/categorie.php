<?php
/**
 * @version		$Id: frontpage.php 21700 2011-06-28 04:32:41Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.categories');

/**
 * Cnj_jeux Component Categorie Model
 *
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @since 1.5
 */
class Cnj_jeuxModelCategorie extends JModelList
{
	/**
	 * Category items data
	 *
	 * @var array
	 */
	protected $_item = null;

	protected $_articles = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_content.category';

	/**
	 * The category that applies.
	 *
	 * @access	protected
	 * @var		object
	 */
	protected $_category = null;

	/**
	 * The list of other newfeed categories.
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_categories = null;

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
                
                $session = JFactory::getSession();
                
                $start                  = urldecode(JRequest::getString('start'));
                $limitstart             = urldecode(JRequest::getString('limitstart'));
                $act                    = urldecode(JRequest::getString('act'));
                $search_titre		= urldecode(JRequest::getString('search_titre'));
                $search_auteur          = urldecode(JRequest::getString('search_auteur'));
                $search_reference       = urldecode(JRequest::getString('search_reference'));
                $search_date_parution_debut = urldecode(JRequest::getString('search_date_parution_debut'));
                $search_date_parution_fin   = urldecode(JRequest::getString('search_date_parution_fin'));
                $search_motcle          = urldecode(JRequest::getString('search_motcle'));
                $search_type            = urldecode(JRequest::getString('search_type'));

                
                // si pagination
                if(!empty($start) || $limitstart!='') {
                    $search_titre = $session->get('search_titre');
                    $search_auteur = $session->get('search_auteur');
                    $search_reference = $session->get('search_reference');
                    $search_date_parution_debut = $session->get('search_date_parution_debut');
                    $search_date_parution_fin = $session->get('search_date_parution_fin');
                    $search_motcle = $session->get('search_motcle');
                    $search_type = $session->get('search_type');
                }
                
                // initialisation de la recherche
		$this->setSearch($search_titre, $search_auteur, $search_reference, $search_date_parution_debut, $search_date_parution_fin, $search_motcle, $search_type);
                
                // si le formulaire est renvoyé on met à jour les infos de session
                if($act == 'search_jeu') {
                    $session->set('search_titre', $search_titre);
                    $session->set('search_auteur', $search_auteur);
                    $session->set('search_reference', $search_reference);
                    $session->set('search_date_parution_debut', $search_date_parution_debut);
                    $session->set('search_date_parution_fin', $search_date_parution_fin);
                    $session->set('search_motcle', $search_motcle);
                    $session->set('search_type', $search_type);
                }
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initiliase variables.
		$app	= JFactory::getApplication('site');
		//$pk		= JRequest::getInt('id');

		//$this->setState('category.id', $pk);

		// List state information
		//$value = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$value = JRequest::getUInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', 15/*$value*/);

		//$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);

		// Load the parameters. Merge Global and Menu Item params into new object
		$params = $app->getParams();
		$menuParams = new JRegistry;

		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);
		$this->setState('params', $mergedParams);

		// Optional filter text
		$this->setState('list.filter', JRequest::getString('filter-search'));

		// filter.order
		$itemid = JRequest::getInt('id', 0) . ':' . JRequest::getInt('Itemid', 0);
		$orderCol = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		if (!in_array($orderCol, $this->filter_fields)) {
			$orderCol = 'a.ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order_Dir',
			'filter_order_Dir', '', 'cmd');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		//$this->setState('layout', JRequest::getCmd('layout'));

	}

	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	function getListQuery()
	{
            $db = $this->getDbo();
            $query = $db->getQuery(true);

            $query->select($this->getState('list.select', 'j.id_jeu, j.titre, j.date_parution_debut, j.date_parution_fin, j.pays_edition'));
            $query->from('cnj_jeu AS j');
          //  $query->where('j.publication = "publie"');
            
            $query->select('d.path_miniature');
            $query->join('LEFT', 'cnj_jeu_to_document AS jd ON (j.id_jeu = jd.id_jeu AND jd.ordre=1)');
            $query->join('LEFT', 'cnj_document AS d ON (d.id = jd.id_document)');

            $search_titre = $this->getState('search_titre');
            if ($search_titre && $search_titre != '%%') {
                $query->where('j.titre like "' . $search_titre . '"');
            }

            $query->where('j.publication = "publie" ');          

            //$query->where('jd.ordre = 1 ');          


            $search_date_parution_debut = (int)$this->getState('search_date_parution_debut');
            if ($search_date_parution_debut != 0) {
                $query->where('(j.date_parution_debut != "" and  j.date_parution_debut >= ' . $search_date_parution_debut.')');          
  			}

            $search_date_parution_fin = (int)$this->getState('search_date_parution_fin');
            if ($search_date_parution_fin != 0) {
			    $query->where('(j.date_parution_debut != "" and  j.date_parution_debut <= ' . $search_date_parution_fin.')');          
			}
            
            $search_auteur = $this->getState('search_auteur');
            if ($search_auteur && $search_auteur != '%%') {
                $query->join('LEFT', 'cnj_jeu_to_auteur AS ja ON j.id_jeu = ja.id_jeu');
                $query->join('LEFT', 'cnj_auteur AS a ON a.id_auteur = ja.id_auteur');
                $query->where('(a.nom like "' . $search_auteur . '" or a.alias like "' . $search_auteur . '")');
            }
            
            $search_reference = $this->getState('search_reference');
            if ($search_reference && $search_reference != '%%') {
                $query->join('LEFT', 'cnj_jeu_to_reference AS jr ON j.id_jeu = jr.id_jeu');
                $query->join('LEFT', 'cnj_reference AS r ON r.id_reference = jr.id_reference');
                $query->where('(r.nom like "' . $search_reference . '" or r.alias like "' . $search_reference . '")');
            }
            
            //$search_motcle = $this->getState('search_motcle');
            //if ($search_motcle && $search_motcle != '%%') {
            //    $query->where('(j.transfert_mot_cle like "' . $search_motcle . '" or j.mecanisme like "' . $search_motcle . '")');
            //}

            $search_motcle = $this->getState('search_motcle');
            if ($search_motcle && $search_motcle != '%%') {
                $query->join('LEFT', 'cnj_jeu_to_motcle AS jmc ON j.id_jeu = jmc.id_jeu');
                $query->join('LEFT', 'cnj_motcle AS mc ON mc.id_motcle = jmc.id_motcle');
                
		$query->join('LEFT', 'cnj_jeu_to_mecanisme AS jm ON j.id_jeu = jm.id_jeu');
                $query->join('LEFT', 'cnj_mecanisme AS m ON m.id_mecanisme = jm.id_mecanisme');
                
		$query->where('(mc.motcle like "' . $search_motcle . '" or m.mecanisme like "' . $search_motcle . '" )');
            }



            
            $search_type = $this->getState('search_type');
            if (!empty($search_type)) {
                if($search_type == 'jeu_de_role')
                    $query->where('j.type = "jeu_de_role"');
                elseif($search_type == 'no_jeu_de_role')
                    $query->where('j.type != "jeu_de_role"');
            }
            
            $query->group('j.id_jeu');
            
            $params = $this->state->params;
            $num_max = $params->get('num_max_items');
            if(isset($num_max) && $num_max!='') {
				if(urldecode(JRequest::getString('Itemid')) == 127)// dernières dernieres aquisitions
				{
                	$query->order('j.date_add desc');
            	}
            	elseif(urldecode(JRequest::getString('Itemid')) == 133)// les jeux primés primes
            	{
                	$query->join('LEFT OUTER', 'cnj_jeu_to_distinction AS jddd ON j.id_jeu = jddd.id_jeu');
                	$query->join('LEFT', 'cnj_distinction AS dist ON dist.id_distinction = jddd.id_distinction');
					$query->where('dist.id_distinction IS NOT NULL ');
            	}
            }
            
            //$query->order(' j.titre asc, jd.ordre asc');
            $query->order(' j.titre asc');


 	//echo $query->__toString();		
		

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
            $params = $this->state->params;
            $num_max = $params->get('num_max_items');
            if($num_max!='') {
                $this->setState('list.start', 0);
                $this->setState('list.limit', $num_max);
            }
            
            $items	= parent::getItems();

            // on ne garde qu'un enregistrement par jeu
            $prev_id = 0;
            //$tmp_items = array();
            foreach ($items as &$item)
            {
                //if($prev_id != $item->id_jeu) {
                //    $tmp_items[] = $item;
                    
                    // recuperation des auteurs et references pour chaque item
                    $item->references = $this->_getReferences($item->id_jeu);
                    $item->auteurs = $this->_getAuteurs($item->id_jeu);
                //}
                //$prev_id = $item->id_jeu;
            }
            
            if($num_max=='') $this->_pagination = parent::getPagination();

            //return $tmp_items;
            return $items;
	}

	public function getPagination()
	{
		if (empty($this->_pagination)) {
			return null;
		}
		return $this->_pagination;
	}

	/**
	 * Method to set the search parameters
	 *
	 * @access	public
	 * @param string search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 */
	function setSearch($search_titre = '', $search_auteur = '', $search_reference = '', $search_date_parution_debut = '', $search_date_parution_fin = '', $search_motcle = '', $search_type = '')
	{
		if (isset($search_titre)) {
			$this->setState('search_titre', '%'.$search_titre.'%');
		}

		if (isset($search_auteur)) {
			$this->setState('search_auteur', '%'.$search_auteur.'%');
		}

		if (isset($search_reference)) {
			$this->setState('search_reference', '%'.$search_reference.'%');
		}

		if (isset($search_date_parution_debut)) {
			$this->setState('search_date_parution_debut', $search_date_parution_debut);
		}

		if (isset($search_date_parution_fin)) {
			$this->setState('search_date_parution_fin', $search_date_parution_fin);
		}

		if (isset($search_motcle)) {
			$this->setState('search_motcle', '%'.$search_motcle.'%');
		}

		if (isset($search_type)) {
			$this->setState('search_type', $search_type);
		}
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
            $query->select($this->getState('list.select', 'r.nom'));
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
	private function _getAuteurs($id)
	{
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'a.nom'));
            $query->from('cnj_auteur AS a');
            $query->join('INNER ', 'cnj_jeu_to_auteur AS ja ON a.id_auteur = ja.id_auteur AND ja.id_jeu = ' . (int)$id);
            
            $data = $this->_getList($query);
 
            return $data;
	}
}
