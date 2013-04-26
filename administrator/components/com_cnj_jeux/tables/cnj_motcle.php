<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Cnj_motcle table
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.5
 */
class Cnj_JeuxTableCnj_motcle extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
		parent::__construct('cnj_motcle', 'id_motcle', $_db);
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
/*
		if ($this->id_motcle == 0) 
                    $this->date_add = date('Y-m-d H:i:s');
		else $this->date_update = date('Y-m-d H:i:s');
*/
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

	    if($ofrom == "jeu" && count($this->getErrors())==0)
	    {
		header('Location:../ok.html');   	
		exit(1);          
	    }
            return count($this->getErrors())==0;
	}

}
