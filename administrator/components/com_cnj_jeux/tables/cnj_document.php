<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Cnj_document table
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.5
 */
class Cnj_JeuxTableCnj_document extends JTable
{
        private $imageFolder;
        
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
            $this->imageFolder = 'images/cnj/ALGEDIM/BWIGOFP/';
            
            parent::__construct('cnj_document', 'id', $_db);
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
		if ($this->id == 0) 
                    $this->date_add = date('Y-m-d H:i:s');
		else $this->date_update = date('Y-m-d H:i:s');

		return true;
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link	http://docs.joomla.org/JTable/store
	 * @since   11.1
	 */
	public function store($updateNulls = false)
	{
            parent::store($updateNulls);
	    
            $ofrom = $_GET['ofrom']; 
            
            if($this->id) {
                // get file
                $file = JRequest::getVar('visuel', '', 'files', 'array');
                $ofrom = $_GET['ofrom']; 

                // set file name
                $id_archive = $this->id_archive;
                if(!$id_archive) {
                    if (!$id_archive = $this->getIdArchive())
                    {
                            $e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_GET_ID_FAILED', get_class($this), $this->_db->getErrorMsg()));
                            $this->setError($e);

                            return false;
                    }
                }

                // set extension
                $ext = mb_strtoupper(substr($file['name'],strpos($file['name'],'.')+1,strlen($file['name'])));

                // set size
                $size = $file['size'];

                switch($ext) 
                {
                    case 'JPG':

                        if($file['tmp_name']) {
                            
                            $tmp_file = $file['tmp_name'];
                            $image = imagecreatefromjpeg($tmp_file);
                            $dimensions = getimagesize($tmp_file);
                            
                            // path_miniature : dimension max 180 * 127    
                            $path_miniature = $this->copy($id_archive, $ext, 'P', $image, $dimensions, array('180', '135'));            
                                
                            // path_optimise : dimension max 640 * 480
                            $path_optimise = $this->copy($id_archive, $ext, 'E', $image, $dimensions, array('640', '480'));
                                
                            // path_hd
                            $path_hd = $this->upload($file, $id_archive, $ext, 'D');

                            
                            // maj enregistrement bdd
                            $db = $this->getDbo();
                            $db->setQuery(
                                    'UPDATE cnj_document SET' .
                                    ' id_archive = "' . $id_archive . '"' .
                                    ' , path_hd = "' . $path_hd . '"' .
                                    ' , path_miniature = "' . $path_miniature . '"' .
                                    ' , path_optimise = "' . $path_optimise . '"' .
                                    ' , type = "' . $ext . '"' .
                                    ' , taille = "' . $size . '"' .
                                    ' WHERE id = ' . (int)$this->id
                            );
                            $db->query();
                            
                        }
                        break;

                    case 'PDF':

                        $path_hd = $this->upload($file, $id_archive, $ext, 'D');
                        $path_miniature = '';
                        $path_optimise = '';

                        // maj enregistrement bdd
                        $db = $this->getDbo();
                        $db->setQuery(
                                'UPDATE cnj_document SET' .
                                ' id_archive = "' . $id_archive . '"' .
                                ' , path_hd = "' . $path_hd . '"' .
                                ' , path_miniature = "' . $path_miniature . '"' .
                                ' , path_optimise = "' . $path_optimise . '"' .
                                ' , type = "' . $ext . '"' .
                                ' , taille = "' . $size . '"' .
                                ' WHERE id = ' . (int)$this->id
                        );
                        $db->query();
                        break;

                    case '':
                        break;
                        
                    default : 
                        JError::raiseWarning(100, JText::_('COM_CNJ_JEUX_ERROR_FILE_TYPE'));
                        return false;
                        break;                        
                }
            }
	    
	    if($ofrom == "jeu")
	    {
		header('Location:../ok.html');   	
		exit(1);          
	    }


            return count($this->getErrors())==0;
            //return true;

        }

	/**
	 * Method to move a row in the ordering sequence of a group of rows defined by an SQL WHERE clause.
	 * Negative numbers move the row up in the sequence and positive numbers move it down.
	 *
	 * @param   integer  $delta  The direction and magnitude to move the row in the ordering sequence.
	 * @param   string   $where  WHERE clause to use for limiting the selection of rows to compact the
	 * ordering values.
	 *
	 * @return  mixed    Boolean true on success.
	 *
	 * @link    http://docs.joomla.org/JTable/move
	 * @since   11.1
	 */
	/*public function move($delta, $where = '')
	{
		// If there is no ordering field set an error and return false.
		if (!property_exists($this, 'ordre'))
		{
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_CLASS_DOES_NOT_SUPPORT_ORDERING', get_class($this)));
			$this->setError($e);
			return false;
		}

		// If the change is none, do nothing.
		if (empty($delta))
		{
			return true;
		}

		// Initialise variables.
		$k = $this->_tbl_key;
		$row = null;
		$query = $this->_db->getQuery(true);

		// Select the primary key and ordering values from the table.
		$query->select($this->_tbl_key . ', ordre');
		$query->from($this->_tbl);

		// If the movement delta is negative move the row up.
		if ($delta < 0)
		{
			$query->where('ordre < ' . (int) $this->ordre);
			$query->order('ordre DESC');
		}
		// If the movement delta is positive move the row down.
		elseif ($delta > 0)
		{
			$query->where('ordre > ' . (int) $this->ordre);
			$query->order('ordre ASC');
		}

		// Add the custom WHERE clause if set.
		if ($where)
		{
			$query->where($where);
		}

		// Select the first row with the criteria.
		$this->_db->setQuery($query, 0, 1);
		$row = $this->_db->loadObject();

		// If a row is found, move the item.
		if (!empty($row))
		{
			// Update the ordering field for this instance to the row's ordering value.
			$query = $this->_db->getQuery(true);
			$query->update($this->_tbl);
			$query->set('ordre = ' . (int) $row->ordre);
			$query->where($this->_tbl_key . ' = ' . $this->_db->quote($this->$k));
			$this->_db->setQuery($query);

			// Check for a database error.
			if (!$this->_db->query())
			{
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_MOVE_FAILED', get_class($this), $this->_db->getErrorMsg()));
				$this->setError($e);

				return false;
			}

			// Update the ordering field for the row to this instance's ordering value.
			$query = $this->_db->getQuery(true);
			$query->update($this->_tbl);
			$query->set('ordre = ' . (int) $this->ordre);
			$query->where($this->_tbl_key . ' = ' . $this->_db->quote($row->$k));
			$this->_db->setQuery($query);

			// Check for a database error.
			if (!$this->_db->query())
			{
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_MOVE_FAILED', get_class($this), $this->_db->getErrorMsg()));
				$this->setError($e);

				return false;
			}

			// Update the instance value.
			$this->ordre = $row->ordre;
		}
		else
		{
			// Update the ordering field for this instance.
			$query = $this->_db->getQuery(true);
			$query->update($this->_tbl);
			$query->set('ordre = ' . (int) $this->ordre);
			$query->where($this->_tbl_key . ' = ' . $this->_db->quote($this->$k));
			$this->_db->setQuery($query);

			// Check for a database error.
			if (!$this->_db->query())
			{
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_MOVE_FAILED', get_class($this), $this->_db->getErrorMsg()));
				$this->setError($e);

				return false;
			}
		}

		return true;
	}*/

	/**
	 * Method to check a row in if the necessary properties/fields exist.  Checking
	 * a row in will allow other users the ability to edit the row.
	 *
	 * @param   mixed  $pk  An optional primary key value to check out.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/checkIn
	 * @since   11.1
	 */
	public function checkIn($pk = null)
	{

		return true;
	}
        
        private function upload($file, $name, $extension, $prefix) 
        {
            if (isset($file['tmp_name']) && !empty($file['tmp_name']))
            { 
                $filepath = $this->setPath($name, $extension, $prefix);

                if (JFile::exists($filepath))
                {
                    // File exists
                    JError::raiseWarning(100, JText::_('COM_CNJ_JEUX_ERROR_FILE_EXISTS'));
                    return false;
                }

                // Move uploaded file
                if (!JFile::upload($file['tmp_name'], '../' . $filepath))
                {
                    // Error in upload
                    JError::raiseWarning(100, JText::_('COM_CNJ_JEUX_ERROR_UNABLE_TO_UPLOAD_FILE'));
                    return false;
                }
                else
                {
                    return $filepath;
                }
            }
            return false;
        }
        
        private function copy($id_archive, $ext, $prefix, $image, $dimensions, $new_dimensions)
        { 
            if ($dimensions[0] > $dimensions[1]) { //paysage
                if($dimensions[0] > $new_dimensions[0]) {
                    $largeur_finale = $new_dimensions[0];
                    $hauteur_finale = round(($new_dimensions[0] * $dimensions[1]) / $dimensions[0]);
                }
                else {
                    $largeur_finale = $dimensions[0];
                    $hauteur_finale = $dimensions[1];
                }
            }
            else {   //portrait
                if($dimensions[1] > $new_dimensions[1]) {
                    $largeur_finale = round(($new_dimensions[1] * $dimensions[0]) / $dimensions[1]);
                    $hauteur_finale = $new_dimensions[1];
                }
                else {
                    $largeur_finale = $dimensions[0];
                    $hauteur_finale = $dimensions[1];
                }
            }
            
            $image_temp = imagecreatetruecolor($largeur_finale, $hauteur_finale); 
            if(imagecopyresampled($image_temp, $image, 0, 0, 0, 0, $largeur_finale, $hauteur_finale, $dimensions[0], $dimensions[1])) 
                if(imagejpeg($image_temp, $this->setPath($id_archive, $ext, $prefix, true), 90))
                    return $this->setPath($id_archive, $ext, $prefix);
                
            return false;
        }
        
        private function setPath($name, $extension, $prefix, $chemin_absolu = false) 
        {
            $chemin = realpath('../');
            $folder = $this->imageFolder . $prefix . substr($name, 0, -2);

            if(!is_dir($chemin . '/' . $folder))
                mkdir($chemin . '/' . $folder);
            
            //var_dump(JPath::clean($folder . '/' . $prefix . $name . '.' . $extension));
            return ($chemin_absolu ? $chemin . '/' : '') . JPath::clean($folder . '/' . $prefix . $name . '.' . $extension);
        }
        
        private function getIdArchive() 
        {
            $db	= JFactory::getDBO();
            $db->setQuery(
                    'INSERT INTO cnj_document_archive VALUES ()'
            );
            $db->query();
            $data = $db->insertid();
            
            return $data;
        }
}
