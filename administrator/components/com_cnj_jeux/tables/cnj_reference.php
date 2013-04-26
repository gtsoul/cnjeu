<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Cnj_reference table
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.5
 */
class Cnj_JeuxTableCnj_reference extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
		parent::__construct('cnj_reference', 'id_reference', $_db);
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
		if ($this->id_reference == 0) 
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

            if($this->id_reference) {
                $db = $this->getDbo();
                $jform = JRequest::getVar("jform");


	
                // DOCUMENTS
                $documents = explode(',',$jform['documents']);
                foreach($documents as $document) // on retire les elements vides s'il y en a
                    if($document != '') $this->documents[] = $document;
                $this->ordres = $jform['ordres'];
                
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_reference_to_document WHERE id_reference = ' . (int)$this->id_reference);
                $db->query();   
                
                // on ajoute les elements
                $i=0;
                foreach($this->documents as $document) 
                {
                    $db->setQuery(
                            'REPLACE INTO cnj_reference_to_document (id_reference, id_document, ordre)' .
                            ' VALUES (' . (int)$this->id_reference . ', ' . (int)$document . ', "' . $this->ordres[$i] . '")'
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
