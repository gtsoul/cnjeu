<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of mecanismes
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class JFormFieldmecanisme extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'mecanisme';

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
function newmecanisme()
{
var a = document.getElementById(\'add_mecanisme\').value;

if (a == "")
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=mecanisme&layout=edit&ofrom=jeu\');
  }
else
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=mecanisme&layout=edit&ofrom=jeu&newmecanisme=\'+a);
  }
} 
</script>  

<input type="text" id="add_mecanisme" name="add_mecanisme" value="" size="60" style="float:none" />
                    <div id="mecanismes_propositions" class="autocomplete"></div>
                    <a id="btn_add_mecanisme" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_ADD_mecanisme') . '</a>&nbsp;&nbsp;<a href="javascript:newmecanisme()" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_NEW_mecanisme') . '</a><br /><br />';
            
                $values = $list = '';
	
                if(is_array($this->value)) 
		{                    
			foreach($this->value as $value) {
                        $values .= $value->id_mecanisme . ',';

                        $html[] = '<div id="mecanisme_' . $value->id_mecanisme . '">&bull; ' . $value->mecanisme . ' <a class="btn_remove_mecanisme" style="cursor:pointer; color:#c00" >X</a><br /></div>';

                    }
                    $html[] = '<input type="hidden" id="list_mecanismes" name="' . $this->name . '" value="' . substr($values,0,-1) . '">';
                }
               
	 
                return '<div class="div_field_autocomplete">' . implode($html) . '</div>';
	}
}
