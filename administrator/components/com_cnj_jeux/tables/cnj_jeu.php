<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Cnj_jeu table
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.5
 */
class Cnj_JeuxTableCnj_jeu extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
		parent::__construct('cnj_jeu', 'id_jeu', $_db);
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
		if ($this->id_jeu == 0) 
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

            if($this->id_jeu) {
                $db = $this->getDbo();
                $jform = JRequest::getVar("jform");
            
                // AUTEURS
                $auteurs = explode(',',$jform['auteurs']);
                foreach($auteurs as $auteur) // on retire les elements vides s'il y en a
                    if($auteur != '') $this->auteurs[] = $auteur;
                $this->qualites = $jform['auteur_qualites'];
                $this->qualites_old = $jform['auteur_qualites_old'];
             
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_jeu_to_auteur WHERE id_jeu = ' . (int)$this->id_jeu);
                $db->query();
                
                // on ajoute les elements
                $i=0;
                foreach($this->auteurs as $auteur) 
                {
                    $db->setQuery(
                            'INSERT INTO cnj_jeu_to_auteur (id_jeu, id_auteur, qualite, qualite_old)' .
                            ' VALUES (' . (int)$this->id_jeu . ', ' . (int)$auteur . ', "' . $this->qualites[$i] . '", "' . $this->qualites_old[$i] . '")'
                    );
                    $db->query();
                    $i++;
                }
            
                
                // REFERENCES
                $references = explode(',',$jform['references']);
                foreach($references as $reference) // on retire les elements vides s'il y en a
                    if($reference != '') $this->references[] = $reference;
                $this->qualites = $jform['reference_qualites'];
                
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_jeu_to_reference WHERE id_jeu = ' . (int)$this->id_jeu);
                $db->query();
                
                // on ajoute les elements
                $i=0;
                foreach($this->references as $reference) 
                {
                    $db->setQuery(
                            'REPLACE INTO cnj_jeu_to_reference (id_jeu, id_reference, qualite)' .
                            ' VALUES (' . (int)$this->id_jeu . ', ' . (int)$reference . ', "' . $this->qualites[$i] . '")'
                    );
                    $db->query();
                    $i++;
                }
            
                
                // DOCUMENTS
                $documents = explode(',',$jform['documents']);
                foreach($documents as $document) // on retire les elements vides s'il y en a
                    if($document != '') $this->documents[] = $document;
                $this->ordres = $jform['ordres'];
                
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_jeu_to_document WHERE id_jeu = ' . (int)$this->id_jeu);
                $db->query();   
                
                // on ajoute les elements
                $i=0;
                foreach($this->documents as $document) 
                {
                    $db->setQuery(
                            'REPLACE INTO cnj_jeu_to_document (id_jeu, id_document, ordre)' .
                            ' VALUES (' . (int)$this->id_jeu . ', ' . (int)$document . ', "' . $this->ordres[$i] . '")'
                    );
                    $db->query();
                    $i++;
                }

                // MOTCLES
                $motcles = explode(',',$jform['motcles']);
  
		
              foreach($motcles as $motcle) // on retire les elements vides s'il y en a
                    if($motcle != '') $this->motcles[] = $motcle;
               // $this->date_motcles = $jform['date_motcles'];
                
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_jeu_to_motcle WHERE id_jeu = ' . (int)$this->id_jeu);
                $db->query();
                
                // on ajoute les elements
                $i=0;

                foreach($this->motcles as $motcle) 
                {
                   $query =   'REPLACE INTO cnj_jeu_to_motcle (id_jeu, id_motcle)' .
                            ' VALUES (' . (int)$this->id_jeu . ', ' . (int)$motcle  . ')';

		   $db->setQuery($query);
                    $db->query();
              		$i++;
                }
            

                // MECANISMES
                $mecanismes = explode(',',$jform['mecanismes']);
  
		
              foreach($mecanismes as $mecanisme) // on retire les elements vides s'il y en a
                    if($mecanisme != '') $this->mecanismes[] = $mecanisme;
                
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_jeu_to_mecanisme WHERE id_jeu = ' . (int)$this->id_jeu);
                $db->query();
                
                // on ajoute les elements
                $i=0;

                foreach($this->mecanismes as $mecanisme) 
                {
                   $query =   'REPLACE INTO cnj_jeu_to_mecanisme (id_jeu, id_mecanisme)' .
                            ' VALUES (' . (int)$this->id_jeu . ', ' . (int)$mecanisme  . ')';

		   $db->setQuery($query);
                    $db->query();
              		$i++;
                }
                
                // DISTINCTIONS
                $distinctions = explode(',',$jform['distinctions']);
                foreach($distinctions as $distinction) // on retire les elements vides s'il y en a
                    if($distinction != '') $this->distinctions[] = $distinction;
                $this->date_distinctions = $jform['date_distinctions'];
                
                // on supprime tous les elements
                $db->setQuery('DELETE FROM cnj_jeu_to_distinction WHERE id_jeu = ' . (int)$this->id_jeu);
                $db->query();
                
                // on ajoute les elements
                $i=0;
                foreach($this->distinctions as $distinction) 
                {
                    $db->setQuery(
                            'REPLACE INTO cnj_jeu_to_distinction (id_jeu, id_distinction, date_distinction)' .
                            ' VALUES (' . (int)$this->id_jeu . ', ' . (int)$distinction . ', "' . $this->date_distinctions[$i] . '")'
                    );
                    $db->query();
                    $i++;
                }
            }

            return count($this->getErrors())==0;
	}	
}
