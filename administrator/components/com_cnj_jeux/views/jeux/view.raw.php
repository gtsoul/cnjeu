<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of jeux.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxViewJeux extends JView
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
            
            $basename		= $this->get('BaseName');
                
            // Get the component configuration values.
            $this->items		= $this->get('Items');
            $this->state		= $this->get('State');

            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                    JError::raiseError(500, implode("\n", $errors));
                    return false;
            }

            // Print the values.
            $content = "JEU_ID;TITRE;REFERENCES;AUTEURS;DATE DE PARUTION DEBUT;DATE DE PARUTION FIN;INFORMATION DATE;VERSION;NOMBRE DE JOUEURS;AGE INDIQUE;MOTS CLES;MECANISME;LOCALISATION" . "\r\n";
           
            foreach ($this->items as $item) {                
                $content .= $item->id_jeu . ";";
                $content .= utf8_decode($item->titre) . ";";
                foreach($item->references as $ref) 
                    $content .= utf8_decode($ref->nom . ($ref->qualite?'('.$ref->qualite.')':'')) . " / ";
                $content .= ";";
                foreach($item->auteurs as $auteur) 
                    $content .= utf8_decode($auteur->nom . ($auteur->qualite?'('.$auteur->qualite.')':'('.$auteur->qualite_old.')')) . " / ";
                $content .= ";";
                $content .= utf8_decode($item->date_parution_debut) . ";";
                $content .= utf8_decode($item->date_parution_fin) . ";";
                $content .= utf8_decode($item->informations_date) . ";";
                $content .= utf8_decode($item->pays_edition) . ";";
                $content .= utf8_decode($item->transfert_nb_joueurs) . ";";
                $content .= utf8_decode($item->age_indique) . ";";
                $content .= utf8_decode($item->transfert_mot_cle) . ";";
                $content .= utf8_decode($item->mecanisme) . ";";
                $content .= utf8_decode($item->transfert_loc) . ";";
                $content .= "\r\n";
            }
                
            $document = JFactory::getDocument();
            $document->setMimeEncoding('text/csv');
            JResponse::setHeader('Content-disposition', 'attachment; filename="'.($basename?$basename:'cnj-jeux').'-export.csv"; creation-date="'.JFactory::getDate()->toRFC822().'"', true);
            echo $content;
	}
}
