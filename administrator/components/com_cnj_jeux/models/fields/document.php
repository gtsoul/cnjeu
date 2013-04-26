<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of documents
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class JFormFieldDocument extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Document';

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
function newDocument()
{
var a = document.getElementById(\'add_document\').value;
if (a == "")
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=document&layout=edit&ofrom=jeu\');
  }
else
  {  
	window.open(\'index.php?option=com_cnj_jeux&view=document&layout=edit&ofrom=jeu&newdocument=\'+a);
  }
} 
</script>  

<input type="text" id="add_document" name="add_document" value="" size="60" style="float:none" />
                    <div id="documents_propositions" class="autocomplete"></div>
                    <a id="btn_add_document" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_ADD_DOCUMENT') . '</a>&nbsp;&nbsp;<a href="javascript:newDocument()" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_NEW_DOCUMENT') . '</a><br /><br />';
            
                $values = $list = '';
                if(is_array($this->value)) {                    
                    foreach($this->value as $value) {
                        $values .= $value->id . ',';
                        $html[] = '<div id="document_' . $value->id . '">&bull; ' . $value->nom . ' - ' . JText::_('COM_CNJ_JEUX_ORDRE') . ' : <input type="text" name="jform[ordres][]" value="' . $value->ordre . '" style="float:none" /> <a class="btn_remove_document" style="cursor:pointer; color:#c00" >X</a>&nbsp;&nbsp;<a href="../voirDocument.php?id='.$value->id.'" target="_blank" style="cursor:pointer; color:#c00">voir</a><br /></div>';
                    }
                    $html[] = '<input type="hidden" id="list_documents" name="' . $this->name . '" value="' . substr($values,0,-1) . '">';
                }
                
                return '<div class="div_field_autocomplete">' . implode($html) . '</div>';
	}
}
