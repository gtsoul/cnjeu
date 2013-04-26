<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Cnj_auteur table
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.5
 */
class Cnj_JeuxTableCnj_auteur extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
		parent::__construct('cnj_auteur', 'id_auteur', $_db);
	}

	/**
	 * Overloaded check function
	 *
	 * @return	boolean
	 * @see		JTable::check
	 * @since	1.5
	 */
	function check()
	{
		if ($this->id_auteur == 0) 
                    $this->date_add = date('Y-m-d H:i:s');
		else $this->date_update = date('Y-m-d H:i:s');

		return true;
	}
        
	/**
	 * method to store a row
	 *
	 * @param boolean $updateNulls True to update fields even if they are null.
	 */
	function store($updateNulls = false)
	{
            // Store the row
            parent::store($updateNulls);
	    
	    $ofrom = $_GET['ofrom']; 

            if($this->id_auteur) {
                $db = $this->getDbo();
                $jform = JRequest::getVar("jform");
                $ofrom = $_GET['ofrom']; 

                // DOCUMENTS
                $documents = explode(',',$jform['documents']);
                foreach($documents as $document) // on retire les elements vides s'il y en a
                    if($document != '') $this->documents[] = $document;
                $this->ordres = $jform['ordres'];
                
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_auteur_to_document WHERE id_auteur = ' . (int)$this->id_auteur);
                $db->query();   
                
                // on ajoute les elements
                $i=0;
                foreach($this->documents as $document) 
                {
                    $db->setQuery(
                            'REPLACE INTO cnj_auteur_to_document (id_auteur, id_document, ordre)' .
                            ' VALUES (' . (int)$this->id_auteur . ', ' . (int)$document . ', "' . $this->ordres[$i] . '")'
                    );
                    $db->query();
                    $i++;
                }
            }
	    
	    if($ofrom == "jeu" && count($this->getErrors())==0)
	    {
		header('Location:../ok.html');   	
		exit(1);          
	    }


            return count($this->getErrors())==0;
	}
}
