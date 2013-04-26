<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of references
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class JFormFieldReference extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Reference';

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
function newReference()
{
var a = document.getElementById(\'add_reference\').value;
if (a == "")
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=reference&layout=edit&ofrom=jeu\');
  }
else
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=reference&layout=edit&ofrom=jeu&newreference=\'+a);
  }
} 
</script>  

<input type="text" id="add_reference" name="add_reference" value="" size="120" style="float:none" />
                    <div id="references_propositions" class="autocomplete"></div>
                    <a id="btn_add_reference" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_ADD_REFERENCE') . '</a>&nbsp;&nbsp;<a href="javascript:newReference()" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_NEW_REFERENCE') . '</a><br /><br />';
           


	
 
                $values = $list = '';
                if(is_array($this->value)) {                    


		$liste_qualites_reference =array("Editeur - Distributeur",
		"Imprimerie",
		"Organisme",
		"Licence",
		"Revue",
		"Distributeur",
		"Producteur (licence)",
		"Importateur",
		"Editeur",
		"Fabricant",
		"Marque",
		"Client",
		"Participation",
		"Création",
		"Développement",
		"Cartier","");


               foreach($this->value as $value) {
                        $values .= $value->id_reference . ',';
                        $html[] = '<div id="reference_' . $value->id_reference . '">&bull; ' . $value->nom . ' - ' . JText::_('COM_CNJ_JEUX_QUALITE') . ' : <select type="text" name="jform[reference_qualites][]" value="' . ($value->qualite?$value->qualite:'') . '" style="float:none" />'; 

		$selected_value = ($value->qualite?$value->qualite:'');

		foreach ($liste_qualites_reference as $qualite)
		{
			if (utf8_encode($qualite) == $selected_value)
				$html[] .= "<option selected = 'selected'   value=   '".utf8_encode($qualite)."'>".htmlentities($qualite)."</option>";
			else
				$html[] .= "<option value='".utf8_encode($qualite)."'>".htmlentities($qualite)."</option>";
		}


		$html[] .= '</select>';
		$html[] .= '<a class="btn_remove_reference" style="cursor:pointer; color:#c00" >X</a><br /></div>';

                    }



                    $html[] = '<input type="hidden" id="list_references" name="' . $this->name . '" value="' . substr($values,0,-1) . '">';
                }
                
                return '<div class="div_field_autocomplete">' . implode($html) . '</div>';
	}
}
