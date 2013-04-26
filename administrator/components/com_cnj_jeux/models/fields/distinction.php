<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of distinctions
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class JFormFieldDistinction extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Distinction';

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
function newDistinction()
{
var a = document.getElementById(\'add_distinction\').value;
if (a == "")
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=distinction&layout=edit&ofrom=jeu\');
  }
else
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=distinction&layout=edit&ofrom=jeu&newdistinction=\'+a);
  }
} 
</script>  

<input type="text" id="add_distinction" name="add_distinction" value="" size="60" style="float:none" />
                    <div id="distinctions_propositions" class="autocomplete"></div>
                    <a id="btn_add_distinction" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_ADD_DISTINCTION') . '</a>&nbsp;&nbsp;<a href="javascript:newDistinction()" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_NEW_DISTINCTION') . '</a><br /><br />';
            
                $values = $list = '';

                if(is_array($this->value)) 
{                    
                    foreach($this->value as $value) {
                        $values .= $value->id_distinction . ',';
                        $html[] = '<div id="distinction_' . $value->id_distinction . '">&bull; ' . $value->nom . ' - ' . JText::_('COM_CNJ_JEUX_DATE_DISTINCTION') . ' : <input type="text" name="jform[date_distinctions][]" value="' . $value->date_distinction . '" style="float:none" /> <a class="btn_remove_distinction" style="cursor:pointer; color:#c00" >X</a><br /></div>';
                    }
                    $html[] = '<input type="hidden" id="list_distinctions" name="' . $this->name . '" value="' . substr($values,0,-1) . '">';
                }
                
                return '<div class="div_field_autocomplete">' . implode($html) . '</div>';
	}
}
