<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of motcles
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class JFormFieldMotcle extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Motcle';

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
function newMotcle()
{
var a = document.getElementById(\'add_motcle\').value;

if (a == "")
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=motcle&layout=edit&ofrom=jeu\');
  }
else
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=motcle&layout=edit&ofrom=jeu&newmotcle=\'+a);
  }
} 
</script>  

<input type="text" id="add_motcle" name="add_motcle" value="" size="60" style="float:none" />
                    <div id="motcles_propositions" class="autocomplete"></div>
                    <a id="btn_add_motcle" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_ADD_MOTCLE') . '</a>&nbsp;&nbsp;<a href="javascript:newMotcle()" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_NEW_MOTCLE') . '</a><br /><br />';
            
                $values = $list = '';
	
                if(is_array($this->value)) 
		{                    
			foreach($this->value as $value) {
                        $values .= $value->id_motcle . ',';

                        $html[] = '<div id="motcle_' . $value->id_motcle . '">&bull; ' . $value->motcle . ' <a class="btn_remove_motcle" style="cursor:pointer; color:#c00" >X</a><br /></div>';

                    }
                    $html[] = '<input type="hidden" id="list_motcles" name="' . $this->name . '" value="' . substr($values,0,-1) . '">';
                }
               
	 
                return '<div class="div_field_autocomplete">' . implode($html) . '</div>';
	}
}
