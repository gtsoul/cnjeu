<?php
/**
 * @version		$Id: fiche.php 21700 2011-06-28 04:32:41Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

/**
 * Cnj_jeux Component Fiche Model
 *
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @since 1.5
 */
class Cnj_jeuxModelFiche extends JModelItem
{


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
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_cnj_jeux.fiche';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = JRequest::getInt('code');
		$this->setState('fiche.codepays', $pk);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	//protected $_context = 'com_cnj_jeux.fiche';
	
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('fiche.codepays');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {

			try {
				$lang=& JFactory::getLanguage();
				$langTag = substr($lang->getTag() ,0, strpos($lang->getTag(), '-') ) ;

				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select($this->getState('item.select', 'j.*'));
				$query->from('cnj_jeu j');
                                
				$query->where('j.id_jeu = ' . (int) $pk);

				$db->setQuery($query);

				$data = $db->loadObject();

				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}

				if (empty($data)) {
					return JError::raiseError(404,JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));
				}
                                
                                // recuperation des auteurs, references et documents
                                $data->references = $this->_getReferences($data->id_jeu);
                                $data->auteurs = $this->_getAuteurs($data->id_jeu);
                                $data->motcles = $this->_getMotCles($data->id_jeu);
                                $data->mecanismes = $this->_getMecanismes($data->id_jeu);
                                $docs = $this->_getDocuments($data->id_jeu);

			           $documents = $visuels = array();
                                if(count($docs)>0) {
                                    foreach($docs as $doc) {
                                    	// correction    if($doc->type=='JPG') $visuels[] = $doc->path_optimise;
						if($doc->type=='JPG')
						{
						    if ($doc->force_telechargement == 'non')  $visuels[] = $doc;
                                      		    else $documents[] = $doc;
						 
						}
                                      	else $documents[] = $doc;
                                    }
                                }
                                $data->documents = $documents;
                                $data->visuels = $visuels;

				$this->_item[$pk] = $data;
			}
			catch (JException $e)
			{
				if ($e->getCode() == 404) {
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else {
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
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
	private function _getAuteurs($id)
	{
            $db = $this->getDbo();
             
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'a.nom, ja.qualite, ja.qualite_old'));
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
	private function _getMotCles($id)
	{
            $db = $this->getDbo();
              
            $query = $db->getQuery(true);
            $query->select($this->getState('list.select', 'm.motcle'));
            $query->from('cnj_motcle AS m');
            $query->join('INNER ', 'cnj_jeu_to_motcle AS jm ON m.id_motcle = jm.id_motcle AND jm.id_jeu = ' . (int)$id);
            
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
            $query->select($this->getState('list.select', 'm.mecanisme'));
            $query->from('cnj_mecanisme AS m');
            $query->join('INNER ', 'cnj_jeu_to_mecanisme AS jm ON m.id_mecanisme = jm.id_mecanisme AND jm.id_jeu = ' . (int)$id);
            
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
            $query->select($this->getState('list.select', 'd.nom, d.type, d.path_hd, d.path_optimise, d.force_telechargement'));
            $query->from('cnj_document AS d');
            $query->join('INNER ', 'cnj_jeu_to_document AS jd ON d.id = jd.id_document AND jd.id_jeu = ' . (int)$id);
	    $query->where('jd.ordre > 0');
            $query->order('jd.ordre asc, d.nom asc');
            $data = $this->_getList($query);
            return $data;
	}
}
