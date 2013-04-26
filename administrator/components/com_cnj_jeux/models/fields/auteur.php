<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;




/**
 * Supports an HTML select list of auteurs
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class JFormFieldAuteur extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Auteur';




	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
                $html[] = '
<script type="text/javascript" >
function newAuteur()
{
var a = document.getElementById(\'add_auteur\').value;
if (a == "")
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=auteur&layout=edit&ofrom=jeu\');
  }
else
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=auteur&layout=edit&ofrom=jeu&newauteurs=\'+a);
  }
} 
</script>  

Qualit&eacute (Alexandrie) : '.$this->value[0]->qualite_old.'<br />
<input type="text" id="add_auteur" name="add_auteur" value="" size="120" style="float:none" />
                    <div id="auteurs_propositions" class="autocomplete"></div>
                    <a id="btn_add_auteur" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_ADD_AUTEUR') . '</a>&nbsp;&nbsp;<a href="javascript:newAuteur()" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_NEW_AUTEUR') . '</a><br /><br />';
            
                $values = $list = '';
                if(is_array($this->value)) {                    


		$liste_qualites_auteur = array("Auteur",
				"Auteur JDR",
				"Collaboration",
				"Coloriste",
				"Création - Edition",
				"Créateur - Designer",
				"Dessinateur",
				"Développement",
				"Directeur de publication",
				"Réalisation Graphique",
				"Historien du jeu",
				"Idée",
				"Illustration - graphisme",
				"Licence",
				"Mise en page",
				"Photographe",
				"Préfacier",
				"Recherche - Compilation",
				"Rédaction",
				"Scénariste",
				"Système de jeu",
				"Traducteur",
				"Textes",""); 





                    foreach($this->value as $value) {
                        $values .= $value->id_auteur . ',';
                 /*       $html[] = '<div id="auteur_' . $value->id_auteur . '">&bull; ' . $value->nom . ' - ' . JText::_('COM_CNJ_JEUX_QUALITE') . ' : <input type="text" name="jform[auteur_qualites][]" value="' . ($value->qualite?$value->qualite:$value->qualite_old) . '" style="float:none" /><input type="hidden" name="jform[auteur_qualites_old][]" value="' . $value->qualite_old . '" /> <a class="btn_remove_auteur" style="cursor:pointer; color:#c00" >X</a><br /></div>';
                 */

                        $html[] = '<div id="auteur_' . $value->id_auteur . '">&bull; ' . $value->nom . ' - ' . JText::_('COM_CNJ_JEUX_QUALITE') . ' : <select name="jform[auteur_qualites][]" value="' . ($value->qualite?$value->qualite:$value->qualite_old) . '" style="float:none">';


	$selected_value =  ($value->qualite?$value->qualite:$value->qualite_old);



	foreach ($liste_qualites_auteur as $qualite)
	{
		if (utf8_encode($qualite) == $selected_value)
			$html[] .= "<option selected = 'selected'   value=   '".utf8_encode($qualite)."'>".htmlentities($qualite)."</option>";
		else
			$html[] .= "<option value='".utf8_encode($qualite)."'>".htmlentities($qualite)."</option>";
	}

	$html[] .= '</select>';
	$html[] .= '<input type="hidden" name="jform[auteur_qualites_old][]" value="' . $value->qualite_old . '" /> <a class="btn_remove_auteur" style="cursor:pointer; color:#c00" >X</a><br /></div>';
                 

   }
                    $html[] = '<input type="hidden" id="list_auteurs" name="' . $this->name . '" value="' . substr($values,0,-1) . '">';
                }
                
                return '<div class="div_field_autocomplete">' . implode($html) . '</div>';
	}
}
